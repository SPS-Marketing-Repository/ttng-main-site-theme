<?php
/**
 * contact-details.php – Phone and email links from Site Settings.
 *
 *   get_template_part( 'template-parts/snippets/contact-details', null, [
 *       'source' => 'recruitment', // general | recruitment
 *       'class'  => 'contact-details--inline',
 *   ] );
 *
 * Styles: assets/css/core/additions.css (.contact-details)
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

$args    = wp_parse_args( $args ?? [], [ 'source' => 'general', 'class' => '' ] );
$details = ttng_get_contact_details( $args['source'] );

if ( ! $details['phones'] && ! $details['email'] ) {
	return;
}
?>
<ul class="<?php echo esc_attr( trim( 'contact-details ' . $args['class'] ) ); ?>">
	<?php foreach ( $details['phones'] as $phone ) : ?>
		<li>
			<a href="tel:<?php echo esc_attr( ttng_tel_href( $phone ) ); ?>">
				<span class="contact-details__icon"><?php echo ttng_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?></span>
				<span><?php echo esc_html( $phone ); ?></span>
			</a>
		</li>
	<?php endforeach; ?>
	<?php if ( $details['email'] ) : ?>
		<li>
			<a href="mailto:<?php echo esc_attr( $details['email'] ); ?>">
				<span class="contact-details__icon"><?php echo ttng_icon( 'email' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?></span>
				<span><?php echo esc_html( $details['email'] ); ?></span>
			</a>
		</li>
	<?php endif; ?>
</ul>
