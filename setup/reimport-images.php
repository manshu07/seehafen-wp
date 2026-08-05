<?php
/**
 * Re-import images for all Seehafen content (fixes missing media library attachments).
 * Run via: wp eval-file /var/www/html/wp-content/reimport-images.php
 * Then delete this file.
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

define( 'SH_ASSET_BASE', '/var/www/html/wp-content/themes/seehafen/assets/img' );

/**
 * Import one image into the media library.
 *
 * @param string $relative Relative path under assets/img.
 * @param string $title    Title.
 *
 * @return int
 */
function sh2_import_image( $relative, $title ) {
	$base = SH_ASSET_BASE;

	$file = $base . '/' . ltrim( $relative, '/' );

	if ( ! file_exists( $file ) ) {
		return 0;
	}

	$tmp = wp_tempnam( basename( $file ) );
	copy( $file, $tmp );

	$file_array = array(
		'name'     => basename( $file ),
		'tmp_name' => $tmp,
	);

	$attachment_id = media_handle_sideload( $file_array, 0, $title );

	if ( is_wp_error( $attachment_id ) ) {
		return 0;
	}

	return $attachment_id;
}

$log = array();

// Services by slug: hero / detail / home images.
$service_images = array(
	'immobilienverkauf'    => array( 'property-hero.jpg', 'team-1.jpg', 'team-1.jpg' ),
	'immobilienbewertung'  => array( 'property-1.jpg', 'team-3.jpg', 'team-3.jpg' ),
	'stockwerkeigentum'    => array( 'about.jpg', 'team-2.jpg', 'about.jpg' ),
	'mietliegenschaften'   => array( 'property-2.jpg', 'property-3.jpg', 'team-2.jpg' ),
	'erstvermietung'       => array( 'property-1.jpg', '', '' ),
	'baumanagement'        => array( 'property-2.jpg', '', '' ),
	'administration'       => array( 'team-1.jpg', '', '' ),
	'investments'          => array( 'property-3.jpg', '', '' ),
);

$services = get_posts( array( 'post_type' => 'service', 'posts_per_page' => -1 ) );

foreach ( $services as $service ) {
	if ( ! isset( $service_images[ $service->post_name ] ) ) {
		continue;
	}

	list( $hero, $detail, $home ) = $service_images[ $service->post_name ];

	if ( $hero ) {
		$hero_id  = sh2_import_image( $hero, $service->post_title . ' hero' );
		$hero_url = $hero_id ? wp_get_attachment_url( $hero_id ) : '';

		if ( $hero_url ) {
			update_post_meta( $service->ID, '_seehafen_hero_image', $hero_url );
		}

		if ( $hero_id ) {
			set_post_thumbnail( $service->ID, $hero_id );
		}
	}

	if ( $detail ) {
		$detail_id  = sh2_import_image( $detail, $service->post_title . ' detail' );
		$detail_url = $detail_id ? wp_get_attachment_url( $detail_id ) : '';

		if ( $detail_url ) {
			update_post_meta( $service->ID, '_seehafen_detail_image', $detail_url );
		}
	}

	if ( $home ) {
		$home_id  = sh2_import_image( $home, $service->post_title . ' home' );
		$home_url = $home_id ? wp_get_attachment_url( $home_id ) : '';

		if ( $home_url ) {
			update_post_meta( $service->ID, '_seehafen_home_image', $home_url );
		}
	}
}

$log[] = 'services images done: ' . count( $services );

// Offers.
$offer_images = array(
	'schaffhausen-15-zimmer' => 'offers/schaffhausen-15-zimmer.avif',
	'huttwil-35-zimmer'      => 'offers/huttwil-35-zimmer.avif',
	'wohlen-lagerraum'       => 'offers/wohlen-lagerraum.avif',
);

$offers = get_posts( array( 'post_type' => 'offer', 'posts_per_page' => -1 ) );

foreach ( $offers as $offer ) {
	if ( isset( $offer_images[ $offer->post_name ] ) ) {
		$image_id = sh2_import_image( $offer_images[ $offer->post_name ], $offer->post_title );

		if ( $image_id ) {
			set_post_thumbnail( $offer->ID, $image_id );
		}
	}
}

$log[] = 'offers images done: ' . count( $offers );

