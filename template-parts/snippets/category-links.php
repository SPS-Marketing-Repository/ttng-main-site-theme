<?php
/**
 * category-links.php – Links to the Insights landing page and each category
 * with posts. Uses plain links, so every category page has its own URL.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

$categories  = get_categories( [ 'hide_empty' => true, 'parent' => 0, 'exclude' => (int) get_option( 'default_category' ) ] );
$posts_page  = (int) get_option( 'page_for_posts' );
$current_cat = is_category() ? get_queried_object_id() : 0;

if ( ! $categories ) {
	return;
}
?>
<nav class="category-links" aria-label="<?php esc_attr_e( 'Insight categories', 'ttng-main-site-theme' ); ?>">
	<div class="container">
		<ul class="category-links__list">
			<?php if ( $posts_page ) : ?>
				<li><a href="<?php echo esc_url( get_permalink( $posts_page ) ); ?>"<?php echo is_home() ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'All', 'ttng-main-site-theme' ); ?></a></li>
			<?php endif; ?>
			<?php foreach ( $categories as $category ) : ?>
				<li><a href="<?php echo esc_url( get_category_link( $category ) ); ?>"<?php echo $category->term_id === $current_cat ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $category->name ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</nav>
