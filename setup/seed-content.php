<?php
/**
 * One-time: import all Seehafen content (pages, services, references, offers, team, menus, CF7).
 * Data source: /var/www/html/wp-content/assets-import + /home/nightmule/seed-data.json (mounted at /tmp/seed-data.json)
 * Run: wp eval-file /var/www/html/wp-content/seed-content.php --allow-root
 */

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

$data = json_decode( file_get_contents( '/tmp/seed-data.json' ), true );
if ( ! $data ) {
	wp_die( 'seed-data.json missing' );
}

$ASSET_BASE = '/var/www/html/wp-content/assets-import';

/**
 * Sideload an asset into the media library.
 */
function sh_import_image( $asset_path, $title ) {
	static $count = 0;
	// JSON asset paths are '/assets/...' and the files live at wp-content/assets/...
	$full = '/var/www/html/wp-content' . $asset_path;
	if ( ! file_exists( $full ) ) {
		return 0;
	}
	$file_array = array(
		'name'     => basename( $full ),
		'tmp_name' => $full,
	);
	$attachment_id = media_handle_sideload( $file_array, 0, $title );
	if ( is_wp_error( $attachment_id ) ) {
		return 0;
	}
	$count++;
	return $attachment_id;
}

function sh_page_exists( $slug ) {
	$existing = get_page_by_path( $slug );
	return $existing ? $existing->ID : 0;
}

$page_defs = array(
	'home'           => array( 'slug' => 'home', 'title' => 'Startseite', 'content' => '', 'meta' => $data['meta']['/'] ),
	'firma'          => array( 'slug' => 'firma', 'title' => 'Firma', 'content' => '', 'meta' => $data['meta']['/firma'] ),
	'dienstleistungen' => array( 'slug' => 'dienstleistungen', 'title' => 'Dienstleistungen', 'content' => '', 'meta' => $data['meta']['/dienstleistungen'] ),
	'angebote'       => array( 'slug' => 'angebote', 'title' => 'Angebote', 'content' => '', 'meta' => $data['meta']['/angebote'] ),
	'referenzen'     => array( 'slug' => 'referenzen', 'title' => 'Referenzen', 'content' => '', 'meta' => $data['meta']['/referenzen'] ),
	'kontakt'        => array( 'slug' => 'kontakt', 'title' => 'Kontakt', 'content' => '', 'meta' => $data['meta']['/kontakt'] ),
	'impressum'      => array( 'slug' => 'impressum', 'title' => 'Impressum', 'content' => '', 'meta' => $data['meta']['/impressum'] ),
	'datenschutz'    => array( 'slug' => 'datenschutz', 'title' => 'Datenschutzerklärung', 'content' => '', 'meta' => $data['meta']['/datenschutz'] ),
	'agb'            => array( 'slug' => 'agb', 'title' => 'AGB', 'content' => '', 'meta' => $data['meta']['/agb'] ),
);

$page_ids = array();
foreach ( $page_defs as $key => $def ) {
	$id = sh_page_exists( $def['slug'] );
	if ( ! $id ) {
		$id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $def['title'],
				'post_name'    => $def['slug'],
				'post_content' => $def['content'],
			)
		);
	}
	$page_ids[ $key ] = $id;
	if ( $def['meta'] ) {
		update_post_meta( $id, 'rank_math_title', $def['meta'][0] );
		update_post_meta( $id, 'rank_math_description', $def['meta'][1] );
	}
}

// Front page = home.
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $page_ids['home'] );

