<?php
/**
 * share-links.php – Plain share links for the current post.
 *
 * Simple links rather than third-party share widgets, so there are no
 * trackers or scripts that would need cookie consent.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

$share_url   = rawurlencode( get_permalink() );
$share_title = rawurlencode( get_the_title() );
$networks    = [
	'LinkedIn' => "https://www.linkedin.com/sharing/share-offsite/?url={$share_url}",
	'Facebook' => "https://www.facebook.com/sharer/sharer.php?u={$share_url}",
	'X'        => "https://x.com/intent/post?url={$share_url}&text={$share_title}",
];
?>
<div class="share-links">
	<p class="share-links__label"><?php esc_html_e( 'Share this post:', 'ttng-main-site-theme' ); ?></p>
	<ul class="share-links__list">
		<?php foreach ( $networks as $network => $url ) : ?>
			<li>
				<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php echo esc_html( $network ); ?>
					<span class="sr-only"><?php esc_html_e( '(opens in a new tab)', 'ttng-main-site-theme' ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
		<li><a href="mailto:?subject=<?php echo esc_attr( $share_title ); ?>&amp;body=<?php echo esc_attr( $share_url ); ?>"><?php esc_html_e( 'Email', 'ttng-main-site-theme' ); ?></a></li>
	</ul>
</div>
