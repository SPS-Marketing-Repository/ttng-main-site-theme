<?php
/**
 * accessibility.php – Basic accessibility improvements
 */

defined( 'ABSPATH' ) || exit;

function add_role_list_to_ul($content) {
    return preg_replace('/<ul(?![^>]*\brole=["\']?list["\']?)([^>]*)>/i', '<ul role="list"$1>', $content);
}

function buffer_ul_output_for_role_list() {
    ob_start('add_role_list_to_ul');
}
add_action('template_redirect', 'buffer_ul_output_for_role_list');

function add_listitem_roles_script() {
    if (!is_admin()) {
        wp_add_inline_script('jquery', "
            jQuery(document).ready(function($) {
                $('ul[role=\"list\"] li').attr('role', 'listitem');
            });
        ");
    }
}
add_action('wp_enqueue_scripts', 'add_listitem_roles_script');

// Add title attributes to nav menu links
add_filter('nav_menu_link_attributes', function ($atts, $item) {
    if (empty($atts['title'])) $atts['title'] = strip_tags($item->title);
    return $atts;
}, 10, 3);

// Add title attributes to Yoast breadcrumbs
add_filter('wpseo_breadcrumb_single_link', function ($link_output, $link) {
    if (isset($link['url'], $link['text'])) {
        $title = esc_attr($link['text']);
        $link_output = preg_replace('/<a /', '<a title="' . $title . '" ', $link_output, 1);
    }
    return $link_output;
}, 10, 2);

// Highlight admin bar if discourage indexing
function custom_admin_bar_color() {
    if (get_option('blog_public') === '0') {
        echo '<style>#wpadminbar { border-top: 5px solid #cf0000 !important; }</style>';
    }
}
add_action('admin_head', 'custom_admin_bar_color');
add_action('wp_head', 'custom_admin_bar_color');
