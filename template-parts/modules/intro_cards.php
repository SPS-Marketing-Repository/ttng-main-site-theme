<?php
/**
 * Intro + 3 Cards Module
 *
 * Centred label / title / content / buttons, then up to three cards.
 * Each card is either a "stat" (big number, e.g. 1100+) or plain content.
 * If a card has a link, the whole card becomes clickable via a stretched
 * link on the title (one link per card for screen readers).
 *
 * Related CSS: assets/css/modules/intro_cards.css
 * Related CSS (Theme CSS): assets/css/core/additions.css
 * Related CSS (Global CSS): assets/css/core/base.css
**/

defined( 'ABSPATH' ) || exit;
?>

<section<?php echo ttng_module_attrs( 'intro-cards' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">

		<div class="row justify-content-center">
			<div class="col-lg-9 col-xl-8 text-center">
				<?php
				get_template_part( 'template-parts/snippets/section-header' );
				get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'justify-content-center' ] );
				?>
			</div>
		</div>

		<?php if ( have_rows( 'cards' ) ) : ?>
			<div class="row intro-cards__row">
				<?php
				while ( have_rows( 'cards' ) ) :
					the_row();
					$card_type  = get_sub_field( 'card_type' );
					$card_icon  = get_sub_field( 'card_icon' );
					$card_stat  = get_sub_field( 'card_stat' );
					$card_title = get_sub_field( 'card_title' );
					$card_text  = get_sub_field( 'card_text' );
					$card_link  = get_sub_field( 'card_link' );
					$has_link   = ! empty( $card_link['url'] );
					$link_tgt   = $has_link && ! empty( $card_link['target'] ) ? $card_link['target'] : '_self';
					?>
					<div class="col-md-4 intro-cards__col">
						<div class="intro-card intro-card--<?php echo esc_attr( 'stat' === $card_type ? 'stat' : 'content' ); ?><?php echo $has_link ? ' has-link' : ''; ?>">

							<?php if ( $card_icon ) : ?>
								<div class="intro-card__icon"><?php echo wp_get_attachment_image( $card_icon, 'thumbnail', false, [ 'alt' => '' ] ); ?></div>
							<?php endif; ?>

							<?php if ( 'stat' === $card_type && $card_stat ) : ?>
								<p class="intro-card__stat"><?php echo esc_html( $card_stat ); ?></p>
							<?php endif; ?>

							<?php if ( $card_title ) : ?>
								<h3 class="intro-card__title">
									<?php if ( $has_link ) : ?>
										<a class="stretched-link" href="<?php echo esc_url( $card_link['url'] ); ?>" target="<?php echo esc_attr( $link_tgt ); ?>"<?php echo '_blank' === $link_tgt ? ' rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $card_title ); ?></a>
									<?php else : ?>
										<?php echo esc_html( $card_title ); ?>
									<?php endif; ?>
								</h3>
							<?php endif; ?>

							<?php if ( $card_text ) : ?>
								<p class="intro-card__text"><?php echo esc_html( $card_text ); ?></p>
							<?php endif; ?>

						</div>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>

	</div>
</section>
