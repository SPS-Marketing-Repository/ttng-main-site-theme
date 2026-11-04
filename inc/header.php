<?php
// inc/header.php

defined( 'ABSPATH' ) || exit;

function ttng_add_custom_meta_before_wp_head() {
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
	<link type="text/plain" rel="author" href="/humans.txt" />
	<?php
}
// Priority 0 to ensure it outputs as early as possible inside wp_head
add_action('wp_head', 'ttng_add_custom_meta_before_wp_head', 0);