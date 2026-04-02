<?php
/**
 * Delivery Date Feature
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Add date input to product page
 */
add_action( 'woocommerce_before_add_to_cart_button', 'stilco_add_delivery_date_field' );
function stilco_add_delivery_date_field() {
    $min_date = date('Y-m-d', strtotime('+3 days')); // Example: +3 days minimum

    echo '<div class="stilco-delivery-date-field mb-4 w-full">';
    echo '<label for="stilco_delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Oczekiwana data dostawy (opcjonalnie)</label>';
    echo '<input type="date" id="stilco_delivery_date" name="stilco_delivery_date" min="' . esc_attr($min_date) . '" class="w-full rounded-md border-gray-300 shadow-sm focus:border-stilco-accent focus:ring-stilco-accent sm:text-sm p-3 border" />';
    echo '<p class="text-xs text-gray-500 mt-2">Jeśli chcesz, abyśmy dostarczyli materac w późniejszym terminie (np. za 2 miesiące z powodu remontu), wybierz datę poniżej. Opcja tylko dla zamówień z wyprzedzeniem min. 3 dniowym.</p>';
    echo '</div>';
}

/**
 * 2. Validate date
 */
add_filter( 'woocommerce_add_to_cart_validation', 'stilco_validate_delivery_date', 10, 3 );
function stilco_validate_delivery_date( $passed, $product_id, $quantity ) {
    if ( isset( $_POST['stilco_delivery_date'] ) && ! empty( $_POST['stilco_delivery_date'] ) ) {
        $selected_date = sanitize_text_field( $_POST['stilco_delivery_date'] );
        $min_date = date('Y-m-d', strtotime('+3 days'));
        
        if ( $selected_date < $min_date ) {
            wc_add_notice( 'Wybrana data dostawy jest zbyt wczesna. Prosimy wybrać późniejszy termin (minimum 3 dni).', 'error' );
            $passed = false;
        }
    }
    return $passed;
}

/**
 * 3. Save to cart item data
 */
add_filter( 'woocommerce_add_cart_item_data', 'stilco_add_delivery_date_to_cart_item', 10, 2 );
function stilco_add_delivery_date_to_cart_item( $cart_item_data, $product_id ) {
    if ( isset( $_POST['stilco_delivery_date'] ) && ! empty( $_POST['stilco_delivery_date'] ) ) {
        $cart_item_data['stilco_delivery_date'] = sanitize_text_field( $_POST['stilco_delivery_date'] );
    }
    return $cart_item_data;
}

/**
 * 4. Display in cart and checkout
 */
add_filter( 'woocommerce_get_item_data', 'stilco_display_delivery_date_in_cart', 10, 2 );
function stilco_display_delivery_date_in_cart( $item_data, $cart_item ) {
    if ( isset( $cart_item['stilco_delivery_date'] ) ) {
        $item_data[] = array(
            'key'     => 'Oczekiwana data dostawy',
            'value'   => wc_clean( $cart_item['stilco_delivery_date'] ),
            'display' => '',
        );
    }
    return $item_data;
}

/**
 * 5. Save to order item meta
 */
add_action( 'woocommerce_checkout_create_order_line_item', 'stilco_save_delivery_date_to_order_item', 10, 4 );
function stilco_save_delivery_date_to_order_item( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['stilco_delivery_date'] ) ) {
        $item->add_meta_data( 'Oczekiwana data dostawy', $values['stilco_delivery_date'], true );
        // Also save a hidden meta key for easier API querying without translated keys
        $item->add_meta_data( '_stilco_delivery_date', $values['stilco_delivery_date'], true );
    }
}

/**
 * 6. Register REST API endpoint
 */
add_action( 'rest_api_init', 'stilco_register_deliveries_endpoint' );
function stilco_register_deliveries_endpoint() {
    register_rest_route( 'stilco/v1', '/deliveries', array(
        'methods'             => 'GET',
        'callback'            => 'stilco_get_deliveries',
        'permission_callback' => '__return_true', // Możemy ograniczyć, ale dla wyświetlacza w sieci lokalnej / TV zostawimy otwarte
    ) );
}

function stilco_get_deliveries( $request ) {
    $args = array(
        'status' => array( 'wc-processing', 'wc-on-hold' ), // We only want active orders
        'limit'  => 500, // adjust as needed
        'return' => 'objects',
    );
    
    $orders = wc_get_orders( $args );
    $deliveries = array();
    
    // Fallback if wc_get_orders fails or is empty
    if ( empty($orders) ) return rest_ensure_response( array() );
    
    foreach ( $orders as $order ) {
        foreach ( $order->get_items() as $item_id => $item ) {
            $delivery_date = $item->get_meta( '_stilco_delivery_date' );
            if ( $delivery_date ) {
                $product = $item->get_product();
                $deliveries[] = array(
                    'order_id'       => $order->get_id(),
                    'order_number'   => $order->get_order_number(),
                    'customer_name'  => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                    'product_name'   => $item->get_name(),
                    'quantity'       => $item->get_quantity(),
                    'delivery_date'  => $delivery_date,
                    'status'         => $order->get_status(),
                );
            }
        }
    }
    
    // Sort by delivery date ascending (closest first)
    usort( $deliveries, function( $a, $b ) {
        return strtotime( $a['delivery_date'] ) - strtotime( $b['delivery_date'] );
    });
    
    return rest_ensure_response( $deliveries );
}
