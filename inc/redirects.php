<?php
/**
 * redirects.php – 301s from old staging/live URLs to the brief's URLs
 *
 * Only runs on requests that would otherwise 404, so a real page at the
 * same path always wins. Runs before WordPress's own "guess the URL" redirect.
 *
 * Add to the map with the `ttng_redirect_map` filter, or edit it below.
 * Before launch: export the live site's URLs (Search Console → Pages, or a
 * crawl) and add any that don't exist on the new site.
 *
 * The content importer also uses this map to repoint old menu links.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Old path => new path (both with leading and trailing slashes, lowercase).
 *
 * @return array<string,string>
 */
function ttng_get_redirect_map() {
	$map = [
		// Brief clean-ups.
		'/privacy-policy-2/'                   => '/privacy-policy/',
		'/terms-conditions-2/'                 => '/terms-conditions/',
		'/membership-types-2/'                 => '/membership-types/',
		'/travel-trust-association/why-join/'  => '/travel-trust-association/',

		// Staging pages not in the new sitemap.
		'/risk/'                               => '/operations/',
		'/risk-management/'                    => '/operations/',
		'/testimonials/'                       => '/category/member-stories/',

		// Sitemap-image names that differ from the brief's URLs.
		'/why-join-us/'                        => '/why-join/',
		'/about-us/'                           => '/about/',
		'/vision-technology/'                  => '/vision-travel-technology/',
		'/commercial-advantages/'              => '/commercial/',
		'/commercial-relationships/'           => '/commercial/',
		'/membership-services/'                => '/membership/',
		'/managed-services/'                   => '/membership-types/managed-services/',
		'/global-travel-group/'                => '/membership-types/global-travel-group/',
		'/membership-types/worldchoice-plus/'  => '/worldchoice-plus/',
		'/membership-options/'                 => '/membership-types/',
		'/start-a-travel-business/'            => '/travel-business-guide/',
		'/how-to-start-a-travel-business/'     => '/travel-business-guide/',
		'/request-a-brochure/'                 => '/request-brochure/',
		'/apply-to-become-a-member/'           => '/join-us/',
		'/consumer-protection/'                => '/faqs/',
		'/financial-protection-operations/'    => '/operations/',
		'/community-events/'                   => '/community/',
		'/the-cruise-club/'                    => '/cruise-club/',
		'/contact/'                            => '/contact-us/',
	];

	// Old Insights URL → wherever the posts page lives now.
	$posts_page = (int) get_option( 'page_for_posts' );
	if ( $posts_page ) {
		$insights = trailingslashit( (string) wp_parse_url( get_permalink( $posts_page ), PHP_URL_PATH ) );
		foreach ( [ '/news-events/', '/news/', '/insights/', '/blog/' ] as $old ) {
			if ( $old !== $insights ) {
				$map[ $old ] = $insights;
			}
		}
	}

	return apply_filters( 'ttng_redirect_map', $map );
}

add_action(
	'template_redirect',
	static function () {
		if ( ! is_404() || is_admin() ) {
			return;
		}

		$request = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- only the path is used, below.
		$path    = strtolower( (string) wp_parse_url( $request, PHP_URL_PATH ) );
		$path    = '/' . trim( $path, '/' ) . '/';
		$map     = ttng_get_redirect_map();

		if ( empty( $map[ $path ] ) || $map[ $path ] === $path ) {
			return;
		}

		$query  = (string) wp_parse_url( $request, PHP_URL_QUERY );
		$target = home_url( $map[ $path ] ) . ( $query ? '?' . $query : '' );

		wp_safe_redirect( $target, 301, 'TTNG redirects' );
		exit;
	},
	1
);
