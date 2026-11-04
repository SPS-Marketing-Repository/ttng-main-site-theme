<?php
/**
 * insights.php – Insights (posts): archive assets and the Events split.
 *
 * Templates: home.php, archive.php, category-events.php, search.php, single.php
 * Fields: acf-json/group_ttng_post_details.json (event_date),
 *         acf-json/group_ttng_category_details.json (category_intro)
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Today's date in the format event_date is stored in (Ymd).
 *
 * @return string
 */
function ttng_today_ymd() {
	return wp_date( 'Ymd' );
}

/**
 * Load the listing/article styles on Insights screens.
 *
 * @return void
 */
function ttng_insights_assets() {
	if ( ! ( is_home() || is_archive() || is_search() || is_singular( 'post' ) || is_page_template( 'page-templates/template-sitemap.php' ) ) ) {
		return;
	}

	foreach ( array( 'assets/css/modules/latest_news.css' => 'latest_news-css', 'assets/css/core/insights.css' => 'style-insights' ) as $file => $handle ) {
		wp_enqueue_style( $handle, get_theme_file_uri( $file ), array(), filemtime( get_theme_file_path( $file ) ) );
	}
}
add_action( 'wp_enqueue_scripts', 'ttng_insights_assets' );

/**
 * Events category: the main (paginated) query lists past events only.
 * category-events.php lists upcoming ones above it with its own query.
 * Events with no date count as past, so nothing disappears from the page.
 *
 * @param WP_Query $query Query being prepared.
 * @return void
 */
function ttng_events_past_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_category( 'events' ) ) {
		return;
	}

	$query->set(
		'meta_query',
		array(
			'relation' => 'OR',
			array(
				'key'     => 'event_date',
				'value'   => ttng_today_ymd(),
				'compare' => '<',
				'type'    => 'NUMERIC',
			),
			array(
				'key'     => 'event_date',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'event_date',
				'value'   => '',
				'compare' => '=',
			),
		)
	);
}
add_action( 'pre_get_posts', 'ttng_events_past_query' );

/**
 * Upcoming events, soonest first.
 *
 * @return WP_Query
 */
function ttng_get_upcoming_events() {
	return new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'category_name'       => 'events',
			'posts_per_page'      => 12,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'meta_key'            => 'event_date', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'orderby'             => 'meta_value_num',
			'order'               => 'ASC',
			'meta_query'          => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => 'event_date',
					'value'   => ttng_today_ymd(),
					'compare' => '>=',
					'type'    => 'NUMERIC',
				),
			),
		)
	);
}
