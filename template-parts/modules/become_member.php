<?php
/**
 * Become a Member Module
 *
 * Left: label, title, content, phone + email from Site Settings and text links.
 * Right: a Contact Form 7 form in a card, with an optional heading.
 * With an image: the image sits under a dark blue or white layer at 80%, and
 * the card is white. Without one: a solid background and a light grey card.
 * The form's code is built in CF7, using the .ttng-form-row wrapper for its
 * two-column rows.
 *
 * The section_background field is the layer colour, so ttng_module_attrs() adds
 * the dark text handling and there is still a solid colour with no image.
 *
 * Related CSS: assets/css/modules/become_member.css
 * Related CSS (Theme CSS): assets/css/core/additions.css
**/

defined( 'ABSPATH' ) || exit;

$image        = get_sub_field( 'background_image' );
$show_details = get_sub_field( 'show_contact_details' );
$links        = get_sub_field( 'section_links' );
$links        = is_array( $links ) ? $links : [];
$form_title   = get_sub_field( 'form_title' );
$form_code    = trim( (string) get_sub_field( 'form_shortcode' ) );

// A shortcode field must actually contain a shortcode — never echo raw text.
if ( 0 !== strpos( $form_code, '[' ) ) {
	$form_code = '';
}

$classes = trim( ( $image ? 'become-member--has-image' : '' ) . ( $form_code ? '' : ' become-member--no-form' ) );
?>

<section<?php echo ttng_module_attrs( 'become-member', 'white', $classes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<?php if ( $image ) : ?>
		<?php
		echo wp_get_attachment_image(
			$image,
			'full',
			false,
			[
				'class' => 'become-member__image',
				'alt'   => '',
				'sizes' => '100vw',
			]
		);
		?>
	<?php endif; ?>

	<div class="container">
		<div class="become-member__layout">

			<div class="become-member__text">
				<?php get_template_part( 'template-parts/snippets/section-header' ); ?>

				<?php if ( $show_details ) : ?>
					<?php get_template_part( 'template-parts/snippets/contact-details', null, [ 'source' => get_sub_field( 'contact_details_source' ) ] ); ?>
				<?php endif; ?>

				<?php if ( $links ) : ?>
					<ul class="become-member__links" role="list">
						<?php foreach ( $links as $link_row ) : ?>
							<?php if ( ! empty( $link_row['section_link']['url'] ) ) : ?>
								<li><?php ttng_the_arrow_link( $link_row['section_link'], 'become-member__link' ); ?></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<?php if ( $form_code ) : ?>
				<div class="become-member__form">
					<?php if ( $form_title ) : ?>
						<h3 class="become-member__form-title"><?php echo esc_html( $form_title ); ?></h3>
					<?php endif; ?>

					<?php
					// Form plugin output (inputs, selects, nonces) — escaping it would strip the form.
					echo do_shortcode( $form_code ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
