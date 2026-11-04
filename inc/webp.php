<?php
// inc/webp.php

defined( 'ABSPATH' ) || exit;

add_filter('mime_types', function ($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
});