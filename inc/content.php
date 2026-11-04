<?php
/**
 * content.php – Content filters and tweaks
 */

defined( 'ABSPATH' ) || exit;

// Open external links in new tabs
add_action('wp_footer', function () {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('a').forEach(function (link) {
                if (link.hostname !== window.location.hostname) {
                    link.setAttribute('target', '_blank');
                    link.setAttribute('rel', 'noopener noreferrer');
                }
            });
        });
    </script>";
});