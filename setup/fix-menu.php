<?php
/**
 * One-time: rebuild the Hauptmenü with dropdown hierarchy.
 * Run: wp eval-file /var/www/html/wp-content/fix-menu.php --allow-root
 */

$menu = wp_get_nav_menu_object( 'Hauptmenü' );
if ( ! $menu ) {
	wp_die( 'Hauptmenü not found' );
}
$menu_id = $menu->term_id;

// Clear existing items.
$items = wp_get_nav_menu_items( $menu_id );
if ( $items ) {
	foreach ( $items as $item ) {
		wp_delete_post( $item->ID, true );
	}
}

function sh_add_item( $menu_id, $title, $url, $parent = 0, $meta = array() ) {
	return wp_update_nav_menu_item(
		$menu_id,
		0,
		array_merge(
			array(
				'menu-item-title'  => $title,
				'menu-item-url'    => $url,
				'menu-item-status' => 'publish',
				'menu-item-parent-id' => $parent,
			),
			$meta
		)
	);
}

$uber = sh_add_item( $menu_id, 'Über uns', home_url( '/firma' ) );
sh_add_item( $menu_id, 'Über uns', home_url( '/firma#uber-uns' ), $uber );
sh_add_item( $menu_id, 'Unser Team', home_url( '/firma#team' ), $uber );
sh_add_item( $menu_id, 'Werte & Arbeitsweise', home_url( '/firma#werte' ), $uber );

$dl = sh_add_item( $menu_id, 'Dienstleistungen', home_url( '/dienstleistungen' ) );
sh_add_item( $menu_id, 'Immobilienverkauf', home_url( '/dienstleistungen/immobilienverkauf' ), $dl );
sh_add_item( $menu_id, 'Immobilienbewertung', home_url( '/dienstleistungen/immobilienbewertung' ), $dl );
sh_add_item( $menu_id, 'Stockwerkeigentum', home_url( '/dienstleistungen/stockwerkeigentum' ), $dl );
sh_add_item( $menu_id, 'Mietliegenschaften', home_url( '/dienstleistungen/mietliegenschaften' ), $dl );

$ang = sh_add_item( $menu_id, 'Angebote', home_url( '/angebote' ) );
sh_add_item( $menu_id, 'Aktuelle Angebote', 'https://www.homegate.ch/anbieter/h475138/seehafen-partner-immobilien-ag', $ang );
sh_add_item( $menu_id, 'Referenzen', home_url( '/referenzen' ), $ang );

sh_add_item( $menu_id, 'Kontakt', home_url( '/kontakt' ) );

// External link should open in new tab.
$items = wp_get_nav_menu_items( $menu_id );
foreach ( $items as $item ) {
	if ( false !== strpos( $item->url, 'homegate' ) ) {
		update_post_meta( $item->ID, '_menu_item_target', '_blank' );
		update_post_meta( $item->ID, '_menu_item_xfn', 'noreferrer' );
	}
}

echo 'Menu rebuilt with hierarchy. Items: ' . count( wp_get_nav_menu_items( $menu_id ) ) . "\n";
