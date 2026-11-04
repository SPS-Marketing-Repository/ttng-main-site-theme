<?php
/**
 * Quote Module
 *
 * Rounded navy panel with a large decorative quotation mark, the quote,
 * and the person's name/role — beside a photo (left or right). Matches the
 * CEO quote on the previous Vision page.
 *
 * Related CSS: assets/css/modules/quote.css
 * Related CSS (Theme CSS): assets/css/core/additions.css
 * Related CSS (Global CSS): assets/css/core/base.css
 * Background shape: assets/images/brand-shape.svg
**/

defined( 'ABSPATH' ) || exit;

$quote_text = get_sub_field( 'quote_text' );

if ( ! $quote_text ) {
	return;
}

$quote_name     = get_sub_field( 'quote_name' );
$quote_role     = get_sub_field( 'quote_role' );
// Build the image first — a deleted attachment returns '' and the panel
// falls back to the full-width, text-only layout.
$quote_image    = get_sub_field( 'quote_image' );
$image_html     = $quote_image ? wp_get_attachment_image( $quote_image, 'large', false, [ 'sizes' => '(max-width: 992px) 100vw, 433px' ] ) : '';
$image_position = 'left' === get_sub_field( 'image_position' ) ? 'left' : 'right';
$panel_classes  = 'quote-panel quote-panel--image-' . $image_position . ( $image_html ? '' : ' quote-panel--no-image' );
?>

<section<?php echo ttng_module_attrs( 'quote' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">
		<figure class="<?php echo esc_attr( $panel_classes ); ?>">

			<div class="quote-panel__content">
				<span class="quote-panel__mark" aria-hidden="true">&ldquo;</span>

				<blockquote class="quote-panel__text">
					<?php echo wp_kses_post( wpautop( esc_html( $quote_text ) ) ); ?>
				</blockquote>

				<?php if ( $quote_name || $quote_role ) : ?>
					<figcaption class="quote-panel__cite">
						<?php if ( $quote_name ) : ?>
							<span class="quote-panel__name"><?php echo esc_html( $quote_name ); ?></span><?php echo $quote_role ? '<span class="quote-panel__sep">,</span> ' : ''; ?>
						<?php endif; ?>
						<?php if ( $quote_role ) : ?>
							<span class="quote-panel__role"><?php echo esc_html( $quote_role ); ?></span>
						<?php endif; ?>
					</figcaption>
				<?php endif; ?>
			</div>

			<?php if ( $image_html ) : ?>
				<div class="quote-panel__media">
					<?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core wp_get_attachment_image() markup. ?>
				</div>
			<?php endif; ?>

		</figure>
	</div>
</section>
