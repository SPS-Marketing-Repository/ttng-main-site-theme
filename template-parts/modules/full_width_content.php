<?php
/**
 * Full Width Content Module
 *
 * A mini flexible content area: headings, rich text, images, video and
 * buttons stacked in whatever order the editor chooses.
 *
 * Options: alignment (left / centre), width (narrow reading width / wide).
 *
 * Related CSS: assets/css/modules/full_width_content.css
 * Related CSS (Theme CSS): assets/css/core/additions.css
 * Related CSS (Global CSS): assets/css/core/base.css
**/

defined( 'ABSPATH' ) || exit;

$label = get_sub_field( 'section_label' );
$align = 'center' === get_sub_field( 'content_align' ) ? 'center' : 'left';
$width = 'wide' === get_sub_field( 'content_width' ) ? 'col-12' : 'col-lg-10 col-xl-8';
?>

<section<?php echo ttng_module_attrs( 'full-width-content' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">
		<div class="row<?php echo 'center' === $align ? ' justify-content-center' : ''; ?>">
			<div class="<?php echo esc_attr( $width ); ?> full-width-content__inner content-block text-<?php echo 'center' === $align ? 'center' : 'start'; ?>">

				<?php if ( $label ) : ?>
					<p class="section-label"><?php echo ttng_format_label( $label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
				<?php endif; ?>

				<?php
				if ( have_rows( 'content_blocks' ) ) :
					while ( have_rows( 'content_blocks' ) ) :
						the_row();

						switch ( get_row_layout() ) {

							case 'fw_heading':
								$heading = get_sub_field( 'heading_text' );
								$level   = get_sub_field( 'heading_level' );
								$level   = in_array( $level, [ 'h2', 'h3', 'h4' ], true ) ? $level : 'h2';
								if ( $heading ) {
									printf( '<%1$s class="fw-block fw-block--heading">%2$s</%1$s>', esc_html( $level ), esc_html( $heading ) );
								}
								break;

							case 'fw_content':
								$text = get_sub_field( 'content_text' );
								if ( $text ) {
									echo '<div class="fw-block fw-block--content">' . wp_kses_post( $text ) . '</div>';
								}
								break;

							case 'fw_image':
								$image   = get_sub_field( 'image_image' );
								$caption = get_sub_field( 'image_caption' );
								if ( $image ) {
									echo '<figure class="fw-block fw-block--image' . ( get_sub_field( 'image_panel' ) ? ' fw-block--image-panel' : '' ) . '">';
									echo wp_get_attachment_image( $image, 'large' );
									if ( $caption ) {
										echo '<figcaption>' . esc_html( $caption ) . '</figcaption>';
									}
									echo '</figure>';
								}
								break;

							case 'fw_video':
								$video = get_sub_field( 'video_embed' );
								if ( $video ) {
									echo '<div class="fw-block fw-block--video videoWrapper">' . ttng_kses_iframe( $video ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised by ttng_kses_iframe().
								}
								break;

							case 'fw_ctas':
								get_template_part(
									'template-parts/snippets/ctas',
									null,
									[
										'field' => 'block_ctas',
										'class' => 'fw-block' . ( 'center' === $align ? ' justify-content-center' : '' ),
									]
								);
								break;

							default:
								// Unknown layout — log it, don't break the page.
								error_log( 'Full Width Content: unknown layout ' . get_row_layout() );
						}

					endwhile;
				endif;
				?>

			</div>
		</div>
	</div>
</section>
