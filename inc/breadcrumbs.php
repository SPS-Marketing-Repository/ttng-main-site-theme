<?php
/**
 * breadcrumbs.php – Breadcrumb trail (visible + BreadcrumbList JSON-LD)
 *
 * Most brief URLs are flat (/commercial/, /marketing/…), so page ancestors
 * alone would give "Home › Commercial Relationships". The trail follows the
 * Primary menu instead, so it matches the navigation: "Home › Why Join Us? ›
 * Commercial Relationships". Pages not in the menu fall back to their page
 * ancestors.
 *
 * Output: ttng_breadcrumbs() — called by page_header, the Insights listing
 * header and the single Insight header. Styles: additions.css (.breadcrumbs).
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build the trail for the current request.
 *
 * @return array[] List of [ 'name' => string, 'url' => string ] — url is '' for unlinked items.
 */
function ttng_get_breadcrumbs() {
	static $trail = null;
	if ( null !== $trail ) {
		return $trail;
	}

	$trail = [];
	if ( is_front_page() ) {
		return $trail;
	}

	$trail[]      = [ 'name' => __( 'Home', 'ttng-main-site-theme' ), 'url' => home_url( '/' ) ];
	$posts_page   = (int) get_option( 'page_for_posts' );
	$insights_url = $posts_page ? get_permalink( $posts_page ) : '';
	$insights     = $posts_page ? get_the_title( $posts_page ) : __( 'Insights', 'ttng-main-site-theme' );

	if ( is_page() ) {
		$page_id = get_queried_object_id();
		$parents = ttng_breadcrumb_menu_parents( 'post_type', $page_id );

		if ( null === $parents ) {
			$parents = [];
			foreach ( array_reverse( get_post_ancestors( $page_id ) ) as $ancestor ) {
				$parents[] = [ 'name' => get_the_title( $ancestor ), 'url' => get_permalink( $ancestor ) ];
			}
		}
		$trail   = array_merge( $trail, $parents );
		$trail[] = [ 'name' => get_the_title( $page_id ), 'url' => '' ];

	} elseif ( is_home() ) {
		$trail[] = [ 'name' => $insights, 'url' => '' ];

	} elseif ( is_category() ) {
		$term = get_queried_object();
		if ( $insights_url ) {
			$trail[] = [ 'name' => $insights, 'url' => $insights_url ];
		}
		foreach ( array_reverse( get_ancestors( $term->term_id, 'category' ) ) as $ancestor ) {
			$trail[] = [ 'name' => get_cat_name( $ancestor ), 'url' => get_category_link( $ancestor ) ];
		}
		$trail[] = [ 'name' => single_cat_title( '', false ), 'url' => '' ];

	} elseif ( is_singular( 'post' ) ) {
		if ( $insights_url ) {
			$trail[] = [ 'name' => $insights, 'url' => $insights_url ];
		}
		$cats = get_the_category();
		if ( $cats ) {
			$trail[] = [ 'name' => $cats[0]->name, 'url' => get_category_link( $cats[0] ) ];
		}
		$trail[] = [ 'name' => get_the_title(), 'url' => '' ];

	} elseif ( is_search() ) {
		$trail[] = [ 'name' => __( 'Search results', 'ttng-main-site-theme' ), 'url' => '' ];

	} elseif ( is_404() ) {
		$trail[] = [ 'name' => __( 'Page not found', 'ttng-main-site-theme' ), 'url' => '' ];

	} elseif ( is_archive() ) {
		$trail[] = [ 'name' => wp_strip_all_tags( get_the_archive_title() ), 'url' => '' ];
	}

	return $trail;
}

/**
 * Parent items of an object in the Primary menu.
 *
 * @param string $type      Menu item type ('post_type' | 'taxonomy').
 * @param int    $object_id Page/term ID.
 * @return array[]|null Parent crumbs (top first), or null if the object isn't in the menu.
 */
function ttng_breadcrumb_menu_parents( $type, $object_id ) {
	$locations = get_nav_menu_locations();
	if ( empty( $locations['primary'] ) ) {
		return null;
	}
	$items = wp_get_nav_menu_items( $locations['primary'] );
	if ( ! $items ) {
		return null;
	}

	$by_id = [];
	$match = null;
	foreach ( $items as $item ) {
		$by_id[ (int) $item->ID ] = $item;
		if ( ! $match && $type === $item->type && (int) $item->object_id === (int) $object_id ) {
			$match = $item;
		}
	}
	if ( ! $match ) {
		return null;
	}

	$parents = [];
	$guard   = 0;
	$parent  = (int) $match->menu_item_parent;
	while ( $parent && isset( $by_id[ $parent ] ) && $guard++ < 6 ) {
		$item = $by_id[ $parent ];
		$url  = ( '#' === $item->url || '' === $item->url ) ? '' : $item->url;

		// Skip a parent that is the same page (e.g. "Overview" duplicating "Memberships").
		if ( 'post_type' !== $item->type || (int) $item->object_id !== (int) $object_id ) {
			array_unshift( $parents, [ 'name' => wp_strip_all_tags( $item->title ), 'url' => $url ] );
		}
		$parent = (int) $item->menu_item_parent;
	}
	return $parents;
}

/**
 * Print the visible breadcrumb trail.
 *
 * @param string $class Extra classes.
 */
function ttng_breadcrumbs( $class = '' ) {
	$trail = ttng_get_breadcrumbs();
	if ( count( $trail ) < 2 ) {
		return;
	}
	$last = count( $trail ) - 1;
	?>
	<nav class="<?php echo esc_attr( trim( 'breadcrumbs ' . $class ) ); ?>" aria-label="<?php esc_attr_e( 'Breadcrumb', 'ttng-main-site-theme' ); ?>">
		<ol class="breadcrumbs__list">
			<?php foreach ( $trail as $i => $crumb ) : ?>
				<li class="breadcrumbs__item">
					<?php if ( $i === $last ) : ?>
						<span aria-current="page"><?php echo esc_html( $crumb['name'] ); ?></span>
					<?php elseif ( $crumb['url'] ) : ?>
						<a href="<?php echo esc_url( $crumb['url'] ); ?>"><?php echo esc_html( $crumb['name'] ); ?></a>
					<?php else : ?>
						<span><?php echo esc_html( $crumb['name'] ); ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</nav>
	<?php
}
