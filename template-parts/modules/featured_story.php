<?php
/**
 * Featured Story Module
 *
 * One story in a rounded card: image with a corner ribbon on the left; date,
 * title, text and a button on the right, over an optional brand shape watermark.
 * story_source picks where the content comes from:
 * - post: everything is read from the chosen Insights post, so it stays up to
 *   date. Events show their event date, as on the news cards.
 * - manual: everything is entered in the module.
 *
 * Related CSS: assets/css/modules/featured_story.css
 * Related CSS (Theme CSS): assets/css/core/additions.css
**/

defined( 'ABSPATH' ) || exit;

if ( 'manual' === get_sub_field( 'story_source' ) ) {
	$image    = get_sub_field( 'story_image' );
	$title    = get_sub_field( 'story_title' );
	$text     = get_sub_field( 'story_text' );
	$raw_date = get_sub_field( 'story_date' );
	$button   = get_sub_field( 'story_link' );
} else {
	$story_id = (int) get_sub_field( 'story_post' );

	// A post that has since been unpublished or deleted shouldn't show on the page.
	if ( ! $story_id || 'publish' !== get_post_status( $story_id ) ) {
		return;
	}

	$button_text = get_sub_field( 'story_button_text' );
	$image       = get_post_thumbnail_id( $story_id );
	$title       = get_the_title( $story_id );
	$text        = wp_trim_words( get_the_excerpt( $story_id ), 40 );
	$raw_date    = get_field( 'event_date', $story_id );
	$button      = [
		'url'   => get_permalink( $story_id ),
		'title' => $button_text ? $button_text : __( 'Read more', 'ttng-main-site-theme' ),
	];
}

if ( ! $title ) {
	return;
}

// Stored as Ymd. Picked posts without an event date use their publish date.
$date_time = $raw_date ? DateTime::createFromFormat( 'Ymd', $raw_date ) : false;
$date_text = $date_time ? date_i18n( get_option( 'date_format' ), $date_time->getTimestamp() ) : '';
if ( ! $date_text && ! empty( $story_id ) ) {
	$date_text = get_the_date( '', $story_id );
}

$ribbon  = get_sub_field( 'story_ribbon' );
$classes = ( $image ? '' : 'featured-story--no-image' ) . ( get_sub_field( 'show_watermark' ) ? ' featured-story--watermark' : '' );
?>

<section<?php echo ttng_module_attrs( 'featured-story', 'navy', trim( $classes ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">
		<article class="featured-story__card">

			<?php if ( $ribbon ) : ?>
				<p class="featured-story__ribbon"><span><?php echo esc_html( $ribbon ); ?></span></p>
			<?php endif; ?>

			<?php if ( $image ) : ?>
				<div class="featured-story__media">
					<?php echo wp_get_attachment_image( $image, 'large', false, [ 'sizes' => '(max-width: 768px) 100vw, 470px' ] ); ?>
				</div>
			<?php endif; ?>

			<div class="featured-story__content">
				<div class="featured-story__inner">
					<?php if ( $date_text ) : ?>
						<p class="featured-story__date"><?php echo esc_html( $date_text ); ?></p>
					<?php endif; ?>

					<h2 class="featured-story__title"><?php echo esc_html( $title ); ?></h2>

					<?php if ( $text ) : ?>
						<p class="featured-story__text"><?php echo esc_html( $text ); ?></p>
					<?php endif; ?>

					<?php ttng_the_button( $button ); ?>
				</div>
			</div>

		</article>
	</div>
</section>
