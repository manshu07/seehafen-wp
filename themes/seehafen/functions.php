<?php
/**
 * Seehafen child theme functions.
 *
 * Plugin-first build: this theme only carries the original design CSS
 * (verbatim from the SPA) plus a thin Elementor-matching layer.
 *
 * @package Seehafen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SEEHAFEN_VERSION', '2.0.0' );

/**
 * Theme setup.
 */
function seehafen_setup() {
	load_child_theme_textdomain( 'seehafen', get_stylesheet_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	register_nav_menus(
		array(
			'primary' => __( 'Hauptmenü', 'seehafen' ),
		)
	);
}
add_action( 'after_setup_theme', 'seehafen_setup' );

require_once get_stylesheet_directory() . '/inc/class-seehafen-nav-walker.php';

/**
 * Enqueue parent + child styles.
 */
function seehafen_enqueue_styles() {
	// Parent theme style (Twenty Twenty-Five).
	wp_enqueue_style(
		'seehafen-parent',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( 'twentytwentyfive' )->get( 'Version' )
	);

	// Original SPA design CSS (verbatim).
	wp_enqueue_style(
		'seehafen-main',
		get_stylesheet_directory_uri() . '/assets/css/main.css',
		array( 'seehafen-parent' ),
		SEEHAFEN_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'seehafen_enqueue_styles' );

/**
 * Allow Elementor widgets to inherit the theme design tokens.
 */
function seehafen_elementor_css() {
	$css = '
	:root {
		--seehafen-ink: #071f42;
		--seehafen-accent: #c9a063;
		--seehafen-bg: #f8f7f4;
	}
	.elementor-section .elementor-heading-title,
	.elementor-widget-heading h1,
	.elementor-widget-heading h2,
	.elementor-widget-heading h3,
	.elementor-widget-heading h4 {
		color: var(--seehafen-ink);
	}
	.elementor-button {
		text-transform: uppercase;
		letter-spacing: 0.08em;
		font-weight: 700;
	}
	';
	wp_add_inline_style( 'seehafen-main', $css );
}
add_action( 'wp_enqueue_scripts', 'seehafen_elementor_css', 20 );
