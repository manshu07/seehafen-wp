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

			<div class="contact-form">
				<div class="form-heading">
					<span class="kicker"><?php esc_html_e( 'Nachricht senden', 'seehafen' ); ?></span>
					<h2><?php esc_html_e( 'Ihre Anfrage', 'seehafen' ); ?></h2>
					<p><?php esc_html_e( 'Füllen Sie nur die notwendigen Angaben aus.', 'seehafen' ); ?></p>
				</div>
				<?php echo do_shortcode( '[contact-form-7 title="Kontaktformular"]' ); ?>
			</div>
		</div>
	</section>

<?php
get_footer();
