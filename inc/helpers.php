<?php
/**
 * helpers.php – Helper functions
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get a trimmed, safe excerpt.
 *
 * @param int $word_limit Number of words.
 * @return string Escaped excerpt.
 */
function ttng_get_trimmed_excerpt( $word_limit = 30 ) {
	$excerpt = get_the_excerpt();

	if ( empty( $excerpt ) ) {
		return '';
	}

	$excerpt = wp_strip_all_tags( $excerpt, true );
	$words   = preg_split( '/\s+/', $excerpt );

	if ( count( $words ) > $word_limit ) {
		$words = array_slice( $words, 0, $word_limit );
		$excerpt = implode( ' ', $words ) . '&hellip;';
	}

	return esc_html( $excerpt );
}

/**
 * Get a trimmed, safe version of the content.
 *
 * @param int $word_limit Number of words.
 * @return string Escaped content.
 */
function ttng_get_trimmed_content( $word_limit = 40 ) {
	$content = get_the_content( '' );

	if ( empty( $content ) ) {
		return '';
	}

	$content = wp_strip_all_tags( $content, true );
	$words   = preg_split( '/\s+/', $content );

	if ( count( $words ) > $word_limit ) {
		$words   = array_slice( $words, 0, $word_limit );
		$content = implode( ' ', $words ) . '&hellip;';
	}

	return esc_html( $content );
}

/**
 * Sanitise iframe/embed output from ACF fields.
 * Allows <iframe> with a broad but explicit attribute allowlist,
 * covering YouTube, Vimeo, Google Maps, and generic third-party embeds.
 * Use instead of esc_html() or wp_kses_post() for embed fields.
 *
 * @param  string $embed Raw HTML string from ACF field.
 * @return string        Sanitised HTML safe for output.
 */
function ttng_kses_iframe( $embed ) {
    if ( empty( $embed ) ) {
        return '';
    }

    $allowed = array(
        'iframe' => array(
            'src'             => true,
            'width'           => true,
            'height'          => true,
            'title'           => true,
            'frameborder'     => true,
            'allow'           => true,
            'allowfullscreen' => true,
            'loading'         => true,
            'referrerpolicy'  => true,
            'style'           => true,
            'class'           => true,
            'id'              => true,
            'name'            => true,
            'scrolling'       => true,
            'data-src'        => true,  // lazy-load patterns
        ),
        // Allow wrapping divs that embed providers sometimes output
        'div' => array(
            'class'          => true,
            'id'             => true,
            'style'          => true,
            'data-url'       => true,  // Typeform pattern
            'data-widget'    => true,
        ),
        // No 'script' key on purpose. wp_kses() checks isset(), so even
        // 'script' => false would ALLOW bare <script> tags. Anything not
        // listed here is stripped.
    );

    return wp_kses( $embed, $allowed );
}

/**
 * Build the opening attributes (class + optional id) for a page builder
 * module <section>, from the shared "Settings" tab sub fields.
 *
 * Must be called inside a page_builder have_rows() loop.
 *
 * @param string $module  Module slug, e.g. 'card-grid'.
 * @param string $default Background to use when none is set.
 * @param string $extra   Extra classes.
 * @return string Escaped attribute string, ready to echo.
 */
function ttng_module_attrs( $module, $default = 'white', $extra = '' ) {
	$allowed    = array( 'white', 'light', 'navy', 'navy-primary', 'gradient' );
	$background = get_sub_field( 'section_background' );

	if ( ! in_array( $background, $allowed, true ) ) {
		$background = $default;
	}

	$classes = array( 'module', 'module--' . $module, 'section-bg--' . $background );

	// Dark backgrounds flip text/button colours — see additions.css.
	if ( in_array( $background, array( 'navy', 'navy-primary', 'gradient' ), true ) ) {
		$classes[] = 'is-dark';
	}

	if ( $extra ) {
		$classes[] = $extra;
	}

	$attrs = ' class="' . esc_attr( implode( ' ', array_unique( $classes ) ) ) . '"';

	$section_id = get_sub_field( 'section_id' );
	if ( $section_id ) {
		$attrs .= ' id="' . esc_attr( sanitize_title( $section_id ) ) . '"';
	}

	return $attrs;
}

/**
 * Render a single ACF link field as a button.
 *
 * @param array  $link  ACF link array (url, title, target).
 * @param string $style Button style: primary | outline | outline-light.
 * @param string $extra Extra classes.
 * @return void
 */
function ttng_the_button( $link, $style = 'primary', $extra = '' ) {
	if ( empty( $link['url'] ) ) {
		return;
	}

	$allowed = array( 'primary', 'outline', 'outline-light' );
	$style   = in_array( $style, $allowed, true ) ? $style : 'primary';
	$title   = ! empty( $link['title'] ) ? $link['title'] : __( 'Find out more', 'ttng-main-site-theme' );
	$target  = ! empty( $link['target'] ) ? $link['target'] : '_self';

	printf(
		'<a class="%1$s" href="%2$s" target="%3$s"%4$s>%5$s</a>',
		esc_attr( trim( 'btn btn--' . $style . ' ' . $extra ) ),
		esc_url( $link['url'] ),
		esc_attr( $target ),
		'_blank' === $target ? ' rel="noopener noreferrer"' : '',
		esc_html( $title )
	);
}

/**
 * Convert a display phone number into a tel: friendly string.
 *
 * @param string $telephone Phone number as entered, e.g. "01483 545780".
 * @return string Digits and leading + only.
 */
function ttng_tel_href( $telephone ) {
	return preg_replace( '/[^0-9+]/', '', (string) $telephone );
}

