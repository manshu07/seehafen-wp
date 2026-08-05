<?php
/**
 * Manual SEO meta — title, description, canonical and Open Graph.
 * Complements Rank Math (which takes over per-post meta when configured).
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render per-page meta tags in wp_head.
 *
 * @return void
 */
function seehafen_seo_head() {
	if ( is_admin() ) {
		return;
	}

	$title       = wp_get_document_title();
	$description = get_bloginfo( 'description' );
	$url         = home_url( wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '', PHP_URL_PATH ) );

	if ( is_singular() ) {
		$post_id = get_queried_object_id();
		$custom  = get_post_meta( $post_id, '_seehafen_seo_description', true );

		if ( $custom ) {
			$description = $custom;
		} elseif ( has_excerpt( $post_id ) ) {
			$description = get_the_excerpt( $post_id );
		}
	}

	$description = wp_strip_all_tags( $description );
	$description = mb_substr( $description, 0, 160 );

	// Canonical (skip when Rank Math already handles it).
	if ( ! class_exists( 'RankMath' ) ) {
		printf( '<link rel="canonical" href="%s" />' . "\n", esc_url( $url ) );
	}

	printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:type" content="website" />' . "\n" );
	printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( $url ) );
	printf( '<meta property="og:site_name" content="%s" />' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:locale" content="de_CH" />' . "\n" );
	printf( '<meta name="theme-color" content="#071f42" />' . "\n" );
}
add_action( 'wp_head', 'seehafen_seo_head', 1 );
