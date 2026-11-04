<?php
/**
 * media.php – Image handling, SVGs, alt attributes, thumbnails
 */

defined( 'ABSPATH' ) || exit;

// Featured image admin label tweak
add_filter('admin_post_thumbnail_html', function ($content) {
    return str_replace(__('Set featured image'), 'Image dimensions 1920x1080', $content);
});

// Replace empty alt attributes with space
add_action('wp_loaded', function () {
    ob_start(function ($buffer) {
        return preg_replace('/<img([^>]*?)alt=""/i', '<img$1alt=" "', $buffer);
    });
}, 1);
add_action('shutdown', function () {
    if (ob_get_level()) ob_end_flush();
});

// Protect email addresses in content and widgets
function ttng_protect_email_addresses($content) {
    return preg_replace_callback('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', function ($matches) {
        return implode('', array_map(function ($char) {
            return rand(0, 1)
                ? '&#' . ord($char) . ';'
                : '&#x' . dechex(ord($char)) . ';';
        }, str_split($matches[0])));
    }, $content);
}
add_filter('the_content', 'ttng_protect_email_addresses');
add_filter('widget_text', 'ttng_protect_email_addresses');

// Allow and sanitize SVG uploads
add_filter('upload_mimes', function ($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});

/**
 * Gives SVG attachments real dimensions.
 *
 * WordPress can't measure SVGs, so an SVG with only a viewBox comes through as
 * width="1" height="1" and renders about 1px tall under CSS that doesn't set
 * both dimensions. This reads the size from the viewBox and fits it to the
 * requested image size. Other image types are left alone.
 *
 * @param array|false  $image         Array of URL, width, height, is-resized, or false.
 * @param int          $attachment_id Attachment ID.
 * @param string|int[] $size          Requested image size.
 * @return array|false The image, with dimensions set for SVGs.
 */
function ttng_svg_image_dimensions( $image, $attachment_id, $size ) {
	if ( ! is_array( $image ) || $image[1] > 1 || $image[2] > 1 || 'image/svg+xml' !== get_post_mime_type( $attachment_id ) ) {
		return $image;
	}

	$file = get_attached_file( $attachment_id );
	$svg  = $file && is_readable( $file ) ? file_get_contents( $file, false, null, 0, 4096 ) : '';

	// The viewBox is on the opening <svg> tag, so the start of the file is enough.
	if ( ! $svg || ! preg_match( '/<svg\b[^>]*?\bviewBox=["\']\s*[-\d.]+[\s,]+[-\d.]+[\s,]+([\d.]+)[\s,]+([\d.]+)/i', $svg, $match ) ) {
		return $image;
	}

	$width  = (int) ceil( (float) $match[1] );
	$height = (int) ceil( (float) $match[2] );

	if ( $width < 1 || $height < 1 ) {
		return $image;
	}

	$sizes = wp_get_registered_image_subsizes();

	if ( is_array( $size ) ) {
		list( $width, $height ) = wp_constrain_dimensions( $width, $height, (int) $size[0], (int) $size[1] );
	} elseif ( isset( $sizes[ $size ] ) ) {
		list( $width, $height ) = wp_constrain_dimensions( $width, $height, $sizes[ $size ]['width'], $sizes[ $size ]['height'] );
	}

	$image[1] = $width;
	$image[2] = $height;

	return $image;
}
add_filter( 'wp_get_attachment_image_src', 'ttng_svg_image_dimensions', 10, 3 );

add_filter('wp_handle_upload_prefilter', function ($file) {
    if ($file['type'] === 'image/svg+xml') {
        $svg = file_get_contents($file['tmp_name']);
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadXML($svg);
        libxml_clear_errors();

        $allowed_tags = ['svg', 'path', 'rect', 'circle', 'polygon', 'polyline', 'line', 'g', 'text'];
        $allowed_attrs = ['width', 'height', 'viewBox', 'fill', 'stroke', 'd', 'x', 'y', 'cx', 'cy', 'r'];

        $sanitize_node = function ($node) use (&$sanitize_node, $allowed_tags, $allowed_attrs) {
            if (!in_array($node->nodeName, $allowed_tags)) {
                $node->parentNode->removeChild($node);
                return;
            }
            if ($node->hasAttributes()) {
                foreach (iterator_to_array($node->attributes) as $attr) {
                    if (!in_array($attr->name, $allowed_attrs)) {
                        $node->removeAttribute($attr->name);
                    }
                }
            }
            foreach (iterator_to_array($node->childNodes) as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE) $sanitize_node($child);
            }
        };

        $sanitize_node($dom->documentElement);
        file_put_contents($file['tmp_name'], $dom->saveXML());
    }
    return $file;
});
