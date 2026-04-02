<?php
/**
 * Front page asset helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue dedicated front page CSS.
 *
 * @return void
 */
function stilco_enqueue_front_page_assets() {
	if ( is_admin() || ! stilco_is_marketing_front_page() ) {
		return;
	}

	wp_enqueue_style(
		'stilco-front-page',
		stilco_get_theme_asset_uri( 'assets/css/front-page.css' ),
		array( 'stilco-style' ),
		stilco_get_theme_asset_version( 'assets/css/front-page.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_front_page_assets', 130 );
