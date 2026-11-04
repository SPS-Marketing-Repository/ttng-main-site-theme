<?php
/**
 * Testimonials Module
 *
 * Staging "What our Members say": one testimonial at a time — round photo,
 * quote, italic name and business — cross-fading with dots below.
 * Pick testimonials in the module; none picked = the latest five.
 * Optional below the slider: a boxed tick list (one point per line of
 * benefits_list), a line of text (benefits_text), then the section buttons.
 *
 * Related CSS: assets/css/modules/testimonials.css
 * Related JS:  assets/js/main.js (.testimonial-slider)
**/

defined( 'ABSPATH' ) || exit;

$testimonial_ids = get_sub_field( 'testimonials' );
$testimonial_ids = is_array( $testimonial_ids ) ? array_map( static fn( $t ) => is_object( $t ) ? $t->ID : (int) $t, $testimonial_ids ) : [];

// Nothing chosen: show the latest five.
if ( ! $testimonial_ids ) {
	$testimonial_ids = get_posts(
		[
			'post_type'      => 'testimonial',
			'post_status'    => 'publish',
			'posts_per_page' => 5,
			'fields'         => 'ids',
		]
	);
}

// Only published testimonials with a quote.
$testimonial_ids = array_values(
	array_filter(
		$testimonial_ids,
		static fn( $id ) => 'publish' === get_post_status( $id ) && get_field( 'testimonial_quote', $id )
	)
);

if ( ! $testimonial_ids ) {
	return;
}

$count = count( $testimonial_ids );

// Read before the buttons: ctas.php runs have_rows() on section_ctas (see faq.php).
$benefits      = preg_split( '/\R/', trim( (string) get_sub_field( 'benefits_list' ) ), -1, PREG_SPLIT_NO_EMPTY );
$benefits_text = get_sub_field( 'benefits_text' );
?>

<section<?php echo ttng_module_attrs( 'testimonials', 'gradient', 'is-dark' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-9 col-xl-8 text-center">
				<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
			</div>
		</div>

		<div class="testimonial-slider"<?php echo $count > 1 ? ' role="region" aria-roledescription="carousel" aria-label="' . esc_attr__( 'Member testimonials', 'ttng-main-site-theme' ) . '"' : ''; ?>>
			<div class="testimonial-slider__track">
				<?php
				foreach ( $testimonial_ids as $i => $testimonial_id ) :
					$quote = get_field( 'testimonial_quote', $testimonial_id );
					$role  = get_field( 'testimonial_role', $testimonial_id );
					$photo = get_post_thumbnail_id( $testimonial_id );
					?>
					<figure class="testimonial-card<?php echo 0 === $i ? ' is-active' : ''; ?>"<?php echo $count > 1 ? ' role="group" aria-roledescription="slide" aria-label="' . esc_attr( sprintf( /* translators: 1: slide number, 2: total */ __( '%1$d of %2$d', 'ttng-main-site-theme' ), $i + 1, $count ) ) . '"' : ''; ?>>
						<?php if ( $photo ) : ?>
							<span class="testimonial-card__photo"><?php echo wp_get_attachment_image( $photo, 'medium', false, [ 'alt' => '' ] ); ?></span>
						<?php endif; ?>
						<blockquote class="testimonial-card__quote">
							<p>&ldquo;<?php echo esc_html( trim( $quote, "\"“” \t\n" ) ); ?>&rdquo;</p>
						</blockquote>
						<figcaption class="testimonial-card__author">
							<span class="testimonial-card__name"><?php echo esc_html( get_the_title( $testimonial_id ) ); ?></span>
							<?php if ( $role ) : ?>
								<span class="testimonial-card__role"><?php echo esc_html( $role ); ?></span>
							<?php endif; ?>
						</figcaption>
					</figure>
				<?php endforeach; ?>
			</div>

			<?php if ( $count > 1 ) : ?>
				<div class="testimonial-slider__dots">
					<?php for ( $d = 0; $d < $count; $d++ ) : ?>
						<button type="button" class="testimonial-slider__dot<?php echo 0 === $d ? ' is-active' : ''; ?>" data-slide="<?php echo esc_attr( $d ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: testimonial number */ __( 'Show testimonial %d', 'ttng-main-site-theme' ), $d + 1 ) ); ?>"<?php echo 0 === $d ? ' aria-current="true"' : ''; ?>></button>
					<?php endfor; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $benefits ) : ?>
			<ul class="testimonials__benefits" role="list">
				<?php foreach ( $benefits as $benefit ) : ?>
					<li class="testimonials__benefit"><?php echo wp_kses_post( trim( $benefit ) ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( $benefits_text ) : ?>
			<div class="testimonials__note section-content text-center"><?php echo wp_kses_post( $benefits_text ); ?></div>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'justify-content-center section-ctas--below' ] ); ?>
	</div>
</section>
