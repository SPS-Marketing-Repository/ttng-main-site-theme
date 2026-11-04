<?php
/**
 * FAQs Module
 *
 * Centred label / title / content, then an accordion of questions built on
 * native <details>/<summary> — keyboard and screen reader accessible with
 * no JavaScript. "One open at a time" uses the details `name` attribute
 * (exclusive accordion); older browsers simply allow several open.
 * assets/js/main.js adds the smooth open/close and takes over "one open at a
 * time", so the others can animate shut too.
 *
 * Optional FAQPage JSON-LD (off by default so it doesn't duplicate schema
 * from an SEO plugin).
 *
 * Related CSS: assets/css/modules/faq.css
 * Related CSS (Theme CSS): assets/css/core/additions.css
 * Related CSS (Global CSS): assets/css/core/base.css
**/

defined( 'ABSPATH' ) || exit;

// Read the repeater as an array rather than via have_rows(): an early
// have_rows() check would start the repeater loop, and every later
// get_sub_field() (settings, section header) would then read the repeater
// row instead of this layout row.
$faqs = get_sub_field( 'faqs' );

if ( empty( $faqs ) || ! is_array( $faqs ) ) {
	return;
}

$first_open  = get_sub_field( 'faq_first_open' );
$single_open = get_sub_field( 'faq_single_open' );
$add_schema  = get_sub_field( 'faq_schema' );
$group_name  = 'faq-' . wp_unique_id();
$schema_qas  = [];
$index       = 0;
?>

<section<?php echo ttng_module_attrs( 'faq' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-12">

				<?php get_template_part( 'template-parts/snippets/section-header', null, [ 'class' => 'text-center' ] ); ?>

				<div class="faq-list">
					<?php
					foreach ( $faqs as $faq ) :
						$question = isset( $faq['faq_question'] ) ? $faq['faq_question'] : '';
						$answer   = isset( $faq['faq_answer'] ) ? $faq['faq_answer'] : '';

						if ( ! $question || ! $answer ) {
							continue;
						}

						$is_open = $first_open && 0 === $index;
						$index++;

						if ( $add_schema ) {
							$schema_qas[] = [
								'@type'          => 'Question',
								'name'           => wp_strip_all_tags( $question ),
								'acceptedAnswer' => [
									'@type' => 'Answer',
									'text'  => wp_kses_post( $answer ),
								],
							];
						}
						?>
						<details class="faq-item"<?php echo $single_open ? ' name="' . esc_attr( $group_name ) . '"' : ''; ?><?php echo $is_open ? ' open' : ''; ?>>
							<summary class="faq-item__question">
								<span class="faq-item__question-text"><?php echo esc_html( $question ); ?></span>
								<span class="faq-item__icon" aria-hidden="true"></span>
							</summary>
							<div class="faq-item__panel">
								<div class="faq-item__answer content-block">
									<?php echo wp_kses_post( $answer ); ?>
								</div>
							</div>
						</details>
					<?php endforeach; ?>
				</div>

				<?php get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'justify-content-center section-ctas--below' ] ); ?>

			</div>
		</div>
	</div>

	<?php if ( $schema_qas ) : ?>
		<script type="application/ld+json">
			<?php
			// JSON_HEX_TAG encodes < and > so answer HTML can't close the script tag.
			echo wp_json_encode(
				[
					'@context'   => 'https://schema.org',
					'@type'      => 'FAQPage',
					'mainEntity' => $schema_qas,
				],
				JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
			);
			?>
		</script>
	<?php endif; ?>
</section>
