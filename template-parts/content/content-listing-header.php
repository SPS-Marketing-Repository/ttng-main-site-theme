<?php
/**
 * content-listing-header.php – H1, intro and category links above a listing.
 *
 * Args: 'title' (string), 'intro' (HTML string, optional).
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args( $args ?? [], [ 'title' => '', 'intro' => '', 'categories' => true ] );
?>
<header class="module section-bg--gradient is-dark insights-listing__header">
	<div class="container">
		<div class="row">
			<div class="col-lg-10 col-xl-8 content-block">
				<?php ttng_breadcrumbs(); ?>
				<h1><?php echo esc_html( $args['title'] ); ?></h1>
				<?php if ( $args['intro'] ) : ?>
					<div class="section-content"><?php echo wp_kses_post( $args['intro'] ); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</header>
<?php if ( $args['categories'] ) : ?>
	<?php get_template_part( 'template-parts/snippets/category-links' ); ?>
<?php endif; ?>
