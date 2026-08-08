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
			<a href="tel:+4144514302"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> +41 44 451 43 02</a>
			<a href="tel:+41797857880"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> +41 79 785 78 80</a>
			<a href="mailto:info@seehafen-immobilien.ch"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail" aria-hidden="true"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg> info@seehafen-immobilien.ch</a>
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
