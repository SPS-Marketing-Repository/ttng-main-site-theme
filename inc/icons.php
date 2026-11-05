<?php
/**
 * icons.php – Icon picker: the designer's icon set, as an option on icon fields.
 *
 * Each module's icon has two fields: icon_picker (a dropdown of this set, or
 * "Upload my own") and its existing image field, shown only for "Upload my own".
 * Rows saved before the picker existed default to "upload", so they keep their
 * uploaded icons.
 *
 * Icons output inline with stroke="currentColor", so each module's CSS sets the
 * colour to suit its background with the `color` property.
 *
 * The set is whatever is in assets/images/icon-library/, built by
 * bin/build-icon-library.py. Adding a file adds it to the dropdown.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * The icons available to pick.
 *
 * @return array Labels keyed by slug, e.g. 'business-advice' => 'Business advice'.
 */
function ttng_get_icon_library() {
	static $library = null;

	if ( null !== $library ) {
		return $library;
	}

	$library = array();
	$files   = glob( get_theme_file_path( 'assets/images/icon-library/*.svg' ) );

	foreach ( (array) $files as $file ) {
		$slug             = basename( $file, '.svg' );
		$library[ $slug ] = ucfirst( str_replace( '-', ' ', $slug ) );
	}

	return $library;
}

/**
 * Inline SVG for an icon in the set.
 *
 * @param string $slug Icon slug.
 * @return string SVG markup from the theme's own files, or empty if there's no such icon.
 */
function ttng_get_library_icon( $slug ) {
	static $cache = array();

	if ( ! isset( ttng_get_icon_library()[ $slug ] ) ) {
		return '';
	}

	if ( ! isset( $cache[ $slug ] ) ) {
		$svg            = file_get_contents( get_theme_file_path( 'assets/images/icon-library/' . $slug . '.svg' ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.
		$cache[ $slug ] = $svg ? trim( $svg ) : '';
	}

	return $cache[ $slug ];
}

/**
 * The markup for a module's icon, from its picker and upload fields.
 *
 * @param string $picked   icon_picker value: an icon slug or 'upload'.
 * @param int    $image_id Uploaded image, used for 'upload' or an icon no longer in the set.
 * @return string Inline SVG or an image tag. Empty if there's no icon.
 */
function ttng_get_field_icon( $picked, $image_id ) {
	$svg = $picked && 'upload' !== $picked ? ttng_get_library_icon( $picked ) : '';

	if ( $svg ) {
		return $svg;
	}

	return $image_id ? wp_get_attachment_image( $image_id, 'thumbnail', false, array( 'alt' => '' ) ) : '';
}

/**
 * Lists the set in the dropdown. On prepare_field, which only runs when the field
 * shows on an edit screen, so the choices never get saved into acf-json.
 *
 * @param array $field The field.
 * @return array The field with its choices.
 */
function ttng_icon_picker_choices( $field ) {
	$field['choices'] = array( 'upload' => __( 'Upload my own', 'ttng-main-site-theme' ) ) + ttng_get_icon_library();

	return $field;
}
add_filter( 'acf/prepare_field/name=icon_picker', 'ttng_icon_picker_choices' );

/**
 * Adds the previews to the picker's dropdown.
 *
 * @return void
 */
function ttng_icon_picker_assets() {
	$script = 'assets/js/admin-icons.js';
	$style  = 'assets/css/admin-icons.css';

	wp_enqueue_style( 'ttng-admin-icons', get_theme_file_uri( $style ), array(), filemtime( get_theme_file_path( $style ) ) );
	wp_enqueue_script( 'ttng-admin-icons', get_theme_file_uri( $script ), array( 'jquery', 'acf-input' ), filemtime( get_theme_file_path( $script ) ), true );
	wp_localize_script( 'ttng-admin-icons', 'ttngIconPicker', array( 'baseUrl' => get_theme_file_uri( 'assets/images/icon-library/' ) ) );
}
add_action( 'acf/input/admin_enqueue_scripts', 'ttng_icon_picker_assets' );
