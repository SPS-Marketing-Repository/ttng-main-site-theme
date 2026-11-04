<?php
/**
 * Insights landing page (Settings > Reading > Posts page).
 *
 * Any page builder modules on the Posts page show above the list, so staff can
 * add a Page Header there. With none, the page's title is used as the H1.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

$posts_page = (int) get_option( 'page_for_posts' );

get_header();
?>
<main id="main" class="site-main">
	<?php if ( $posts_page && have_rows( 'page_builder', $posts_page ) ) : ?>
		<?php ttng_render_page_builder( $posts_page ); ?>
		<?php get_template_part( 'template-parts/snippets/category-links' ); ?>
	<?php else : ?>
		<?php
		get_template_part(
			'template-parts/content/content-listing-header',
			null,
			[ 'title' => $posts_page ? get_the_title( $posts_page ) : __( 'Insights', 'ttng-main-site-theme' ) ]
		);
		?>
	<?php endif; ?>

	<section class="module section-bg--white insights-listing">
		<div class="container">
			<?php get_template_part( 'template-parts/content/content-listing' ); ?>
		</div>
	</section>
</main>
<?php
get_footer();
