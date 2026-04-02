<?php
/**
 * WooCommerce archive sidebar.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<aside class="w-full lg:w-1/4 order-2 lg:order-1">
	<?php if ( is_active_sidebar( 'shop-sidebar' ) ) : ?>
		<div class="bg-white rounded-3xl p-6 shadow-sm sticky top-24">
			<?php dynamic_sidebar( 'shop-sidebar' ); ?>
		</div>
	<?php else : ?>
		<div class="bg-white rounded-3xl p-6 shadow-sm sticky top-24">
			<p class="text-gray-500 text-sm">Brak filtrów. Dodaj widgety do panelu "Shop Sidebar".</p>
		</div>
	<?php endif; ?>
</aside>
