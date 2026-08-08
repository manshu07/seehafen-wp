<?php
/**
 * Seehafen header — matches the SPA header (logo, dropdown nav, CTA).
 *
 * @package Seehafen
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e( 'Zum Inhalt springen', 'seehafen' ); ?></a>
<header class="site-header">
	<div class="nav-shell">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" aria-label="Seehafen & Partner – Startseite">
			<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/logo.png' ); ?>" alt="Seehafen & Partner Immobilien AG" />
		</a>
		<nav id="main-navigation" class="main-nav" aria-label="Hauptnavigation">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'walker'         => new Seehafen_Nav_Walker(),
					'fallback_cb'    => false,
				)
			);
			?>
			<a class="header-cta" href="<?php echo esc_url( home_url( '/kontakt' ) ); ?>"><?php esc_html_e( 'Kostenlose Bewertung', 'seehafen' ); ?> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>
		</nav>
		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-navigation" aria-label="<?php esc_attr_e( 'Menü öffnen', 'seehafen' ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu" aria-hidden="true"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
		</button>
	</div>
</header>
<main id="main-content">
