<?php
// inc/gutenberg.php

defined( 'ABSPATH' ) || exit;

add_filter( 'use_block_editor_for_post_type', '__return_false' );

function ttng_remove_block_css() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style');
    wp_dequeue_style('storefront-gutenberg-blocks');
}
add_action('wp_enqueue_scripts', 'ttng_remove_block_css', 100);