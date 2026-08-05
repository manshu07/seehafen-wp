<?php
/**
 * Template Name: Referenzen (References archive)
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

get_header();

$all_references = seehafen_get_references( '', -1 );
$visible_count  = 9;
?>
	<section class="references-title">
		<div class="content">
			<h1><?php esc_html_e( 'Referenzen', 'seehafen' ); ?></h1>
		</div>
	</section>
	<section class="reference-archive">
		<div class="content">
			<div class="reference-archive-grid" id="reference-grid" data-reference-grid data-total="<?php echo esc_attr( $all_references->post_count ); ?>">
				<?php
				$index = 0;

				while ( $all_references->have_posts() ) :
					$all_references->the_post();

					if ( $index >= $visible_count ) {
						break;
					}

					seehafen_reference_tile( get_post() );
					$index++;
				endwhile;

				wp_reset_postdata();
				?>
			</div>
			<?php if ( $all_references->post_count > $visible_count ) : ?>
				<div class="reference-show-more">
					<button class="button button-solid" type="button" data-reference-more aria-controls="reference-grid" aria-expanded="false">
						<?php esc_html_e( 'Mehr anzeigen', 'seehafen' ); ?> <?php seehafen_icon( 'arrow-down' ); ?>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php seehafen_cta_section(); ?>

<?php
get_footer();
