<?php
/**
 * Card Grid Module
 *
 * Section header, then a grid of cards (2 / 3 / 4 columns), each with an
 * image or logo, title, text and a button. Section buttons sit below.
 *
 * Card styles:
 * - image: photo fills the top of the card (ttng-card 3:2 crop)
 * - logo:  logo contained and centred on a white panel
 *
 * Related CSS: assets/css/modules/card_grid.css
 * Related CSS (Theme CSS): assets/css/core/additions.css
 * Related CSS (Global CSS): assets/css/core/base.css
**/

defined( 'ABSPATH' ) || exit;

$columns    = get_sub_field( 'grid_columns' );
$card_style = 'logo' === get_sub_field( 'card_style' ) ? 'logo' : 'image';

// Bootstrap column classes per desktop column count.
$col_classes = [
	'2' => 'col-md-6',
	'3' => 'col-md-6 col-lg-4',
	'4' => 'col-md-6 col-xl-3',
];
$col_class = isset( $col_classes[ $columns ] ) ? $col_classes[ $columns ] : $col_classes['4'];
?>

<section<?php echo ttng_module_attrs( 'card-grid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">

		<div class="row justify-content-center">
			<div class="col-lg-9 col-xl-8 text-center">
				<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
			</div>
		</div>

		<?php if ( have_rows( 'cards' ) ) : ?>
			<div class="row card-grid__row">
				<?php
				while ( have_rows( 'cards' ) ) :
					the_row();
					$card_image = get_sub_field( 'card_image' );
					$card_title = get_sub_field( 'card_title' );
					$card_text  = get_sub_field( 'card_text' );
					$card_link  = get_sub_field( 'card_link' );
					?>
					<div class="<?php echo esc_attr( $col_class ); ?> card-grid__col">
						<article class="grid-card grid-card--<?php echo esc_attr( $card_style ); ?>">

							<?php if ( $card_image ) : ?>
								<div class="grid-card__media">
									<?php echo wp_get_attachment_image( $card_image, 'logo' === $card_style ? 'medium' : 'ttng-card' ); ?>
								</div>
							<?php endif; ?>

							<div class="grid-card__body">
								<?php if ( $card_title ) : ?>
									<h3 class="grid-card__title"><?php echo esc_html( $card_title ); ?></h3>
								<?php endif; ?>

								<?php if ( $card_text ) : ?>
									<p class="grid-card__text"><?php echo esc_html( $card_text ); ?></p>
								<?php endif; ?>

								<?php if ( ! empty( $card_link['url'] ) ) : ?>
									<div class="grid-card__cta">
										<?php ttng_the_button( $card_link, 'primary', 'btn--small' ); ?>
									</div>
								<?php endif; ?>
							</div>

						</article>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'justify-content-center section-ctas--below' ] ); ?>

	</div>
</section>
