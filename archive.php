<?php
/**
 * Category, date and author archives.
 *
 * Category intro: Posts > Categories > Intro Paragraph (category_intro), falling
 * back to the category's normal description.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

$intro = '';

if ( is_category() ) {
	$intro = get_field( 'category_intro', get_queried_object() );
	$intro = $intro ? $intro : category_description();
}

get_header();
?>
<main id="main" class="site-main">
	<?php
	get_template_part(
		'template-parts/content/content-listing-header',
		null,
		[
			'title' => wp_strip_all_tags( get_the_archive_title() ),
			'intro' => $intro,
		]
	);
	?>
	<section class="module section-bg--white insights-listing">
		<div class="container">
			<?php get_template_part( 'template-parts/content/content-listing' ); ?>
		</div>
	</section>
</main>
<?php
get_footer();
