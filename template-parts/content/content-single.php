<?php
/**
 * content-single.php – Single Insight (post), Member Story or Event.
 *
 * Written in the normal WordPress editor rather than the page builder:
 * back link, title, date/author, featured image, body, share links, previous
 * link and "More to Explore".
 *
 * Styles: assets/css/core/insights.css
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

$insights_url = get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : home_url( '/' );
$event_date   = get_field( 'event_date' );
?>
<main id="main" class="site-main">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<article <?php post_class( 'insight' ); ?>>

			<header class="module section-bg--gradient is-dark insight__header">
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-lg-10 col-xl-8">
							<?php ttng_breadcrumbs( 'insight__breadcrumbs' ); ?>
							<a class="insight__back" href="<?php echo esc_url( $insights_url ); ?>">
								<?php echo ttng_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?>
								<?php esc_html_e( 'Back to Insights', 'ttng-main-site-theme' ); ?>
							</a>
							<h1 class="insight__title"><?php the_title(); ?></h1>
							<p class="insight__meta">
								<?php if ( $event_date ) : ?>
									<?php $event_time = DateTime::createFromFormat( 'Ymd', $event_date ); ?>
									<?php if ( $event_time ) : ?>
										<span><?php echo esc_html( sprintf( /* translators: %s: event date */ __( 'Event date: %s', 'ttng-main-site-theme' ), date_i18n( get_option( 'date_format' ), $event_time->getTimestamp() ) ) ); ?></span>
									<?php endif; ?>
								<?php else : ?>
									<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
								<?php endif; ?>
								<span><?php the_author(); ?></span>
							</p>
						</div>
					</div>
				</div>
			</header>

			<div class="module section-bg--white insight__body">
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-lg-10 col-xl-8">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="insight__image"><?php the_post_thumbnail( 'large', [ 'loading' => 'eager' ] ); ?></div>
							<?php endif; ?>

							<div class="content-block"><?php the_content(); ?></div>

							<?php get_template_part( 'template-parts/snippets/share-links' ); ?>

							<?php $previous = get_previous_post(); ?>
							<?php if ( $previous ) : ?>
								<nav class="insight__pager" aria-label="<?php esc_attr_e( 'Previous insight', 'ttng-main-site-theme' ); ?>">
									<a href="<?php echo esc_url( get_permalink( $previous ) ); ?>" rel="prev">
										<small><?php esc_html_e( 'Previous', 'ttng-main-site-theme' ); ?></small>
										<?php echo esc_html( get_the_title( $previous ) ); ?>
									</a>
								</nav>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

		</article>

		<?php
		$related = new WP_Query(
			[
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => 3,
				'post__not_in'        => [ get_the_ID() ],
				'category__in'        => wp_get_post_categories( get_the_ID() ),
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			]
		);
		?>
		<?php if ( $related->have_posts() ) : ?>
			<section class="module section-bg--light insight__related" aria-labelledby="insight-related-title">
				<div class="container">
					<h2 id="insight-related-title" class="text-center"><?php esc_html_e( 'More to Explore', 'ttng-main-site-theme' ); ?></h2>
					<div class="row latest-news__row">
						<?php while ( $related->have_posts() ) : ?>
							<?php $related->the_post(); ?>
							<div class="col-md-6 col-lg-4 latest-news__col">
								<?php get_template_part( 'template-parts/snippets/news-card' ); ?>
							</div>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</div>
			</section>
		<?php endif; ?>
	<?php endwhile; ?>
</main>
