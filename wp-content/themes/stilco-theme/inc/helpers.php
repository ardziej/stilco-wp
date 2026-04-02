<?php
/**
 * Theme helper functions.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the configured Vite dev server origin.
 *
 * @return string
 */
function stilco_get_vite_dev_server_origin() {
	return untrailingslashit( (string) apply_filters( 'stilco_vite_dev_server_origin', 'http://localhost:5173' ) );
}

/**
 * Determine whether the current environment should probe the Vite dev server.
 *
 * @return bool
 */
function stilco_should_probe_vite_dev_server() {
	$environment = function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production';

	if ( in_array( $environment, array( 'local', 'development' ), true ) ) {
		return true;
	}

	return defined( 'WP_DEBUG' ) && WP_DEBUG;
}

/**
 * Check whether the Vite dev server is reachable.
 *
 * The result is cached briefly to avoid probing localhost on every request.
 *
 * @return bool
 */
function stilco_is_vite_dev_server_available() {
	static $is_available = null;

	if ( null !== $is_available ) {
		return $is_available;
	}

	if ( ! stilco_should_probe_vite_dev_server() ) {
		$is_available = false;
		return $is_available;
	}

	$cache_key = 'stilco_vite_dev_server_status';
	$cached    = get_transient( $cache_key );

	if ( false !== $cached ) {
		$is_available = 'available' === $cached;
		return $is_available;
	}

	$response = wp_remote_get(
		stilco_get_vite_dev_server_origin() . '/@vite/client',
		array(
			'timeout'   => 0.2,
			'sslverify' => false,
		)
	);

	$is_available = ! is_wp_error( $response ) && 200 === (int) wp_remote_retrieve_response_code( $response );

	set_transient( $cache_key, $is_available ? 'available' : 'missing', MINUTE_IN_SECONDS );

	return $is_available;
}

/**
 * Read and cache the Vite manifest.
 *
 * @return array<string, mixed>
 */
function stilco_get_vite_manifest() {
	static $manifest = null;

	if ( null !== $manifest ) {
		return $manifest;
	}

	$manifest_path = STILCO_THEME_DIR . '/dist/.vite/manifest.json';

	if ( ! file_exists( $manifest_path ) ) {
		$manifest = array();
		return $manifest;
	}

	$manifest_contents = file_get_contents( $manifest_path );

	if ( false === $manifest_contents ) {
		$manifest = array();
		return $manifest;
	}

	$decoded_manifest = json_decode( $manifest_contents, true );

	$manifest = is_array( $decoded_manifest ) ? $decoded_manifest : array();

	return $manifest;
}

/**
 * Resolve a Vite manifest entry to a public URL.
 *
 * @param string $entry Manifest entry key.
 * @return string
 */
function stilco_get_vite_asset_url( $entry ) {
	$manifest = stilco_get_vite_manifest();

	if ( empty( $manifest[ $entry ]['file'] ) ) {
		return '';
	}

	return STILCO_THEME_URI . '/dist/' . ltrim( $manifest[ $entry ]['file'], '/' );
}

/**
 * Build a public theme asset URL.
 *
 * @param string $relative_path Relative asset path within the theme.
 * @return string
 */
function stilco_get_theme_asset_uri( $relative_path ) {
	return STILCO_THEME_URI . '/' . ltrim( $relative_path, '/' );
}

/**
 * Build an absolute theme asset path.
 *
 * @param string $relative_path Relative asset path within the theme.
 * @return string
 */
function stilco_get_theme_asset_path( $relative_path ) {
	return STILCO_THEME_DIR . '/' . ltrim( $relative_path, '/' );
}

/**
 * Resolve a stable version for a static theme asset.
 *
 * @param string $relative_path Relative asset path within the theme.
 * @return string
 */
function stilco_get_theme_asset_version( $relative_path ) {
	$asset_path = stilco_get_theme_asset_path( $relative_path );

	if ( file_exists( $asset_path ) ) {
		return (string) filemtime( $asset_path );
	}

	return STILCO_THEME_VERSION;
}
