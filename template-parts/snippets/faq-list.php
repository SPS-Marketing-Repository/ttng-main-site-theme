<?php
/**
 * faq-list.php – Shared accordion and optional FAQPage JSON-LD for FAQ modules.
 *
 * Reads the shared sub fields faq_first_open, faq_single_open and faq_schema
 * from the current page_builder row. Pass the questions as an array:
 *
 *   get_template_part( 'template-parts/snippets/faq-list', null, [ 'faqs' => $faqs ] );
 *
 * Built on native <details>/<summary> — keyboard and screen reader accessible
 * with no JavaScript. "One open at a time" uses the details `name` attribute
 * (exclusive accordion); older browsers simply allow several open.
 * assets/js/main.js adds the smooth open/close and takes over "one open at a
 * time", so the others can animate shut too.
 *
 * FAQPage JSON-LD is off by default so it doesn't duplicate schema from an
 * SEO plugin.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

$faqs = $args['faqs'] ?? [];

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
