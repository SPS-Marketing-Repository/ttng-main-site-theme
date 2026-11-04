<?php
// inc/lazy-load.php

defined( 'ABSPATH' ) || exit;

/**
 * Default every attachment image to lazy loading, unless the caller has
 * explicitly asked for something else (e.g. 'loading' => 'eager' on the
 * home banner's first slide, which is the LCP element and must not be lazy).
 *
 * @param array $attr Image attributes.
 * @return array
 */
add_filter( 'wp_get_attachment_image_attributes', function ( $attr ) {
	if ( empty( $attr['loading'] ) || 'eager' !== $attr['loading'] ) {
		$attr['loading'] = 'lazy';
	}
	return $attr;
} );
