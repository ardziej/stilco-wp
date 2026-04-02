<?php
/**
 * WooCommerce theme integrations.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Refresh cart counter fragment after AJAX add-to-cart.
 *
 * @param array<string, string> $fragments Existing fragments.
 * @return array<string, string>
 */
function stilco_cart_fragments( $fragments ) {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $fragments;
	}

	$count = (int) WC()->cart->get_cart_contents_count();

	ob_start();
	?>
	<span class="cart-contents-count absolute -top-2 -right-2 bg-stilco-accent text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full mt-0 <?php echo esc_attr( 0 === $count ? 'hidden' : '' ); ?>">
		<?php echo esc_html( $count ); ?>
	</span>
	<?php
	$fragments['span.cart-contents-count'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'stilco_cart_fragments' );

remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );

/**
 * Render theme-specific mini-cart CTA buttons.
 *
 * @return void
 */
function stilco_custom_mini_cart_buttons() {
	printf(
		'<a href="%1$s" class="btn-outline w-full text-center py-3">%2$s</a>',
		esc_url( wc_get_cart_url() ),
		esc_html__( 'Zobacz Koszyk', 'stilco' )
	);

	printf(
		'<a href="%1$s" class="btn-primary w-full text-center py-3">%2$s</a>',
		esc_url( wc_get_checkout_url() ),
		esc_html__( 'Przejdź do Kasy', 'stilco' )
	);
}
add_action( 'woocommerce_widget_shopping_cart_buttons', 'stilco_custom_mini_cart_buttons', 20 );
