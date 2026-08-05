<?php
/**
 * Fallback template (also used for reference archives).
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( is_post_type_archive( 'reference' ) ) :
	?>
	<section class="references-title">
		<div class="content">
			<h1><?php esc_html_e( 'Referenzen', 'seehafen' ); ?></h1>
		</div>
	</section>
	<section class="reference-archive">
		<div class="content">
			<div class="reference-archive-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					seehafen_reference_tile( get_post() );
				endwhile;
				?>
			</div>
		</div>
	</section>
	<?php
elseif ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'content single-content' ); ?>>
			<h1><?php the_title(); ?></h1>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endwhile;
else :
	?>
	<section class="not-found">
		<div class="content">
			<span>404</span>
			<h1><?php esc_html_e( 'Diese Seite wurde nicht gefunden.', 'seehafen' ); ?></h1>
			<a class="button button-solid" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Zur Startseite', 'seehafen' ); ?> <?php seehafen_icon( 'arrow-right' ); ?></a>
		</div>
	</section>
	<?php
endif;

get_footer();
