<?php
/**
 * Contact page asset helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue contact page CSS assets.
 *
 * @return void
 */
function stilco_enqueue_contact_page_assets() {
	if ( is_admin() || ! stilco_is_contact_page() ) {
		return;
	}

	wp_enqueue_style(
		'stilco-contact-page',
		stilco_get_theme_asset_uri( 'assets/css/contact-page.css' ),
		array( 'stilco-style' ),
		stilco_get_theme_asset_version( 'assets/css/contact-page.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_contact_page_assets', 130 );
