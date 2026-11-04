<?php
/**
 * People Module
 *
 * Team members (team-member post type) as a slider or grid. Name = post title,
 * photo = featured image, with a placeholder silhouette until one is added.
 * The slider is a CSS scroll-snap track; main.js wires up previous/next.
 *
 * Related CSS: assets/css/modules/people_slider.css
 * Related JS: assets/js/main.js (.people-slider)
**/

defined( 'ABSPATH' ) || exit;

$people  = get_sub_field( 'people' );
$display = 'grid' === get_sub_field( 'people_display' ) ? 'grid' : 'slider';

if ( empty( $people ) ) {
	$people = get_posts(
		[
			'post_type'      => 'team-member',
			'post_status'    => 'publish',
			'posts_per_page' => 50,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
			'fields'         => 'ids',
		]
	);
}

if ( empty( $people ) ) {
	return;
}

$title = get_sub_field( 'section_title' );
?>

<section<?php echo ttng_module_attrs( 'people' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">

		<div class="row justify-content-center">
			<div class="col-lg-9 col-xl-8 text-center">
				<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
			</div>
		</div>

		<div class="people-slider people-slider--<?php echo esc_attr( $display ); ?>">
			<?php if ( 'slider' === $display ) : ?>
				<button type="button" class="people-slider__arrow people-slider__arrow--prev" aria-label="<?php esc_attr_e( 'Previous people', 'ttng-main-site-theme' ); ?>" hidden><?php echo ttng_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?></button>
			<?php endif; ?>

			<?php // tabindex lets keyboard users scroll the track with the arrow keys. ?>
			<ul class="people-slider__track"<?php echo 'slider' === $display ? ' tabindex="0" aria-label="' . esc_attr( $title ? $title : __( 'Our people', 'ttng-main-site-theme' ) ) . '"' : ''; ?>>
				<?php foreach ( $people as $person_id ) : ?>
					<?php
					$role       = get_field( 'team_role', $person_id );
					$bio        = get_field( 'team_text', $person_id );
					$person_url = get_field( 'team_link', $person_id );
					?>
					<li class="people-slider__slide">
						<article class="person-card">
							<div class="person-card__photo">
								<?php if ( has_post_thumbnail( $person_id ) ) : ?>
									<?php echo get_the_post_thumbnail( $person_id, 'medium', [ 'alt' => '' ] ); ?>
								<?php else : ?>
									<?php echo ttng_icon( 'person' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?>
								<?php endif; ?>
							</div>
							<div class="person-card__body">
								<h3 class="person-card__name"><?php echo esc_html( get_the_title( $person_id ) ); ?></h3>
								<?php if ( $role ) : ?>
									<p class="person-card__role"><?php echo esc_html( $role ); ?></p>
								<?php endif; ?>
								<?php if ( $bio ) : ?>
									<p class="person-card__text"><?php echo esc_html( $bio ); ?></p>
								<?php endif; ?>
								<?php if ( ! empty( $person_url['url'] ) ) : ?>
									<div class="person-card__cta"><?php ttng_the_button( $person_url, 'outline', 'btn--small' ); ?></div>
								<?php endif; ?>
							</div>
						</article>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php if ( 'slider' === $display ) : ?>
				<button type="button" class="people-slider__arrow people-slider__arrow--next" aria-label="<?php esc_attr_e( 'Next people', 'ttng-main-site-theme' ); ?>" hidden><?php echo ttng_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?></button>
			<?php endif; ?>
		</div>

		<?php get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'justify-content-center section-ctas--below' ] ); ?>

	</div>
</section>
