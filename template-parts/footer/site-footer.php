<?php
/**
 * Site footer + sub footer.
 *
 * Column headings for the three menu columns are the menu names set in
 * Appearance → Menus (wp_get_nav_menu_name), so the client can rename
 * them without touching code.
 *
 * Related CSS: assets/css/core/footer.css
 */

$footer_logo    = get_field( 'site_footer_logo', 'option' );
$footer_tagline = get_field( 'footer_tagline', 'option' );
$address        = get_field( 'contact_address', 'option' );
$contact_email  = get_field( 'contact_email', 'option' );
$contact_tel    = get_field( 'contact_telephone', 'option' );
$copyright      = get_field( 'copyright_text', 'option' );
$company_reg    = get_field( 'company_registration', 'option' );
$menu_columns   = array( 'footer_one', 'footer_two', 'footer_three' );
?>
<footer class="site-footer" role="contentinfo" aria-label="<?php esc_attr_e( 'Site footer', 'ttng-main-site-theme' ); ?>">
	<div class="container">
		<div class="row">

			<!-- About: logo, tagline, address, contact, accreditations -->
			<div class="col-lg-4 footer-col footer-col--about">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="footer-logo">
					<?php
					if ( $footer_logo ) {
						echo wp_get_attachment_image( $footer_logo, 'full' );
					} else {
						echo '<span class="footer-logo__text">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
					}
					?>
				</a>

				<?php if ( $footer_tagline ) : ?>
					<p class="footer-tagline"><?php echo esc_html( $footer_tagline ); ?></p>
				<?php endif; ?>

				<?php if ( $address ) : ?>
					<p class="footer-address__label"><?php esc_html_e( 'Registered address:', 'ttng-main-site-theme' ); ?></p>
					<address class="footer-address"><?php echo nl2br( esc_html( $address ) ); ?></address>
				<?php endif; ?>

				<?php if ( $contact_tel || $contact_email ) : ?>
					<ul class="footer-contact">
						<?php if ( $contact_tel ) : ?>
							<li>
								<a href="tel:<?php echo esc_attr( ttng_tel_href( $contact_tel ) ); ?>">
									<?php echo ttng_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?>
									<span><?php echo esc_html( $contact_tel ); ?></span>
								</a>
							</li>
						<?php endif; ?>
						<?php if ( $contact_email ) : ?>
							<li>
								<a href="mailto:<?php echo esc_attr( $contact_email ); ?>">
									<?php echo ttng_icon( 'email' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?>
									<span><?php echo esc_html( $contact_email ); ?></span>
								</a>
							</li>
						<?php endif; ?>
					</ul>
				<?php endif; ?>
			</div><!-- /.footer-col--about -->

			<div class="col-lg-8">
				<div class="row">

					<?php foreach ( $menu_columns as $location ) : ?>
						<?php if ( has_nav_menu( $location ) ) : ?>
							<div class="col-md-6 col-xl-3 footer-col">
								<h2 class="footer-col__heading"><?php echo esc_html( wp_get_nav_menu_name( $location ) ); ?></h2>
								<?php
								wp_nav_menu( [
									'container_class' => 'footer-menu-container',
									'menu_class'      => 'footer-menu',
									'menu_id'         => '',
									'theme_location'  => $location,
									'depth'           => 1,
								] );
								?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>

					<!-- Social + accreditations -->
					<div class="col-md-6 col-xl-3 footer-col footer-col--social">
						<h2 class="footer-col__heading"><?php esc_html_e( 'Follow us', 'ttng-main-site-theme' ); ?></h2>
						<?php get_template_part( 'template-parts/snippets/social-links' ); ?>

						<?php if ( have_rows( 'footer_accreditations', 'option' ) ) : ?>
							<ul class="footer-accreditations">
								<?php
								while ( have_rows( 'footer_accreditations', 'option' ) ) :
									the_row();
									$acc_logo = get_sub_field( 'accreditation_logo' );
									$acc_url  = get_sub_field( 'accreditation_url' );
									if ( ! $acc_logo ) {
										continue;
									}
									?>
									<li>
										<?php if ( $acc_url ) : ?>
											<a href="<?php echo esc_url( $acc_url ); ?>" target="_blank" rel="noopener noreferrer">
												<?php echo wp_get_attachment_image( $acc_logo, 'medium' ); ?>
												<span class="sr-only"><?php esc_html_e( '(opens in a new tab)', 'ttng-main-site-theme' ); ?></span>
											</a>
										<?php else : ?>
											<?php echo wp_get_attachment_image( $acc_logo, 'medium' ); ?>
										<?php endif; ?>
									</li>
								<?php endwhile; ?>
							</ul>
						<?php endif; ?>
					</div>

				</div>
			</div>

		</div><!-- /.row -->
	</div><!-- /.container -->
</footer>

<section class="sub-footer" aria-label="<?php esc_attr_e( 'Copyright and legal', 'ttng-main-site-theme' ); ?>">
	<div class="container">
		<div class="sub-footer__inner">
			<!-- Row 1: copyright left, company registration right -->
			<div class="sub-footer__row">
			<p class="sub-footer__copyright">
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( $copyright ? $copyright : get_bloginfo( 'name' ) ); ?>
			</p>
			<?php if ( $company_reg ) : ?>
				<p class="sub-footer__registration"><?php echo esc_html( $company_reg ); ?></p>
			<?php endif; ?>
			</div>

			<!-- Row 2: legal links left, credit right -->
			<div class="sub-footer__row">
			<?php if ( has_nav_menu( 'legal' ) ) : ?>
				<nav class="sub-footer__legal" aria-label="<?php esc_attr_e( 'Legal', 'ttng-main-site-theme' ); ?>">
					<?php
					wp_nav_menu( [
						'container'      => false,
						'menu_class'     => 'sub-footer__menu',
						'menu_id'        => '',
						'theme_location' => 'legal',
						'depth'          => 1,
					] );
					?>
				</nav>
			<?php endif; ?>

			<p class="sub-footer__credit"><?php esc_html_e( 'Website by', 'ttng-main-site-theme' ); ?> <a href="https://spsmarketing.co.uk/" target="_blank" rel="noopener noreferrer">SPS Marketing<span class="sr-only"> <?php esc_html_e( '(opens in a new tab)', 'ttng-main-site-theme' ); ?></span></a></p>
			</div>
		</div>
	</div>
</section>
</div>
<?php wp_footer(); ?>
</body>
</html>
