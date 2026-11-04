<?php
/**
 * news-card.php – Post card used by the Latest News module and Insights listings.
 *
 * Expects to run inside The Loop (setup_postdata / the_post()).
 * The image link is hidden from assistive tech and the tab order so each
 * card only has one announced link (the title) plus "Read more".
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<article class="news-card">
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="news-card__image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'ttng-card' ); ?>
		</a>
	<?php endif; ?>

	<div class="news-card__body">
		<?php
		// Events show their event date; everything else its publish date.
		$event_date = function_exists( 'get_field' ) ? get_field( 'event_date' ) : '';
		$event_time = $event_date ? DateTime::createFromFormat( 'Ymd', $event_date ) : false;
		?>
		<?php if ( $event_time ) : ?>
			<p class="news-card__date"><?php echo esc_html( date_i18n( get_option( 'date_format' ), $event_time->getTimestamp() ) ); ?></p>
		<?php else : ?>
			<p class="news-card__date"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
		<?php endif; ?>

		<h3 class="news-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

		<?php $excerpt = ttng_get_trimmed_excerpt( 20 ); ?>
		<?php if ( $excerpt ) : ?>
			<p class="news-card__excerpt"><?php echo $excerpt; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside ttng_get_trimmed_excerpt(). ?></p>
		<?php endif; ?>

		<a class="news-card__more" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Read more', 'ttng-main-site-theme' ); ?>
			<span class="sr-only"><?php echo esc_html( sprintf( /* translators: %s: post title */ __( 'about %s', 'ttng-main-site-theme' ), get_the_title() ) ); ?></span>
			<?php echo ttng_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?>
		</a>
	</div>
</article>
