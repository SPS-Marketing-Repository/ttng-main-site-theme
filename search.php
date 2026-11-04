<?php
/**
 * Search results.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="site-main">
	<?php
	get_template_part(
		'template-parts/content/content-listing-header',
		null,
		[
			/* translators: %s: search term */
			'title'      => sprintf( __( 'Search results for "%s"', 'ttng-main-site-theme' ), get_search_query( false ) ),
			'categories' => false,
		]
	);
	?>
	<section class="module section-bg--white insights-listing">
		<div class="container">
			<div class="insights-listing__search"><?php get_search_form(); ?></div>
			<?php get_template_part( 'template-parts/content/content-listing', null, [ 'empty' => __( 'No results found. Try a different search.', 'ttng-main-site-theme' ) ] ); ?>
		</div>
	</section>
</main>
<?php
get_footer();
