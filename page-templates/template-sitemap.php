<?php
/**
 * Template Name: HTML Sitemap
 *
 * Generated list of published pages and Insights categories, for the footer's
 * Sitemap link. Updates itself as pages are added.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="site-main">
	<?php get_template_part( 'template-parts/content/content-listing-header', null, [ 'title' => get_the_title(), 'categories' => false ] ); ?>
	<section class="module section-bg--white html-sitemap">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 content-block">
					<h2><?php esc_html_e( 'Pages', 'ttng-main-site-theme' ); ?></h2>
					<ul><?php wp_list_pages( [ 'title_li' => '' ] ); ?></ul>
				</div>
				<div class="col-lg-4 content-block">
					<h2><?php esc_html_e( 'Insights', 'ttng-main-site-theme' ); ?></h2>
					<ul><?php wp_list_categories( [ 'title_li' => '', 'exclude' => (int) get_option( 'default_category' ) ] ); ?></ul>
				</div>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
