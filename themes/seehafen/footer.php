<?php
/**
 * Site footer — brand, offices, direct contact, legal links.
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

$phone_land   = get_theme_mod( 'seehafen_phone_land', '+41 44 451 43 02' );
$phone_mobile = get_theme_mod( 'seehafen_phone_mobile', '+41 79 785 78 80' );
$email        = get_theme_mod( 'seehafen_email', 'info@seehafen-immobilien.ch' );
$address_main = get_theme_mod( 'seehafen_address_main', "Bahnhofstrasse 4\n6430 Schwyz" );
$address_branch = get_theme_mod( 'seehafen_address_branch', "Cheiblerrain 13\n5610 Wohlen" );
$footer_text  = get_theme_mod( 'seehafen_footer_text', 'Persönliche Immobiliendienstleistungen mit Weitblick – in Schwyz, Wohlen und der ganzen Schweiz.' );
?>
</main>

<footer class="footer">
	<div class="content footer-main">
		<div class="footer-brand">
			<?php seehafen_logo(); ?>
			<p><?php echo esc_html( $footer_text ); ?></p>
		</div>
		<div>
			<strong><?php esc_html_e( 'Hauptsitz Schwyz', 'seehafen' ); ?></strong>
			<p><?php echo nl2br( esc_html( $address_main ) ); ?></p>
			<strong><?php esc_html_e( 'Filiale Wohlen', 'seehafen' ); ?></strong>
			<p><?php echo nl2br( esc_html( $address_branch ) ); ?></p>
		</div>
		<div class="footer-contact">
			<strong><?php esc_html_e( 'Direkter Kontakt', 'seehafen' ); ?></strong>
			<a href="<?php echo esc_url( seehafen_phone_land_tel() ); ?>"><?php seehafen_icon( 'phone' ); ?> <?php echo esc_html( $phone_land ); ?></a>
			<a href="<?php echo esc_url( seehafen_phone_mobile_tel() ); ?>"><?php seehafen_icon( 'phone' ); ?> <?php echo esc_html( $phone_mobile ); ?></a>
			<a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php seehafen_icon( 'mail' ); ?> <?php echo esc_html( $email ); ?></a>
		</div>
	</div>
	<div class="content footer-bottom">
		<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php esc_html_e( 'Seehafen & Partner Immobilien AG', 'seehafen' ); ?></span>
		<span>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'footer',
				'container'      => false,
				'menu_class'     => 'footer-menu',
				'items_wrap'     => '%3$s',
				'depth'          => 1,
				'fallback_cb'    => false,
			) );
			?>
		</span>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
