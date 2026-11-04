<?php
/**
 * forms.php – Contact Form 7 spam protection (no plugin, no CAPTCHA)
 *
 * Every CF7 form gets two invisible checks:
 * 1. Honeypot: a hidden "website" field. People never see it; bots fill it in.
 * 2. Time trap: a signed timestamp. Submissions sent less than 3 seconds after
 *    the form loaded are bots.
 *
 * Failing either marks the submission as spam: CF7 shows its normal spam
 * message and sends no email. Nothing is shown to real visitors, so there is
 * no accessibility cost. Styles: additions.css (.ttng-hp).
 *
 * If spam still gets through, add Cloudflare Turnstile via CF7's
 * Integration screen on top of this.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

const TTNG_FORM_MIN_SECONDS = 3;

add_filter(
	'wpcf7_form_elements',
	static function ( $html ) {
		$time = time();
		$sig  = wp_hash( 'ttng_form|' . $time );
		$id   = 'ttng-hp-' . wp_unique_id();

		$html .= '<div class="ttng-hp" aria-hidden="true">'
			. '<label for="' . esc_attr( $id ) . '">' . esc_html__( 'Leave this field empty', 'ttng-main-site-theme' ) . '</label>'
			. '<input type="text" id="' . esc_attr( $id ) . '" name="ttng_website" value="" tabindex="-1" autocomplete="off">'
			. '</div>'
			. '<input type="hidden" name="ttng_form_ts" value="' . esc_attr( $time . '|' . $sig ) . '">';

		return $html;
	}
);

add_filter(
	'wpcf7_spam',
	static function ( $spam, $submission = null ) {
		if ( $spam ) {
			return $spam;
		}

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- CF7 verifies its own request; these are read-only spam checks.
		$honeypot = isset( $_POST['ttng_website'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['ttng_website'] ) ) ) : '';
		$stamp    = isset( $_POST['ttng_form_ts'] ) ? sanitize_text_field( wp_unslash( $_POST['ttng_form_ts'] ) ) : '';
		// phpcs:enable

		$reason = '';
		if ( '' !== $honeypot ) {
			$reason = 'Honeypot field was filled in.';
		} else {
			$parts = explode( '|', $stamp );
			$time  = (int) ( $parts[0] ?? 0 );
			$sig   = (string) ( $parts[1] ?? '' );

			if ( ! $time || ! hash_equals( wp_hash( 'ttng_form|' . $time ), $sig ) ) {
				$reason = 'Missing or invalid form timestamp.';
			} elseif ( time() - $time < TTNG_FORM_MIN_SECONDS ) {
				$reason = 'Submitted too quickly after the form loaded.';
			}
		}

		if ( ! $reason ) {
			return false;
		}

		if ( $submission && method_exists( $submission, 'add_spam_log' ) ) {
			$submission->add_spam_log(
				[
					'agent'  => 'ttng-theme',
					'reason' => $reason,
				]
			);
		}
		return true;
	},
	10,
	2
);
