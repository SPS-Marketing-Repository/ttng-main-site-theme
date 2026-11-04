<?php
/**
 * content-listing.php – Post grid and pagination for the main query.
 * Shared by home.php, archive.php, category-events.php and search.php.
 *
 * Args: 'empty' (string) message when there are no posts.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args( $args ?? [], [ 'empty' => __( 'Nothing to show here yet.', 'ttng-main-site-theme' ) ] );
?>
<?php if ( have_posts() ) : ?>
	<div class="row latest-news__row insights-listing__row">
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<div class="col-md-6 col-lg-4 latest-news__col">
				<?php get_template_part( 'template-parts/snippets/news-card' ); ?>
			</div>
		<?php endwhile; ?>
	</div>

	<?php
	the_posts_pagination(
		[
			'mid_size'           => 1,
			'prev_text'          => __( 'Previous', 'ttng-main-site-theme' ),
			'next_text'          => __( 'Next', 'ttng-main-site-theme' ),
			'screen_reader_text' => __( 'Pages', 'ttng-main-site-theme' ),
		]
	);
	?>
<?php else : ?>
	<p class="insights-listing__empty text-center"><?php echo esc_html( $args['empty'] ); ?></p>
<?php endif; ?>
