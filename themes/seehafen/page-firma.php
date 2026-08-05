<?php
/**
 * Template Name: Firma (Company overview)
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

get_header();

$values = seehafen_get_values();
$process = seehafen_get_process();
$team    = seehafen_get_team();
?>
	<section class="company-about" id="uber-uns">
		<div class="content company-about-grid">
			<div class="company-about-copy">
				<span class="kicker"><?php esc_html_e( 'Über uns', 'seehafen' ); ?></span>
				<h1><?php esc_html_e( 'Drei Persönlichkeiten.<br />Eine Leidenschaft.', 'seehafen' ); ?></h1>
				<p class="company-about-lead"><?php esc_html_e( 'Wir betreuen Immobilien mit Engagement, Fachwissen und Weitblick – persönlich, effizient und immer im Interesse unserer Kundschaft.', 'seehafen' ); ?></p>
			</div>
			<div class="company-about-aside">
				<div class="company-about-text">
					<p><?php esc_html_e( 'Als unabhängiges Immobilienunternehmen hören wir zu, denken voraus und schaffen klare Lösungen.', 'seehafen' ); ?></p>
					<p><?php esc_html_e( 'Unser Anspruch ist, Immobilien nicht nur zu verwalten oder zu vermitteln, sondern Werte nachhaltig zu sichern und weiterzuentwickeln.', 'seehafen' ); ?></p>
				</div>
				<nav class="company-about-nav" aria-label="<?php esc_attr_e( 'Firma entdecken', 'seehafen' ); ?>">
					<a href="#team"><span>01</span><strong><?php esc_html_e( 'Unser Team', 'seehafen' ); ?></strong><?php seehafen_icon( 'arrow-right' ); ?></a>
					<a href="#werte"><span>02</span><strong><?php esc_html_e( 'Werte & Arbeitsweise', 'seehafen' ); ?></strong><?php seehafen_icon( 'arrow-right' ); ?></a>
				</nav>
			</div>
		</div>
	</section>

	<section class="company-team" id="team">
		<div class="content">
			<div class="company-section-heading">
				<span class="kicker"><?php esc_html_e( 'Unser Team', 'seehafen' ); ?></span>
				<h2><?php esc_html_e( 'Persönlich für Sie da.', 'seehafen' ); ?></h2>
				<p><?php esc_html_e( 'Drei Persönlichkeiten, ein gemeinsamer Anspruch: Ihre Immobilie zuverlässig und mit Weitblick zu begleiten.', 'seehafen' ); ?></p>
			</div>
			<div class="team-grid">
				<?php
				while ( $team->have_posts() ) :
					$team->the_post();

					$initials = get_post_meta( get_the_ID(), '_seehafen_initials', true );
					$role     = get_post_meta( get_the_ID(), '_seehafen_role', true );
					$bio      = get_post_meta( get_the_ID(), '_seehafen_bio', true );
					?>
					<article>
						<span class="team-avatar"><?php echo esc_html( $initials ); ?></span>
						<h2><?php the_title(); ?></h2>
						<strong><?php echo esc_html( $role ); ?></strong>
						<p><?php echo esc_html( $bio ); ?></p>
					</article>
				<?php endwhile; ?>

				<?php wp_reset_postdata(); ?>
			</div>
		</div>
	</section>

	<section class="company-values" id="werte">
		<div class="content">
			<div class="company-section-heading">
				<span class="kicker"><?php esc_html_e( 'Werte & Arbeitsweise', 'seehafen' ); ?></span>
				<h2><?php esc_html_e( 'Klar in der Haltung.<br />Strukturiert im Handeln.', 'seehafen' ); ?></h2>
				<p><?php esc_html_e( 'Unsere Zusammenarbeit basiert auf Vertrauen, transparenter Kommunikation und einem verlässlichen Vorgehen.', 'seehafen' ); ?></p>
			</div>
			<div class="company-values-layout">
				<div class="company-values-column">
					<h3><?php esc_html_e( 'Unsere Werte', 'seehafen' ); ?></h3>
					<div class="company-detail-list">
						<?php foreach ( $values as $index => $value ) : ?>
							<article>
								<span><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
								<div>
									<h4><?php echo esc_html( $value[0] ); ?></h4>
									<p><?php echo esc_html( $value[1] ); ?></p>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="company-values-column">
					<h3><?php esc_html_e( 'So arbeiten wir', 'seehafen' ); ?></h3>
					<div class="company-detail-list">
						<?php foreach ( $process as $step ) : ?>
							<article>
								<span><?php echo esc_html( $step[0] ); ?></span>
								<div>
									<h4><?php echo esc_html( $step[1] ); ?></h4>
									<p><?php echo esc_html( $step[2] ); ?></p>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php seehafen_cta_section(); ?>

<?php
get_footer();
