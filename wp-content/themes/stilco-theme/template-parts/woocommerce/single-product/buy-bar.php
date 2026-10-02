<?php
/**
 * Mobile buy bar: shown by single-product-variants.js once the add-to-cart
 * button has scrolled out of view. Its button drives the real form.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! $product instanceof WC_Product || ! $product->is_purchasable() ) {
	return;
}

$price_html = $product->is_type( 'variable' )
	? 'od ' . wc_price( $product->get_variation_price( 'min', true ) )
	: $product->get_price_html();
?>
<div class="buy-bar" data-buy-bar>
	<div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
		<div class="min-w-0">
			<p class="font-serif font-bold text-stilco-dark text-base truncate"><?php the_title(); ?></p>
			<p class="text-stilco-accent font-semibold text-sm" data-buy-bar-price><?php echo wp_kses_post( $price_html ); ?></p>
		</div>
		<button type="button" class="bg-stilco-accent text-white font-medium py-3 px-6 rounded-full shadow-md hover:bg-stilco-accent-hover transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-accent text-sm whitespace-nowrap" data-buy-bar-button>
			<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
		</button>
	</div>
</div>
