<?php
// inc/rss.php

defined( 'ABSPATH' ) || exit;

add_filter('the_excerpt_rss', 'gak_rss_featured_img');
add_filter('the_content_feed', 'gak_rss_featured_img');

function gak_rss_featured_img($content) {
    global $post;
    if (has_post_thumbnail($post->ID)) {
        $content = get_the_post_thumbnail($post->ID, 'large', ['style' => 'margin-bottom:10px;']) . $content;
    }
    return $content;
}