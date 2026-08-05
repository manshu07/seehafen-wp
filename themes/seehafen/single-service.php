<?php
/**
 * Single service detail page.
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$lead         = get_post_meta( get_the_ID(), '_seehafen_lead', true );
	$heading      = get_post_meta( get_the_ID(), '_seehafen_heading', true );
	$copy         = get_post_meta( get_the_ID(), '_seehafen_copy', true );
	$detail_image = get_post_meta( get_the_ID(), '_seehafen_detail_image', true );

	if ( ! $detail_image ) {
		$detail_image = get_template_directory_uri() . '/assets/img/team-1.jpg';
	}

	$points = get_post_meta( get_the_ID(), '_seehafen_points', true );
	$points = array_filter( array_map( 'trim', explode( "\n", $points ) ) );
	?>
	<section class="service-detail">
		<div class="content service-detail-header-grid">
			<div class="service-detail-heading">
				<span class="kicker"><?php esc_html_e( 'Dienstleistungen', 'seehafen' ); ?></span>
				<h1><?php the_title(); ?></h1>
				<p><?php echo esc_html( $lead ); ?></p>
			</div>
			<div class="service-detail-explanation">
				<span class="kicker"><?php esc_html_e( 'Unsere Leistung', 'seehafen' ); ?></span>
				<h2><?php echo esc_html( $heading ); ?></h2>
				<p><?php echo esc_html( $copy ); ?></p>
				<a class="button button-solid" href="<?php echo esc_url( home_url( '/kontakt/' ) ); ?>"><?php esc_html_e( 'Beratung anfragen', 'seehafen' ); ?> <?php seehafen_icon( 'arrow-right' ); ?></a>
			</div>
		</div>
		<div class="service-detail-support-wrap">
			<div class="content service-detail-support">
				<img src="<?php echo esc_url( $detail_image ); ?>" alt="" />
				<ul class="service-detail-points">
					<?php foreach ( $points as $point ) : ?>
						<li><?php seehafen_icon( 'check' ); ?> <span><?php echo esc_html( $point ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</section>

	<?php seehafen_cta_section(); ?>
	<?php
endwhile;

get_footer();
