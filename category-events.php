<?php
/**
 * Events category: Upcoming (by event_date, soonest first), then Past.
 *
 * The main query is limited to past events in inc/insights.php, which also
 * keeps its pagination working. Upcoming events only show on page one.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

$intro    = get_field( 'category_intro', get_queried_object() );
$intro    = $intro ? $intro : category_description();
$upcoming = is_paged() ? null : ttng_get_upcoming_events();

get_header();
?>
<main id="main" class="site-main">
	<?php
	get_template_part(
		'template-parts/content/content-listing-header',
		null,
		[
			'title' => single_cat_title( '', false ),
			'intro' => $intro,
		]
	);
	?>

	<?php if ( $upcoming && $upcoming->have_posts() ) : ?>
		<section class="module section-bg--light insights-listing" aria-labelledby="events-upcoming">
			<div class="container">
				<h2 id="events-upcoming" class="text-center"><?php esc_html_e( 'Upcoming Events', 'ttng-main-site-theme' ); ?></h2>
				<div class="row latest-news__row">
					<?php while ( $upcoming->have_posts() ) : ?>
						<?php $upcoming->the_post(); ?>
						<div class="col-md-6 col-lg-4 latest-news__col">
							<?php get_template_part( 'template-parts/snippets/news-card' ); ?>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="module section-bg--white insights-listing" aria-labelledby="events-past">
		<div class="container">
			<h2 id="events-past" class="text-center"><?php esc_html_e( 'Past Events', 'ttng-main-site-theme' ); ?></h2>
			<?php get_template_part( 'template-parts/content/content-listing', null, [ 'empty' => __( 'No past events yet.', 'ttng-main-site-theme' ) ] ); ?>
		</div>
	</section>
</main>
<?php
get_footer();
