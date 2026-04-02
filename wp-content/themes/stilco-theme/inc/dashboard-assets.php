<?php
/**
 * Production dashboard asset helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the production dashboard deliveries endpoint URL.
 *
 * @return string
 */
function stilco_get_production_dashboard_deliveries_endpoint() {
	return rest_url( 'stilco/v1/deliveries' );
}

/**
 * Enqueue production dashboard assets.
 *
 * @return void
 */
function stilco_enqueue_production_dashboard_assets() {
	if ( ! stilco_is_production_dashboard_page() ) {
		return;
	}

	wp_enqueue_style(
		'stilco-production-dashboard',
		stilco_get_theme_asset_uri( 'assets/css/dashboard.css' ),
		array(),
		stilco_get_theme_asset_version( 'assets/css/dashboard.css' )
	);

	wp_enqueue_style(
		'stilco-production-dashboard-cards',
		stilco_get_theme_asset_uri( 'assets/css/dashboard-cards.css' ),
		array( 'stilco-production-dashboard' ),
		stilco_get_theme_asset_version( 'assets/css/dashboard-cards.css' )
	);

	wp_enqueue_script(
		'stilco-production-dashboard',
		stilco_get_theme_asset_uri( 'assets/js/dashboard.js' ),
		array(),
		stilco_get_theme_asset_version( 'assets/js/dashboard.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_production_dashboard_assets', 120 );
