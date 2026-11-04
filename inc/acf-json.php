<?php
/**
 * acf-json.php – ACF Local JSON paths
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Change where ACF saves JSON.
 * Saves to the child theme's /acf-json folder (child-theme-first).
 *
 * @param string $path Default path.
 * @return string New path.
 */
function ttng_acf_json_save_point( $path ) {
	return get_stylesheet_directory() . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'ttng_acf_json_save_point' );

/**
 * Change where ACF loads JSON from.
 * Child theme first, then parent.
 *
 * @param array $paths Default load paths.
 * @return array Modified paths.
 */
function ttng_acf_json_load_points( $paths ) {
	// Remove the default path (theme root).
	if ( isset( $paths[0] ) ) {
		unset( $paths[0] );
	}

	// Child theme first, then parent.
	$paths[] = get_stylesheet_directory() . '/acf-json';
	$paths[] = get_template_directory() . '/acf-json';

	return $paths;
}
add_filter( 'acf/settings/load_json', 'ttng_acf_json_load_points' );
