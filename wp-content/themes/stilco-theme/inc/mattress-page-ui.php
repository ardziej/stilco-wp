<?php
/**
 * Mattress landing page UI helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue mattress page assets.
 *
 * @return void
 */
function stilco_enqueue_mattress_page_assets() {
	if ( is_admin() || ! stilco_is_mattress_page() ) {
		return;
	}

	wp_enqueue_style(
		'stilco-mattress-page',
		stilco_get_theme_asset_uri( 'assets/css/mattress-page.css' ),
		array( 'stilco-style' ),
		stilco_get_theme_asset_version( 'assets/css/mattress-page.css' )
	);

	wp_enqueue_script(
		'stilco-mattress-page',
		stilco_get_theme_asset_uri( 'assets/js/mattress-page.js' ),
		array(),
		stilco_get_theme_asset_version( 'assets/js/mattress-page.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_mattress_page_assets', 130 );
