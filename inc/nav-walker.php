<?php
/**
 * nav-walker.php – Mega menu walker for the primary and mobile navigation.
 *
 * Built from a normal WordPress menu, no plugin:
 * - Every item with children gets a real <button> beside its link, so keyboard
 *   and touch users can open it (main.js .nav-toggle) while the link still works.
 * - A top-level item becomes a full-width mega panel (.menu-item--mega) if it
 *   has 6+ children or any grandchildren. Others stay simple dropdowns.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Adds the toggle button and mega panel class to wp_nav_menu() output.
 */
class TTNG_Mega_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Flag top-level items that need the mega panel, before they're output.
	 *
	 * @param WP_Post $element           Menu item.
	 * @param array   $children_elements Items keyed by parent ID.
	 * @param int     $max_depth         Max depth.
	 * @param int     $depth             Current depth.
	 * @param array   $args              wp_nav_menu() args.
	 * @param string  $output            Output so far.
	 * @return void
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( 0 === $depth && ! empty( $children_elements[ $element->ID ] ) ) {
			$children = $children_elements[ $element->ID ];
			$is_mega  = count( $children ) >= 6;

			foreach ( $children as $child ) {
				$is_mega = $is_mega || ! empty( $children_elements[ $child->ID ] );
			}

			if ( $is_mega ) {
				$element->classes[] = 'menu-item--mega';
			}
		}

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	/**
	 * Output one item, adding the toggle button to parents.
	 *
	 * @param string   $output Output so far.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   wp_nav_menu() args.
	 * @param int      $id     Item ID.
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		parent::start_el( $output, $item, $depth, $args, $id );

		if ( in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
			$output .= sprintf(
				'<button type="button" class="nav-toggle" aria-expanded="false"><span class="sr-only">%1$s</span>%2$s</button>',
				esc_html( sprintf( /* translators: %s: menu item title */ __( 'Show %s submenu', 'ttng-main-site-theme' ), $item->title ) ),
				ttng_icon( 'chevron' )
			);
		}
	}
}
