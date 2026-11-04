<?php
/**
 * Comparison Table Module
 *
 * Features down the side, up to 4 columns across (e.g. membership types).
 * Each cell is Included, Extra cost or Not included. A real <table> so screen
 * readers announce row and column. Scrolls sideways on small screens.
 *
 * Related CSS: assets/css/modules/comparison_table.css
**/

defined( 'ABSPATH' ) || exit;

// Read as arrays: an early have_rows() would start the repeater loop before
// the section header's get_sub_field() calls run (see faq.php).
$columns = get_sub_field( 'table_columns' );
$rows    = get_sub_field( 'table_rows' );

if ( empty( $columns ) || empty( $rows ) ) {
	return;
}

$columns = array_slice( $columns, 0, 4 );
$labels  = [
	'included' => __( 'Included', 'ttng-main-site-theme' ),
	'extra'    => __( 'Extra cost', 'ttng-main-site-theme' ),
];
$title   = get_sub_field( 'section_title' );
?>

<section<?php echo ttng_module_attrs( 'comparison-table' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">

		<div class="row justify-content-center">
			<div class="col-lg-9 col-xl-8 text-center">
				<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
			</div>
		</div>

		<ul class="comparison-table__legend">
			<li><span class="comparison-table__status comparison-table__status--included"><?php echo ttng_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?></span> <?php echo esc_html( $labels['included'] ); ?></li>
			<li><span class="comparison-table__status comparison-table__status--extra"><?php echo ttng_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?></span> <?php echo esc_html( $labels['extra'] ); ?></li>
		</ul>

		<?php // tabindex lets keyboard users scroll the table sideways on small screens. ?>
		<div class="comparison-table__scroll" role="region" tabindex="0" aria-label="<?php echo esc_attr( $title ? $title : __( 'Comparison table', 'ttng-main-site-theme' ) ); ?>">
			<table class="comparison-table__table">
				<thead>
					<?php if ( array_filter( wp_list_pluck( $columns, 'column_logo' ) ) ) : ?>
						<?php // Brand logos above each column (staging Membership Types table). ?>
						<tr class="comparison-table__logos">
							<td></td>
							<?php foreach ( $columns as $column ) : ?>
								<td>
									<?php
									$logo = ! empty( $column['column_logo'] ) ? wp_get_attachment_image( $column['column_logo'], 'medium', false, [ 'class' => 'comparison-table__logo', 'alt' => '' ] ) : '';
									$link = $column['column_link'] ?? [];
									if ( $logo && ! empty( $link['url'] ) ) :
										$target = ! empty( $link['target'] ) ? $link['target'] : '_self';
										?>
										<a href="<?php echo esc_url( $link['url'] ); ?>" target="<?php echo esc_attr( $target ); ?>"<?php echo '_blank' === $target ? ' rel="noopener noreferrer"' : ''; ?> aria-label="<?php echo esc_attr( ! empty( $link['title'] ) ? $link['title'] : $column['column_title'] ); ?>"><?php echo $logo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup. ?></a>
									<?php else : ?>
										<?php echo $logo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup. ?>
									<?php endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endif; ?>
					<tr class="comparison-table__headings">
						<td></td>
						<?php foreach ( $columns as $column ) : ?>
							<?php $colour = sanitize_hex_color( $column['column_colour'] ?? '' ); ?>
							<th scope="col"<?php echo $colour ? ' style="--column-colour: ' . esc_attr( $colour ) . '"' : ''; ?>><?php echo esc_html( $column['column_title'] ); ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $rows as $row ) : ?>
						<tr>
							<th scope="row"><?php echo esc_html( $row['row_label'] ); ?></th>
							<?php foreach ( array_keys( $columns ) as $index ) : ?>
								<?php $status = $row[ 'value_' . ( $index + 1 ) ] ?? ''; ?>
								<td>
									<?php if ( isset( $labels[ $status ] ) ) : ?>
										<span class="comparison-table__status comparison-table__status--<?php echo esc_attr( $status ); ?>">
											<?php echo ttng_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?>
											<span class="sr-only"><?php echo esc_html( $labels[ $status ] ); ?></span>
										</span>
									<?php else : ?>
										<span class="comparison-table__none" aria-hidden="true"></span>
										<span class="sr-only"><?php esc_html_e( 'Not included', 'ttng-main-site-theme' ); ?></span>
									<?php endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<?php get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'justify-content-center section-ctas--below' ] ); ?>

	</div>
</section>
