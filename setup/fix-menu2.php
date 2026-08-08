<?php
/**
 * One-time: remove the Kontakt item from Hauptmenü (nav = 3 dropdowns + CTA, exactly like the SPA).
 * Run: wp eval-file /var/www/html/wp-content/fix-menu2.php --allow-root
 */

$menu = wp_get_nav_menu_object( 'Hauptmenü' );
if ( ! $menu ) {
	wp_die( 'Hauptmenü not found' );
}
$items = wp_get_nav_menu_items( $menu->term_id );
$removed = 0;
foreach ( $items as $item ) {
	if ( 'Kontakt' === $item->title && home_url( '/kontakt' ) === $item->url ) {
		wp_delete_post( $item->ID, true );
		$removed++;
	}
}
echo "Removed Kontakt items: {$removed}" . PHP_EOL;
