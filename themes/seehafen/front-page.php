<?php
/**
 * Front page — Home (hero, services, offers, references, CTA).
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

get_header();

$hero_eyebrow = get_theme_mod( 'seehafen_hero_eyebrow', 'Langfristig. Persönlich. Verlässlich.' );
$hero_title   = get_theme_mod( 'seehafen_hero_title', 'Immobilien<br />mit Weitblick.' );
$hero_lead    = get_theme_mod( 'seehafen_hero_lead', 'Persönliche Beratung, verantwortungsvolle Entscheidungen und engagierte Begleitung.' );
$hero_image   = get_theme_mod( 'seehafen_hero_image', get_template_directory_uri() . '/assets/img/hero-team-house.png' );
$home_kicker  = get_theme_mod( 'seehafen_home_kicker', 'Unsere Expertise' );
$home_heading = get_theme_mod( 'seehafen_home_heading', 'Persönlich begleitet.<br />Klar entschieden.' );
$home_intro   = get_theme_mod( 'seehafen_home_intro', 'Der Verkauf oder die Bewirtschaftung einer Liegenschaft ist mehr als eine Transaktion. Wir führen Sie sicher durch den gesamten Prozess – professionell, transparent und mit Herzblut.' );
?>
	<section class="hero">
		<img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php esc_attr_e( 'Das Seehafen-Team im Gespräch vor einer modernen Immobilie', 'seehafen' ); ?>" />
		<div class="hero-overlay" />
		<div class="content hero-content">
			<p class="hero-eyebrow"><?php echo esc_html( $hero_eyebrow ); ?></p>
			<h1><?php echo wp_kses_post( $hero_title ); ?></h1>
			<p class="hero-lead"><?php echo esc_html( $hero_lead ); ?></p>
			<a class="button hero-cta" href="<?php echo esc_url( home_url( '/kontakt/' ) ); ?>">
				<?php seehafen_icon( 'calendar' ); ?>
				<?php esc_html_e( 'Beratung vereinbaren', 'seehafen' ); ?>
			</a>
		</div>
	</section>

	<section id="expertise" class="home-intro">
		<div class="content">
			<div class="home-heading">
				<div>
					<span class="kicker"><?php echo esc_html( $home_kicker ); ?></span>
					<h2><?php echo wp_kses_post( $home_heading ); ?></h2>
				</div>
				<p><?php echo esc_html( $home_intro ); ?></p>
			</div>
			<div class="home-services">
				<?php
				$services = seehafen_get_services( 'primary' );

				while ( $services->have_posts() ) :
					$services->the_post();

					$image = get_post_meta( get_the_ID(), '_seehafen_home_image', true );

					if ( ! $image ) {
						$image = get_post_meta( get_the_ID(), '_seehafen_hero_image', true );
					}

					if ( ! $image ) {
						$image = get_template_directory_uri() . '/assets/img/team-1.jpg';
					}

					$home_text = get_post_meta( get_the_ID(), '_seehafen_home_text', true );

					if ( '' === $home_text ) {
						$home_text = get_post_meta( get_the_ID(), '_seehafen_lead', true );
					}
					?>
					<article class="home-service-card">
						<img src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy" />
						<div>
							<h3><?php the_title(); ?></h3>
							<p><?php echo esc_html( $home_text ); ?></p>
							<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Mehr erfahren', 'seehafen' ); ?> <?php seehafen_icon( 'arrow-right' ); ?></a>
						</div>
					</article>
				<?php endwhile; ?>

				<?php wp_reset_postdata(); ?>
			</div>
		</div>
	</section>

	<section class="home-offers" id="angebote">
		<div class="content">
			<?php seehafen_offer_showcase(); ?>
		</div>
	</section>

	<section class="home-references">
		<div class="content">
			<div class="section-heading">
				<div>
					<span class="kicker"><?php esc_html_e( 'Referenzen', 'seehafen' ); ?></span>
					<h2><?php esc_html_e( 'Kürzlich verkaufte Objekte.', 'seehafen' ); ?></h2>
				</div>
				<a class="text-link" href="<?php echo esc_url( home_url( '/referenzen/' ) ); ?>"><?php esc_html_e( 'Alle Referenzen', 'seehafen' ); ?> <?php seehafen_icon( 'arrow-right' ); ?></a>
			</div>
			<div class="reference-preview-grid">
				<?php
				$references = seehafen_get_references( 'verkauft', 3 );

				while ( $references->have_posts() ) :
					$references->the_post();
					seehafen_reference_tile( get_post() );
				endwhile;

				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>

	<?php seehafen_cta_section(); ?>

<?php
get_footer();
