<?php
/**
 * security.php – Security and WordPress cleanup.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Head cleanup.
 */
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );
remove_action( 'welcome_panel', 'wp_welcome_panel' );

// Hide WordPress version.
add_filter( 'the_generator', '__return_empty_string' );

/**
 * Auto-updates & update emails.
 *
 * IMPORTANT:
 * This only disables auto-updates if the project explicitly opts in
 * by defining TTNG_DISABLE_AUTO_UPDATES as true in wp-config.php, e.g.:
 *
 * define( 'TTNG_DISABLE_AUTO_UPDATES', true );
 */
if ( defined( 'TTNG_DISABLE_AUTO_UPDATES' ) && TTNG_DISABLE_AUTO_UPDATES ) {

	// Disable plugin auto-update emails.
	add_filter( 'auto_plugin_update_send_email', '__return_false' );

	// Disable theme auto-update emails.
	add_filter( 'auto_theme_update_send_email', '__return_false' );

	// Disable plugin auto-updates.
	add_filter( 'auto_update_plugin', '__return_false' );

	// Disable theme auto-updates.
	add_filter( 'auto_update_theme', '__return_false' );

	// Optional: if you also want to disable core auto-updates and emails,
	// uncomment these:
	/*
	add_filter( 'auto_update_core', '__return_false' );
	add_filter( 'auto_core_update_send_email', '__return_false' );
	*/
}

/**
 * Limit post revisions.
 *
 * NOTE: Ideally WP_POST_REVISIONS is defined in wp-config.php.
 * This is a fallback if it isn't already defined.
 */
if ( ! defined( 'WP_POST_REVISIONS' ) ) {
	define( 'WP_POST_REVISIONS', 5 );
}

// Note: query-string stripping removed — it negated filemtime() cache-busting in assets.php.

/**
 * Disable XML-RPC.
 *
 * If you use Jetpack, the mobile app, or external publishing tools,
 * you may need to turn this back on.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Remove some default dashboard widgets.
 */
function ttng_cleanup_dashboard_widgets() {
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', 'ttng_cleanup_dashboard_widgets' );
