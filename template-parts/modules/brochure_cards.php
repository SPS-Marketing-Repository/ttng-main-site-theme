<?php
/**
 * Brochure Cards Module
 *
 * One row per brochure: image, title and text beside its own form embed
 * (ClickUp iframe). Embeds go through ttng_kses_iframe(), which removes
 * <script> tags, so give the iframe a fixed height (see the CSS min-height).
 *
 * Related CSS: assets/css/modules/brochure_cards.css
**/

defined( 'ABSPATH' ) || exit;

// Read as an array: see the repeater note in faq.php.
$brochures = get_sub_field( 'brochures' );

if ( empty( $brochures ) ) {
	return;
}
?>

<section<?php echo ttng_module_attrs( 'brochure-cards' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">

		<div class="row justify-content-center">
			<div class="col-lg-9 col-xl-8 text-center">
				<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
			</div>
		</div>

		<div class="brochure-cards__list">
			<?php foreach ( $brochures as $brochure ) : ?>
				<article class="brochure-card">
					<div class="row">
						<div class="<?php echo $brochure['brochure_form'] ? 'col-lg-5' : 'col-12'; ?> brochure-card__intro">
							<?php if ( $brochure['brochure_image'] ) : ?>
								<div class="brochure-card__image"><?php echo wp_get_attachment_image( $brochure['brochure_image'], 'medium' ); ?></div>
							<?php endif; ?>
							<h3 class="brochure-card__title"><?php echo esc_html( $brochure['brochure_title'] ); ?></h3>
							<?php if ( $brochure['brochure_text'] ) : ?>
								<p class="brochure-card__text"><?php echo esc_html( $brochure['brochure_text'] ); ?></p>
							<?php endif; ?>
						</div>
						<?php if ( $brochure['brochure_form'] ) : ?>
							<div class="col-lg-7 brochure-card__form">
								<?php echo ttng_kses_iframe( $brochure['brochure_form'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised by ttng_kses_iframe(). ?>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
