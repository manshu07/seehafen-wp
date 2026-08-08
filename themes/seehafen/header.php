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
					'menu_class'     => 'main-nav-list',
					'walker'         => new Seehafen_Nav_Walker(),
					'fallback_cb'    => false,
				)
			);
			?>
			<a class="header-cta" href="<?php echo esc_url( home_url( '/kontakt' ) ); ?>"><?php esc_html_e( 'Kostenlose Bewertung', 'seehafen' ); ?> →</a>
		</nav>
		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-navigation" aria-label="<?php esc_attr_e( 'Menü öffnen', 'seehafen' ); ?>">
			<span class="nav-toggle-icon"></span>
		</button>
	</div>
</header>
<main id="main-content">
