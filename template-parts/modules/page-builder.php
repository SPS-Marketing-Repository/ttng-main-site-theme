<?php
/**
 * Child-aware Page Builder
 *
 * For each ACF Flexible Content row in 'page_builder':
 * - Enqueue CSS from child if present, else parent:
 *     assets/css/modules/{layout}.css
 * - Include PHP template from child if present, else parent:
 *     template-parts/modules/{layout}.php
 *     modules/{layout}.php
 *     template-parts/{layout}.php
 *     {layout}.php
 *
 * Notes:
 * - Uses get_theme_file_path()/get_theme_file_uri() and locate_template()
 *   so the child theme can override without copying everything.
 * - Call through ttng_render_page_builder(), which sets $ttng_builder_post_id
 *   (false = the current post).
 */

defined( 'ABSPATH' ) || exit;

$ttng_builder_post_id = $ttng_builder_post_id ?? false;

if ( have_rows( 'page_builder', $ttng_builder_post_id ) ) :
  while ( have_rows( 'page_builder', $ttng_builder_post_id ) ) : the_row();

    // ACF layout name, e.g. 'content_split_width_module'
    $layout_raw = get_row_layout();
    if ( ! $layout_raw ) {
      continue;
    }

    // Make sure the layout becomes a safe filename/slug
    $layout = sanitize_title( $layout_raw );

    // ---------- CSS (child-first, then parent) ----------
    $css_rel  = "assets/css/modules/{$layout}.css";
    $css_path = get_theme_file_path( $css_rel ); // child-first path
    if ( file_exists( $css_path ) ) {
      $handle   = "{$layout}-css";
      $version  = @filemtime( $css_path ) ?: null;

      // Prevent duplicate enqueue if the same module appears multiple times
      if ( ! wp_style_is( $handle, 'enqueued' ) && ! wp_style_is( $handle, 'registered' ) ) {
        wp_enqueue_style(
          $handle,
          get_theme_file_uri( $css_rel ), // child-first URL
          [],
          $version
        );
      }
    }

    // ---------- PHP template (child-first, then parent) ----------
    // Try common locations, child first automatically via locate_template()
    $candidates = [
      "template-parts/modules/{$layout}.php",
      "modules/{$layout}.php",
      "template-parts/{$layout}.php",
      "{$layout}.php",
    ];

    $module_file = locate_template( $candidates, false, false ); // return path only
    if ( $module_file && file_exists( $module_file ) ) {
      include $module_file;
    } else {
      // Optional: helpful notice in development
      if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        trigger_error(
          sprintf( 'Page Builder: no template found for layout "%s". Checked: %s', $layout, implode( ', ', $candidates ) ),
          E_USER_NOTICE
        );
      }
    }

  endwhile;
endif;
