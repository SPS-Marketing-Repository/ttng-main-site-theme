<?php
/**
 * FAQs Module
 *
 * Centred label / title / content, then an accordion of questions.
 * The accordion and optional FAQPage JSON-LD are shared with the FAQs with
 * Quick Links module: template-parts/snippets/faq-list.php
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
?>

<section<?php echo ttng_module_attrs( 'faq' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-12">

				<?php get_template_part( 'template-parts/snippets/section-header', null, [ 'class' => 'text-center' ] ); ?>

				<?php get_template_part( 'template-parts/snippets/faq-list', null, [ 'faqs' => $faqs ] ); ?>

				<?php get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'justify-content-center section-ctas--below' ] ); ?>

			</div>
		</div>
	</div>
</section>
