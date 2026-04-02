<?php
/**
 * Mattress landing sticky cart bar.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="sticky-cart-bar" class="fixed bottom-0 left-0 w-full bg-white/80 backdrop-blur-xl border-t border-white/40 shadow-[0_-10px_30px_rgba(0,0,0,0.05)] z-40 transform translate-y-full transition-transform duration-500 py-3 px-6 hidden md:block">
	<div class="max-w-7xl mx-auto flex items-center justify-between">
		<div class="flex items-center space-x-4">
			<div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100 hidden sm:block">
				<img src="<?php echo esc_url( stilco_get_mattress_landing_image_uri( 'image112.jpg' ) ); ?>" alt="Materac Stilco" class="w-full h-full object-cover">
			</div>
			<div>
				<h4 class="font-serif font-bold text-stilco-dark text-sm sm:text-base">Materac Stilco</h4>
				<span class="text-stilco-accent font-semibold text-sm">od 2 595 zł</span>
			</div>
		</div>
		<button type="button" class="bg-stilco-accent text-white font-medium py-3 px-8 rounded-full shadow-md hover:bg-[#A84A34] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-accent text-sm md:text-base whitespace-nowrap">
			Dodaj do koszyka
		</button>
	</div>
</div>
