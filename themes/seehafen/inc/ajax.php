<?php
/**
 * AJAX endpoints — load more references.
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

/**
 * AJAX handler: render the next batch of reference tiles.
 *
 * @return void
 */
function seehafen_load_more_references() {
	check_ajax_referer( 'seehafen_contact', 'nonce' );

	$offset = isset( $_POST['offset'] ) ? absint( $_POST['offset'] ) : 9;
	$limit  = 9;
	$total  = isset( $_POST['total'] ) ? absint( $_POST['total'] ) : 0;

	$args = array(
		'post_type'      => 'reference',
		'posts_per_page' => $limit,
		'offset'         => $offset,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	);

	$query = new WP_Query( $args );

	ob_start();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			seehafen_reference_tile( get_post() );
		}
	}

	wp_reset_postdata();

	$html = ob_get_clean();

	wp_send_json_success( array(
		'html'     => $html,
		'next'     => $offset + $limit,
		'has_more' => ( $offset + $limit ) < $total,
	) );
}
add_action( 'wp_ajax_seehafen_load_more', 'seehafen_load_more_references' );
add_action( 'wp_ajax_nopriv_seehafen_load_more', 'seehafen_load_more_references' );
