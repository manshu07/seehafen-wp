<?php
/**
 * One-time: set the Kontaktformular form markup + mail via the WPCF7 API.
 * Run: wp eval-file /var/www/html/wp-content/fix-cf7.php --allow-root
 */

if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
	include_once WP_PLUGIN_DIR . '/contact-form-7/includes/contact-form.php';
}

$form = wpcf7_contact_form( 78 );
if ( ! $form ) {
	wp_die( 'Kontaktformular not found' );
}

$props = $form->get_properties();

$props['form'] = '<div class="form-fields">' . "\n"
	. '<label>Name *' . "\n"
	. '    [text* name autocomplete:name]' . "\n"
	. '</label>' . "\n"
	. '<label>E-Mail *' . "\n"
	. '    [email* email autocomplete:email]' . "\n"
	. '</label>' . "\n"
	. '<label>Telefon' . "\n"
	. '    [tel phone autocomplete:tel]' . "\n"
	. '</label>' . "\n"
	. '<label>Thema' . "\n"
	. '    [select subject "Allgemeine Anfrage" "Immobilienverkauf" "Bewirtschaftung" "Immobilienberatung" "Immobiliensuche"]' . "\n"
	. '</label>' . "\n"
	. '<label class="full">Nachricht *' . "\n"
	. '    [textarea* message x6]' . "\n"
	. '</label>' . "\n"
	. '<label class="consent full">' . "\n"
	. '    [acceptance privacy] <span>Ich habe die <a href="/datenschutz">Datenschutzerklärung</a> gelesen und stimme der Bearbeitung meiner Angaben zur Kontaktaufnahme zu.</span>' . "\n"
	. '</label>' . "\n"
	. '<button class="button button-solid" type="submit">Nachricht senden →</button>' . "\n"
	. '</div>';

$props['mail'] = array(
	'subject'  => 'Website-Anfrage: [_subject]',
	'sender'   => 'Seehafen Website <wordpress@seehafen.local>',
	'body'     => "Name: [name]\nE-Mail: [email]\nTelefon: [phone]\nThema: [subject]\n\nNachricht:\n[message]",
	'recipient' => 'info@seehafen-immobilien.ch',
	'additional_headers' => 'Reply-To: [email]',
);

$props['messages'] = array(
	'sent'             => 'Vielen Dank. Ihre Nachricht wurde erfolgreich gesendet.',
	'invalid_required' => 'Bitte füllen Sie alle Pflichtfelder aus.',
	'validation_error' => 'Bitte prüfen Sie Ihre Eingaben.',
	'accept_terms'     => 'Bitte akzeptieren Sie die Datenschutzerklärung.',
);

$form->set_properties( $props );
$form->save();

// Verify.
$out = do_shortcode( '[contact-form-7 id="78"]' );
echo 'form len: ' . strlen( $out ) . PHP_EOL;
echo 'has E-Mail: ' . ( strpos( $out, 'E-Mail' ) !== false ? 'YES' : 'NO' ) . PHP_EOL;
echo 'has Thema select: ' . ( strpos( $out, 'Thema' ) !== false ? 'YES' : 'NO' ) . PHP_EOL;
echo 'has consent: ' . ( strpos( $out, 'Datenschutzerklärung' ) !== false ? 'YES' : 'NO' ) . PHP_EOL;
echo 'has submit button: ' . ( strpos( $out, 'Nachricht senden' ) !== false ? 'YES' : 'NO' ) . PHP_EOL;
