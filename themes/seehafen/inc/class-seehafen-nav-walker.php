<?php
/**
 * Nav walker: renders SPA-style dropdown groups.
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
		$item_class = $has_children ? 'nav-dropdown' : 'nav-item';
		$output    .= '<li class="' . esc_attr( $item_class ) . '">';

		if ( $has_children && 0 === $depth ) {
			$output .= '<div class="nav-dropdown-trigger">';
			$output .= '<a href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a>';
			$output .= '<button type="button" aria-expanded="false" aria-label="' . esc_attr( $item->title ) . ' Untermenü öffnen"><span class="chevron">▾</span></button>';
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
		$output .= '</li>';
	}

	/**
	 * Start level.
	 *
	 * @param string   $output Passed by reference.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<div class="dropdown-panel"><div class="dropdown-panel-inner">';
	}

	/**
	 * End level.
	 *
	 * @param string   $output Passed by reference.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</div></div>';
	}
}
