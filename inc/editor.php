<?php
/**
 * editor.php – TinyMCE and editor enhancements
 */

defined( 'ABSPATH' ) || exit;

// Load custom editor stylesheet
add_action('admin_init', function () {
    add_editor_style('css/custom-editor-style.css');
});

// Add styleselect to TinyMCE buttons
add_filter('mce_buttons', function ($buttons) {
    array_unshift($buttons, 'styleselect');
    return $buttons;
});