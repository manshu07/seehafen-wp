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

// Elementor: don't print its Google Fonts (SPA uses system font stack).
add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );
add_filter( 'elementor/frontend/print_font_awesome', '__return_true' );

require_once get_stylesheet_directory() . '/inc/class-seehafen-nav-walker.php';
require_once get_stylesheet_directory() . '/inc/shortcodes.php';

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
	/* Neutralize Elementor kit defaults — the SPA design CSS owns typography. */
	body,
	.elementor-kit,
	.elementor-kit body,
	.elementor-page body {
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
		color: #071f42 !important;
		background-color: #ffffff !important;
	}
	.elementor-kit h1, .elementor-kit h2, .elementor-kit h3, .elementor-kit h4, .elementor-kit h5, .elementor-kit h6,
	.elementor-widget-heading h1, .elementor-widget-heading h2, .elementor-widget-heading h3, .elementor-widget-heading h4 {
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
		color: #071f42 !important;
	}
	.elementor-widget { margin-bottom: 0 !important; }
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
	/* Neutralize Elementor global image flattening on design components. */
	.elementor .hero img, .elementor .logo img, .elementor .home-service-card img,
	.elementor .offer-showcase-image img, .elementor .reference-tile img,
	.elementor .page-hero-media img, .elementor .overview-link-card img,
	.elementor .primary-service-card img, .elementor .secondary-service-grid article img,
	.elementor .service-detail-support img {
		border-radius: revert;
		box-shadow: revert;
		max-width: revert;
	}
	.elementor .home-service-card > img { height: 250px; }
	.elementor .reference-tile > img { height: 220px; }
	.elementor .overview-link-card > img { height: 235px; }
	.elementor .primary-service-card > img { height: 210px; }
	.elementor .service-detail-support > img { height: 330px; }
	.elementor .hero > img { position: absolute; top: 0; right: 0; bottom: 0; width: 64%; height: 100%; object-position: center 56%; }
	.elementor .offer-showcase-image img { position: absolute; height: 100%; }
	.elementor .page-hero-media img { width: 100%; }
	.elementor .offer-showcase-stage { display: none; }
	.elementor .offer-showcase-stage.is-active { display: grid; }
	/* Offer showcase multi-stage: only the active stage is visible. */
	.offer-showcase-stage { display: none; }
	.offer-showcase-stage.is-active { display: grid; }

	/* Mobile nav toggle: Menu <-> X icon swap like the SPA. */
	.nav-toggle .lucide-x { display: none; }
	body.menu-open .nav-toggle .lucide-menu { display: none; }
	body.menu-open .nav-toggle .lucide-x { display: block; }

	/* CF7 form — SPA form styling. CF7 emits one giant <p>; flatten it so labels become grid cells. */
	.contact-form .form-fields p { display: contents; }
	.contact-form .form-fields br { display: none; }
	.contact-form .form-fields label {
		display: flex; gap: 8px; margin: 0;
		color: rgba(255, 255, 255, 0.82);
		font-size: 13px; font-weight: 650; flex-direction: column;
	}
	.contact-form .form-fields label.full { grid-column: 1 / -1; }
	.contact-form .form-fields .wpcf7-form-control-wrap { display: flex; flex-direction: column; gap: 6px; width: 100%; }
	.contact-form .form-fields .wpcf7-text,
	.contact-form .form-fields .wpcf7-email,
	.contact-form .form-fields .wpcf7-tel,
	.contact-form .form-fields .wpcf7-select,
	.contact-form .form-fields .wpcf7-textarea {
		width: 100%; min-height: 50px; padding: 12px 14px;
		color: var(--seehafen-ink); background: #fff;
		border: 1px solid transparent; border-radius: 0;
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 15px;
	}
	.contact-form .form-fields .wpcf7-textarea { min-height: 145px; resize: vertical; }
	.contact-form .form-fields .wpcf7-text:focus,
	.contact-form .form-fields .wpcf7-email:focus,
	.contact-form .form-fields .wpcf7-tel:focus,
	.contact-form .form-fields .wpcf7-select:focus,
	.contact-form .form-fields .wpcf7-textarea:focus {
		border-color: #c9a063; outline: 2px solid #c9a063; outline-offset: 1px;
	}
	.contact-form .form-fields label.consent { display: grid; grid-template-columns: 18px minmax(0, 1fr); gap: 11px; align-items: start; font-weight: 400; line-height: 1.55; }
	.contact-form .form-fields label.consent .wpcf7-list-item { margin: 0; }
	.contact-form .form-fields label.consent input[type="checkbox"] { width: 18px; height: 18px; accent-color: #c9a063; margin: 2px 0 0; }
	.contact-form .form-fields .button { justify-self: start; }
	.contact-form .wpcf7-response-output { margin: 0; padding: 14px 16px; font-size: 14px; }
	.contact-form .wpcf7-not-valid-tip { color: #ffb4a2; font-size: 12px; }
	.contact-form .wpcf7 form.invalid .wpcf7-response-output,
	.contact-form .wpcf7 form.failed .wpcf7-response-output,
	.contact-form .wpcf7 form.sent .wpcf7-response-output { border: 1px solid rgba(255,255,255,0.25); color: #fff; background: rgba(255,255,255,0.06); }
	';
	wp_add_inline_style( 'seehafen-main', $css );
}
add_action( 'wp_enqueue_scripts', 'seehafen_elementor_css', 20 );

/**
 * Enqueue the theme JS (SPA behavior port).
 */
function seehafen_enqueue_scripts() {
	wp_enqueue_script(
		'seehafen-main',
		get_stylesheet_directory_uri() . '/assets/js/main.js',
		array(),
		SEEHAFEN_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'seehafen_enqueue_scripts' );
