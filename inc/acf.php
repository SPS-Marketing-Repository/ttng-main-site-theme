<?php
/**
 * acf.php – ACF options page and admin tweaks
 */
defined( 'ABSPATH' ) || exit;

function ttng_acf_op_init() {
	if ( function_exists( 'acf_add_options_page' ) ) {

		// Main Site Settings Page
		acf_add_options_page( [
			'page_title' => 'Site Settings : General content used throughout the theme',
			'menu_title' => 'Site Settings',
			'menu_slug'  => 'theme-general-settings',
			'capability' => 'edit_theme_options',
			'redirect'   => false,
		] );

		// Separate Page for Text Files (e.g. humans.txt)
		acf_add_options_page( [
			'page_title' => 'Humans.txt Editor',
			'menu_title' => 'Humans.txt',
			'menu_slug'  => 'site-text-files',
			'capability' => 'manage_options',
			'redirect'   => false,
		] );

	}
}
add_action( 'acf/init', 'ttng_acf_op_init' );


// Save humans.txt content from ACF field
function ttng_write_humans_txt_on_save( $post_id ) {
	// Only trigger on Options page
	if ( 'options' !== $post_id ) return;

	// Get content from ACF textarea field (field name: 'humans_txt_content')
	$content = get_field( 'humans_txt_content', 'option', false );

	// Sanitise and write to /humans.txt if the field exists
	if ( false !== $content ) {
		$file_path = ABSPATH . 'humans.txt';
		file_put_contents( $file_path, wp_strip_all_tags( sanitize_textarea_field( $content ) ) );
	}
}
add_action( 'acf/save_post', 'ttng_write_humans_txt_on_save', 20 );


// Hide escaped HTML admin notice
add_filter( 'acf/admin/prevent_escaped_html_notice', '__return_true' );


/**
 * Module thumbnails in ACF Extended's "Add Module" picker.
 *
 * The picker itself (pop-up, 4 columns, categories, copy/paste) is set on
 * page_builder in acf-json/group_677bab909515c.json. Thumbnails aren't set
 * there: ACF Extended would save a media library ID, which doesn't exist on
 * other sites. Instead each module's thumbnail is a theme file,
 * assets/images/modules/{layout}.{jpg|png|webp|svg}, 400x250px, named after
 * the layout (e.g. page_header.svg). Every module ships with an SVG wireframe
 * drawn by bin/module-thumbnails.py; a .jpg/.png/.webp with the same name
 * (e.g. a real screenshot) takes priority. Without ACF Extended active this
 * filter never runs and the normal ACF dropdown is used.
 *
 * @param mixed $thumbnail Thumbnail from the field group (unused).
 * @param array $field     The page_builder field.
 * @param array $layout    The layout being listed.
 * @return mixed URL of the theme file, or the original value.
 */
function ttng_acfe_module_thumbnail( $thumbnail, $field, $layout ) {
	foreach ( array( 'jpg', 'png', 'webp', 'svg' ) as $extension ) {
		$file = 'assets/images/modules/' . $layout['name'] . '.' . $extension;

		if ( file_exists( get_theme_file_path( $file ) ) ) {
			return get_theme_file_uri( $file );
		}
	}

	return $thumbnail;
}
add_filter( 'acfe/flexible/thumbnail/name=page_builder', 'ttng_acfe_module_thumbnail', 10, 3 );

/**
 * Keeps thumbnail URLs out of the saved field group.
 *
 * ttng_acfe_module_thumbnail() supplies them at runtime, but saving the field
 * group in the ACF admin would otherwise write that site's URLs to acf-json.
 *
 * @param array $field_group The field group being exported.
 * @return array The field group without page builder thumbnails.
 */
function ttng_acf_strip_module_thumbnails( $field_group ) {
	foreach ( (array) ( $field_group['fields'] ?? array() ) as $field_index => $field ) {
		if ( 'page_builder' !== ( $field['name'] ?? '' ) || empty( $field['layouts'] ) ) {
			continue;
		}

		foreach ( array_keys( $field['layouts'] ) as $layout_key ) {
			$field_group['fields'][ $field_index ]['layouts'][ $layout_key ]['acfe_flexible_thumbnail'] = '';
		}
	}

	return $field_group;
}
add_filter( 'acf/prepare_field_group_for_export', 'ttng_acf_strip_module_thumbnails' );
