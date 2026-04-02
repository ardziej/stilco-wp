<?php
/**
 * Checkout-specific hooks.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check whether checkout-specific UI assets should load.
 *
 * @return bool
 */
function stilco_should_load_checkout_assets() {
	return function_exists( 'is_checkout' ) && is_checkout() && ! stilco_is_order_received_endpoint();
}

/**
 * Enqueue checkout-specific UI assets without changing checkout flow.
 *
 * @return void
 */
function stilco_enqueue_checkout_assets() {
	if ( ! stilco_should_load_checkout_assets() ) {
		return;
	}

	wp_enqueue_style(
		'stilco-checkout',
		stilco_get_theme_asset_uri( 'assets/css/checkout.css' ),
		array( 'stilco-style' ),
		stilco_get_theme_asset_version( 'assets/css/checkout.css' )
	);

	wp_enqueue_script(
		'stilco-checkout',
		stilco_get_theme_asset_uri( 'assets/js/checkout.js' ),
		array(),
		stilco_get_theme_asset_version( 'assets/js/checkout.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_checkout_assets', 200 );

/**
 * Simplify selected checkout fields without changing checkout flow.
 *
 * @param array<string, mixed> $fields Checkout fields.
 * @return array<string, mixed>
 */
function stilco_minimal_checkout_fields( $fields ) {
	unset( $fields['billing']['billing_address_2'] );
	unset( $fields['shipping']['shipping_address_2'] );

	if ( isset( $fields['order']['order_comments'] ) ) {
		$fields['order']['order_comments']['placeholder'] = __( 'Dodatkowe uwagi do zamówienia (opcjonalnie)', 'stilco' );
		$fields['order']['order_comments']['label']       = __( 'Uwagi', 'stilco' );
	}

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'stilco_minimal_checkout_fields' );
