<?php
/**
 * Icon Grid Module
 *
 * Section header, then a grid of icon + title + text items (3 or 4 columns).
 * Items with a link become a single clickable block.
 *
 * Related CSS: assets/css/modules/icon_grid.css
 * Related CSS (Theme CSS): assets/css/core/additions.css
 * Related CSS (Global CSS): assets/css/core/base.css
**/

defined( 'ABSPATH' ) || exit;

$col_class = '3' === get_sub_field( 'grid_columns' ) ? 'col-6 col-lg-4' : 'col-6 col-lg-3';
?>

<section<?php echo ttng_module_attrs( 'icon-grid', 'gradient' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>>
	<div class="container">

		<div class="row justify-content-center">
			<div class="col-lg-9 col-xl-8 text-center">
				<?php get_template_part( 'template-parts/snippets/section-header' ); ?>
			</div>
		</div>

		<?php if ( have_rows( 'icons' ) ) : ?>
			<ul class="row justify-content-center icon-grid__row" role="list">
				<?php
				while ( have_rows( 'icons' ) ) :
					the_row();
					$icon_image = get_sub_field( 'icon_image' );
					$icon_title = get_sub_field( 'icon_title' );
					$icon_text  = get_sub_field( 'icon_text' );
					$icon_link  = get_sub_field( 'icon_link' );
					$has_link   = ! empty( $icon_link['url'] );
					$link_tgt   = $has_link && ! empty( $icon_link['target'] ) ? $icon_link['target'] : '_self';
					?>
					<li class="<?php echo esc_attr( $col_class ); ?> icon-grid__col">
						<?php if ( $has_link ) : ?>
							<a class="icon-item icon-item--link" href="<?php echo esc_url( $icon_link['url'] ); ?>" target="<?php echo esc_attr( $link_tgt ); ?>"<?php echo '_blank' === $link_tgt ? ' rel="noopener noreferrer"' : ''; ?>>
						<?php else : ?>
							<div class="icon-item">
						<?php endif; ?>

							<?php if ( $icon_image ) : ?>
								<span class="icon-item__icon"><?php echo wp_get_attachment_image( $icon_image, 'thumbnail', false, [ 'alt' => '' ] ); ?></span>
							<?php endif; ?>

							<?php if ( $icon_title ) : ?>
								<h3 class="icon-item__title"><?php echo esc_html( $icon_title ); ?></h3>
							<?php endif; ?>

							<?php if ( $icon_text ) : ?>
								<p class="icon-item__text"><?php echo esc_html( $icon_text ); ?></p>
							<?php endif; ?>

						<?php echo $has_link ? '</a>' : '</div>'; ?>
					</li>
				<?php endwhile; ?>
			</ul>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/snippets/ctas', null, [ 'class' => 'justify-content-center section-ctas--below' ] ); ?>

	</div>
</section>
