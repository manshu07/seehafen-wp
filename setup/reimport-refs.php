<?php
/**
 * Re-import reference images keyed by menu_order (1:1 SPA order).
 * Run via: wp eval-file /var/www/html/wp-content/reimport-refs.php
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
 * @param string $relative Relative path.
 * @param string $title    Title.
 *
 * @return int
 */
function sh3_import_image( $relative, $title ) {
	$base = SH_ASSET_BASE;

	$file = $base . '/' . ltrim( $relative, '/' );

	if ( ! file_exists( $file ) ) {
		return 0;
	}

	$tmp = wp_tempnam( basename( $file ) );
	copy( $file, $tmp );

	$attachment_id = media_handle_sideload( array(
		'name'     => basename( $file ),
		'tmp_name' => $tmp,
	), 0, $title );

	if ( is_wp_error( $attachment_id ) ) {
		return 0;
	}

	return $attachment_id;
}

// Reference images in SPA order (menu_order 1..28).
$ref_images = array(
	'references/sale-haegglingen-6.jpg',
	'references/sale-olten-24.jpg',
	'references/sale-zuerich-35.jpg',
	'references/sale-bubikon-35.jpg',
	'references/sale-hinwil-25.jpg',
	'references/sale-daellikon-45.png',
	'references/rent-wuerenlos-45.jpg',
	'references/rent-zuerich-15.jpg',
	'references/rent-aarburg-45.png',
	'references/rent-reichenburg-45.jpg',
	'references/rent-rudolfstetten-35.png',
	'references/rent-altstetten.jpg',
	'references/rent-rieden-attika.jpg',
	'references/rent-zuerich-55.jpg',
	'references/rent-wohlen-25-35.jpg',
	'references/rent-zuerich-35.jpg',
	'references/rent-wohlen-45.jpg',
	'references/rent-wohlen-reihenhaus.jpg',
	'references/rent-wohlen-gewerbe.jpg',
	'references/rent-opfikon-15.jpg',
	'references/manage-bubendorf-6.jpg',
	'references/manage-shops-apartments.jpg',
	'references/manage-staad-8.jpg',
	'references/manage-haegglingen-6.jpg',
	'references/manage-rheineck-12.jpg',
	'references/manage-glarus-8.jpg',
	'references/manage-haegglingen-8.png',
	'references/manage-schaffhausen.png',
);

$references = get_posts( array(
	'post_type'      => 'reference',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
) );

$done   = 0;
$failed = array();

foreach ( $references as $reference ) {
	$order = (int) $reference->menu_order;

	if ( ! isset( $ref_images[ $order - 1 ] ) ) {
		$failed[] = $reference->post_name . ' (no mapping for order ' . $order . ')';
		continue;
	}

	$image_id = sh3_import_image( $ref_images[ $order - 1 ], $reference->post_title );

	if ( $image_id ) {
		set_post_thumbnail( $reference->ID, $image_id );
		$done++;
	} else {
		$failed[] = $reference->post_name . ' (import failed)';
	}
}

echo 'REFERENCES REIMPORT: ' . $done . ' of ' . count( $references ) . PHP_EOL;

if ( ! empty( $failed ) ) {
	echo 'FAILED:' . PHP_EOL . implode( PHP_EOL, $failed ) . PHP_EOL;
}
