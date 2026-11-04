<?php
/**
 * frontend.php – Frontend UX/UI tweaks
 */

defined( 'ABSPATH' ) || exit;

// Add a class to the body based on the post type
add_filter('body_class', function ($classes) {
    if (is_singular()) {
        global $post;
        $classes[] = 'post-type-' . $post->post_type;
    }
    return $classes;
});

// Add page slug to body class
add_filter('body_class', function ($classes) {
    if (is_page()) {
        global $post;
        $classes[] = 'page-' . $post->post_name;
    }
    return $classes;
});
