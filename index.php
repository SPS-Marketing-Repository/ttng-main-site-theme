<?php
/**
 * Required fallback. Pages, posts and archives all have their own templates,
 * so this only runs for anything unexpected: list whatever the query found.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="site-main">
	<?php get_template_part( 'template-parts/content/content-listing-header', null, [ 'title' => get_bloginfo( 'name' ), 'categories' => false ] ); ?>
	<section class="module section-bg--white insights-listing">
		<div class="container">
			<?php get_template_part( 'template-parts/content/content-listing' ); ?>
		</div>
	</section>
</main>
<?php
get_footer();
