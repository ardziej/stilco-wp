<?php
/**
 * Front page mid-page CTA.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id  = get_queried_object_id();
$cta_link = stilco_get_link_data( 'home_mid_cta_button_text', 'home_mid_cta_button_url', 'Wybierz swój materac', '/produkt/materac-stilco/', $page_id );
?>
<section class="py-12 bg-stilco-dark text-white">
	<div class="max-w-6xl mx-auto px-6">
		<div class="rounded-[2rem] border border-white/10 bg-white/5 px-8 py-10 shadow-xl backdrop-blur-sm md:flex md:items-center md:justify-between md:gap-10">
			<div class="max-w-2xl">
				<h2 class="text-3xl md:text-4xl font-display font-bold mb-3"><?php echo esc_html( stilco_get_page_field( 'home_mid_cta_title', 'Wybierz swój materac', $page_id ) ); ?></h2>
				<p class="text-white/80 text-lg"><?php echo esc_html( stilco_get_page_field( 'home_mid_cta_text', 'Przejdź od razu do konfiguratora i sprawdź wariant najlepiej dopasowany do Twojego snu.', $page_id ) ); ?></p>
			</div>
			<div class="mt-6 md:mt-0">
				<a href="<?php echo esc_url( $cta_link['url'] ); ?>" class="inline-block rounded-full bg-stilco-accent px-8 py-4 text-base font-semibold text-white shadow-lg shadow-stilco-accent/30 transition-colors hover:bg-[#A84A34]">
					<?php echo esc_html( $cta_link['label'] ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
