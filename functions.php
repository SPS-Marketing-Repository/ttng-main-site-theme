<?php
/**
 * functions.php – Loader for modular includes
 */

// Define theme constants
if (!defined('TTNG_THEME_DIR')) define('TTNG_THEME_DIR', get_template_directory());
if (!defined('TTNG_THEME_URI')) define('TTNG_THEME_URI', get_template_directory_uri());

// Load all modules
$modules = [
    'assets',          // Enqueue scripts and styles
    'theme-setup',     // Theme setup, theme supports, menus
    'gutenberg',       // Disable Gutenberg and remove block styles
    'acf',             // ACF options pages and admin tweaks
    'security',        // Security cleanup, optional auto-update opt-out, remove version strings
    'updater',         // Theme updates in wp-admin from GitHub Releases
    'comments',        // Disable comment system and UI
    'editor',          // TinyMCE buttons and custom editor styles
    'tinymce-styles',  // Custom TinyMCE style formats (buttons dropdown)
    'helpers',         // Helper functions (excerpts, content limits)
    'icons',           // Icon picker: the designer's icon set on icon fields
    'accessibility',   // ARIA roles, title attributes, admin bar styling
    'media',           // Image filters, SVG sanitization, email obfuscation
    'content',         // Content tweaks, external links new tab, JS tweaks
    'cleanup',         // Remove emojis and unused scripts
    'rss',             // Add featured images to RSS feed content
    'lazy-load',       // Add lazy loading attribute to images
    'webp',            // Support for WebP image uploads
    'post-labels',     // Rename "Posts" post type to "Insights"
    'insights',        // Insights archive assets, Events upcoming/past split
    'nav-walker',      // Mega menu walker (primary + mobile navigation)
    'mega-menu',       // Desktop primary nav with staging-style mega panels
    'frontend',        // Misc front end updates
    'header',          // Controls the site-header.php meta tags
    'acf-json',        // acf-json save/load paths
    'acf-defaults',    // Pre-populate page_builder field for new posts
    'post-types',      // Testimonial, Team Member, Reusable Section post types
    'breadcrumbs',     // Breadcrumb trail from the Primary menu (visible + JSON-LD)
    'seo',             // Meta description, Open Graph, JSON-LD (off if an SEO plugin is active)
    'redirects',       // 301s from old staging/live URLs on 404
    'forms',           // CF7 honeypot + time-trap spam protection
    'tracking',        // GTM + Consent Mode v2 defaults (Site Settings > Tracking)
];

foreach ($modules as $module) {
    $file = TTNG_THEME_DIR . '/inc/' . $module . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        error_log( 'TTNG Theme — missing module: ' . $file );
    }
}