// --- Services (4 primary, full detail) ---
$service_ids = array();
foreach ( $data['primaryServices'] as $svc ) {
	$slug = $svc['slug'];
	$existing = get_page_by_path( 'dienstleistungen/' . $slug, OBJECT, 'service' );
	if ( ! $existing ) {
		$existing = get_page_by_path( $slug, OBJECT, 'service' );
	}
	if ( $existing ) {
		$post_id = $existing->ID;
	} else {
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'service',
				'post_status'  => 'publish',
				'post_title'   => $svc['title'],
				'post_name'    => $slug,
				'post_content' => $svc['copy'],
				'post_excerpt' => $svc['lead'],
			)
		);
	}
	$service_ids[ $slug ] = $post_id;

	$hero_id  = sh_import_image( $svc['hero'], $svc['title'] . ' Hero' );
	$home_id  = sh_import_image( $svc['detailImage'], $svc['title'] . ' Detail' );
	if ( $hero_id ) {
		set_post_thumbnail( $post_id, $hero_id );
		update_field( 'seehafen_hero_image', $hero_id, $post_id );
	}
	if ( $home_id ) {
		update_field( 'seehafen_home_image', $home_id, $post_id );
	}

	// Lead + heading + points stored as post meta for the detail template.
	update_post_meta( $post_id, 'seehafen_lead', $svc['lead'] );
	update_post_meta( $post_id, 'seehafen_heading', $svc['heading'] );
	update_post_meta( $post_id, 'seehafen_points', $svc['points'] );

	$meta_key = '/dienstleistungen/' . $slug;
	if ( isset( $data['meta'][ $meta_key ] ) ) {
		update_post_meta( $post_id, 'rank_math_title', $data['meta'][ $meta_key ][0] );
		update_post_meta( $post_id, 'rank_math_description', $data['meta'][ $meta_key ][1] );
	}
}

// --- References (28) ---
foreach ( $data['references'] as $idx => $ref ) {
	list( $title, $location, $type, $detail, $image ) = $ref;
	$post_name = 'ref-' . ( $idx + 1 );
	$existing  = get_page_by_path( $post_name, OBJECT, 'reference' );
	if ( $existing ) {
		$post_id = $existing->ID;
	} else {
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'reference',
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_name'    => $post_name,
				'menu_order'   => $idx + 1,
			)
		);
	}
	$img_id = sh_import_image( $image, $title );
	if ( $img_id ) {
		set_post_thumbnail( $post_id, $img_id );
	}
	update_field( 'seehafen_location', $location, $post_id );
	update_field( 'seehafen_detail', $detail, $post_id );
	update_post_meta( $post_id, 'seehafen_type', $type );
}

// --- Offers (3) ---
foreach ( $data['offerShowcaseItems'] as $offer ) {
	$existing = get_page_by_path( 'angebote/' . $offer['slug'], OBJECT, 'offer' );
	if ( $existing ) {
		$post_id = $existing->ID;
	} else {
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'offer',
				'post_status'  => 'publish',
				'post_title'   => $offer['title'],
				'post_name'    => $offer['slug'],
			)
		);
	}
	$img_id = sh_import_image( $offer['image'], $offer['title'] );
	if ( $img_id ) {
		set_post_thumbnail( $post_id, $img_id );
	}
	update_field( 'seehafen_price', $offer['price'], $post_id );
	update_field( 'seehafen_location', $offer['location'], $post_id );
	update_post_meta( $post_id, 'seehafen_rooms', $offer['rooms'] );
	if ( ! empty( $offer['area'] ) ) {
		update_post_meta( $post_id, 'seehafen_area', $offer['area'] );
	}
	update_post_meta( $post_id, 'seehafen_label', $offer['label'] );
}

// --- Team (3) ---
foreach ( $data['team'] as $member ) {
	list( $initials, $name, $role, $bio ) = $member;
	$post_name = 'team-' . strtolower( str_replace( ' ', '-', $name ) );
	$existing  = get_page_by_path( $post_name, OBJECT, 'team_member' );
	if ( $existing ) {
		$post_id = $existing->ID;
	} else {
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'team_member',
				'post_status'  => 'publish',
				'post_title'   => $name,
				'post_name'    => $post_name,
				'post_content' => $bio,
			)
		);
	}
	update_field( 'seehafen_role', $role, $post_id );
	update_post_meta( $post_id, 'seehafen_initials', $initials );
}

// --- Menus ---
$menu_primary = wp_get_nav_menu_object( 'Hauptmenü' );
if ( ! $menu_primary ) {
	$menu_primary_id = wp_create_nav_menu( 'Hauptmenü' );
} else {
	$menu_primary_id = $menu_primary->term_id;
}

function sh_menu_item( $menu_id, $title, $url ) {
	$existing = false;
	$items = wp_get_nav_menu_items( $menu_id );
	if ( $items ) {
		foreach ( $items as $item ) {
			if ( $item->title === $title && $item->url === $url ) {
				$existing = $item->ID;
			}
		}
	}
	if ( $existing ) {
		return $existing;
	}
	return wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'  => $title,
			'menu-item-url'    => $url,
			'menu-item-status' => 'publish',
		)
	);
}

