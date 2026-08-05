<?php
/**
 * Template Name: Rechtliches (Legal pages: Impressum, Datenschutz, AGB)
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<?php
	seehafen_page_hero(
		__( 'Rechtliches', 'seehafen' ),
		get_the_title(),
		get_the_excerpt(),
		get_template_directory_uri() . '/assets/img/property-hero.jpg'
	);
	?>
	<section class="legal-page">
		<div class="content legal-content">
			<?php the_content(); ?>
		</div>
	</section>
	<?php
endwhile;

get_footer();
