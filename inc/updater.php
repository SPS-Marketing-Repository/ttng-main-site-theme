<?php
/**
 * updater.php – Theme updates in wp-admin, delivered from GitHub Releases.
 *
 * The Update URI header in style.css sends WordPress's theme update check to
 * the update_themes_github.com filter (WordPress 6.1+). This returns the latest
 * published release, and core then handles the update notice, one-click update,
 * auto-update toggle and "Check again" as it does for any other theme.
 *
 * Releases need the built ttng-main-site-theme.zip asset, because GitHub's own
 * source zips unpack to a differently named folder, which would install as a
 * second theme. .github/workflows/release.yml builds it when a version tag is
 * pushed. See "Releasing an Update" in README.md.
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

define( 'TTNG_UPDATE_REPO', 'SPS-Marketing-Repository/ttng-main-site-theme' );
define( 'TTNG_UPDATE_ASSET', 'ttng-main-site-theme.zip' );
define( 'TTNG_UPDATE_CACHE_KEY', 'ttng_latest_release' );

/**
 * Whether this install should be offered updates.
 *
 * Off for git checkouts: an update replaces the whole theme folder, which would
 * delete .git and any uncommitted work. Core only protects against this for
 * automatic updates, not for the Update button.
 *
 * @return bool
 */
function ttng_updater_is_enabled() {
	$enabled = ! file_exists( get_template_directory() . '/.git' );

	/**
	 * Filters whether the theme is offered updates from GitHub.
	 *
	 * @param bool $enabled True unless the theme folder is a git checkout.
	 */
	return (bool) apply_filters( 'ttng_updater_enabled', $enabled );
}

/**
 * Turns a GitHub "latest release" API response into update details.
 *
 * @param array|WP_Error $response Response from wp_remote_get().
 * @return array Version, release URL, package URL, requirements and notes, or
 *               an empty array if the response can't be used.
 */
