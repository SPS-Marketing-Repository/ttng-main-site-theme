<?php
/**
 * Download Module
 *
 * Staging "Brochure Download" section: centred accent bar, section header and
 * buttons, with an image underneath. The buttons are the shared section_ctas,
 * so they can link to a page with a form or straight to a file.
 *
 * Related CSS: assets/css/modules/download.css
**/

defined( 'ABSPATH' ) || exit;

// Read before the buttons: ctas.php runs have_rows() on section_ctas (see faq.php).
$image = get_sub_field( 'download_image' );

if ( ! $image && ! get_sub_field( 'section_title' ) && ! get_sub_field( 'section_content' ) && ! get_sub_field( 'section_ctas' ) ) {
	return;
}
?>

<section<?php echo ttng_module_attrs( 'download' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">
		<div class="download__intro text-center">
			<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
			<?php get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'justify-content-center' ] ); ?>
		</div>

		<?php if ( $image ) : ?>
			<div class="download__media">
				<?php
				// medium_large (768px) covers the 450px cap on retina screens without loading the full upload.
				echo wp_get_attachment_image(
					$image,
					'medium_large',
					false,
					[
						'class' => 'download__image',
						'sizes' => '(max-width: 600px) 320px, 450px',
					]
				);
				?>
			</div>
		<?php endif; ?>
	</div>
</section>
