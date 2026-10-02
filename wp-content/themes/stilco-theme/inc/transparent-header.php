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
	// The scrolled background swap lives in assets/js/modules/header-scroll-state.js.
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_transparent_header_assets', 130 );
