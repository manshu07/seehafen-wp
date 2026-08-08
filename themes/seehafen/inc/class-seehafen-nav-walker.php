<?php
/**
 * Nav walker: renders SPA-style dropdown groups.
 * Depth 0 items render as <li> direct children of .main-nav (via items_wrap=%3$s).
 * Depth 1+ items render as bare <a> inside .dropdown-panel (exactly like the SPA).
 *
 * @package Seehafen
 */

/**
 * Menu walker.
 */
class Seehafen_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Start element.
	 *
	 * @param string   $output Passed by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );

		if ( $depth > 0 ) {
			// Sub-items: bare link inside the dropdown panel, like the SPA.
			$output .= '<a href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a>';
			return;
		}

		$item_class = $has_children ? 'nav-dropdown' : 'nav-item';
		$output    .= '<li class="' . esc_attr( $item_class ) . '">';

		if ( $has_children ) {
			$output .= '<div class="nav-dropdown-trigger">';
			$output .= '<a href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a>';
			$output .= '<button type="button" aria-expanded="false" aria-label="' . esc_attr( $item->title ) . ' Untermenü öffnen"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></button>';
			$output .= '</div>';
		} else {
			$output .= '<a href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a>';
		}
	}

	/**
	 * End element.
	 *
	 * @param string   $output Passed by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '</li>';
		}
	}

	/**
	 * Start level.
	 *
	 * @param string   $output Passed by reference.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<div class="dropdown-panel">';
	}

	/**
	 * End level.
	 *
	 * @param string   $output Passed by reference.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</div>';
	}
}
