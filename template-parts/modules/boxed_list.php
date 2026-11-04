<?php
/**
 * Boxed List Module
 *
 * Staging "Why Choose Us Over Other Homeworking Agencies?" panel: section
 * header, then a rounded panel of 1 to 4 joined columns. Each column has its
 * own background colour and a centred title, then groups of a subheading and
 * ticked points. Each line of an item's text is one ticked point.
 *
 * Related CSS: assets/css/modules/boxed_list.css
**/

defined( 'ABSPATH' ) || exit;

// Read as an array — see faq.php for why not have_rows() here.
$columns = get_sub_field( 'list_columns' );

if ( empty( $columns ) || ! is_array( $columns ) ) {
	return;
}

// Ignore rows with nothing in them, so they don't leave an empty column.
$columns = array_filter(
	$columns,
	function ( $column ) {
		return ! empty( $column['column_title'] ) || ! empty( $column['column_items'] );
	}
);

if ( empty( $columns ) ) {
	return;
}

$allowed_backgrounds = [ 'navy', 'navy-deep', 'dark', 'black', 'grey', 'white' ];
$column_count        = min( count( $columns ), 4 );
?>

<section<?php echo ttng_module_attrs( 'boxed-list', 'navy-primary', 'boxed-list--cols-' . $column_count ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8 col-xl-6 text-center">
				<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
			</div>
		</div>

		<div class="boxed-list__panel">
			<?php foreach ( array_slice( $columns, 0, 4 ) as $column ) : ?>
				<?php
				$background = $column['column_background'] ?? '';
				$background = in_array( $background, $allowed_backgrounds, true ) ? $background : 'dark';
				$items      = ! empty( $column['column_items'] ) && is_array( $column['column_items'] ) ? $column['column_items'] : [];
				?>
				<div class="boxed-list__column boxed-list__column--<?php echo esc_attr( $background ); ?>">
					<?php if ( ! empty( $column['column_title'] ) ) : ?>
						<h3 class="boxed-list__title"><?php echo esc_html( $column['column_title'] ); ?></h3>
					<?php endif; ?>

					<?php foreach ( $items as $item ) : ?>
						<?php
						$item_title = $item['item_title'] ?? '';
						$points     = preg_split( '/\R/', trim( (string) ( $item['item_text'] ?? '' ) ), -1, PREG_SPLIT_NO_EMPTY );

						if ( ! $item_title && ! $points ) {
							continue;
						}
						?>
						<div class="boxed-list__item">
							<?php if ( $item_title ) : ?>
								<h4 class="boxed-list__item-title"><?php echo esc_html( $item_title ); ?></h4>
							<?php endif; ?>

							<?php if ( $points ) : ?>
								<ul class="boxed-list__points" role="list">
									<?php foreach ( $points as $point ) : ?>
										<li class="boxed-list__point"><?php echo wp_kses_post( trim( $point ) ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'justify-content-center section-ctas--below' ] ); ?>
	</div>
</section>
