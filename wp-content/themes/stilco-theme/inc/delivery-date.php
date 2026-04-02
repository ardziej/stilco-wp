<?php
/**
 * Delivery date feature.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the minimum allowed delivery date.
 *
 * @return string
 */
function stilco_get_min_delivery_date() {
	return wp_date( 'Y-m-d', strtotime( '+3 days', current_time( 'timestamp' ) ) );
}

/**
 * Read and sanitize the submitted delivery date.
 *
 * @return string
 */
function stilco_get_submitted_delivery_date() {
	if ( empty( $_POST['stilco_delivery_date'] ) ) {
		return '';
	}

	$delivery_date = sanitize_text_field( wp_unslash( $_POST['stilco_delivery_date'] ) );

	return preg_match( '/^\d{4}-\d{2}-\d{2}$/', $delivery_date ) ? $delivery_date : '';
}

/**
 * Check whether a submitted delivery date is valid.
 *
 * @param string $delivery_date Submitted delivery date.
 * @return bool
 */
function stilco_is_valid_delivery_date( $delivery_date ) {
	return '' !== $delivery_date && $delivery_date >= stilco_get_min_delivery_date();
}

/**
 * Add delivery date input to the single product form.
 *
 * @return void
 */
function stilco_add_delivery_date_field() {
	$min_date = stilco_get_min_delivery_date();
	?>
	<div class="stilco-delivery-date-field mb-4 w-full">
		<label for="stilco_delivery_date" class="block text-sm font-medium text-gray-700 mb-2">
			<?php esc_html_e( 'Oczekiwana data dostawy (opcjonalnie)', 'stilco' ); ?>
		</label>
		<input type="date" id="stilco_delivery_date" name="stilco_delivery_date" min="<?php echo esc_attr( $min_date ); ?>" class="w-full rounded-md border-gray-300 shadow-sm focus:border-stilco-accent focus:ring-stilco-accent sm:text-sm p-3 border" />
		<p class="text-xs text-gray-500 mt-2">
			<?php esc_html_e( 'Jeśli chcesz, abyśmy dostarczyli materac w późniejszym terminie (np. za 2 miesiące z powodu remontu), wybierz datę poniżej. Opcja tylko dla zamówień z wyprzedzeniem min. 3 dniowym.', 'stilco' ); ?>
		</p>
	</div>
	<?php
}
add_action( 'woocommerce_before_add_to_cart_button', 'stilco_add_delivery_date_field' );

/**
 * Validate the submitted delivery date before adding a product to cart.
 *
 * @param bool $passed     Whether validation has passed.
 * @param int  $product_id Product ID.
 * @param int  $quantity   Requested quantity.
 * @return bool
 */
function stilco_validate_delivery_date( $passed, $product_id = 0, $quantity = 0 ) {
	$delivery_date = stilco_get_submitted_delivery_date();

	if ( '' === $delivery_date ) {
		return $passed;
	}

	if ( ! stilco_is_valid_delivery_date( $delivery_date ) ) {
		wc_add_notice( __( 'Wybrana data dostawy jest zbyt wczesna. Prosimy wybrać późniejszy termin (minimum 3 dni).', 'stilco' ), 'error' );
		return false;
	}

	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'stilco_validate_delivery_date', 10, 3 );

/**
 * Save the submitted delivery date in cart item data.
 *
 * @param array<string, mixed> $cart_item_data Existing cart item data.
 * @param int                  $product_id     Product ID.
 * @return array<string, mixed>
 */
function stilco_add_delivery_date_to_cart_item( $cart_item_data, $product_id = 0 ) {
	$delivery_date = stilco_get_submitted_delivery_date();

	if ( '' !== $delivery_date ) {
		$cart_item_data['stilco_delivery_date'] = $delivery_date;
	}

	return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'stilco_add_delivery_date_to_cart_item', 10, 2 );

/**
 * Display the delivery date in cart and checkout item meta.
 *
 * @param array<int, array<string, string>> $item_data Existing item data.
 * @param array<string, mixed>              $cart_item Cart item data.
 * @return array<int, array<string, string>>
 */
function stilco_display_delivery_date_in_cart( $item_data, $cart_item ) {
	if ( empty( $cart_item['stilco_delivery_date'] ) ) {
		return $item_data;
	}

	$item_data[] = array(
		'key'     => __( 'Oczekiwana data dostawy', 'stilco' ),
		'value'   => wc_clean( $cart_item['stilco_delivery_date'] ),
		'display' => '',
	);

	return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'stilco_display_delivery_date_in_cart', 10, 2 );

/**
 * Save delivery date metadata on order line items.
 *
 * @param WC_Order_Item_Product $item          Order item.
 * @param string                $cart_item_key Cart item key.
 * @param array<string, mixed>  $values        Cart item values.
 * @param WC_Order              $order         Order object.
 * @return void
 */
function stilco_save_delivery_date_to_order_item( $item, $cart_item_key, $values, $order ) {
	if ( empty( $values['stilco_delivery_date'] ) ) {
		return;
	}

	$item->add_meta_data( __( 'Oczekiwana data dostawy', 'stilco' ), $values['stilco_delivery_date'], true );
	$item->add_meta_data( '_stilco_delivery_date', $values['stilco_delivery_date'], true );
}
add_action( 'woocommerce_checkout_create_order_line_item', 'stilco_save_delivery_date_to_order_item', 10, 4 );
