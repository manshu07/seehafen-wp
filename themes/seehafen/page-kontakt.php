<?php
/**
 * Template Name: Kontakt (Contact)
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

get_header();

$phone_land   = get_theme_mod( 'seehafen_phone_land', '+41 44 451 43 02' );
$phone_mobile = get_theme_mod( 'seehafen_phone_mobile', '+41 79 785 78 80' );
$email        = get_theme_mod( 'seehafen_email', 'info@seehafen-immobilien.ch' );
$address_main = get_theme_mod( 'seehafen_address_main', "Bahnhofstrasse 4\n6430 Schwyz" );
$address_branch = get_theme_mod( 'seehafen_address_branch', "Cheiblerrain 13\n5610 Wohlen" );
$opening      = get_theme_mod( 'seehafen_opening_hours', 'Montag bis Freitag<br />08:00–12:00 · 13:30–17:00 Uhr' );
?>
	<section class="contact-intro">
		<div class="content contact-intro-copy">
			<span class="kicker"><?php esc_html_e( 'Kontakt', 'seehafen' ); ?></span>
			<h1><?php esc_html_e( 'Wie können wir Ihnen helfen?', 'seehafen' ); ?></h1>
			<p><?php esc_html_e( 'Rufen Sie uns an, schreiben Sie uns eine E-Mail oder senden Sie Ihre Anfrage über das Formular. Wir melden uns persönlich bei Ihnen zurück.', 'seehafen' ); ?></p>
		</div>
	</section>
	<section class="contact-page">
		<div class="content contact-layout">
			<aside class="contact-sidebar">
				<div class="contact-direct-panel">
					<span class="kicker"><?php esc_html_e( 'Direkt erreichbar', 'seehafen' ); ?></span>
					<h2><?php esc_html_e( 'Persönlich für Sie da.', 'seehafen' ); ?></h2>
					<div class="contact-methods">
						<a href="<?php echo esc_url( seehafen_phone_land_tel() ); ?>">
							<?php seehafen_icon( 'phone' ); ?>
							<span><small><?php esc_html_e( 'Telefon', 'seehafen' ); ?></small><?php echo esc_html( $phone_land ); ?></span>
						</a>
						<a href="<?php echo esc_url( seehafen_phone_mobile_tel() ); ?>">
							<?php seehafen_icon( 'phone' ); ?>
							<span><small><?php esc_html_e( 'Mobil', 'seehafen' ); ?></small><?php echo esc_html( $phone_mobile ); ?></span>
						</a>
						<a href="<?php echo esc_url( 'mailto:' . $email ); ?>">
							<?php seehafen_icon( 'mail' ); ?>
							<span><small><?php esc_html_e( 'E-Mail', 'seehafen' ); ?></small><?php echo esc_html( $email ); ?></span>
						</a>
					</div>
					<p><strong><?php esc_html_e( 'Öffnungszeiten', 'seehafen' ); ?></strong><br /><?php echo wp_kses_post( $opening ); ?></p>
				</div>

				<div class="contact-locations">
					<article>
						<span class="kicker"><?php esc_html_e( 'Hauptsitz', 'seehafen' ); ?></span>
						<h3><?php esc_html_e( 'Schwyz', 'seehafen' ); ?></h3>
						<p><?php echo nl2br( esc_html( $address_main ) ); ?></p>
					</article>
					<article>
						<span class="kicker"><?php esc_html_e( 'Filiale', 'seehafen' ); ?></span>
						<h3><?php esc_html_e( 'Wohlen', 'seehafen' ); ?></h3>
						<p><?php echo nl2br( esc_html( $address_branch ) ); ?></p>
					</article>
				</div>
			</aside>

			<form class="contact-form" data-contact-form novalidate>
				<div class="form-heading">
					<span class="kicker"><?php esc_html_e( 'Nachricht senden', 'seehafen' ); ?></span>
					<h2><?php esc_html_e( 'Ihre Anfrage', 'seehafen' ); ?></h2>
					<p><?php esc_html_e( 'Füllen Sie nur die notwendigen Angaben aus.', 'seehafen' ); ?></p>
				</div>
				<div class="form-fields">
					<label><?php esc_html_e( 'Name', 'seehafen' ); ?> *<input name="name" required autocomplete="name" /></label>
					<label><?php esc_html_e( 'E-Mail', 'seehafen' ); ?> *<input name="email" type="email" required autocomplete="email" /></label>
					<label><?php esc_html_e( 'Telefon', 'seehafen' ); ?><input name="phone" type="tel" autocomplete="tel" /></label>
					<label><?php esc_html_e( 'Thema', 'seehafen' ); ?>
						<select name="subject">
							<option selected><?php esc_html_e( 'Allgemeine Anfrage', 'seehafen' ); ?></option>
							<option><?php esc_html_e( 'Immobilienverkauf', 'seehafen' ); ?></option>
							<option><?php esc_html_e( 'Bewirtschaftung', 'seehafen' ); ?></option>
							<option><?php esc_html_e( 'Immobilienberatung', 'seehafen' ); ?></option>
							<option><?php esc_html_e( 'Immobiliensuche', 'seehafen' ); ?></option>
						</select>
					</label>
					<label class="full"><?php esc_html_e( 'Nachricht', 'seehafen' ); ?> *<textarea name="message" required rows="6"></textarea></label>
					<label class="honeypot" aria-hidden="true"><?php esc_html_e( 'Website', 'seehafen' ); ?><input name="website" tabindex="-1" autocomplete="off" /></label>
					<label class="consent full">
						<input name="privacy" type="checkbox" required />
						<span><?php esc_html_e( 'Ich habe die', 'seehafen' ); ?> <a href="<?php echo esc_url( home_url( '/datenschutz/' ) ); ?>"><?php esc_html_e( 'Datenschutzerklärung', 'seehafen' ); ?></a> <?php esc_html_e( 'gelesen und stimme der Bearbeitung meiner Angaben zur Kontaktaufnahme zu.', 'seehafen' ); ?></span>
					</label>
					<button class="button button-solid" data-contact-submit>
						<span data-contact-label><?php esc_html_e( 'Nachricht senden', 'seehafen' ); ?></span>
						<?php seehafen_icon( 'arrow-right' ); ?>
					</button>
					<div class="form-feedback" aria-live="polite">
						<p class="form-success" data-contact-success hidden><?php esc_html_e( 'Vielen Dank. Ihre Nachricht wurde erfolgreich gesendet.', 'seehafen' ); ?></p>
						<p class="form-error" data-contact-error hidden></p>
					</div>
				</div>
			</form>
		</div>
	</section>

<?php
get_footer();
