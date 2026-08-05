<?php
/**
 * Enqueue scripts and styles.
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue the theme stylesheet and main script.
 *
 * @return void
 */
function seehafen_enqueue_assets() {
	wp_enqueue_style(
		'seehafen-main',
		get_stylesheet_directory_uri() . '/assets/css/main.css',
		array(),
		SEEHAFEN_VERSION
	);

	wp_enqueue_script(
		'seehafen-main',
		get_stylesheet_directory_uri() . '/assets/js/main.js',
		array(),
		SEEHAFEN_VERSION,
		true
	);

	wp_localize_script(
		'seehafen-main',
		'seehafenData',
		array(
			'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
			'contactNonce'  => wp_create_nonce( 'seehafen_contact' ),
			'referencesUrl' => home_url( '/referenzen/' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'seehafen_enqueue_assets' );
