<?php
/**
 * seo.php – Meta description, Open Graph / Twitter tags and JSON-LD
 *
 * The site has no SEO plugin, so the theme provides the basics:
 * - "SEO" fields on pages and Insights (title, description, share image, noindex).
 * - <meta name="description">, robots noindex, Open Graph and Twitter card tags.
 * - One JSON-LD @graph: Organization + WebSite everywhere, BreadcrumbList
 *   (from inc/breadcrumbs.php) off the home page, BlogPosting on Insights.
 *   FAQPage comes from the faq module itself.
 *
 * Everything here switches itself off if Rank Math or Yoast is activated
 * (staging used Rank Math), so there's never duplicate output.
 *
 * The field group lives in acf-json/group_ttng_seo.json. Don't change its keys:
 * the content importer writes field_ttng_seo_description directly.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * True when a dedicated SEO plugin is active.
 */
function ttng_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' );
}

/* -------------------------------------------------------------------------
 * Fields.
 * ---------------------------------------------------------------------- */

/**
 * Hide the SEO field group while a dedicated SEO plugin is active.
 *
 * Runs after ACF adds its local JSON groups (priority 20).
 *
 * @param array $field_groups Field groups.
 * @return array
 */
function ttng_seo_hide_field_group( $field_groups ) {
	if ( ! ttng_seo_plugin_active() ) {
		return $field_groups;
	}

	return array_values(
		array_filter(
			$field_groups,
			static function ( $field_group ) {
				return 'group_ttng_seo' !== $field_group['key'];
			}
		)
	);
}
add_filter( 'acf/load_field_groups', 'ttng_seo_hide_field_group', 30 );

/* -------------------------------------------------------------------------
 * Values for the current request.
 * ---------------------------------------------------------------------- */

/**
 * Meta description for the current request.
 */
function ttng_seo_description() {
	$description = '';

	if ( is_singular() ) {
		$post_id     = get_queried_object_id();
		$description = (string) get_field( 'seo_description', $post_id );

		if ( ! $description && has_excerpt( $post_id ) ) {
			$description = get_the_excerpt( $post_id );
		}
		if ( ! $description && is_singular( 'post' ) ) {
			$description = wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ), 28, '…' );
		}
	} elseif ( is_category() ) {
		$description = wp_strip_all_tags( (string) get_field( 'category_intro', get_queried_object() ) );
		$description = $description ? $description : wp_strip_all_tags( category_description() );
	} elseif ( is_home() && get_option( 'page_for_posts' ) ) {
		$description = (string) get_field( 'seo_description', (int) get_option( 'page_for_posts' ) );
	}

	if ( ! $description && is_front_page() ) {
		$description = get_bloginfo( 'description' );
	}

	return trim( wp_html_excerpt( preg_replace( '/\s+/', ' ', (string) $description ), 160, '…' ) );
}

/**
 * URL of a site-wide logo (Site Settings > Logos), whatever the field returns.
 */
function ttng_seo_logo_url() {
	$logo = get_field( 'site_main_logo', 'option' );
	if ( is_array( $logo ) ) {
		return $logo['url'] ?? '';
	}
	if ( is_numeric( $logo ) ) {
		return (string) wp_get_attachment_image_url( (int) $logo, 'full' );
	}
	return is_string( $logo ) ? $logo : '';
}

/**
 * Share image URL for the current request.
 */
function ttng_seo_image() {
	if ( is_singular() ) {
		$post_id = get_queried_object_id();
		$image   = get_field( 'seo_image', $post_id );
		if ( $image ) {
			return (string) wp_get_attachment_image_url( (int) $image, 'ttng-featured' );
		}
		if ( has_post_thumbnail( $post_id ) ) {
			return (string) get_the_post_thumbnail_url( $post_id, 'ttng-featured' );
		}
	}
	return ttng_seo_logo_url();
}

/**
 * Canonical-ish URL for the current request.
 */
function ttng_seo_url() {
	if ( is_singular() ) {
		return get_permalink( get_queried_object_id() );
	}
	if ( is_category() ) {
		return get_category_link( get_queried_object_id() );
	}
	if ( is_home() && get_option( 'page_for_posts' ) ) {
		return get_permalink( (int) get_option( 'page_for_posts' ) );
	}
	return home_url( '/' );
}

/* -------------------------------------------------------------------------
 * <title>.
 * ---------------------------------------------------------------------- */
add_filter(
	'pre_get_document_title',
	static function ( $title ) {
		if ( ttng_seo_plugin_active() || ! is_singular() ) {
			return $title;
		}
		$custom = (string) get_field( 'seo_title', get_queried_object_id() );
		return $custom ? wp_strip_all_tags( $custom ) : $title;
	}
);

add_filter(
	'document_title_separator',
	static fn() => '|'
);

/* -------------------------------------------------------------------------
 * Robots: per-page noindex (WordPress adds the site-wide one when
 * "Discourage search engines" is ticked).
 * ---------------------------------------------------------------------- */
add_filter(
	'wp_robots',
	static function ( $robots ) {
		if ( ! ttng_seo_plugin_active() && is_singular() && get_field( 'seo_noindex', get_queried_object_id() ) ) {
			$robots['noindex']  = true;
			$robots['nofollow'] = false;
		}
		if ( ! ttng_seo_plugin_active() && ( is_search() || is_404() ) ) {
			$robots['noindex'] = true;
		}
		return $robots;
	}
);

/* -------------------------------------------------------------------------
 * Meta + social tags.
 * ---------------------------------------------------------------------- */
