<?php
/**
 * Customizer settings — all site-wide content is editable from Appearance > Customize.
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Customizer settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 *
 * @return void
 */
function seehafen_customize_register( $wp_customize ) {
	seehafen_customize_contact_section( $wp_customize );
	seehafen_customize_home_section( $wp_customize );
	seehafen_customize_cta_section( $wp_customize );
}
add_action( 'customize_register', 'seehafen_customize_register' );

/**
 * Contact information settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 *
 * @return void
 */
function seehafen_customize_contact_section( $wp_customize ) {
	$wp_customize->add_section( 'seehafen_contact', array(
		'title'    => __( 'Seehafen: Contact', 'seehafen' ),
		'priority' => 30,
	) );

	$settings = array(
		'seehafen_phone_land'      => array(
			'label'       => __( 'Landline phone', 'seehafen' ),
			'default'     => '+41 44 451 43 02',
			'tel_default' => '+41444514302',
		),
		'seehafen_phone_mobile'    => array(
			'label'       => __( 'Mobile phone', 'seehafen' ),
			'default'     => '+41 79 785 78 80',
			'tel_default' => '+41797857880',
		),
		'seehafen_email'           => array(
			'label'       => __( 'Email', 'seehafen' ),
			'default'     => 'info@seehafen-immobilien.ch',
			'tel_default' => '',
		),
		'seehafen_homegate_url'    => array(
			'label'       => __( 'Homegate profile URL', 'seehafen' ),
			'default'     => 'https://www.homegate.ch/anbieter/h475138/seehafen-partner-immobilien-ag',
			'tel_default' => '',
		),
		'seehafen_address_main'    => array(
			'label'       => __( 'Main office address (2 lines)', 'seehafen' ),
			'default'     => "Bahnhofstrasse 4\n6430 Schwyz",
			'tel_default' => '',
		),
		'seehafen_address_branch'  => array(
			'label'       => __( 'Branch address (2 lines)', 'seehafen' ),
			'default'     => "Cheiblerrain 13\n5610 Wohlen",
			'tel_default' => '',
		),
		'seehafen_opening_hours'   => array(
			'label'       => __( 'Opening hours', 'seehafen' ),
			'default'     => 'Montag bis Freitag<br />08:00–12:00 · 13:30–17:00 Uhr',
			'tel_default' => '',
		),
		'seehafen_footer_text'     => array(
			'label'       => __( 'Footer tagline', 'seehafen' ),
			'default'     => 'Persönliche Immobiliendienstleistungen mit Weitblick – in Schwyz, Wohlen und der ganzen Schweiz.',
			'tel_default' => '',
		),
	);

	foreach ( $settings as $setting => $args ) {
		$wp_customize->add_setting( $setting, array(
			'default'           => $args['default'],
			'sanitize_callback' => 'seehafen_sanitize_customizer_text',
		) );

		$wp_customize->add_control( $setting, array(
			'label'   => $args['label'],
			'section' => 'seehafen_contact',
			'type'    => 'textarea',
		) );
	}
}

/**
 * Home page hero + intro settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 *
 * @return void
 */
