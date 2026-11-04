<?php
/**
 * Guide Navigation Module
 *
 * Series list and previous/next links for the Start a Travel Business guide.
 * The pages and their order live in one place: Site Settings > Guide. On a page
 * outside the list (e.g. the guide hub) only the series list shows.
 *
 * Related CSS: assets/css/modules/guide_nav.css
**/

defined( 'ABSPATH' ) || exit;

$pages = get_field( 'guide_pages', 'option' );

if ( empty( $pages ) ) {
	return;
}

$pages   = array_values( array_filter( array_map( 'absint', $pages ), static fn( $id ) => 'publish' === get_post_status( $id ) ) );
$display = get_sub_field( 'guide_nav_display' );
$display = in_array( $display, [ 'series', 'prev_next' ], true ) ? $display : 'both';
$current = array_search( get_queried_object_id(), $pages, true );
$prev    = ( false !== $current && $current > 0 ) ? $pages[ $current - 1 ] : 0;
$next    = ( false !== $current && $current < count( $pages ) - 1 ) ? $pages[ $current + 1 ] : 0;
?>

<section<?php echo ttng_module_attrs( 'guide-nav', 'light' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">

		<?php if ( 'prev_next' !== $display ) : ?>
			<nav aria-label="<?php esc_attr_e( 'Guide contents', 'ttng-main-site-theme' ); ?>">
				<ol class="guide-nav__list">
					<?php foreach ( $pages as $i => $page_id ) : ?>
						<li>
							<a class="guide-nav__link" href="<?php echo esc_url( get_permalink( $page_id ) ); ?>"<?php echo $i === $current ? ' aria-current="page"' : ''; ?>>
								<span class="guide-nav__number" aria-hidden="true"><?php echo esc_html( $i + 1 ); ?></span>
								<span><?php echo esc_html( get_the_title( $page_id ) ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ol>
			</nav>
		<?php endif; ?>

		<?php if ( 'series' !== $display && ( $prev || $next ) ) : ?>
			<nav class="guide-nav__pager" aria-label="<?php esc_attr_e( 'Previous and next guide', 'ttng-main-site-theme' ); ?>">
				<?php if ( $prev ) : ?>
					<a class="guide-nav__pager-link guide-nav__pager-link--prev" href="<?php echo esc_url( get_permalink( $prev ) ); ?>" rel="prev">
						<?php echo ttng_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?>
						<span><small><?php esc_html_e( 'Previous', 'ttng-main-site-theme' ); ?></small> <?php echo esc_html( get_the_title( $prev ) ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( $next ) : ?>
					<a class="guide-nav__pager-link guide-nav__pager-link--next" href="<?php echo esc_url( get_permalink( $next ) ); ?>" rel="next">
						<span><small><?php esc_html_e( 'Next', 'ttng-main-site-theme' ); ?></small> <?php echo esc_html( get_the_title( $next ) ); ?></span>
						<?php echo ttng_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?>
					</a>
				<?php endif; ?>
			</nav>
		<?php endif; ?>

	</div>
</section>
