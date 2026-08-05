<?php
/**
 * Contact form AJAX handler — wp_mail with nonce, honeypot and sanitization.
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handle the AJAX contact form submission.
 *
 * @return void
 */
function seehafen_handle_contact_form() {
	check_ajax_referer( 'seehafen_contact', 'nonce' );

	$honeypot = isset( $_POST['website'] ) ? sanitize_text_field( wp_unslash( $_POST['website'] ) ) : '';

	// Honeypot: bots fill this hidden field.
	if ( '' !== $honeypot ) {
		wp_send_json_success();
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( '' === $name || '' === $message || ! is_email( $email ) ) {
		wp_send_json_error( array( 'error' => __( 'Bitte füllen Sie alle Pflichtfelder korrekt aus.', 'seehafen' ) ), 400 );
	}

	$to      = get_theme_mod( 'seehafen_email', 'info@seehafen-immobilien.ch' );
	$subject = sprintf(
		/* translators: %s: form subject */
		__( 'Website-Anfrage: %s', 'seehafen' ),
		'' === $subject ? __( 'Allgemeine Anfrage', 'seehafen' ) : $subject
	);

	$body  = sprintf( "Name: %s\n", $name );
	$body .= sprintf( "E-Mail: %s\n", $email );
	$body .= sprintf( "Telefon: %s\n", '' === $phone ? __( 'Nicht angegeben', 'seehafen' ) : $phone );
	$body .= sprintf( "Thema: %s\n\n", $subject );
	$body .= sprintf( "Nachricht:\n%s\n", $message );

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( $sent ) {
		wp_send_json_success();
	}

	wp_send_json_error( array( 'error' => __( 'Die Nachricht konnte nicht gesendet werden. Bitte versuchen Sie es später erneut.', 'seehafen' ) ), 500 );
}
add_action( 'wp_ajax_seehafen_contact', 'seehafen_handle_contact_form' );
add_action( 'wp_ajax_nopriv_seehafen_contact', 'seehafen_handle_contact_form' );
