<?php
/**
 * theme-setup.php – Theme setup functions
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'ttng_theme_setup' ) ) :

/**
 * Set up theme defaults and register support for WordPress features.
 */
function ttng_theme_setup() {

	// Core supports.
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Menus. The three footer columns use the menu's own name as the column
	// heading (see template-parts/footer/site-footer.php), so name the menus
	// e.g. "Membership Options", "Terms & Conditions", "Consumer Information".
	register_nav_menus(
		array(
			'primary'      => __( 'Primary Navigation', 'ttng-main-site-theme' ),
			'mobile'       => __( 'Mobile Navigation', 'ttng-main-site-theme' ),
			'footer_one'   => __( 'Footer Column 1', 'ttng-main-site-theme' ),
			'footer_two'   => __( 'Footer Column 2', 'ttng-main-site-theme' ),
			'footer_three' => __( 'Footer Column 3', 'ttng-main-site-theme' ),
			'legal'        => __( 'Legal Navigation (sub footer)', 'ttng-main-site-theme' ),
		)
	);

	// Generic image sizes for module authors to reach for instead of 'full'.
	// Add more per-project in a client theme's own theme-setup.php override,
	// not here — keep the base set small.
	add_image_size( 'ttng-card', 600, 400, true );      // 3:2 — listing/card grids
	add_image_size( 'ttng-featured', 1600, 900, true );  // 16:9 — hero/featured banners
}

endif;

add_action( 'after_setup_theme', 'ttng_theme_setup' );
