<?php
/**
 * Shared request-context helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check whether the current request is the WooCommerce order-received endpoint.
 *
 * @return bool
 */
function stilco_is_order_received_endpoint() {
	return function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' );
}

/**
 * Check whether the current request uses the live checkout layout.
 *
 * @return bool
 */
function stilco_is_live_checkout_page() {
	return class_exists( 'WooCommerce' ) && function_exists( 'is_checkout' ) && is_checkout() && ! stilco_is_order_received_endpoint();
}
