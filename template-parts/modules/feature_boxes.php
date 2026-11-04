<?php
/**
 * Feature Boxes Module
 *
 * Staging membership-page boxes ("Why Choose Independent Travel Experts?",
 * "How It Works", "What we can offer"): section header, then rounded navy
 * gradient boxes in 2 or 3 columns, each with an optional icon, a title and
 * content. Bulleted lists inside a box show blue tick icons. A row that
 * doesn't fill up is centred. box_width overrides the column width per box,
 * for custom rows such as 3 / 2 / 1 ("How It Works").
 * Cards style (About "The Principles We Live By"): centred icon on top,
 * alternating navy / blue cards, up to 5 in a row.
 * Per box: colour override (box_colour), logos under the content
 * (box_images) and a button (box_link). Optional text + image below the
 * boxes (footer_text / footer_image, staging Managed Services).
 *
 * Related CSS: assets/css/modules/feature_boxes.css
**/

defined( 'ABSPATH' ) || exit;

// Read as an array — see faq.php for why not have_rows() here.
$boxes = get_sub_field( 'boxes' );

if ( empty( $boxes ) || ! is_array( $boxes ) ) {
	return;
}

$columns = (int) get_sub_field( 'box_columns' );
$columns = in_array( $columns, [ 2, 3, 4, 5 ], true ) ? $columns : 2;
$style   = 'cards' === get_sub_field( 'box_style' ) ? 'cards' : 'boxes';
?>

<section<?php echo ttng_module_attrs( 'feature-boxes', 'white', 'feature-boxes--cols-' . $columns . ' feature-boxes--' . $style ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-10 col-xl-9 text-center">
				<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
			</div>
		</div>

		<ul class="feature-boxes__list" role="list">
			<?php foreach ( $boxes as $box ) : ?>
				<?php
				$icon    = $box['box_icon'] ?? 0;
				$title   = $box['box_title'] ?? '';
				$content = $box['box_content'] ?? '';
				if ( ! $title && ! $content && empty( $box['box_images'] ) ) {
					continue;
				}

				// 'default' and rows saved before this field existed get no class, so they follow Columns.
				$width     = $box['box_width'] ?? '';
				$box_class = in_array( $width, [ 'third', 'half', 'wide', 'two-thirds', 'full' ], true ) ? ' feature-box--' . $width : '';
				$colour    = $box['box_colour'] ?? '';
				$box_class .= in_array( $colour, [ 'navy', 'blue', 'dark', 'light' ], true ) ? ' feature-box--' . $colour : '';
				$images    = array_filter( (array) ( $box['box_images'] ?? [] ) );
				$box_link  = $box['box_link'] ?? [];
				?>
				<li class="feature-box<?php echo esc_attr( $box_class ); ?>">
					<?php if ( $title || $icon ) : ?>
						<h3 class="feature-box__title">
							<?php if ( $icon ) : ?>
								<span class="feature-box__icon"><?php echo wp_get_attachment_image( $icon, 'thumbnail', false, [ 'alt' => '' ] ); ?></span>
							<?php endif; ?>
							<span><?php echo esc_html( $title ); ?></span>
						</h3>
					<?php endif; ?>
					<?php if ( $content ) : ?>
						<div class="feature-box__content content-block"><?php echo wp_kses_post( $content ); ?></div>
					<?php endif; ?>
					<?php if ( $images ) : ?>
						<div class="feature-box__images">
							<?php foreach ( $images as $image_id ) : ?>
								<?php echo wp_get_attachment_image( $image_id, 'medium', false, [ 'alt' => '' ] ); ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $box_link['url'] ) ) : ?>
						<div class="feature-box__cta"><?php ttng_the_button( $box_link, 'primary', 'btn--small' ); ?></div>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php
		$footer_text  = get_sub_field( 'footer_text' );
		$footer_image = get_sub_field( 'footer_image' );
		?>
		<?php if ( $footer_text || $footer_image ) : ?>
			<div class="feature-boxes__footer">
				<?php if ( $footer_text ) : ?>
					<div class="feature-boxes__footer-text content-block"><?php echo wp_kses_post( $footer_text ); ?></div>
				<?php endif; ?>
				<?php if ( $footer_image ) : ?>
					<div class="feature-boxes__footer-image"><?php echo wp_get_attachment_image( $footer_image, 'medium', false, [ 'alt' => '' ] ); ?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'justify-content-center section-ctas--below' ] ); ?>
	</div>
</section>
