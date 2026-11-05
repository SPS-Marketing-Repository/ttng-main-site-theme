<?php
/**
 * Icon Cards Module
 *
 * Left-aligned section header with an optional text link opposite, then a grid
 * of icon / title / text / link items (3 or 4 columns).
 * Options: White (pale blue) or Blue (dark navy) background, brand shape
 * watermark on/off, and card design on/off. With the card design off, items
 * sit straight on the background and are centred. A linked item is clickable
 * across its whole area. Each item picks an icon from the set or uploads its own.
 *
 * Related CSS: assets/css/modules/icon_cards.css
 * Related CSS (Theme CSS): assets/css/core/additions.css
**/

defined( 'ABSPATH' ) || exit;

// Read as an array — see faq.php for why not have_rows() here.
$cards = get_sub_field( 'cards' );

if ( empty( $cards ) || ! is_array( $cards ) ) {
	return;
}

$columns = '4' === (string) get_sub_field( 'grid_columns' ) ? 4 : 3;
$classes = 'icon-cards--cols-' . $columns;
$classes .= get_sub_field( 'card_design' ) ? ' icon-cards--cards' : ' icon-cards--plain';
$classes .= get_sub_field( 'show_watermark' ) ? ' icon-cards--watermark' : '';

$header_link = get_sub_field( 'header_link' );
?>

<section<?php echo ttng_module_attrs( 'icon-cards', 'pale', $classes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">

		<div class="icon-cards__head">
			<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
			<?php ttng_the_arrow_link( $header_link, 'icon-cards__header-link' ); ?>
		</div>

		<ul class="icon-cards__list" role="list">
			<?php foreach ( $cards as $card ) : ?>
				<?php
				$icon  = ttng_get_field_icon( $card['icon_picker'] ?? '', $card['card_icon'] ?? 0 );
				$title = $card['card_title'] ?? '';
				$text  = $card['card_text'] ?? '';
				$link  = $card['card_link'] ?? [];

				if ( ! $icon && ! $title && ! $text ) {
					continue;
				}

				$has_link = ! empty( $link['url'] );
				?>
				<li class="icon-card<?php echo $has_link ? ' icon-card--link' : ''; ?>">
					<?php if ( $icon ) : ?>
						<span class="icon-card__icon"><?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme SVG or wp_get_attachment_image(). ?></span>
					<?php endif; ?>

					<?php if ( $title ) : ?>
						<h3 class="icon-card__title"><?php echo esc_html( $title ); ?></h3>
					<?php endif; ?>

					<?php if ( $text ) : ?>
						<p class="icon-card__text"><?php echo esc_html( $text ); ?></p>
					<?php endif; ?>

					<?php
					if ( $has_link ) {
						ttng_the_arrow_link( $link, 'icon-card__link stretched-link', $title );
					}
					?>
				</li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
