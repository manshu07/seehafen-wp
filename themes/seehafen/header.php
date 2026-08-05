<?php
/**
 * Site header — nav shell with dropdown menus and mobile toggle.
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main-content"><?php esc_html_e( 'Zum Inhalt springen', 'seehafen' ); ?></a>

<header class="site-header">
	<div class="nav-shell">
		<?php seehafen_logo(); ?>

		<nav id="main-navigation" class="main-nav" aria-label="<?php esc_attr_e( 'Hauptnavigation', 'seehafen' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'main-nav-list',
				'items_wrap'     => '%3$s',
				'walker'         => new Seehafen_Nav_Walker(),
				'depth'          => 2,
				'fallback_cb'    => false,
			) );
			?>
			<a class="header-cta" href="<?php echo esc_url( home_url( '/kontakt/' ) ); ?>">
				<?php esc_html_e( 'Kostenlose Bewertung', 'seehafen' ); ?>
				<?php seehafen_icon( 'arrow-right' ); ?>
			</a>
		</nav>

		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-navigation" aria-label="<?php esc_attr_e( 'Menü öffnen', 'seehafen' ); ?>">
			<span data-nav-icon-open><?php seehafen_icon( 'menu' ); ?></span>
			<span data-nav-icon-close hidden><?php seehafen_icon( 'x' ); ?></span>
		</button>
	</div>
</header>

<main id="main-content">
