<?php
/**
 * 404 template.
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<section class="not-found">
		<div class="content">
			<span>404</span>
			<h1><?php esc_html_e( 'Diese Seite wurde nicht gefunden.', 'seehafen' ); ?></h1>
			<p><?php esc_html_e( 'Die gewünschte Adresse existiert nicht oder wurde verschoben.', 'seehafen' ); ?></p>
			<a class="button button-solid" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Zur Startseite', 'seehafen' ); ?> <?php seehafen_icon( 'arrow-right' ); ?></a>
		</div>
	</section>

<?php
get_footer();
