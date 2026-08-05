<?php
/**
 * Template Name: Angebote (Offers overview)
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<section class="overview-links">
		<div class="content">
			<div class="overview-links-heading">
				<div>
					<span class="kicker"><?php esc_html_e( 'Angebote', 'seehafen' ); ?></span>
					<h1><?php esc_html_e( 'Immobilien im Überblick.', 'seehafen' ); ?></h1>
				</div>
				<p><?php esc_html_e( 'Entdecken Sie aktuelle Kauf- und Mietangebote oder werfen Sie einen Blick auf erfolgreich begleitete Projekte.', 'seehafen' ); ?></p>
			</div>
			<div class="overview-link-grid">
				<a href="<?php echo esc_url( seehafen_homegate_url() ); ?>" class="overview-link-card" target="_blank" rel="noreferrer">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/property-3.jpg' ); ?>" alt="" loading="lazy" />
					<div>
						<h2><?php esc_html_e( 'Aktuelle Angebote', 'seehafen' ); ?></h2>
						<p><?php esc_html_e( 'Verfügbare Kauf- und Mietobjekte auf unserem offiziellen Anbieterprofil.', 'seehafen' ); ?></p>
						<span><?php esc_html_e( 'Auf Homegate', 'seehafen' ); ?> <?php seehafen_icon( 'external' ); ?></span>
					</div>
				</a>
				<a href="<?php echo esc_url( home_url( '/referenzen/' ) ); ?>" class="overview-link-card">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/property-2.jpg' ); ?>" alt="" loading="lazy" />
					<div>
						<h2><?php esc_html_e( 'Referenzen', 'seehafen' ); ?></h2>
						<p><?php esc_html_e( 'Eine Auswahl verkaufter, vermieteter und verwalteter Immobilien.', 'seehafen' ); ?></p>
						<span><?php esc_html_e( 'Entdecken', 'seehafen' ); ?> <?php seehafen_icon( 'arrow-right' ); ?></span>
					</div>
				</a>
			</div>
		</div>
	</section>

	<?php seehafen_cta_section(); ?>

<?php
get_footer();
