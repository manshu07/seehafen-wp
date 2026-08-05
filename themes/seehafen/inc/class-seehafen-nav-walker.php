<?php
/**
 * Custom nav walker producing the Seehafen dropdown markup (1:1 with the SPA).
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

/**
 * Walker that renders top-level items with children as dropdown groups.
 */
class Seehafen_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Panel ID of the currently open dropdown.
	 *
	 * @var string
	 */
	private $current_panel_id = '';

	/**
	 * Starts the element output.
	 *
	 * @param string   $output Passed by reference.
	 * @param WP_Post  $item   Menu item object.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Menu args.
	 * @param int      $id     Current object ID.
	 *
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes     = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );
		$is_active   = in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-ancestor', $classes, true );

		if ( 0 === $depth ) {
			if ( $has_children ) {
				$output .= $this->open_dropdown( $item, $is_active );
			} else {
				$output .= $this->render_link( $item, $is_active );
			}
		} else {
			$output .= $this->render_child_link( $item );
		}
	}

	/**
	 * Ends the element output.
	 *
	 * @param string   $output Passed by reference.
	 * @param WP_Post  $item   Menu item object.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Menu args.
	 *
	 * @return void
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;

			if ( in_array( 'menu-item-has-children', $classes, true ) ) {
				$output .= '</div>';
			}
		}
	}

	/**
	 * Starts the submenu list.
	 *
	 * @param string   $output Passed by reference.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Menu args.
	 *
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<div class="dropdown-panel" id="' . esc_attr( $this->current_panel_id ) . '">';
	}

	/**
	 * Ends the submenu list.
	 *
	 * @param string   $output Passed by reference.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Menu args.
	 *
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</div>';
	}

	/**
	 * Open a dropdown group container.
	 *
	 * @param WP_Post $item      Menu item.
	 * @param bool    $is_active Whether the group is active.
	 *
	 * @return string
	 */
	private function open_dropdown( $item, $is_active ) {
		$title    = apply_filters( 'the_title', $item->title, $item->ID );
		$panel_id = 'nav-' . sanitize_title( $title ) . '-panel';
		$group_cls = 'nav-dropdown' . ( $is_active ? ' is-active' : '' );

		$this->current_panel_id = $panel_id;

		$output  = '<div class="' . esc_attr( $group_cls ) . '">';
		$output .= '<div class="nav-dropdown-trigger">';
		$output .= '<a href="' . esc_url( $item->url ) . '"' . ( $is_active ? ' aria-current="page"' : '' ) . '>' . esc_html( $title ) . '</a>';
		$output .= '<button type="button" aria-expanded="false" aria-controls="' . esc_attr( $panel_id ) . '" aria-label="' . esc_attr( $title ) . ' Untermenü öffnen">';
		$output .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>';
		$output .= '</button>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * Render a top-level link (no children).
	 *
	 * @param WP_Post $item      Menu item.
	 * @param bool    $is_active Whether the item is the current page.
	 *
	 * @return string
	 */
	private function render_link( $item, $is_active ) {
		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$class = $is_active ? ' nav-link is-active' : ' nav-link';

		$output  = '<a class="' . esc_attr( $class ) . '" href="' . esc_url( $item->url ) . '"';
		$output .= $is_active ? ' aria-current="page"' : '';
		$output .= '>' . esc_html( $title ) . '</a>';

		return $output;
	}

	/**
	 * Render a child link inside a dropdown panel.
	 *
	 * @param WP_Post $item Child menu item.
	 *
	 * @return string
	 */
	private function render_child_link( $item ) {
		$title    = apply_filters( 'the_title', $item->title, $item->ID );
		$external = 0 === strpos( $item->url, 'http' );

		$output  = '<a href="' . esc_url( $item->url ) . '"';
		$output .= $external ? ' target="_blank" rel="noreferrer"' : '';
		$output .= '>' . esc_html( $title ) . '</a>';

		return $output;
	}
}
