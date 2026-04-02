<?php
/**
 * Transparent header UI helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue transparent header assets.
 *
 * @return void
 */
function stilco_enqueue_transparent_header_assets() {
	if ( is_admin() || ! stilco_is_transparent_header_context() ) {
		return;
	}

	wp_enqueue_style(
		'stilco-transparent-header',
		stilco_get_theme_asset_uri( 'assets/css/transparent-header.css' ),
		array( 'stilco-style' ),
		stilco_get_theme_asset_version( 'assets/css/transparent-header.css' )
	);

	wp_enqueue_script(
		'stilco-transparent-header',
		stilco_get_theme_asset_uri( 'assets/js/transparent-header.js' ),
		array(),
		stilco_get_theme_asset_version( 'assets/js/transparent-header.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_transparent_header_assets', 130 );