function seehafen_customize_home_section( $wp_customize ) {
	$wp_customize->add_section( 'seehafen_home', array(
		'title'    => __( 'Seehafen: Home', 'seehafen' ),
		'priority' => 31,
	) );

	$settings = array(
		'seehafen_hero_eyebrow' => array(
			'label'   => __( 'Hero eyebrow', 'seehafen' ),
			'default' => 'Langfristig. Persönlich. Verlässlich.',
		),
		'seehafen_hero_title'   => array(
			'label'   => __( 'Hero title (use <br /> for line break)', 'seehafen' ),
			'default' => 'Immobilien<br />mit Weitblick.',
		),
		'seehafen_hero_lead'    => array(
			'label'   => __( 'Hero lead', 'seehafen' ),
			'default' => 'Persönliche Beratung, verantwortungsvolle Entscheidungen und engagierte Begleitung.',
		),
		'seehafen_hero_image'   => array(
			'label'   => __( 'Hero image URL', 'seehafen' ),
			'default' => get_template_directory_uri() . '/assets/img/hero-team-house.png',
		),
		'seehafen_home_kicker'  => array(
			'label'   => __( 'Home intro kicker', 'seehafen' ),
			'default' => 'Unsere Expertise',
		),
		'seehafen_home_heading' => array(
			'label'   => __( 'Home intro heading', 'seehafen' ),
			'default' => 'Persönlich begleitet.<br />Klar entschieden.',
		),
		'seehafen_home_intro'   => array(
			'label'   => __( 'Home intro paragraph', 'seehafen' ),
			'default' => 'Der Verkauf oder die Bewirtschaftung einer Liegenschaft ist mehr als eine Transaktion. Wir führen Sie sicher durch den gesamten Prozess – professionell, transparent und mit Herzblut.',
		),
	);

	foreach ( $settings as $setting => $args ) {
		$wp_customize->add_setting( $setting, array(
			'default'           => $args['default'],
			'sanitize_callback' => 'seehafen_sanitize_customizer_html',
		) );

		$wp_customize->add_control( $setting, array(
			'label'   => $args['label'],
			'section' => 'seehafen_home',
			'type'    => 'textarea',
		) );
	}
}

/**
 * CTA strip settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 *
 * @return void
 */
function seehafen_customize_cta_section( $wp_customize ) {
	$wp_customize->add_section( 'seehafen_cta', array(
		'title'    => __( 'Seehafen: CTA strip', 'seehafen' ),
		'priority' => 32,
	) );

	$settings = array(
		'seehafen_cta_kicker'   => array(
			'label'   => __( 'Kicker', 'seehafen' ),
			'default' => 'Kostenloses Erstgespräch',
		),
		'seehafen_cta_heading'  => array(
			'label'   => __( 'Heading', 'seehafen' ),
			'default' => 'Lassen Sie uns über Ihre Immobilie sprechen.',
		),
		'seehafen_cta_text'     => array(
			'label'   => __( 'Text', 'seehafen' ),
			'default' => 'Montag bis Freitag · 08:00–12:00 und 13:30–17:00 Uhr',
		),
		'seehafen_cta_button'   => array(
			'label'   => __( 'Button label', 'seehafen' ),
			'default' => 'Kontakt aufnehmen',
		),
	);

	foreach ( $settings as $setting => $args ) {
		$wp_customize->add_setting( $setting, array(
			'default'           => $args['default'],
			'sanitize_callback' => 'seehafen_sanitize_customizer_html',
		) );

		$wp_customize->add_control( $setting, array(
			'label'   => $args['label'],
			'section' => 'seehafen_cta',
			'type'    => 'textarea',
		) );
	}
}

/**
 * Sanitize plain text Customizer values.
 *
 * @param string $value Raw value.
 *
 * @return string
 */
function seehafen_sanitize_customizer_text( $value ) {
	return sanitize_text_field( $value );
}

/**
 * Sanitize Customizer values that may contain limited HTML.
 *
 * @param string $value Raw value.
 *
 * @return string
 */
function seehafen_sanitize_customizer_html( $value ) {
	return wp_kses_post( $value );
}

/**
 * Helper: Homegate profile URL.
 *
 * @return string
 */
function seehafen_homegate_url() {
	return get_theme_mod( 'seehafen_homegate_url', 'https://www.homegate.ch/anbieter/h475138/seehafen-partner-immobilien-ag' );
}

/**
 * Helper: landline phone with digits only.
 *
 * @return string
 */
function seehafen_phone_land_tel() {
	return 'tel:+41444514302';
}

/**
 * Helper: mobile phone with digits only.
 *
 * @return string
 */
function seehafen_phone_mobile_tel() {
	return 'tel:+41797857880';
}
