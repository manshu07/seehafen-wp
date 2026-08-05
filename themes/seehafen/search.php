<?php
/**
 * Search results template.
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<section class="references-title">
		<div class="content">
			<h1>
				<?php
				/* translators: %s: search query */
				printf( esc_html__( 'Suchergebnisse für: %s', 'seehafen' ), esc_html( get_search_query() ) );
				?>
			</h1>
		</div>
	</section>
	<section class="reference-archive">
		<div class="content">
			<?php if ( have_posts() ) : ?>
				<div class="reference-archive-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<a class="overview-link-card" href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'full' ); ?>
							<?php endif; ?>
							<div>
								<h2><?php the_title(); ?></h2>
							</div>
						</a>
					<?php endwhile; ?>
				</div>
			<?php else : ?>
				<p><?php esc_html_e( 'Keine Ergebnisse gefunden.', 'seehafen' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

<?php
get_footer();
