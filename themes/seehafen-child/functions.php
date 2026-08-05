<?php
/**
 * Seehafen Child theme functions.
 *
 * The parent theme (seehafen) loads its own functions automatically; this
 * file is additive — child-specific hooks, overrides and customizations
 * belong here.
 *
 * @package Seehafen_Child
 */

defined( 'ABSPATH' ) || exit;

define( 'SEEHAFEN_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue the child stylesheet after the parent theme styles.
 *
 * @return void
 */
function seehafen_child_enqueue_styles() {
	// Parent main stylesheet is enqueued as 'seehafen-main' by the parent theme.
	wp_enqueue_style(
		'seehafen-child',
		get_stylesheet_uri(),
		array( 'seehafen-main' ),
		SEEHAFEN_CHILD_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'seehafen_child_enqueue_styles' );
