<?php
/**
 * Default page template (fallback for pages without a custom template).
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="legal-page">
		<div class="content legal-content">
			<?php the_content(); ?>
		</div>
	</section>
	<?php
endwhile;

get_footer();
