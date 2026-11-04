<?php
/**
 * Tabs Module
 *
 * Section header, then tabbed panels (rich text plus optional image).
 * Without JavaScript all panels stay visible, stacked. main.js adds the tab
 * bar behaviour, including arrow-key navigation.
 * Side layout: coloured tab buttons (tab_colour) down the left with an arrow,
 * content beside them, no panel card.
 *
 * Related CSS: assets/css/modules/tabs.css
 * Related JS: assets/js/main.js (.ttng-tabs)
**/

defined( 'ABSPATH' ) || exit;

// Read as an array: see the repeater note in faq.php.
$tabs = get_sub_field( 'tabs' );

if ( empty( $tabs ) ) {
	return;
}

// unique so two Tabs modules on one page never share ARIA ids.
$uid    = wp_unique_id( 'ttng-tabs-' );
$layout = 'side' === get_sub_field( 'tabs_layout' ) ? 'side' : 'top';
$colours = [ 'navy', 'pink', 'teal', 'black', 'blue' ];
?>

<section<?php echo ttng_module_attrs( 'tabs', 'white', 'tabs--' . $layout ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">

		<div class="row justify-content-center">
			<div class="<?php echo 'side' === $layout ? 'col-12' : 'col-lg-9 col-xl-8 text-center'; ?>">
				<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
			</div>
		</div>

		<div class="ttng-tabs ttng-tabs--<?php echo esc_attr( $layout ); ?>">
			<div class="ttng-tabs__list" role="tablist" hidden>
				<?php foreach ( $tabs as $i => $item ) : ?>
					<?php $colour = in_array( $item['tab_colour'] ?? '', $colours, true ) ? $item['tab_colour'] : 'navy'; ?>
					<button type="button" class="ttng-tabs__tab<?php echo 'side' === $layout ? ' ttng-tabs__tab--' . esc_attr( $colour ) : ''; ?>" role="tab" id="<?php echo esc_attr( "{$uid}-tab-{$i}" ); ?>" aria-controls="<?php echo esc_attr( "{$uid}-panel-{$i}" ); ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"<?php echo 0 === $i ? '' : ' tabindex="-1"'; ?>>
						<span class="ttng-tabs__tab-text"><?php echo esc_html( $item['tab_title'] ); ?></span>
						<?php echo 'side' === $layout ? ttng_icon( 'arrow' ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?>
					</button>
				<?php endforeach; ?>
			</div>

			<?php foreach ( $tabs as $i => $item ) : ?>
				<div class="ttng-tabs__panel" id="<?php echo esc_attr( "{$uid}-panel-{$i}" ); ?>" aria-labelledby="<?php echo esc_attr( "{$uid}-tab-{$i}" ); ?>">
					<div class="row align-items-center">
						<div class="<?php echo $item['tab_image'] ? 'col-lg-7' : 'col-12'; ?>">
							<?php // Heading is only for the stacked no-JS view; main.js hides it. ?>
							<h3 class="ttng-tabs__panel-title"><?php echo esc_html( $item['tab_title'] ); ?></h3>
							<div class="content-block"><?php echo wp_kses_post( $item['tab_content'] ); ?></div>
						</div>
						<?php if ( $item['tab_image'] ) : ?>
							<div class="col-lg-5 ttng-tabs__media">
								<?php echo wp_get_attachment_image( $item['tab_image'], 'large' ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'justify-content-center section-ctas--below' ] ); ?>

	</div>
</section>
