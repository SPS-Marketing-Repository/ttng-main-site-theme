<?php
/**
 * Page Header Module
 *
 * Inner page hero: optional logo, label, H1 and subheading, plus buttons.
 * Background = header_background_image, else the page's featured image (staging
 * style), else the navy underlay. header_background_size / _position apply to
 * whichever image is used.
 * Image (header_logo) sits in a white rounded panel to the right of the
 * text, like the staging TTA page; header_logo_link makes it a link.
 * The title is the page H1 (falls back to the page title), so use this
 * module once per page, as the first module.
 *
 * Related CSS: assets/css/modules/page_header.css
**/

defined( 'ABSPATH' ) || exit;

$label   = get_sub_field( 'section_label' );
$title   = get_sub_field( 'section_title' );
$content = get_sub_field( 'section_content' );
$logo    = get_sub_field( 'header_logo' );
$link    = get_sub_field( 'header_logo_link' );
$has_url = is_array( $link ) && ! empty( $link['url'] );
$title   = $title ? $title : get_the_title( get_queried_object_id() ); // Queried object, so this also works above the Insights post list.
?>

<?php
// Staging-style photo header with a navy wash: the module's own image, else the page's featured image.
$background_id = get_sub_field( 'header_background_image' );
$header_image  = $background_id ? wp_get_attachment_image_url( $background_id, 'full' ) : '';

if ( ! $header_image && has_post_thumbnail( get_queried_object_id() ) ) {
	$header_image = get_the_post_thumbnail_url( get_queried_object_id(), 'full' );
}

$header_style = '';
$header_zoom  = false;

if ( $header_image ) {
	$size     = get_sub_field( 'header_background_size' );
	$position = get_sub_field( 'header_background_position' );

	// Rows saved before these fields existed return nothing, so fall back to cover / center.
	$size     = in_array( $size, [ 'cover', 'contain', 'auto' ], true ) ? $size : 'cover';
	$position = in_array( $position, [ 'left top', 'center top', 'right top', 'left center', 'center center', 'right center', 'left bottom', 'center bottom', 'right bottom' ], true ) ? $position : 'center center';

	// The slow zoom only suits a full-bleed image: a contained one would show twice.
	$header_zoom = 'cover' === $size;

	$header_style = sprintf(
		' style="--page-header-image: url(%1$s); --page-header-size: %2$s; --page-header-position: %3$s"',
		esc_url( $header_image ),
		esc_attr( $size ),
		esc_attr( $position )
	);
}
?>
<section<?php echo ttng_module_attrs( 'page-header', 'gradient', trim( ( $header_image ? 'has-image' : '' ) . ( $header_zoom ? ' has-image-zoom' : '' ) . ( $logo ? ' has-media' : '' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?><?php echo $header_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>>
	<div class="container">
		<div class="row align-items-center">
			<div class="<?php echo $logo ? 'col-lg-6 page-header__text' : 'col-lg-10 col-xl-8'; ?>">
				<?php ttng_breadcrumbs( 'page-header__breadcrumbs' ); ?>

				<div class="section-header content-block">
					<?php if ( $label ) : ?>
						<p class="section-label"><?php echo ttng_format_label( $label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
					<?php endif; ?>

					<h1 class="section-title"><?php echo esc_html( $title ); ?></h1>

					<?php if ( $content ) : ?>
						<div class="section-content"><?php echo wp_kses_post( $content ); ?></div>
					<?php endif; ?>
				</div>

				<?php get_template_part( 'template-parts/snippets/ctas' ); ?>
			</div>

			<?php if ( $logo ) : ?>
				<div class="col-lg-6 page-header__media-col">
					<?php
					$image = wp_get_attachment_image( $logo, 'large', false, [ 'loading' => 'eager', 'class' => 'page-header__image' ] );
					if ( $has_url ) :
						$target = ! empty( $link['target'] ) ? $link['target'] : '_self';
						$label  = ! empty( $link['title'] ) ? $link['title'] : get_post_meta( $logo, '_wp_attachment_image_alt', true );
						?>
						<a class="page-header__media page-header__media--link" href="<?php echo esc_url( $link['url'] ); ?>" target="<?php echo esc_attr( $target ); ?>"<?php echo '_blank' === $target ? ' rel="noopener noreferrer"' : ''; ?><?php echo $label ? ' aria-label="' . esc_attr( $label . ( '_blank' === $target ? ' ' . __( '(opens in a new tab)', 'ttng-main-site-theme' ) : '' ) ) . '"' : ''; ?>>
							<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup. ?>
						</a>
					<?php else : ?>
						<div class="page-header__media"><?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup. ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
