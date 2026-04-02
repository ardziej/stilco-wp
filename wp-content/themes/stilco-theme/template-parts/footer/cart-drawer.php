<?php
/**
 * Footer cart drawer.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="slide-over-cart" class="relative z-50 hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
	<div id="cart-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity opacity-0 z-40"></div>

	<div class="fixed inset-0 overflow-hidden z-50 pointer-events-none">
		<div class="absolute inset-0 overflow-hidden">
			<div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
				<div id="cart-panel" class="pointer-events-auto w-screen max-w-md transform transition ease-in-out duration-500 translate-x-full">
					<div class="flex h-full flex-col bg-white shadow-2xl">
						<div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
							<div class="flex items-start justify-between">
								<h2 class="text-2xl font-display font-medium text-stilco-dark" id="slide-over-title">
									<?php esc_html_e( 'Twój Koszyk', 'stilco' ); ?>
								</h2>
								<div class="ml-3 flex h-7 items-center">
									<button type="button" id="close-cart-btn" class="relative -m-2 p-2 text-gray-400 hover:text-gray-500 transition-colors">
										<span class="absolute -inset-0.5"></span>
										<span class="sr-only"><?php esc_html_e( 'Zamknij koszyk', 'stilco' ); ?></span>
										<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
											<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
										</svg>
									</button>
								</div>
							</div>

							<div class="mt-8">
								<div class="flow-root">
									<div class="widget_shopping_cart_content h-full">
										<?php if ( function_exists( 'woocommerce_mini_cart' ) ) : ?>
											<?php woocommerce_mini_cart(); ?>
										<?php else : ?>
											<p class="text-gray-500 p-6 text-center"><?php esc_html_e( 'Dodatek WooCommerce nie jest jeszcze aktywny.', 'stilco' ); ?></p>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
