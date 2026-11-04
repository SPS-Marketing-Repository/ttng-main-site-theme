<?php
/**
 * Contact Form Module
 *
 * Left: label, title, content and (optionally) phone + email from Site
 * Settings. Right: a form, either from a shortcode (e.g. Contact Form 7)
 * or an iframe embed (e.g. ClickUp form).
 *
 * Related CSS: assets/css/modules/contact_form.css
 * Related CSS (Theme CSS): assets/css/core/additions.css
 * Related CSS (Global CSS): assets/css/core/base.css
**/

defined( 'ABSPATH' ) || exit;

$show_details = get_sub_field( 'show_contact_details' );
$form_type    = 'embed' === get_sub_field( 'form_type' ) ? 'embed' : 'shortcode';
$form_code    = trim( (string) get_sub_field( 'shortcode' === $form_type ? 'form_shortcode' : 'form_embed' ) );

// A shortcode field must actually contain a shortcode — never echo raw text.
if ( 'shortcode' === $form_type && 0 !== strpos( $form_code, '[' ) ) {
	$form_code = '';
}
?>

<section<?php echo ttng_module_attrs( 'contact-form', 'gradient' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">
		<div class="row">

			<div class="<?php echo $form_code ? 'col-lg-5' : 'col-lg-8'; ?> contact-form__text">
				<?php get_template_part( 'template-parts/snippets/section-header' ); ?>

				<?php if ( $show_details ) : ?>
					<?php get_template_part( 'template-parts/snippets/contact-details', null, [ 'source' => get_sub_field( 'contact_details_source' ) ] ); ?>
				<?php endif; ?>
			</div>

			<?php if ( $form_code ) : ?>
				<div class="col-lg-7 contact-form__form-col">
					<div class="contact-form__form">
						<?php
						if ( 'shortcode' === $form_type ) {
							// Form plugin output (inputs, selects, nonces) — escaping it would strip the form.
							echo do_shortcode( $form_code ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							echo ttng_kses_iframe( $form_code ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised by ttng_kses_iframe().
						}
						?>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
