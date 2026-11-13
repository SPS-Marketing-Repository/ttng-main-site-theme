<?php
/**
 * Timeline / Steps Module
 *
 * An ordered list, shown two ways:
 * - timeline: vertical line with items either side (company history);
 *             an item's Image / logo shows as a photo across the card top
 * - steps:    numbered cards in a row (joining process)
 * - story:    year tab + logo cards in a scroll-snap carousel (About
 *             "From Humble Beginnings"); main.js wires up the arrows
 *
 * Related CSS: assets/css/modules/timeline.css
**/

defined( 'ABSPATH' ) || exit;

// Read as an array: see the repeater note in faq.php.
$items = get_sub_field( 'timeline_items' );
$style = get_sub_field( 'timeline_style' );
$style = in_array( $style, [ 'steps', 'story' ], true ) ? $style : 'timeline';

if ( empty( $items ) ) {
	return;
}
?>

<section<?php echo ttng_module_attrs( 'timeline' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">

		<div class="row justify-content-center">
			<div class="col-lg-9 col-xl-8 text-center">
				<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
			</div>
		</div>

		<?php if ( 'story' === $style ) : ?>
		<div class="timeline-carousel">
			<button type="button" class="timeline-carousel__arrow timeline-carousel__arrow--prev" aria-label="<?php esc_attr_e( 'Previous', 'ttng-main-site-theme' ); ?>" hidden><?php echo ttng_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?></button>
		<?php endif; ?>

		<ol class="timeline timeline--<?php echo esc_attr( $style ); ?>"<?php echo 'story' === $style ? ' tabindex="0"' : ''; ?>>
			<?php foreach ( $items as $i => $item ) : ?>
				<?php
				$marker = $item['item_marker'];
				if ( ! $marker && 'steps' === $style ) {
					$marker = (string) ( $i + 1 );
				}
				?>
				<li class="timeline__item">
					<?php if ( $marker ) : ?>
						<span class="timeline__marker"><?php echo esc_html( $marker ); ?></span>
					<?php endif; ?>
					<div class="timeline__body">
						<?php if ( 'story' === $style ) : ?>
							<div class="timeline__logo">
								<?php if ( ! empty( $item['item_image'] ) ) : ?>
									<?php echo wp_get_attachment_image( $item['item_image'], 'medium', false, [ 'alt' => '' ] ); ?>
								<?php endif; ?>
							</div>
						<?php elseif ( 'timeline' === $style && ! empty( $item['item_image'] ) ) : ?>
							<?php // Photo across the top of the card; alt text comes from the Media Library. ?>
							<div class="timeline__media">
								<?php echo wp_get_attachment_image( $item['item_image'], 'medium_large', false, [ 'sizes' => '(max-width: 768px) 100vw, 600px' ] ); ?>
							</div>
						<?php endif; ?>
						<?php if ( $item['item_title'] ) : ?>
							<h3 class="timeline__title"><?php echo esc_html( $item['item_title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( $item['item_text'] ) : ?>
							<p class="timeline__text"><?php echo esc_html( $item['item_text'] ); ?></p>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>

		<?php if ( 'story' === $style ) : ?>
			<button type="button" class="timeline-carousel__arrow timeline-carousel__arrow--next" aria-label="<?php esc_attr_e( 'Next', 'ttng-main-site-theme' ); ?>" hidden><?php echo ttng_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?></button>
		</div>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'justify-content-center section-ctas--below' ] ); ?>

	</div>
</section>
