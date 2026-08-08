<?php
/**
 * Seehafen footer — matches the SPA footer.
 *
 * @package Seehafen
 */
?>
</main>
<footer class="footer">
	<div class="content footer-main">
		<div class="footer-brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
				<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/logo.png' ); ?>" alt="Seehafen & Partner Immobilien AG" />
			</a>
			<p><?php esc_html_e( 'Persönliche Immobiliendienstleistungen mit Weitblick – in Schwyz, Wohlen und der ganzen Schweiz.', 'seehafen' ); ?></p>
		</div>
		<div>
			<strong><?php esc_html_e( 'Hauptsitz Schwyz', 'seehafen' ); ?></strong>
			<p>Bahnhofstrasse 4<br />6430 Schwyz</p>
			<strong><?php esc_html_e( 'Filiale Wohlen', 'seehafen' ); ?></strong>
			<p>Cheiblerrain 13<br />5610 Wohlen</p>
		</div>
		<div class="footer-contact">
			<strong><?php esc_html_e( 'Direkter Kontakt', 'seehafen' ); ?></strong>
			<a href="tel:+41444514302"><span class="footer-icon phone"></span> +41 44 451 43 02</a>
			<a href="tel:+41797857880"><span class="footer-icon phone"></span> +41 79 785 78 80</a>
			<a href="mailto:info@seehafen-immobilien.ch"><span class="footer-icon mail"></span> info@seehafen-immobilien.ch</a>
		</div>
	</div>
	<div class="content footer-bottom">
		<span>© 2026 Seehafen &amp; Partner Immobilien AG</span>
		<span>
			<a href="<?php echo esc_url( home_url( '/impressum' ) ); ?>"><?php esc_html_e( 'Impressum', 'seehafen' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/datenschutz' ) ); ?>"><?php esc_html_e( 'Datenschutz', 'seehafen' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/agb' ) ); ?>"><?php esc_html_e( 'AGB', 'seehafen' ); ?></a>
		</span>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
