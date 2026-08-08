<?php
/**
 * One-time: create ACF field groups + fields in the DB (admin-editable).
 * Run: wp eval-file /var/www/html/wp-content/seed-acf.php --allow-root
 */

$groups = array(
	array(
		'key'       => 'group_seehafen_service',
		'title'     => 'Service Details',
		'post_type' => 'service',
		'fields'    => array(
			array( 'key' => 'field_seehafen_service_hero', 'label' => 'Hero Image', 'name' => 'seehafen_hero_image', 'type' => 'image', 'return_format' => 'array' ),
			array( 'key' => 'field_seehafen_service_home', 'label' => 'Home Image', 'name' => 'seehafen_home_image', 'type' => 'image', 'return_format' => 'array' ),
		),
	),
	array(
		'key'       => 'group_seehafen_reference',
		'title'     => 'Reference Details',
		'post_type' => 'reference',
		'fields'    => array(
			array( 'key' => 'field_seehafen_ref_loc', 'label' => 'Location', 'name' => 'seehafen_location', 'type' => 'text' ),
			array( 'key' => 'field_seehafen_ref_detail', 'label' => 'Detail', 'name' => 'seehafen_detail', 'type' => 'text' ),
			array( 'key' => 'field_seehafen_ref_home', 'label' => 'Home Image', 'name' => 'seehafen_home_image', 'type' => 'image', 'return_format' => 'array' ),
		),
	),
	array(
		'key'       => 'group_seehafen_offer',
		'title'     => 'Offer Details',
		'post_type' => 'offer',
		'fields'    => array(
			array( 'key' => 'field_seehafen_offer_price', 'label' => 'Price', 'name' => 'seehafen_price', 'type' => 'text' ),
			array( 'key' => 'field_seehafen_offer_loc', 'label' => 'Location', 'name' => 'seehafen_location', 'type' => 'text' ),
			array( 'key' => 'field_seehafen_offer_home', 'label' => 'Home Image', 'name' => 'seehafen_home_image', 'type' => 'image', 'return_format' => 'array' ),
		),
	),
	array(
		'key'       => 'group_seehafen_team',
		'title'     => 'Team Member Details',
		'post_type' => 'team_member',
		'fields'    => array(
			array( 'key' => 'field_seehafen_team_role', 'label' => 'Role', 'name' => 'seehafen_role', 'type' => 'text' ),
			array( 'key' => 'field_seehafen_team_phone', 'label' => 'Phone', 'name' => 'seehafen_phone', 'type' => 'text' ),
			array( 'key' => 'field_seehafen_team_email', 'label' => 'Email', 'name' => 'seehafen_email', 'type' => 'email' ),
		),
	),
);

$created = 0;

foreach ( $groups as $g ) {
	$field_group = acf_update_field_group(
		array(
			'key'                   => $g['key'],
			'title'                 => $g['title'],
			'fields'                => array(),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => $g['post_type'],
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => array(),
			'active'                => true,
			'show_in_rest'          => 1,
		)
	);

	foreach ( $g['fields'] as $f ) {
		$field = array(
			'key'          => $f['key'],
			'label'        => $f['label'],
			'name'         => $f['name'],
			'aria-label'   => $f['label'],
			'type'         => $f['type'],
			'parent'       => $g['key'],
			'instructions' => '',
			'required'     => 0,
			'conditional_logic' => 0,
			'wrapper'      => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
		);

		if ( 'image' === $f['type'] ) {
			$field['return_format'] = 'array';
			$field['preview_size']  = 'medium';
			$field['library']       = 'all';
		}

		if ( 'email' === $f['type'] ) {
			$field['placeholder'] = '';
		}

		acf_update_field( $field );
	}

	$created++;
}

echo "ACF field groups created: {$created}\n";
