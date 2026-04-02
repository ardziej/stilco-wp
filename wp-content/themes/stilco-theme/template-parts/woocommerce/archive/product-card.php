<?php
/**
 * WooCommerce archive product card.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product = isset( $args['product'] ) ? $args['product'] : null;

if ( ! $product instanceof WC_Product || ! $product->is_visible() ) {
	return;
}
?>
<div class="group bg-white rounded-3xl p-6 shadow-sm hover:shadow-xl transition-all duration-500 border border-transparent hover:border-stilco-accent/20 relative flex flex-col items-center text-center">
	<?php if ( $product->is_on_sale() ) : ?>
		<span class="absolute top-6 left-6 bg-stilco-accent text-white text-[10px] font-bold px-3 py-1.5 rounded-full z-10 tracking-widest uppercase shadow-sm">
			Promocja
		</span>
	<?php endif; ?>

	<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="block w-full mb-6 overflow-hidden rounded-2xl relative focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent">
		<?php
		echo $product->get_image(
			'woocommerce_thumbnail',
			array(
				'class' => 'w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out',
			)
		);
		?>
	</a>

	<h3 class="font-serif font-semibold text-xl mb-3 text-stilco-dark">
		<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm"><?php echo esc_html( $product->get_title() ); ?></a>
	</h3>

	<div class="text-stilco-dark font-medium mb-6 flex items-center justify-center space-x-2">
		<?php echo wp_kses_post( $product->get_price_html() ); ?>
	</div>

	<div class="mt-auto w-full">
		<a href="?add-to-cart=<?php echo esc_attr( $product->get_id() ); ?>" data-quantity="1" class="btn-outline w-full block py-3 text-sm rounded-full border border-stilco-accent text-stilco-accent hover:bg-stilco-accent hover:text-white transition-all duration-300 font-medium focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-dark" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" aria-label="Dodaj do koszyka">
			Dodaj do koszyka
		</a>
	</div>
</div>
