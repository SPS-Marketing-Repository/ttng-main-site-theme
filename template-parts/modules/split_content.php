<?php
/**
 * Split Content Module (Text + Media)
 *
 * Text one side, image or video the other — the staging alternating rows
 * (Commercial, Marketing, Operations…). Consecutive split sections are
 * separated by a thin divider, like staging.
 *
 * Image style:
 *   inset — image inside the container (default).
 *   bleed — image fills its half of the section edge to edge, full height
 *           (staging "Find out more" / "Talk to us" call-to-action).
 *
 * Minimum height (Settings tab): optional floor for the section on desktop,
 * with the content centred vertically.
 *
 * Related CSS: assets/css/modules/split_content.css
**/

defined( 'ABSPATH' ) || exit;

$media_type     = get_sub_field( 'media_type' );
$media_type     = in_array( $media_type, [ 'video', 'map' ], true ) ? $media_type : 'image';
$media_position = 'left' === get_sub_field( 'media_position' ) ? 'left' : 'right';
$media_style    = 'bleed' === get_sub_field( 'media_style' ) ? 'bleed' : 'inset';
$media_image    = 'image' === $media_type ? get_sub_field( 'media_image' ) : false;
$media_video    = 'video' === $media_type ? get_sub_field( 'media_video' ) : false;
$media_map      = 'map' === $media_type ? trim( (string) get_sub_field( 'media_map' ) ) : '';
$has_media      = $media_image || $media_video || $media_map;
$is_bleed       = $has_media && $media_image && 'bleed' === $media_style;
$min_height     = absint( get_sub_field( 'section_min_height' ) ); // "auto" or an unset field gives 0.
$extra          = 'split-content--media-' . $media_position . ( $is_bleed ? ' split-content--bleed' : '' );

if ( in_array( $min_height, [ 400, 500, 600, 700 ], true ) ) {
	$extra .= ' split-content--min-' . $min_height;
}
?>

<section<?php echo ttng_module_attrs( 'split-content', 'navy', $extra ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>

	<?php if ( $is_bleed ) : ?>
		<div class="split-content__bleed" aria-hidden="true">
			<?php echo wp_get_attachment_image( $media_image, 'full', false, [ 'alt' => '' ] ); ?>
		</div>
	<?php endif; ?>

	<div class="container">
		<div class="row align-items-center">

			<div class="<?php echo $has_media ? 'col-lg-6' : 'col-lg-8'; ?> split-content__text<?php echo $has_media && 'left' === $media_position ? ' order-lg-2' : ''; ?>">
				<?php
				get_template_part( 'template-parts/snippets/section-header' );
				get_template_part( 'template-parts/snippets/ctas' );
				?>
			</div>

			<?php if ( $has_media && ! $is_bleed ) : ?>
				<div class="col-lg-6 split-content__media<?php echo 'left' === $media_position ? ' order-lg-1' : ''; ?>">
					<?php if ( $media_image ) : ?>
						<div class="split-content__image">
							<?php echo wp_get_attachment_image( $media_image, 'large' ); ?>
						</div>
					<?php elseif ( $media_map ) : ?>
						<div class="split-content__map">
							<iframe src="<?php echo esc_url( 'https://maps.google.com/maps?q=' . rawurlencode( $media_map ) . '&output=embed' ); ?>" title="<?php echo esc_attr( sprintf( /* translators: %s: address */ __( 'Map: %s', 'ttng-main-site-theme' ), $media_map ) ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
						</div>
					<?php else : ?>
						<div class="videoWrapper split-content__video">
							<?php echo ttng_kses_iframe( $media_video ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised by ttng_kses_iframe(). ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
