<?php
/**
 * tracking.php – Google Tag Manager with Consent Mode v2 defaults
 *
 * Set the container ID in Site Settings > Tracking (GTM-XXXXXXX). Nothing is
 * output until it's set, and nothing is output for logged-in editors.
 *
 * Consent: every storage type defaults to "denied" before GTM loads. The
 * cookie banner (Cookiebot / CookieScript, configured in GTM) updates
 * consent when the visitor accepts, so GA4 runs cookieless until then.
 *
 * Form events: assets/js/main.js pushes `form_submit` to the dataLayer when a
 * Contact Form 7 form is sent (form ID, form name and page path only — no
 * field values). In GTM, trigger a GA4 event tag on Custom Event =
 * form_submit. ClickUp iframe forms can't be tracked from this page.
 *
 * The GTM snippets are inline by necessity (Google's loader); this is the
 * one exception to the theme's "no inline JS" rule.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Validated GTM container ID, or ''.
 *
 * The field lives in acf-json/group_ttng_tracking.json.
 */
function ttng_gtm_id() {
	if ( ! function_exists( 'get_field' ) || is_user_logged_in() ) {
		return '';
	}
	$id = strtoupper( trim( (string) get_field( 'gtm_container_id', 'option' ) ) );
	return preg_match( '/^GTM-[A-Z0-9]{4,12}$/', $id ) ? $id : '';
}

add_action(
	'wp_head',
	static function () {
		$id = ttng_gtm_id();
		if ( ! $id ) {
			return;
		}
		?>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('consent', 'default', { ad_storage: 'denied', ad_user_data: 'denied', ad_personalization: 'denied', analytics_storage: 'denied', functionality_storage: 'granted', security_storage: 'granted', wait_for_update: 500 });
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo esc_js( $id ); ?>');
</script>
		<?php
	},
	1
);

add_action(
	'wp_body_open',
	static function () {
		$id = ttng_gtm_id();
		if ( ! $id ) {
			return;
		}
		printf(
			'<noscript><iframe src="%s" height="0" width="0" style="display:none;visibility:hidden" title="Google Tag Manager"></iframe></noscript>',
			esc_url( 'https://www.googletagmanager.com/ns.html?id=' . $id )
		);
	}
);
