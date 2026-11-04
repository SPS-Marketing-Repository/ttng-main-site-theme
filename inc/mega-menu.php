<?php
/**
 * mega-menu.php – Desktop primary navigation with staging-style mega panels
 *
 * Every top-level item with children opens a white panel (matching the old
 * Elementor/ElementsKit menu):
 *
 *   [ Overview: parent title + description ] [ child "tabs" ] [ preview: image | navy card ]
 *
 * Hovering or focusing a child tab swaps the preview (assets/js/main.js).
 * The preview shows the child's image, heading, description, its own
 * children (e.g. TTA FAQs) as links, and a "Get started" link.
 *
 * Content comes from the normal WordPress menu (Appearance → Menus):
 * - Description: the menu item's Description field (Screen Options → Description).
 * - Image / preview heading: the "Mega menu" fields on each menu item
 *   (acf-json/group_ttng_mega_menu.json). Image falls back to the linked page's
 *   featured image.
 *
 * Mobile keeps the off-canvas accordion menu (TTNG_Mega_Menu_Walker).
 * Styles: assets/css/core/header.css (.mega-panel).
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Image ID for a menu item's preview.
 *
 * @param WP_Post $item Menu item.
 * @return int
 */
function ttng_mega_image_id( $item ) {
	$image = function_exists( 'get_field' ) ? (int) get_field( 'mega_image', $item->ID ) : 0;
	if ( ! $image && 'post_type' === $item->type && $item->object_id ) {
		$image = (int) get_post_thumbnail_id( (int) $item->object_id );
	}
	return $image;
}

/**
 * Print the desktop primary navigation.
 */
function ttng_primary_nav() {
	$locations = get_nav_menu_locations();
	if ( empty( $locations['primary'] ) ) {
		return;
	}
	$items = wp_get_nav_menu_items( $locations['primary'] );
	if ( ! $items ) {
		return;
	}
	_wp_menu_item_classes_by_context( $items );

	$tree = [];
	foreach ( $items as $item ) {
		$tree[ (int) $item->menu_item_parent ][] = $item;
	}

	echo '<ul class="site-header__nav-list">';

	foreach ( $tree[0] ?? [] as $top ) {
		$kids    = $tree[ (int) $top->ID ] ?? [];
		$classes = array_filter( array_map( 'sanitize_html_class', (array) $top->classes ) );
		$classes = array_merge( $classes, [ 'menu-item', 'menu-item-' . (int) $top->ID ] );
		if ( $kids ) {
			$classes[] = 'menu-item-has-children';
			$classes[] = 'menu-item--mega';
		}
		$panel_id = 'mega-panel-' . (int) $top->ID;

		printf( '<li class="%s">', esc_attr( implode( ' ', array_unique( $classes ) ) ) );
		printf(
			'<a href="%1$s"%2$s>%3$s</a>',
			esc_url( $top->url ? $top->url : '#' ),
			in_array( 'current-menu-item', $classes, true ) ? ' aria-current="page"' : '',
			esc_html( $top->title )
		);

		if ( $kids ) {
			printf(
				'<button type="button" class="nav-toggle" aria-expanded="false" aria-controls="%1$s"><span class="sr-only">%2$s</span>%3$s</button>',
				esc_attr( $panel_id ),
				esc_html( sprintf( /* translators: %s: menu item title */ __( 'Show %s submenu', 'ttng-main-site-theme' ), $top->title ) ),
				ttng_icon( 'chevron' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG.
			);
			ttng_mega_panel( $top, $kids, $tree, $panel_id );
		}

		echo '</li>';
	}

	echo '</ul>';
}

/**
 * One mega panel.
 *
 * @param WP_Post   $top      Top-level item.
 * @param WP_Post[] $kids     Its children.
 * @param array     $tree     Items keyed by parent ID.
 * @param string    $panel_id Panel element ID.
 */
function ttng_mega_panel( $top, $kids, $tree, $panel_id ) {
	?>
	<div class="mega-panel" id="<?php echo esc_attr( $panel_id ); ?>">
		<div class="mega-panel__inner">

			<div class="mega-panel__intro">
				<p class="mega-panel__title"><?php echo esc_html( $top->title ); ?></p>
				<?php if ( $top->description ) : ?>
					<p class="mega-panel__text"><?php echo esc_html( $top->description ); ?></p>
				<?php endif; ?>
				<?php if ( $top->url && '#' !== $top->url ) : ?>
					<a class="mega-panel__overview" href="<?php echo esc_url( $top->url ); ?>"><?php esc_html_e( 'Overview', 'ttng-main-site-theme' ); ?><?php echo ttng_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?></a>
				<?php endif; ?>
			</div>

			<ul class="mega-panel__tabs">
				<?php foreach ( $kids as $i => $kid ) : ?>
					<?php
					$is_current = array_intersect( [ 'current-menu-item', 'current-menu-ancestor', 'current-menu-parent' ], (array) $kid->classes );
					?>
					<li>
						<a class="mega-panel__tab<?php echo 0 === $i ? ' is-active' : ''; ?>" href="<?php echo esc_url( $kid->url ); ?>" data-mega-preview="mega-preview-<?php echo (int) $kid->ID; ?>"<?php echo in_array( 'current-menu-item', (array) $kid->classes, true ) ? ' aria-current="page"' : ''; ?>>
							<?php echo esc_html( $kid->title ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<div class="mega-panel__previews">
				<?php foreach ( $kids as $i => $kid ) : ?>
					<?php
					$image    = ttng_mega_image_id( $kid );
					$heading  = function_exists( 'get_field' ) ? (string) get_field( 'mega_heading', $kid->ID ) : '';
					$grandkid = $tree[ (int) $kid->ID ] ?? [];
					$is_logo  = $image && 'image/svg+xml' === get_post_mime_type( $image );
					?>
					<div class="mega-panel__preview<?php echo 0 === $i ? ' is-active' : ''; ?>" id="mega-preview-<?php echo (int) $kid->ID; ?>">
						<div class="mega-panel__image<?php echo $is_logo ? ' mega-panel__image--logo' : ''; ?><?php echo $image ? '' : ' mega-panel__image--empty'; ?>">
							<?php
							if ( $image ) {
								echo wp_get_attachment_image( $image, 'ttng-card', false, [ 'alt' => '', 'loading' => 'lazy' ] );
							}
							?>
						</div>
						<div class="mega-panel__card">
							<p class="mega-panel__card-title"><?php echo esc_html( $heading ? $heading : $kid->title ); ?></p>
							<?php if ( $kid->description ) : ?>
								<p class="mega-panel__card-text"><?php echo esc_html( $kid->description ); ?></p>
							<?php endif; ?>
							<?php if ( $grandkid ) : ?>
								<ul class="mega-panel__sublinks">
									<?php foreach ( $grandkid as $g ) : ?>
										<li><a href="<?php echo esc_url( $g->url ); ?>"><?php echo esc_html( $g->title ); ?></a></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<a class="mega-panel__more" href="<?php echo esc_url( $kid->url ); ?>" tabindex="-1">
								<?php esc_html_e( 'Get started', 'ttng-main-site-theme' ); ?>
								<span class="sr-only"><?php echo esc_html( $kid->title ); ?></span>
								<?php echo ttng_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed theme SVG. ?>
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

		</div>
	</div>
	<?php
}
