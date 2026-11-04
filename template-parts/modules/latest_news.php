<?php
/**
 * Latest News Module
 *
 * Latest posts, optionally limited to one or more categories.
 * The whole section is skipped if there are no matching posts.
 *
 * Related CSS: assets/css/modules/latest_news.css
 * Card markup: template-parts/snippets/news-card.php
 * Related CSS (Theme CSS): assets/css/core/additions.css
 * Related CSS (Global CSS): assets/css/core/base.css
**/

defined( 'ABSPATH' ) || exit;

$news_categories = get_sub_field( 'news_categories' );
$news_count      = absint( get_sub_field( 'news_count' ) );
$news_count      = $news_count ? min( $news_count, 12 ) : 3;
$news_display    = 'carousel' === get_sub_field( 'news_display' ) ? 'carousel' : 'grid';

$query_args = [
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => $news_count,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true, // No pagination needed — skip the COUNT query.
];

if ( ! empty( $news_categories ) ) {
	$query_args['category__in'] = array_map( 'absint', (array) $news_categories );
}

// Don't list the post we're currently reading.
if ( is_singular( 'post' ) ) {
	$query_args['post__not_in'] = [ get_the_ID() ];
}

$news_query = new WP_Query( $query_args );

if ( ! $news_query->have_posts() ) {
	return;
}
?>

<section<?php echo ttng_module_attrs( 'latest-news' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">

		<div class="row justify-content-center">
			<div class="col-lg-9 col-xl-8 text-center">
				<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
			</div>
		</div>

		<?php if ( 'carousel' === $news_display ) : ?>
			<?php // Staging "What do our Members say?" — two cards per view, swipeable (main.js .news-carousel). ?>
			<div class="news-carousel" role="region" aria-roledescription="carousel" aria-label="<?php echo esc_attr( get_sub_field( 'section_title' ) ? get_sub_field( 'section_title' ) : __( 'Latest stories', 'ttng-main-site-theme' ) ); ?>">
				<div class="news-carousel__track" tabindex="0">
					<?php
					while ( $news_query->have_posts() ) :
						$news_query->the_post();
						?>
						<div class="news-carousel__slide">
							<?php get_template_part( 'template-parts/snippets/news-card' ); ?>
						</div>
					<?php endwhile; ?>
				</div>
				<div class="news-carousel__controls">
					<button type="button" class="news-carousel__arrow news-carousel__arrow--prev" aria-label="<?php esc_attr_e( 'Previous stories', 'ttng-main-site-theme' ); ?>"><?php echo ttng_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?></button>
					<div class="news-carousel__dots" aria-hidden="true"></div>
					<button type="button" class="news-carousel__arrow news-carousel__arrow--next" aria-label="<?php esc_attr_e( 'Next stories', 'ttng-main-site-theme' ); ?>"><?php echo ttng_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?></button>
				</div>
			</div>
		<?php else : ?>
			<div class="row latest-news__row">
				<?php
				while ( $news_query->have_posts() ) :
					$news_query->the_post();
					?>
					<div class="col-md-6 col-lg-4 latest-news__col">
						<?php get_template_part( 'template-parts/snippets/news-card' ); ?>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>

		<?php
		// Restore the page's own post before reading more ACF sub fields.
		wp_reset_postdata();
		get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'justify-content-center section-ctas--below' ] );
		?>

	</div>
</section>