// References: match by title.
$reference_titles = array(
	'Mehrfamilienhaus' => array( 'Hägglingen AG', 'references/sale-haegglingen-6.jpg' ),
	'Wohnportfolio' => array( 'Olten SO', 'references/sale-olten-24.jpg' ),
	'3.5-Zimmer-Wohnung' => array( 'Zürich ZH', 'references/sale-zuerich-35.jpg' ),
	'3.5-Zimmer-Wohnung' => array( 'Bubikon ZH', 'references/sale-bubikon-35.jpg' ),
	'2.5-Zimmer-Wohnung' => array( 'Hinwil ZH', 'references/sale-hinwil-25.jpg' ),
	'4.5-Zimmer-Wohnung' => array( 'Dällikon ZH', 'references/sale-daellikon-45.png' ),
	'4.5-Zimmer-Wohnung' => array( 'Würenlos AG', 'references/rent-wuerenlos-45.jpg' ),
	'1.5-Zimmer-Wohnung' => array( 'Zürich ZH', 'references/rent-zuerich-15.jpg' ),
	'Zwei 4.5-Zimmer-Wohnungen' => array( 'Aarburg AG', 'references/rent-aarburg-45.png' ),
	'4.5-Zimmer-Wohnung' => array( 'Reichenburg SZ', 'references/rent-reichenburg-45.jpg' ),
	'3.5-Zimmer-Wohnung' => array( 'Rudolfstetten AG', 'references/rent-rudolfstetten-35.png' ),
	'4.5- & 3.5-Zimmer-Wohnungen' => array( 'Altstetten ZH', 'references/rent-altstetten.jpg' ),
	'Attika-Maisonette-Terrassenhaus' => array( 'Rieden SG', 'references/rent-rieden-attika.jpg' ),
	'5.5-Zimmer-Wohnung' => array( 'Zürich ZH', 'references/rent-zuerich-55.jpg' ),
	'2.5- & 3.5-Zimmer-Wohnungen' => array( 'Wohlen AG', 'references/rent-wohlen-25-35.jpg' ),
	'3.5-Zimmer-Wohnung' => array( 'Zürich ZH', 'references/rent-zuerich-35.jpg' ),
	'4.5-Zimmer-Wohnung' => array( 'Wohlen AG', 'references/rent-wohlen-45.jpg' ),
	'4-Zimmer-Reihenhaus' => array( 'Wohlen AG', 'references/rent-wohlen-reihenhaus.jpg' ),
	'Gewerbefläche' => array( 'Wohlen AG', 'references/rent-wohlen-gewerbe.jpg' ),
	'1.5-Zimmer-Wohnung' => array( 'Opfikon ZH', 'references/rent-opfikon-15.jpg' ),
	'Wohnliegenschaft' => array( 'Bubendorf BL', 'references/manage-bubendorf-6.jpg' ),
	'Wohn- und Geschäftsliegenschaft' => array( '', 'references/manage-shops-apartments.jpg' ),
	'Wohnliegenschaft' => array( 'Staad SG', 'references/manage-staad-8.jpg' ),
	'Wohnliegenschaft' => array( 'Hägglingen AG', 'references/manage-haegglingen-6.jpg' ),
	'Wohnliegenschaft' => array( 'Rheineck SG', 'references/manage-rheineck-12.jpg' ),
	'Wohnliegenschaft' => array( 'Glarus GL', 'references/manage-glarus-8.jpg' ),
	'Wohnliegenschaft' => array( 'Hägglingen AG', 'references/manage-haegglingen-8.png' ),
	'Wohn- und Gewerbeliegenschaft' => array( 'Schaffhausen SH', 'references/manage-schaffhausen.png' ),
);

$references = get_posts( array( 'post_type' => 'reference', 'posts_per_page' => -1 ) );
$done       = 0;

foreach ( $references as $reference ) {
	$location = get_post_meta( $reference->ID, '_seehafen_location', true );

	// Find matching image by title + location.
	foreach ( $reference_titles as $title => $data ) {
		if ( $title !== $reference->post_title ) {
			continue;
		}

		if ( $data[0] !== $location ) {
			continue;
		}

		$image_id = sh2_import_image( $data[1], $reference->post_title );

		if ( $image_id ) {
			set_post_thumbnail( $reference->ID, $image_id );
			$done++;
		}

		break;
	}
}

$log[] = 'references images done: ' . $done . ' of ' . count( $references );

echo 'REIMPORT DONE:' . "\n" . implode( "\n", $log ) . "\n";
