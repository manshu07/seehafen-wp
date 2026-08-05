<?php
/**
 * Template Name: Dienstleistungen (Services overview)
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

get_header();

$primary    = seehafen_get_services( 'primary' );
$additional = seehafen_get_services( 'additional' );
?>
	<?php
	seehafen_page_hero(
		__( 'Dienstleistungen', 'seehafen' ),
		__( 'Immobilien. Einfach gut begleitet.', 'seehafen' ),
		__( 'Umfassende Immobiliendienstleistungen für Eigentümer, Investoren und Mieter – mit einem festen Ansprechpartner und klaren Lösungen.', 'seehafen' ),
		get_template_directory_uri() . '/assets/img/property-hero.jpg'
	);
	?>

	<section class="services-page">
		<div class="content primary-service-grid">
			<?php
			while ( $primary->have_posts() ) :
				$primary->the_post();

				$image = get_post_meta( get_the_ID(), '_seehafen_hero_image', true );

				if ( ! $image ) {
					$image = get_template_directory_uri() . '/assets/img/property-hero.jpg';
				}
				?>
				<article class="primary-service-card">
					<img src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy" />
					<div>
						<h2><?php the_title(); ?></h2>
						<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Mehr erfahren', 'seehafen' ); ?> <?php seehafen_icon( 'arrow-right' ); ?></a>
					</div>
				</article>
			<?php endwhile; ?>

			<?php wp_reset_postdata(); ?>
		</div>
	</section>

	<section class="secondary-services">
		<div class="content">
			<div class="section-heading split-heading">
				<div>
					<span class="kicker"><?php esc_html_e( 'Weitere Fachbereiche', 'seehafen' ); ?></span>
					<h2><?php esc_html_e( 'Ergänzend für Sie da.', 'seehafen' ); ?></h2>
				</div>
				<p><?php esc_html_e( 'Bei komplexeren Vorhaben koordinieren wir auch die angrenzenden Themen – übersichtlich und aus einer Hand.', 'seehafen' ); ?></p>
			</div>
			<div class="secondary-service-grid">
				<?php
				while ( $additional->have_posts() ) :
					$additional->the_post();

					$image  = get_post_meta( get_the_ID(), '_seehafen_hero_image', true );

					if ( ! $image ) {
						$image = get_template_directory_uri() . '/assets/img/property-1.jpg';
					}

					$points = get_post_meta( get_the_ID(), '_seehafen_points', true );
					$points = array_filter( array_map( 'trim', explode( "\n", $points ) ) );
					?>
					<article id="<?php echo esc_attr( get_post_field( 'post_name', get_the_ID() ) ); ?>">
						<img src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy" />
						<div>
							<h3><?php the_title(); ?></h3>
							<p><?php echo esc_html( get_post_meta( get_the_ID(), '_seehafen_copy', true ) ); ?></p>
							<ul>
								<?php foreach ( $points as $point ) : ?>
									<li><?php seehafen_icon( 'check' ); ?> <?php echo esc_html( $point ); ?></li>
								<?php endforeach; ?>
							</ul>
							<a href="<?php echo esc_url( home_url( '/kontakt/' ) ); ?>"><?php esc_html_e( 'Beratung anfragen', 'seehafen' ); ?> <?php seehafen_icon( 'arrow-right' ); ?></a>
						</div>
					</article>
				<?php endwhile; ?>

				<?php wp_reset_postdata(); ?>
			</div>
		</div>
	</section>

	<?php seehafen_process_section( true ); ?>

	<?php seehafen_cta_section(); ?>

<?php
get_footer();
