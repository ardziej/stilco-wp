<?php
/**
 * Front page trust bar, directly under the hero.
 *
 * Restored on review comment J11 with three items: free 100-night trial,
 * five-year guarantee, Polish brand.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id = get_queried_object_id();

$items = array(
	array(
		'label' => stilco_get_page_field( 'home_trust_1_label', 'Darmowy test 100 nocy', $page_id ),
		'icon'  => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z',
	),
	array(
		'label' => stilco_get_page_field( 'home_trust_2_label', '5 lat gwarancji', $page_id ),
		'icon'  => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
	),
	array(
		'label' => stilco_get_page_field( 'home_trust_3_label', 'Marka polska', $page_id ),
		'icon'  => 'M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9',
	),
);
?>
<section class="border-b border-stilco-secondary/15 bg-stilco-sand" aria-label="Dlaczego warto">
	<div class="mx-auto flex max-w-5xl flex-col items-stretch gap-4 px-6 py-6 sm:flex-row sm:items-center sm:justify-between sm:gap-8 md:py-7">
		<?php foreach ( $items as $item ) : ?>
			<div class="flex items-center justify-center gap-3 text-stilco-dark">
				<svg class="h-6 w-6 shrink-0 text-stilco-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?php echo esc_attr( $item['icon'] ); ?>"></path>
				</svg>
				<span class="text-sm font-medium tracking-wide"><?php echo esc_html( $item['label'] ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
</section>
