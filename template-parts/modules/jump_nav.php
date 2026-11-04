<?php
/**
 * Jump Links Module
 *
 * Row of links to sections on the same page (#section-id) or to other pages.
 * Give the target module a Section ID in its Settings tab.
 *
 * Related CSS: assets/css/modules/jump_nav.css
**/

defined( 'ABSPATH' ) || exit;

$title = get_sub_field( 'section_title' );
$links = get_sub_field( 'jump_links' );

if ( empty( $links ) ) {
	return;
}
?>

<section<?php echo ttng_module_attrs( 'jump-nav', 'light' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">
		<?php if ( $title ) : ?>
			<h2 class="jump-nav__title text-center"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<nav aria-label="<?php echo esc_attr( $title ? $title : __( 'On this page', 'ttng-main-site-theme' ) ); ?>">
			<ul class="jump-nav__list">
				<?php foreach ( $links as $row ) : ?>
					<?php
					$item = $row['jump_link'];
					if ( empty( $item['url'] ) ) {
						continue;
					}
					$target = ! empty( $item['target'] ) ? $item['target'] : '_self';
					?>
					<li>
						<a class="jump-nav__link" href="<?php echo esc_url( $item['url'] ); ?>" target="<?php echo esc_attr( $target ); ?>"<?php echo '_blank' === $target ? ' rel="noopener noreferrer"' : ''; ?>>
							<span><?php echo esc_html( $item['title'] ); ?></span>
							<?php echo ttng_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>
	</div>
</section>
