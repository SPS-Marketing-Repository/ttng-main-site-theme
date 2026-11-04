<?php
/**
 * assets.php – Enqueue scripts and styles
 */

defined( 'ABSPATH' ) || exit;

// Preconnects for Google Fonts (fast + correct CORS for gstatic).
// Brand font is Montserrat (400/500/600/700) — the --content/--header font
// tokens live in assets/css/core/additions.css. If the font is later
// self-hosted, remove this preconnect and the enqueue below.
add_action('wp_head', function () { ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php }, 1);

function ttng_assets() {
  // --- Styles ---
  wp_enqueue_style(
    'custom-font',
    'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap',
    [],
    null
  );

  wp_enqueue_style(
    'bs',
    TTNG_THEME_URI . '/assets/css/bootstrap-custom.min.css',
    [],
    filemtime(TTNG_THEME_DIR . '/assets/css/bootstrap-custom.min.css')
  );

  // base.css loads first so additions.css can override its :root variables
  wp_enqueue_style(
    'style',
    TTNG_THEME_URI . '/assets/css/core/base.css',
    [],
    filemtime(TTNG_THEME_DIR . '/assets/css/core/base.css')
  );

  wp_enqueue_style(
    'style-additions',
    TTNG_THEME_URI . '/assets/css/core/additions.css',
    [],
    filemtime(TTNG_THEME_DIR . '/assets/css/core/additions.css')
  );

  wp_enqueue_style(
    'style-header',
    TTNG_THEME_URI . '/assets/css/core/header.css',
    [],
    filemtime(TTNG_THEME_DIR . '/assets/css/core/header.css')
  );

  wp_enqueue_style(
    'style-footer',
    TTNG_THEME_URI . '/assets/css/core/footer.css',
    [],
    filemtime(TTNG_THEME_DIR . '/assets/css/core/footer.css')
  );

  wp_enqueue_style(
    'style-cookie-consent',
    TTNG_THEME_URI . '/assets/css/core/cookie-consent.css',
    [],
    filemtime(TTNG_THEME_DIR . '/assets/css/core/cookie-consent.css')
  );

  // --- Scripts ---
  wp_enqueue_script('jquery');

  wp_enqueue_script(
    'main',
    TTNG_THEME_URI . '/assets/js/main.js',
    ['jquery'],
    filemtime(TTNG_THEME_DIR . '/assets/js/main.js'),
    true
  );
}
add_action('wp_enqueue_scripts', 'ttng_assets');