/**
 * Phone numbers and email for a contact source, from Site Settings.
 *
 * Recruitment falls back to the general details for anything left blank, so a
 * module never renders empty just because a field hasn't been filled in.
 *
 * @param string $source general | recruitment.
 * @return array { phones: string[], email: string }
 */
function ttng_get_contact_details( $source = 'general' ) {
	$phones = array( get_field( 'contact_telephone', 'option' ) );
	$email  = get_field( 'contact_email', 'option' );

	if ( 'recruitment' === $source ) {
		$recruitment = array_filter(
			array(
				get_field( 'recruitment_telephone', 'option' ),
				get_field( 'recruitment_telephone_alt', 'option' ),
			)
		);
		$phones      = $recruitment ? $recruitment : $phones;
		$email       = get_field( 'recruitment_email', 'option' ) ? get_field( 'recruitment_email', 'option' ) : $email;
	}

	return array(
		'phones' => array_values( array_filter( $phones ) ),
		'email'  => (string) $email,
	);
}

/**
 * Render a post's page_builder modules.
 *
 * In a function so each module's variables stay local, which matters because
 * a Reusable Section renders a builder inside a builder. Nesting is limited
 * to one level, so a section can't include itself.
 *
 * @param int|false $post_id Post to read from. False = the current post.
 * @return void
 */
function ttng_render_page_builder( $post_id = false ) {
	static $depth = 0;

	if ( $depth > 1 ) {
		return;
	}

	++$depth;
	$ttng_builder_post_id = $post_id;
	include get_theme_file_path( 'template-parts/modules/page-builder.php' );
	--$depth;
}

/**
 * Return a small theme-authored inline SVG icon.
 *
 * Fixed strings only (never user input), so safe to echo directly.
 *
 * @param string $name Icon name: email | phone | download | quote | arrow | check | plus | chevron | person.
 * @return string SVG markup or empty string.
 */
function ttng_icon( $name ) {
	$icons = array(
		'email'    => '<svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg>',
		'phone'    => '<svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
		'download' => '<svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></svg>',
		'quote'    => '<svg class="icon" width="40" height="32" viewBox="0 0 40 32" fill="currentColor" aria-hidden="true" focusable="false"><path d="M0 32V19.2C0 8.5 5.6 2.1 16.8 0l1.6 4.2C12.5 5.8 9.6 9.3 9.4 14.6H17V32H0zm23 0V19.2C23 8.5 28.6 2.1 39.8 0l1.6 4.2c-5.9 1.6-8.8 5.1-9 10.4H40V32H23z"/></svg>',
		'arrow'    => '<svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>',
		'check'    => '<svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true" focusable="false"><path d="m5 12 5 5 9-10"/></svg>',
		'plus'     => '<svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true" focusable="false"><path d="M12 5v14"/><path d="M5 12h14"/></svg>',
		'star'     => '<svg class="icon" width="15" height="15" viewBox="0 0 550 526" fill="currentColor" aria-hidden="true" focusable="false"><path d="M296.6 13.1C292.5 5.1 284.2 0 275.2 0s-17.3 5.1-21.4 13.1l-73.6 144.2-159.9 25.4c-8.9 1.4-16.3 7.7-19.1 16.3-2.8 8.6-.5 18 5.8 24.4l114.4 114.5-25.2 159.9c-1.4 8.9 2.3 17.9 9.6 23.2s16.9 6.1 25 2l144.4-73.4 144.3 73.4c8 4.1 17.7 3.3 25-2 7.3-5.3 11-14.2 9.6-23.2l-25.3-159.9 114.4-114.5c6.4-6.4 8.6-15.8 5.8-24.4-2.8-8.6-10.1-14.9-19.1-16.3l-159.8-25.4z"/></svg>',
		'brochure' => '<svg class="icon" width="12" height="14" viewBox="0 0 384 512" fill="currentColor" aria-hidden="true" focusable="false"><path d="M64 0C28.7 0 0 28.7 0 64v384c0 35.3 28.7 64 64 64h256c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zm192 0v128h128L256 0z"/></svg>',
		'chevron'  => '<svg class="icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true" focusable="false"><path d="m6 9 6 6 6-6"/></svg>',
		'person'   => '<svg class="icon" width="96" height="96" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><circle cx="12" cy="8" r="4.5"/><path d="M3.5 22c0-5 3.8-8 8.5-8s8.5 3 8.5 8z"/></svg>',
	);

	return isset( $icons[ $name ] ) ? $icons[ $name ] : '';
}

/**
 * Section label with the staging two-tone style: "The Travel <span>Network Group</span>".
 *
 * Wrap the accent words in *asterisks* in the field ("The Travel *Network Group*").
 * Labels without asterisks that end in "Network Group" get the accent automatically.
 *
 * @param string $label Raw label text.
 * @return string Safe HTML.
 */
function ttng_format_label( $label ) {
	$label = esc_html( (string) $label );
	if ( false !== strpos( $label, '*' ) ) {
		return preg_replace( '/\*(.+?)\*/', '<span class="section-label__accent">$1</span>', $label );
	}
	return preg_replace( '/(Network Group)$/i', '<span class="section-label__accent">$1</span>', $label );
}

/**
 * Highlight words in a heading with the brand gradient: "The *UK's biggest* independent".
 * "UK's biggest" is highlighted automatically when no asterisks are used.
 *
 * @param string $text Raw text.
 * @return string Safe HTML.
 */
function ttng_format_highlight( $text ) {
	$text = esc_html( (string) $text );
	if ( false !== strpos( $text, '*' ) ) {
		return preg_replace( '/\*(.+?)\*/', '<strong class="text-gradient">$1</strong>', $text );
	}
	return preg_replace( '/(UK(?:&#039;|\')s biggest)/i', '<strong class="text-gradient">$1</strong>', $text );
}