// Primary menu items (flat for now; dropdowns via CSS hover).
sh_menu_item( $menu_primary_id, 'Über uns', home_url( '/firma' ) );
sh_menu_item( $menu_primary_id, 'Dienstleistungen', home_url( '/dienstleistungen' ) );
sh_menu_item( $menu_primary_id, 'Immobilienverkauf', home_url( '/dienstleistungen/immobilienverkauf' ) );
sh_menu_item( $menu_primary_id, 'Immobilienbewertung', home_url( '/dienstleistungen/immobilienbewertung' ) );
sh_menu_item( $menu_primary_id, 'Stockwerkeigentum', home_url( '/dienstleistungen/stockwerkeigentum' ) );
sh_menu_item( $menu_primary_id, 'Mietliegenschaften', home_url( '/dienstleistungen/mietliegenschaften' ) );
sh_menu_item( $menu_primary_id, 'Angebote', home_url( '/angebote' ) );
sh_menu_item( $menu_primary_id, 'Aktuelle Angebote', $data['homegateProfileUrl'] );
sh_menu_item( $menu_primary_id, 'Referenzen', home_url( '/referenzen' ) );
sh_menu_item( $menu_primary_id, 'Kontakt', home_url( '/kontakt' ) );

set_theme_mod( 'nav_menu_locations', array( 'primary' => $menu_primary_id ) );

// --- Contact Form 7 ---
if ( ! function_exists( 'wpcf7_save_contact_form' ) ) {
	include_once WP_PLUGIN_DIR . '/contact-form-7/includes/contact-form-functions.php';
}
if ( function_exists( 'wpcf7_save_contact_form' ) && ! get_page_by_path( 'kontaktformular', OBJECT, 'wpcf7_contact_form' ) ) {
	$form_markup = '<div class="form-fields">' . "\n"
		. '<label>Name *' . "\n" . '    [text* name autocomplete:name]' . "\n" . '</label>' . "\n"
		. '<label>E-Mail *' . "\n" . '    [email* email autocomplete:email]' . "\n" . '</label>' . "\n"
		. '<label>Telefon' . "\n" . '    [tel phone autocomplete:tel]' . "\n" . '</label>' . "\n"
		. '<label>Thema' . "\n" . '    [select subject "Allgemeine Anfrage" "Immobilienverkauf" "Bewirtschaftung" "Immobilienberatung" "Immobiliensuche"]' . "\n" . '</label>' . "\n"
		. '<label class="full">Nachricht *' . "\n" . '    [textarea* message x6]' . "\n" . '</label>' . "\n"
		. '<label class="consent full">' . "\n" . '    [acceptance privacy] <span>Ich habe die <a href="/datenschutz">Datenschutzerklärung</a> gelesen und stimme der Bearbeitung meiner Angaben zur Kontaktaufnahme zu.</span>' . "\n" . '</label>' . "\n"
		. '<button class="button button-solid" type="submit">Nachricht senden →</button>' . "\n"
		. '</div>';
	$mail = array(
		'subject'  => 'Website-Anfrage: [_subject]',
		'sender'   => 'Seehafen Website <wordpress@seehafen.local>',
		'body'     => "Name: [name]\nE-Mail: [email]\nTelefon: [phone]\nThema: [subject]\n\nNachricht:\n[message]",
		'recipient' => 'info@seehafen-immobilien.ch',
	);
	$properties = array(
		'form'     => $form_markup,
		'mail'     => $mail,
		'messages' => array(
			'sent'             => 'Vielen Dank. Ihre Nachricht wurde erfolgreich gesendet.',
			'invalid_required' => 'Bitte füllen Sie alle Pflichtfelder aus.',
			'validation_error' => 'Bitte prüfen Sie Ihre Eingaben.',
		),
	);
	$cf7 = wpcf7_save_contact_form(
		array(
			'id'         => -1,
			'title'      => 'Kontaktformular',
			'properties' => $properties,
		)
	);
}

// Flush rewrite for CPT routes.
delete_option( 'rewrite_rules' );

echo 'Pages: ' . count( $page_ids ) . ' | Services: ' . count( $service_ids ) . ' | Refs: 28 | Offers: 3 | Team: 3 | Menu: ' . $menu_primary_id . "\n";
