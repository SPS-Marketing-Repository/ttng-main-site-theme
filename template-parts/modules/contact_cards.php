<?php
/**
 * Contact Cards Module
 *
 * Staging Contact Us "General Enquiries" / "Joining Enquiries": section
 * header, then full-width rounded panels, each with a title, a line of text
 * and a phone number and email address with icons. Navy or light panel.
 *
 * Related CSS: assets/css/modules/contact_cards.css
**/

defined( 'ABSPATH' ) || exit;

// Read as an array — see faq.php for why not have_rows() here.
$cards = get_sub_field( 'contact_cards' );

if ( empty( $cards ) || ! is_array( $cards ) ) {
	return;
}
?>

<section<?php echo ttng_module_attrs( 'contact-cards' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">

		<?php if ( get_sub_field( 'section_title' ) || get_sub_field( 'section_content' ) ) : ?>
			<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
		<?php endif; ?>

		<div class="contact-cards__list">
			<?php foreach ( $cards as $card ) : ?>
				<?php
				$style = 'light' === ( $card['card_style'] ?? '' ) ? 'light' : 'navy';
				$phone = trim( (string) ( $card['card_phone'] ?? '' ) );
				$email = sanitize_email( $card['card_email'] ?? '' );
				if ( empty( $card['card_title'] ) && ! $phone && ! $email ) {
					continue;
				}
				?>
				<div class="contact-card contact-card--<?php echo esc_attr( $style ); ?>">
					<?php if ( ! empty( $card['card_title'] ) ) : ?>
						<h2 class="contact-card__title"><?php echo esc_html( $card['card_title'] ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $card['card_text'] ) ) : ?>
						<p class="contact-card__text"><?php echo wp_kses( $card['card_text'], [ 'br' => [] ] ); ?></p>
					<?php endif; ?>
					<?php if ( $phone || $email ) : ?>
						<ul class="contact-card__details" role="list">
							<?php if ( $phone ) : ?>
								<li><?php echo ttng_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?><a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
							<?php endif; ?>
							<?php if ( $email ) : ?>
								<li><?php echo ttng_icon( 'email' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?><a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a></li>
							<?php endif; ?>
						</ul>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'section-ctas--below' ] ); ?>
	</div>
</section>
