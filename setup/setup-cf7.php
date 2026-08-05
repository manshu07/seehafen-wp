<?php
/**
 * One-time: create the Seehafen Contact Form 7 form (German, design-matching markup).
 * Run via: wp eval-file /var/www/html/wp-content/setup-cf7.php
 * Then delete this file.
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpcf7_save_contact_form' ) ) {
	echo "CF7 not active\n";
	exit;
}

$form = '<div class="form-fields">
<label>Name * [text* name]</label>
<label>E-Mail * [email* email]</label>
<label>Telefon [tel phone]</label>
<label>Thema [select subject "Allgemeine Anfrage" "Immobilienverkauf" "Bewirtschaftung" "Immobilienberatung" "Immobiliensuche"]</label>
<label class="full">Nachricht * [textarea* message]</label>
<label class="honeypot">Website [honeypot website]</label>
<div class="consent full">[acceptance* privacy] <span>Ich habe die <a href="/datenschutz/">Datenschutzerklärung</a> gelesen und stimme der Bearbeitung meiner Angaben zur Kontaktaufnahme zu.</span></div>
[submit class:button class:button-solid "Nachricht senden"]
</div>';

$mail = array(
	'subject'          => 'Website-Anfrage: [subject]',
	'sender'           => 'Seehafen Website <wordpress@seehafen-immobilien.ch>',
	'body'             => "Name: [name]\nE-Mail: [email]\nTelefon: [phone]\nThema: [subject]\n\nNachricht:\n[message]",
	'recipient'        => 'info@seehafen-immobilien.ch',
	'additional_headers' => 'Reply-To: [email]',
	'attachments'      => '',
	'use_html'         => 0,
);

$messages = array(
	'validation_error'          => 'Bitte füllen Sie alle Pflichtfelder korrekt aus.',
	'invalid_required'          => 'Bitte füllen Sie das Pflichtfeld aus.',
	'invalid_email'             => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
	'invalid_tel'               => 'Bitte geben Sie eine gültige Telefonnummer ein.',
	'invalid_subject'           => 'Bitte wählen Sie ein Thema aus.',
	'invalid_message'           => 'Bitte geben Sie eine Nachricht ein.',
	'mail_sent_ok'              => 'Vielen Dank. Ihre Nachricht wurde erfolgreich gesendet.',
	'mail_sent_ng'              => 'Die Nachricht konnte nicht gesendet werden. Bitte versuchen Sie es später erneut.',
	'accept_terms'              => 'Bitte stimmen Sie der Bearbeitung Ihrer Angaben zu.',
	'invalid_acceptance'        => 'Bitte stimmen Sie der Bearbeitung Ihrer Angaben zu.',
	'quiz_answer_not_correct'   => 'Ihre Antwort ist falsch.',
	'spam'                      => 'Ihre Nachricht wurde als Spam erkannt.',
);

$result = wpcf7_save_contact_form( array(
	'title'    => 'Kontaktformular',
	'form'     => $form,
	'mail'     => $mail,
	'messages' => $messages,
	'locale'   => 'de_DE',
) );

if ( is_wp_error( $result ) ) {
	echo 'ERROR: ' . $result->get_error_message() . "\n";
	exit;
}

echo 'CF7 form created: ID ' . $result->id() . "\n";
echo 'Shortcode: [contact-form-7 id="' . $result->id() . '" title="Kontaktformular"]' . "\n";
