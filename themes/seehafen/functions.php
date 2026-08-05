<?php
/**
 * Seehafen theme functions and definitions.
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

define( 'SEEHAFEN_VERSION', '1.0.0' );

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/class-seehafen-nav-walker.php';
require_once get_template_directory() . '/inc/ajax.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/contact-form.php';

/**
 * Redirect /immobilien to the Homegate profile (matches the SPA worker).
 *
 * @return void
 */
function seehafen_immobilien_redirect() {
	$request_path = wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '', PHP_URL_PATH );

	if ( '/immobilien' === $request_path ) {
		// External 301 to Homegate — wp_redirect (not safe_redirect) is correct here.
		wp_redirect( seehafen_homegate_url(), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'seehafen_immobilien_redirect', 5 );
