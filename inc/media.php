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
