<?php
/**
 * FAQs with Quick Links Module
 *
 * Sidebar on the left (label, quick links, short text and a link), with the
 * section title, intro and accordion on the right. Questions slide open and
 * shut through the shared .faq-item handling in assets/js/main.js.
 *
 * The sidebar comes first in the markup to match the reading order on desktop,
 * and moves below the questions on smaller screens (see the CSS).
 *
 * Related CSS: assets/css/modules/faq_links.css (layout and sidebar) and
 * assets/css/modules/faq.css (the accordion, shared with the FAQs module)
 * Related snippet: template-parts/snippets/faq-list.php
**/

defined( 'ABSPATH' ) || exit;

// Read as an array — see faq.php for why not have_rows() here.
$faqs = get_sub_field( 'faqs' );

if ( empty( $faqs ) || ! is_array( $faqs ) ) {
	return;
}

// The accordion uses the FAQs module's styles. Same handle as the page builder
// gives faq.css, so it loads once when both modules are on a page.
if ( ! wp_style_is( 'faq-css', 'enqueued' ) ) {
	$faq_css = 'assets/css/modules/faq.css';
	wp_enqueue_style( 'faq-css', get_theme_file_uri( $faq_css ), [], (string) filemtime( get_theme_file_path( $faq_css ) ) );
}

$sidebar_label = get_sub_field( 'sidebar_label' );
$quick_links   = get_sub_field( 'quick_links' );
$sidebar_text  = get_sub_field( 'sidebar_text' );
$sidebar_link  = get_sub_field( 'sidebar_link' );
$quick_links   = is_array( $quick_links ) ? $quick_links : [];
$has_sidebar   = $sidebar_label || $quick_links || $sidebar_text || ! empty( $sidebar_link['url'] );
?>

<section<?php echo ttng_module_attrs( 'faq-links', 'white', $has_sidebar ? '' : 'faq-links--no-sidebar' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">
		<div class="faq-links__layout">

			<?php if ( $has_sidebar ) : ?>
				<aside class="faq-links__sidebar"<?php echo $sidebar_label ? ' aria-label="' . esc_attr( str_replace( '*', '', $sidebar_label ) ) . '"' : ''; ?>>
					<?php if ( $sidebar_label ) : ?>
						<p class="section-label"><?php echo ttng_format_label( $sidebar_label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
					<?php endif; ?>

					<?php if ( $quick_links ) : ?>
						<ul class="faq-links__list" role="list">
							<?php foreach ( $quick_links as $quick_link ) : ?>
								<?php if ( ! empty( $quick_link['quick_link']['url'] ) ) : ?>
									<li><?php ttng_the_arrow_link( $quick_link['quick_link'], 'faq-links__link' ); ?></li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( $sidebar_text ) : ?>
						<p class="faq-links__text"><?php echo esc_html( $sidebar_text ); ?></p>
					<?php endif; ?>

					<?php ttng_the_arrow_link( $sidebar_link, 'faq-links__link' ); ?>
				</aside>
			<?php endif; ?>

			<div class="faq-links__main">
				<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
				<?php get_template_part( 'template-parts/snippets/faq-list', null, [ 'faqs' => $faqs ] ); ?>
			</div>

		</div>
	</div>
</section>
