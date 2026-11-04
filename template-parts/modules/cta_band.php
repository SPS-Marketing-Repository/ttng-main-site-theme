<?php
/**
 * CTA Band Module
 *
 * Centred label, title, content, buttons and optional contact details, over
 * a colour or a photo. The photo is a real <img> with an empty alt (it's
 * decorative), so it lazy-loads and gets responsive sizes.
 *
 * Related CSS: assets/css/modules/cta_band.css
**/

defined( 'ABSPATH' ) || exit;

$background = get_sub_field( 'cta_background' );
$details    = get_sub_field( 'contact_details' );
?>

<section<?php echo ttng_module_attrs( 'cta-band', 'navy', $background ? 'cta-band--has-image is-dark' : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<?php if ( $background ) : ?>
		<?php echo wp_get_attachment_image( $background, 'ttng-featured', false, [ 'class' => 'cta-band__image', 'alt' => '' ] ); ?>
	<?php endif; ?>

	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-9 col-xl-8 text-center">
				<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
				<?php get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'justify-content-center section-ctas--below' ] ); ?>

				<?php if ( $details ) : ?>
					<?php get_template_part( 'template-parts/snippets/contact-details', null, [ 'source' => $details, 'class' => 'contact-details--inline' ] ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
