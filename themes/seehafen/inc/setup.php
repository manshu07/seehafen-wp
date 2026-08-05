<?php
/**
 * Theme setup.
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @return void
 */
function seehafen_setup() {
	load_theme_textdomain( 'seehafen', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 120,
		'width'       => 320,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'seehafen' ),
		'footer'  => __( 'Footer Menu', 'seehafen' ),
	) );
}
add_action( 'after_setup_theme', 'seehafen_setup' );

/**
 * Set the content width.
 *
 * @return void
 */
function seehafen_content_width() {
	$GLOBALS['content_width'] = 1300;
}
add_action( 'after_setup_theme', 'seehafen_content_width', 0 );
