<?php
/**
 * Site header – white top bar + main header (logo, mega menu). Not fixed,
 * matching the staging site: on the home page the header sits over the hero.
 *
 * Top bar and contact details come from Site Settings (ACF options):
 * contact_email, contact_telephone, top_bar_link, header_cta.
 *
 * Related CSS: assets/css/core/header.css
 * Related JS:  assets/js/main.js (off-canvas toggle, .is-scrolled state)
 */

$contact_email = get_field( 'contact_email', 'option' );
$contact_tel   = get_field( 'contact_telephone', 'option' );
$top_bar_link  = get_field( 'top_bar_link', 'option' );
$header_cta    = get_field( 'header_cta', 'option' );
$top_tagline   = get_field( 'top_bar_tagline', 'option' );
$has_top_bar   = $top_tagline || $contact_email || $contact_tel || ! empty( $top_bar_link['url'] );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class( $has_top_bar ? 'has-top-bar' : '' ); ?>>
		<?php wp_body_open(); ?>
		<a class="sr-only sr-only-focusable" href="#main">Skip to main content</a>

		<!-- Off-canvas mobile menu — driven by the "mobile" nav menu location.
		     inert + aria-hidden keep its links out of the tab order while
		     closed (see assets/js/main.js setOffCanvasOpen). -->
		<div class="full-height off-canvas" id="slide-div" aria-hidden="true" inert>
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<nav role="navigation" aria-label="<?php esc_attr_e( 'Mobile navigation', 'ttng-main-site-theme' ); ?>">
							<?php
							wp_nav_menu( array(
								'container_class' => 'menu-main-menu-container',
								'menu_class'      => 'main-header-menu',
								'menu_id'         => '',
								'theme_location'  => 'mobile',
								'walker'          => new TTNG_Mega_Menu_Walker(),
							) );
							?>
						</nav>

						<?php if ( ! empty( $header_cta['url'] ) ) : ?>
							<div class="off-canvas__cta">
								<?php ttng_the_button( $header_cta, 'primary' ); ?>
							</div>
						<?php endif; ?>

						<?php if ( $contact_email || $contact_tel ) : ?>
						<div class="contact-block content-block">
							<h2 class="contact-block__heading"><?php esc_html_e( 'Other ways to reach us', 'ttng-main-site-theme' ); ?></h2>
							<?php if ( $contact_email ) : ?>
								<p><?php esc_html_e( 'Email:', 'ttng-main-site-theme' ); ?> <a href="mailto:<?php echo esc_attr( $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a></p>
							<?php endif; ?>
							<?php if ( $contact_tel ) : ?>
								<p><?php esc_html_e( 'Tel:', 'ttng-main-site-theme' ); ?> <a href="tel:<?php echo esc_attr( ttng_tel_href( $contact_tel ) ); ?>"><?php echo esc_html( $contact_tel ); ?></a></p>
							<?php endif; ?>
						</div>
						<?php endif; ?>

						<?php get_template_part( 'template-parts/snippets/social-links' ); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="mobile-overflow">
			<div class="site-header-wrap" id="site-header-wrap">

				<?php if ( $has_top_bar ) : ?>
				<!-- Top bar: email / phone / link -->
				<div class="top-bar">
					<div class="container container-header top-bar__inner">
						<?php if ( $top_tagline ) : ?>
							<p class="top-bar__tagline"><?php echo ttng_icon( 'star' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?><span><?php echo esc_html( $top_tagline ); ?></span></p>
						<?php endif; ?>
						<ul class="top-bar__list">
							<?php if ( $contact_email ) : ?>
								<li class="top-bar__item">
									<a href="mailto:<?php echo esc_attr( $contact_email ); ?>">
										<?php echo ttng_icon( 'email' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?>
										<?php // Text is visually hidden on mobile (header.css) but stays readable to screen readers. ?>
										<span class="top-bar__text"><?php echo esc_html( $contact_email ); ?></span>
									</a>
								</li>
							<?php endif; ?>

							<?php if ( $contact_tel ) : ?>
								<li class="top-bar__item">
									<a href="tel:<?php echo esc_attr( ttng_tel_href( $contact_tel ) ); ?>">
										<?php echo ttng_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?>
										<span><?php echo esc_html( $contact_tel ); ?></span>
									</a>
								</li>
							<?php endif; ?>

							<?php if ( ! empty( $top_bar_link['url'] ) ) : ?>
								<?php $top_bar_target = ! empty( $top_bar_link['target'] ) ? $top_bar_link['target'] : '_self'; ?>
								<li class="top-bar__item top-bar__item--link">
									<a href="<?php echo esc_url( $top_bar_link['url'] ); ?>" target="<?php echo esc_attr( $top_bar_target ); ?>"<?php echo '_blank' === $top_bar_target ? ' rel="noopener noreferrer"' : ''; ?>>
										<?php echo ttng_icon( 'brochure' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?>
										<span><?php echo esc_html( $top_bar_link['title'] ? $top_bar_link['title'] : __( 'Download our brochure', 'ttng-main-site-theme' ) ); ?></span>
									</a>
								</li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>

				<header class="site-header">
					<div class="container container-header">
						<div class="site-header__inner">

							<!-- Logo -->
							<div class="site-header__logo">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
									<?php
									$logo = get_field( 'site_main_logo', 'option' );
									if ( $logo ) {
										echo wp_get_attachment_image( $logo, 'full', false, [ 'class' => 'site-header__logo-img', 'loading' => 'eager' ] );
									} else {
										echo '<span class="site-header__logo-text">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
									}
									?>
								</a>
							</div>

							<!-- Primary navigation (desktop) -->
							<nav class="site-header__nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'ttng-main-site-theme' ); ?>">
								<?php ttng_primary_nav(); // inc/mega-menu.php ?>
								<?php if ( ! empty( $header_cta['url'] ) ) : ?>
									<?php ttng_the_button( $header_cta, 'primary', 'site-header__cta' ); ?>
								<?php endif; ?>
							</nav>

							<!-- Hamburger (mobile only) — id must remain #trigger, main.js binds to it -->
							<button class="hamburger" id="trigger" type="button" aria-label="<?php esc_attr_e( 'Open navigation menu', 'ttng-main-site-theme' ); ?>" aria-expanded="false" aria-controls="slide-div">
								<span class="hamburger-lines" aria-hidden="true">
									<span class="top-bun"></span>
									<span class="meat"></span>
									<span class="bottom-bun"></span>
								</span>
							</button>

						</div>
					</div>
				</header>
			</div>
			<div class="hidden-height" aria-hidden="true"></div>
