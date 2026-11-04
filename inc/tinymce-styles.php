<?php
/**
 * editor.php – TinyMCE and editor enhancements
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

// Load custom editor stylesheet.
add_action(
	'admin_init',
	function () {
		// Assuming you add assets/css/editor.css to the theme.
		add_editor_style( get_theme_file_uri( 'assets/css/custom-editor-style.css' ) );
	}
);

// Add styleselect to TinyMCE buttons.
add_filter(
	'mce_buttons',
	function ( $buttons ) {
		array_unshift( $buttons, 'styleselect' );
		return $buttons;
	}
);