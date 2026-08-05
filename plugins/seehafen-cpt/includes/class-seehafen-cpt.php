<?php
/**
 * CPT and taxonomy registration for Seehafen.
 *
 * @package Seehafen_CPT
 */

/**
 * Registers the Seehafen custom post types and taxonomies.
 */
class Seehafen_CPT {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
	}

	/**
	 * Register all custom post types.
	 *
	 * @return void
	 */
	public function register_post_types() {
		$this->register_service_post_type();
		$this->register_reference_post_type();
		$this->register_offer_post_type();
		$this->register_team_member_post_type();
	}

	/**
	 * Register the service post type (primary + additional services).
	 *
	 * @return void
	 */
	private function register_service_post_type() {
		$labels = array(
			'name'               => __( 'Services', 'seehafen' ),
			'singular_name'      => __( 'Service', 'seehafen' ),
			'add_new_item'       => __( 'Add New Service', 'seehafen' ),
			'edit_item'          => __( 'Edit Service', 'seehafen' ),
			'new_item'           => __( 'New Service', 'seehafen' ),
			'view_item'          => __( 'View Service', 'seehafen' ),
			'search_items'       => __( 'Search Services', 'seehafen' ),
			'not_found'          => __( 'No services found', 'seehafen' ),
			'not_found_in_trash' => __( 'No services found in Trash', 'seehafen' ),
		);

		$args = array(
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => false,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-building',
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'      => array(
				'slug'       => 'dienstleistungen',
				'with_front' => false,
			),
		);

		register_post_type( 'service', $args );
	}

	/**
	 * Register the reference post type.
	 *
	 * @return void
	 */
	private function register_reference_post_type() {
		$labels = array(
			'name'               => __( 'References', 'seehafen' ),
			'singular_name'      => __( 'Reference', 'seehafen' ),
			'add_new_item'       => __( 'Add New Reference', 'seehafen' ),
			'edit_item'          => __( 'Edit Reference', 'seehafen' ),
			'new_item'           => __( 'New Reference', 'seehafen' ),
			'view_item'          => __( 'View Reference', 'seehafen' ),
			'search_items'       => __( 'Search References', 'seehafen' ),
			'not_found'          => __( 'No references found', 'seehafen' ),
			'not_found_in_trash' => __( 'No references found in Trash', 'seehafen' ),
		);

		$args = array(
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-portfolio',
			'supports'     => array( 'title', 'thumbnail' ),
			'rewrite'      => array(
				'slug'       => 'referenz',
				'with_front' => false,
			),
		);

		register_post_type( 'reference', $args );
	}

	/**
	 * Register the offer post type.
	 *
	 * @return void
	 */
	private function register_offer_post_type() {
		$labels = array(
			'name'               => __( 'Offers', 'seehafen' ),
			'singular_name'      => __( 'Offer', 'seehafen' ),
			'add_new_item'       => __( 'Add New Offer', 'seehafen' ),
			'edit_item'          => __( 'Edit Offer', 'seehafen' ),
			'new_item'           => __( 'New Offer', 'seehafen' ),
			'view_item'          => __( 'View Offer', 'seehafen' ),
			'search_items'       => __( 'Search Offers', 'seehafen' ),
			'not_found'          => __( 'No offers found', 'seehafen' ),
			'not_found_in_trash' => __( 'No offers found in Trash', 'seehafen' ),
		);

		$args = array(
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => false,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-tag',
			'supports'     => array( 'title', 'thumbnail' ),
			'rewrite'      => array(
				'slug'       => 'angebot',
				'with_front' => false,
			),
		);

		register_post_type( 'offer', $args );
	}

	/**
	 * Register the team member post type.
	 *
	 * @return void
	 */
	private function register_team_member_post_type() {
		$labels = array(
			'name'               => __( 'Team Members', 'seehafen' ),
			'singular_name'      => __( 'Team Member', 'seehafen' ),
			'add_new_item'       => __( 'Add New Team Member', 'seehafen' ),
			'edit_item'          => __( 'Edit Team Member', 'seehafen' ),
			'new_item'           => __( 'New Team Member', 'seehafen' ),
			'view_item'          => __( 'View Team Member', 'seehafen' ),
			'search_items'       => __( 'Search Team Members', 'seehafen' ),
			'not_found'          => __( 'No team members found', 'seehafen' ),
			'not_found_in_trash' => __( 'No team members found in Trash', 'seehafen' ),
		);

		$args = array(
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => false,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-groups',
			'supports'     => array( 'title', 'editor' ),
			'rewrite'      => array(
				'slug'       => 'team',
				'with_front' => false,
			),
		);

		register_post_type( 'team_member', $args );
	}

	/**
	 * Register all custom taxonomies.
	 *
	 * @return void
	 */
	public function register_taxonomies() {
		$this->register_service_type_taxonomy();
		$this->register_reference_type_taxonomy();
	}

	/**
	 * Service type taxonomy (primary vs additional).
	 *
	 * @return void
	 */
	private function register_service_type_taxonomy() {
		$labels = array(
			'name'          => __( 'Service Types', 'seehafen' ),
			'singular_name' => __( 'Service Type', 'seehafen' ),
		);

		$args = array(
			'labels'       => $labels,
			'public'       => true,
			'hierarchical' => true,
			'show_in_rest' => true,
			'show_admin_column' => true,
		);

		register_taxonomy( 'service_type', array( 'service' ), $args );
	}

	/**
	 * Reference type taxonomy (verkauft / vermietet / verwaltung).
	 *
	 * @return void
	 */
	private function register_reference_type_taxonomy() {
		$labels = array(
			'name'          => __( 'Reference Types', 'seehafen' ),
			'singular_name' => __( 'Reference Type', 'seehafen' ),
		);

		$args = array(
			'labels'       => $labels,
			'public'       => true,
			'hierarchical' => true,
			'show_in_rest' => true,
			'show_admin_column' => true,
		);

		register_taxonomy( 'reference_type', array( 'reference' ), $args );
	}
}
