<?php
/**
 * Deliveries REST endpoint helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine whether the deliveries REST endpoint should be publicly accessible.
 *
 * @return bool
 */
function stilco_deliveries_endpoint_permission_callback() {
	$is_public = (bool) apply_filters( 'stilco_deliveries_endpoint_public', true );

	if ( $is_public ) {
		return true;
	}

	return current_user_can( 'manage_woocommerce' ) || current_user_can( 'edit_shop_orders' );
}

/**
 * Register the deliveries REST endpoint.
 *
 * @return void
 */
function stilco_register_deliveries_endpoint() {
	register_rest_route(
		'stilco/v1',
		'/deliveries',
		array(
			'methods'             => 'GET',
			'callback'            => 'stilco_get_deliveries',
			'permission_callback' => 'stilco_deliveries_endpoint_permission_callback',
		)
	);
}
add_action( 'rest_api_init', 'stilco_register_deliveries_endpoint' );

/**
 * Build a dashboard delivery payload row from an order item.
 *
 * @param WC_Order              $order Order object.
 * @param WC_Order_Item_Product $item  Order item.
 * @return array<string, int|string>|null
 */
function stilco_build_delivery_entry( $order, $item ) {
	$delivery_date = $item->get_meta( '_stilco_delivery_date' );

	if ( ! $delivery_date ) {
		return null;
	}

	return array(
		'order_id'      => $order->get_id(),
		'order_number'  => $order->get_order_number(),
		'customer_name' => trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ),
		'product_name'  => $item->get_name(),
		'quantity'      => $item->get_quantity(),
		'delivery_date' => $delivery_date,
		'status'        => $order->get_status(),
	);
}

/**
 * Deliveries REST endpoint callback.
 *
 * @param WP_REST_Request $request REST request object.
 * @return WP_REST_Response
 */
function stilco_get_deliveries( $request ) {
	if ( ! function_exists( 'wc_get_orders' ) ) {
		return rest_ensure_response( array() );
	}

	$orders = wc_get_orders(
		array(
			'status' => array( 'wc-processing', 'wc-on-hold' ),
			'limit'  => 500,
			'return' => 'objects',
		)
	);

	if ( empty( $orders ) ) {
		return rest_ensure_response( array() );
	}

	$deliveries = array();

	foreach ( $orders as $order ) {
		foreach ( $order->get_items() as $item ) {
			$delivery_entry = stilco_build_delivery_entry( $order, $item );

			if ( $delivery_entry ) {
				$deliveries[] = $delivery_entry;
			}
		}
	}

	usort(
		$deliveries,
		function ( $left, $right ) {
			return strtotime( $left['delivery_date'] ) - strtotime( $right['delivery_date'] );
		}
	);

	return rest_ensure_response( $deliveries );
}