add_action(
	'wp_head',
	static function () {
		if ( ttng_seo_plugin_active() ) {
			return;
		}

		$description = ttng_seo_description();
		$image       = ttng_seo_image();
		$title       = wp_get_document_title();
		$type        = is_singular( 'post' ) ? 'article' : 'website';

		if ( $description ) {
			printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
		}

		$tags = [
			'og:locale'      => 'en_GB',
			'og:type'        => $type,
			'og:site_name'   => get_bloginfo( 'name' ),
			'og:title'       => $title,
			'og:description' => $description,
			'og:url'         => ttng_seo_url(),
			'og:image'       => $image,
		];
		foreach ( array_filter( $tags ) as $property => $content ) {
			printf( '<meta property="%s" content="%s">' . "\n", esc_attr( $property ), esc_attr( $content ) );
		}

		if ( 'article' === $type ) {
			printf( '<meta property="article:published_time" content="%s">' . "\n", esc_attr( get_the_date( 'c' ) ) );
			printf( '<meta property="article:modified_time" content="%s">' . "\n", esc_attr( get_the_modified_date( 'c' ) ) );
		}

		printf( '<meta name="twitter:card" content="%s">' . "\n", $image ? 'summary_large_image' : 'summary' );
	},
	5
);

/* -------------------------------------------------------------------------
 * JSON-LD.
 * ---------------------------------------------------------------------- */
add_action(
	'wp_head',
	static function () {
		if ( ttng_seo_plugin_active() ) {
			return;
		}

		$home   = home_url( '/' );
		$org_id = $home . '#organization';
		$graph  = [];

		$same_as = [];
		foreach ( [ 'facebook', 'linkedin', 'youtube', 'instagram', 'twitter', 'pinterest' ] as $network ) {
			$url = get_field( $network . '_url', 'option' );
			if ( $url ) {
				$same_as[] = esc_url_raw( $url );
			}
		}

		$organization = [
			'@type' => 'Organization',
			'@id'   => $org_id,
			'name'  => get_bloginfo( 'name' ),
			'url'   => $home,
		];
		if ( ttng_seo_logo_url() ) {
			$organization['logo'] = ttng_seo_logo_url();
		}
		if ( get_field( 'contact_email', 'option' ) ) {
			$organization['email'] = sanitize_email( get_field( 'contact_email', 'option' ) );
		}
		if ( get_field( 'contact_telephone', 'option' ) ) {
			$organization['telephone'] = sanitize_text_field( get_field( 'contact_telephone', 'option' ) );
		}
		if ( get_field( 'contact_address', 'option' ) ) {
			$organization['address'] = [
				'@type'          => 'PostalAddress',
				'streetAddress'  => trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( str_replace( [ '<br>', '<br />', "\n" ], ', ', get_field( 'contact_address', 'option' ) ) ) ), ', ' ),
				'addressCountry' => 'GB',
			];
		}
		if ( $same_as ) {
			$organization['sameAs'] = $same_as;
		}
		$graph[] = $organization;

		$graph[] = [
			'@type'           => 'WebSite',
			'@id'             => $home . '#website',
			'url'             => $home,
			'name'            => get_bloginfo( 'name' ),
			'inLanguage'      => 'en-GB',
			'publisher'       => [ '@id' => $org_id ],
			'potentialAction' => [
				'@type'       => 'SearchAction',
				'target'      => $home . '?s={search_term_string}',
				'query-input' => 'required name=search_term_string',
			],
		];

		$crumbs = function_exists( 'ttng_get_breadcrumbs' ) ? ttng_get_breadcrumbs() : [];
		if ( count( $crumbs ) > 1 ) {
			$list = [];
			foreach ( $crumbs as $i => $crumb ) {
				$entry = [
					'@type'    => 'ListItem',
					'position' => $i + 1,
					'name'     => $crumb['name'],
				];
				$url = $crumb['url'] ? $crumb['url'] : ( $i === count( $crumbs ) - 1 ? ttng_seo_url() : '' );
				if ( $url ) {
					$entry['item'] = $url;
				}
				$list[] = $entry;
			}
			$graph[] = [
				'@type'           => 'BreadcrumbList',
				'@id'             => ttng_seo_url() . '#breadcrumb',
				'itemListElement' => $list,
			];
		}

		if ( is_singular( 'post' ) ) {
			$post_id = get_queried_object_id();
			$article = [
				'@type'            => 'BlogPosting',
				'@id'              => get_permalink( $post_id ) . '#article',
				'headline'         => wp_strip_all_tags( get_the_title( $post_id ) ),
				'datePublished'    => get_the_date( 'c', $post_id ),
				'dateModified'     => get_the_modified_date( 'c', $post_id ),
				'mainEntityOfPage' => get_permalink( $post_id ),
				'author'           => [ '@id' => $org_id ],
				'publisher'        => [ '@id' => $org_id ],
				'inLanguage'       => 'en-GB',
			];
			if ( ttng_seo_description() ) {
				$article['description'] = ttng_seo_description();
			}
			if ( has_post_thumbnail( $post_id ) ) {
				$article['image'] = get_the_post_thumbnail_url( $post_id, 'ttng-featured' );
			}
			$graph[] = $article;
		}

		echo '<script type="application/ld+json">' . wp_json_encode(
			[
				'@context' => 'https://schema.org',
				'@graph'   => $graph,
			],
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
		) . "</script>\n";
	},
	20
);
