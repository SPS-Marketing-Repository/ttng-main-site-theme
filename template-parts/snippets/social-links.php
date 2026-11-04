<?php
/**
 * social-links.php – Renders the site's social icon list from ACF options.
 *
 * Icons are inline SVG (not Font Awesome — a whole icon-font kit is
 * unnecessary weight for a handful of glyphs) so they inherit colour via
 * currentColor and respond to the existing .social-links rules in
 * header.css / footer.css. Add more networks by adding a key to both
 * $social_icons and $social_labels below, plus the matching ACF option
 * field ({key}_url).
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

$social_icons = [
	'facebook'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.891h-2.33v6.987C18.343 21.128 22 16.991 22 12z"/></svg>',
	'instagram' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2c-2.716 0-3.056.012-4.123.06-1.064.049-1.79.218-2.427.465a4.9 4.9 0 0 0-1.771 1.153A4.9 4.9 0 0 0 2.525 5.45c-.247.637-.416 1.363-.465 2.427C2.012 8.944 2 9.284 2 12s.012 3.056.06 4.123c.049 1.064.218 1.79.465 2.427a4.9 4.9 0 0 0 1.153 1.771 4.9 4.9 0 0 0 1.771 1.153c.637.247 1.363.416 2.427.465C8.944 21.988 9.284 22 12 22s3.056-.012 4.123-.06c1.064-.049 1.79-.218 2.427-.465a4.9 4.9 0 0 0 1.771-1.153 4.9 4.9 0 0 0 1.153-1.771c.247-.637.416-1.363.465-2.427.048-1.067.06-1.407.06-4.123s-.012-3.056-.06-4.123c-.049-1.064-.218-1.79-.465-2.427a4.9 4.9 0 0 0-1.153-1.771A4.9 4.9 0 0 0 18.55 2.525c-.637-.247-1.363-.416-2.427-.465C15.056 2.012 14.716 2 12 2zm0 1.802c2.67 0 2.987.01 4.042.058.976.045 1.505.207 1.858.344.467.182.8.399 1.15.748.35.35.566.683.748 1.15.137.353.3.882.344 1.858.048 1.055.058 1.372.058 4.042s-.01 2.987-.058 4.042c-.045.976-.207 1.505-.344 1.858a3.1 3.1 0 0 1-.748 1.15 3.1 3.1 0 0 1-1.15.748c-.353.137-.882.3-1.858.344-1.055.048-1.372.058-4.042.058s-2.987-.01-4.042-.058c-.976-.045-1.505-.207-1.858-.344a3.1 3.1 0 0 1-1.15-.748 3.1 3.1 0 0 1-.748-1.15c-.137-.353-.3-.882-.344-1.858-.048-1.055-.058-1.372-.058-4.042s.01-2.987.058-4.042c.045-.976.207-1.505.344-1.858.182-.467.399-.8.748-1.15.35-.35.683-.566 1.15-.748.353-.137.882-.3 1.858-.344 1.055-.048 1.372-.058 4.042-.058zm0 3.064a5.134 5.134 0 1 0 0 10.268 5.134 5.134 0 0 0 0-10.268zm0 8.468a3.334 3.334 0 1 1 0-6.668 3.334 3.334 0 0 1 0 6.668zm6.538-8.671a1.2 1.2 0 1 1-2.4 0 1.2 1.2 0 0 1 2.4 0z"/></svg>',
	'linkedin'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zM7.119 20.452H3.555V9h3.564z"/></svg>',
	'youtube'   => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.016 3.016 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.016 3.016 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
	'twitter'   => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
	'pinterest' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12.017 0C5.396 0 0 5.396 0 12.017c0 5.086 3.163 9.42 7.627 11.155-.105-.949-.2-2.404.042-3.44.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.024 0 1.518.769 1.518 1.69 0 1.03-.655 2.568-.993 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.223.083.344-.09.375-.293 1.19-.332 1.355-.052.219-.173.265-.4.16-1.492-.694-2.424-2.876-2.424-4.626 0-3.767 2.737-7.226 7.892-7.226 4.144 0 7.365 2.953 7.365 6.899 0 4.116-2.595 7.428-6.199 7.428-1.211 0-2.35-.629-2.74-1.372l-.746 2.842c-.269 1.037-.998 2.336-1.487 3.128C9.681 23.752 10.828 24 12.017 24c6.62 0 12.017-5.396 12.017-12.017C24.034 5.396 18.638 0 12.017 0z"/></svg>',
];

$social_labels = [
	'facebook'  => __( 'Facebook', 'ttng-main-site-theme' ),
	'instagram' => __( 'Instagram', 'ttng-main-site-theme' ),
	'linkedin'  => __( 'LinkedIn', 'ttng-main-site-theme' ),
	'youtube'   => __( 'YouTube', 'ttng-main-site-theme' ),
	'twitter'   => __( 'Twitter', 'ttng-main-site-theme' ),
	'pinterest' => __( 'Pinterest', 'ttng-main-site-theme' ),
];

$social_links = '';
foreach ( $social_icons as $key => $icon_svg ) {
	$url = get_field( $key . '_url', 'option' );
	if ( ! $url ) {
		continue;
	}
	$label      = isset( $social_labels[ $key ] ) ? $social_labels[ $key ] : ucfirst( $key );
	$aria_label = sprintf(
		/* translators: %s: Social network name. */
		__( 'Visit our %s profile (opens in a new tab)', 'ttng-main-site-theme' ),
		$label
	);
	$social_links .= sprintf(
		'<li class="social-%1$s"><a href="%2$s" target="_blank" rel="noopener noreferrer" aria-label="%4$s">%3$s</a></li>',
		esc_attr( $key ),
		esc_url( $url ),
		$icon_svg, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme-authored SVG constant above, not user input.
		esc_attr( $aria_label )
	);
}

if ( $social_links ) :
	?>
	<ul class="social-links"><?php echo $social_links; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built entirely from esc_url()/esc_attr() plus the fixed SVGs above. ?></ul>
<?php endif; ?>