function ttng_updater_parse_release( $response ) {
	if ( is_wp_error( $response ) ) {
		error_log( 'TTNG updater: GitHub request failed: ' . $response->get_error_message() );
		return array();
	}

	$code = (int) wp_remote_retrieve_response_code( $response );

	if ( 200 !== $code ) {
		error_log( 'TTNG updater: GitHub returned HTTP ' . $code );
		return array();
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( ! is_array( $data ) || empty( $data['tag_name'] ) || ! is_string( $data['tag_name'] ) ) {
		error_log( 'TTNG updater: GitHub returned an unexpected response.' );
		return array();
	}

	$version = ltrim( $data['tag_name'], 'v' );

	if ( ! preg_match( '/^\d+\.\d+\.\d+$/', $version ) ) {
		error_log( 'TTNG updater: ignoring release tag ' . $data['tag_name'] );
		return array();
	}

	// Only accept the built zip from this repository's own release downloads.
	$package_prefix = 'https://github.com/' . TTNG_UPDATE_REPO . '/releases/download/';
	$package        = '';

	foreach ( (array) ( $data['assets'] ?? array() ) as $asset ) {
		$url = $asset['browser_download_url'] ?? '';

		if ( TTNG_UPDATE_ASSET === ( $asset['name'] ?? '' ) && is_string( $url ) && str_starts_with( $url, $package_prefix ) ) {
			$package = $url;
			break;
		}
	}

	if ( '' === $package ) {
		error_log( 'TTNG updater: release ' . $version . ' has no ' . TTNG_UPDATE_ASSET . ' asset.' );
		return array();
	}

	$notes = is_string( $data['body'] ?? null ) ? $data['body'] : '';

	// The release workflow writes the new version's requirements into the notes,
	// so core can hold back an update this server couldn't run.
	$theme        = wp_get_theme( get_template() );
	$requires     = preg_match( '/^Requires WordPress:\s*([\d.]+)/mi', $notes, $wp_match ) ? $wp_match[1] : $theme->get( 'RequiresWP' );
	$requires_php = preg_match( '/^Requires PHP:\s*([\d.]+)/mi', $notes, $php_match ) ? $php_match[1] : $theme->get( 'RequiresPHP' );

	return array(
		'version'      => $version,
		'html_url'     => 'https://github.com/' . TTNG_UPDATE_REPO . '/releases/tag/' . rawurlencode( $data['tag_name'] ),
		'package'      => $package,
		'requires'     => $requires,
		'requires_php' => $requires_php,
		'notes'        => $notes,
	);
}

/**
 * Gets the latest release, cached so sites stay within GitHub's limit of 60
 * unauthenticated requests per hour per IP address.
 *
 * @return array Release details, or an empty array if none is available.
 */
function ttng_updater_get_release() {
	$cached = get_site_transient( TTNG_UPDATE_CACHE_KEY );

	if ( is_array( $cached ) ) {
		return $cached;
	}

	$response = wp_remote_get(
		'https://api.github.com/repos/' . TTNG_UPDATE_REPO . '/releases/latest',
		array(
			'timeout' => 10,
			'headers' => array(
				'Accept'     => 'application/vnd.github+json',
				'User-Agent' => 'TTNG-Theme-Updater',
			),
		)
	);

	$release = ttng_updater_parse_release( $response );

	// Failures are cached too, but briefly, so an outage isn't retried on every check.
	set_site_transient( TTNG_UPDATE_CACHE_KEY, $release, $release ? 3 * HOUR_IN_SECONDS : 15 * MINUTE_IN_SECONDS );

	return $release;
}

/**
 * Answers WordPress's update check for this theme.
 *
 * @param array|false $update     Update data so far.
 * @param array       $theme_data Theme headers.
 * @param string      $stylesheet Theme directory name.
 * @return array|false Update data, or the original value.
 */
function ttng_updater_check( $update, $theme_data, $stylesheet ) {
	if ( get_template() !== $stylesheet || ! ttng_updater_is_enabled() ) {
		return $update;
	}

	$release = ttng_updater_get_release();

	if ( ! $release ) {
		return $update;
	}

	return array(
		'theme'        => $stylesheet,
		'version'      => $release['version'],
		'url'          => add_query_arg( 'action', 'ttng_update_details', admin_url( 'admin-ajax.php' ) ),
		'package'      => $release['package'],
		'requires'     => $release['requires'],
		'requires_php' => $release['requires_php'],
	);
}
add_filter( 'update_themes_github.com', 'ttng_updater_check', 10, 3 );

/**
 * Makes "Check again" on Dashboard > Updates ignore the cached release.
 *
 * Runs before core's own check on this hook, at priority 10.
 */
function ttng_updater_force_check() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- core's link has no nonce; this only clears a cache.
	if ( ! empty( $_GET['force-check'] ) && current_user_can( 'update_themes' ) ) {
		delete_site_transient( TTNG_UPDATE_CACHE_KEY );
	}
}
add_action( 'load-update-core.php', 'ttng_updater_force_check', 1 );

/**
 * Prints the "View version details" pop-up.
 *
 * Core shows the update's URL in an iframe, and GitHub refuses to be framed, so
 * linking to the release directly would open an empty box.
 */
function ttng_updater_details() {
	if ( ! current_user_can( 'update_themes' ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to view this.', 'ttng-main-site-theme' ), 403 );
	}

	$release = ttng_updater_get_release();

	if ( ! $release ) {
		wp_die( esc_html__( 'Release details are not available right now.', 'ttng-main-site-theme' ) );
	}

	header( 'Content-Type: text/html; charset=utf-8' );
	?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="utf-8">
		<title><?php echo esc_html( $release['version'] ); ?></title>
	</head>
	<body>
		<h1>
			<?php
			/* translators: %s: version number. */
			echo esc_html( sprintf( __( 'Version %s', 'ttng-main-site-theme' ), $release['version'] ) );
			?>
		</h1>
		<p><?php echo nl2br( esc_html( $release['notes'] ) ); ?></p>
		<p>
			<a href="<?php echo esc_url( $release['html_url'] ); ?>" target="_blank" rel="noopener">
				<?php esc_html_e( 'View this release on GitHub', 'ttng-main-site-theme' ); ?>
			</a>
		</p>
	</body>
	</html>
	<?php
	wp_die();
}
add_action( 'wp_ajax_ttng_update_details', 'ttng_updater_details' );
