<?php
/**
 * Home Banner Module
 *
 * Matches the staging hero: the header sits over it (header.css), left
 * column is the H1 + text + buttons, right column a rounded image slider
 * (slides horizontally, slow Ken Burns zoom, pill-shaped pager).
 *
 * H1 = label + title:
 *   section_label → first line, e.g. "The *UK's biggest* independent"
 *                    (*asterisks* = gradient highlight; "UK's biggest" is automatic)
 *   section_title → big second line, e.g. "travel network"
 * Use this module once per page.
 *
 * Related CSS: assets/css/modules/home_banner.css
 * Related JS:  assets/js/main.js (.ttng-slider)
**/

defined( 'ABSPATH' ) || exit;

$label    = get_sub_field( 'section_label' );
$title    = get_sub_field( 'section_title' );
$content  = get_sub_field( 'section_content' );
$slides   = get_sub_field( 'banner_slides' );
$interval = absint( get_sub_field( 'banner_interval' ) );
$interval = $interval ? $interval : 5;
$slides   = is_array( $slides ) ? array_values( array_filter( array_map( static fn( $s ) => is_array( $s ) ? ( $s['ID'] ?? 0 ) : $s, $slides ) ) ) : [];
$count    = count( $slides );
?>

<section class="module module--home-banner is-dark">
	<div class="container">
		<div class="row align-items-center">

			<div class="<?php echo $count ? 'col-lg-6' : 'col-lg-8'; ?> home-banner__content">
				<h1 class="home-banner__title">
					<?php if ( $label ) : ?>
						<span class="home-banner__lead"><?php echo ttng_format_highlight( $label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></span>
					<?php endif; ?>
					<?php if ( $title ) : ?>
						<span class="home-banner__main"><?php echo esc_html( $title ); ?></span>
					<?php endif; ?>
				</h1>

				<?php if ( $content ) : ?>
					<div class="section-content home-banner__text"><?php echo wp_kses_post( $content ); ?></div>
				<?php endif; ?>

				<?php get_template_part( 'template-parts/snippets/ctas' ); ?>
			</div>

			<?php if ( $count ) : ?>
				<div class="col-lg-6 home-banner__media">
					<div class="ttng-slider home-banner__slider"
						data-interval="<?php echo esc_attr( $interval * 1000 ); ?>"
						style="--slide-duration: <?php echo esc_attr( $interval + 1 ); ?>s"
						<?php if ( $count > 1 ) : ?>
							role="region"
							aria-roledescription="carousel"
							aria-label="<?php esc_attr_e( 'Image slideshow', 'ttng-main-site-theme' ); ?>"
						<?php endif; ?>>

						<div class="ttng-slider__viewport">
							<div class="ttng-slider__track">
								<?php foreach ( $slides as $i => $slide_id ) : ?>
									<div class="ttng-slider__slide<?php echo 0 === $i ? ' is-active' : ''; ?>"
										<?php if ( $count > 1 ) : ?>
											role="group"
											aria-roledescription="slide"
											aria-label="<?php echo esc_attr( sprintf( /* translators: 1: slide number, 2: total */ __( '%1$d of %2$d', 'ttng-main-site-theme' ), $i + 1, $count ) ); ?>"
											<?php echo 0 === $i ? '' : 'aria-hidden="true"'; ?>
										<?php endif; ?>>
										<?php
										// First slide is the LCP element — load it eagerly with high priority.
										echo wp_get_attachment_image(
											$slide_id,
											'large',
											false,
											0 === $i ? [ 'loading' => 'eager', 'fetchpriority' => 'high' ] : [ 'loading' => 'lazy' ]
										);
										?>
									</div>
								<?php endforeach; ?>
							</div>
						</div>

						<?php if ( $count > 1 ) : ?>
							<div class="ttng-slider__controls">
								<button type="button" class="ttng-slider__pause" aria-label="<?php esc_attr_e( 'Pause slideshow', 'ttng-main-site-theme' ); ?>" data-label-pause="<?php esc_attr_e( 'Pause slideshow', 'ttng-main-site-theme' ); ?>" data-label-play="<?php esc_attr_e( 'Play slideshow', 'ttng-main-site-theme' ); ?>">
									<span class="ttng-slider__pause-icon" aria-hidden="true"></span>
								</button>
								<div class="ttng-slider__dots">
									<?php for ( $d = 0; $d < $count; $d++ ) : ?>
										<button type="button" class="ttng-slider__dot<?php echo 0 === $d ? ' is-active' : ''; ?>" data-slide="<?php echo esc_attr( $d ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: slide number */ __( 'Show slide %d', 'ttng-main-site-theme' ), $d + 1 ) ); ?>"<?php echo 0 === $d ? ' aria-current="true"' : ''; ?>></button>
									<?php endfor; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
