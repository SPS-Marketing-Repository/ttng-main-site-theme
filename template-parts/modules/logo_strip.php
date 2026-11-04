<?php
/**
 * Logo Strip Module
 *
 * Optional title and a row of logos (partners, accreditations), each with an
 * optional link.
 *
 * Related CSS: assets/css/modules/logo_strip.css
**/

defined( 'ABSPATH' ) || exit;

$title = get_sub_field( 'section_title' );
$logos = get_sub_field( 'logos' );

if ( empty( $logos ) ) {
	return;
}
?>

<section<?php echo ttng_module_attrs( 'logo-strip' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">
		<?php if ( $title ) : ?>
			<h2 class="logo-strip__title text-center"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<ul class="logo-strip__list">
			<?php foreach ( $logos as $row ) : ?>
				<?php
				if ( empty( $row['logo_image'] ) ) {
					continue;
				}
				?>
				<li class="logo-strip__item">
					<?php if ( $row['logo_url'] ) : ?>
						<a href="<?php echo esc_url( $row['logo_url'] ); ?>" target="_blank" rel="noopener noreferrer">
							<?php echo wp_get_attachment_image( $row['logo_image'], 'medium' ); ?>
							<span class="sr-only"><?php esc_html_e( '(opens in a new tab)', 'ttng-main-site-theme' ); ?></span>
						</a>
					<?php else : ?>
						<?php echo wp_get_attachment_image( $row['logo_image'], 'medium' ); ?>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
