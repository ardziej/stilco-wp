<?php
/**
 * About page values section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="py-24 bg-stilco-light">
	<div class="max-w-7xl mx-auto px-6">
		<h2 class="text-3xl md:text-5xl font-serif font-bold text-center text-stilco-dark mb-16">Nasze wartości</h2>

		<div class="grid grid-cols-1 md:grid-cols-3 gap-8 auto-rows-[280px]">
			<div class="bg-white rounded-3xl p-10 shadow-sm flex flex-col justify-between border border-gray-100 md:col-span-2 relative overflow-hidden group animate-zoom">
				<div class="relative z-10 w-full mb-6">
					<svg class="w-10 h-10 text-stilco-accent mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
					<h3 class="text-3xl font-display font-semibold text-stilco-dark mb-3">Pianki Premium</h3>
					<p class="text-gray-600 text-lg max-w-xl leading-relaxed">Używamy wyselekcjonowanych i trwalszych pianek kalibrowanych (np HR40 zamiast taniej T25). Obiecujemy zero kompromisów przy dnie łóżka.</p>
				</div>
			</div>

			<div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 flex flex-col justify-center items-center text-center transform transition-transform hover:-translate-y-2 duration-500 animate-zoom delay-100">
				<svg class="w-12 h-12 text-stilco-accent mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 15.536c-1.171 1.952-3.07 1.952-4.242 0-1.172-1.953-1.172-5.119 0-7.072 1.171-1.952 3.07-1.952 4.242 0M8 10.5h4m-4 3h4m9-1.5a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
				<h3 class="text-2xl font-display font-semibold text-stilco-dark mb-3">Mistrzowska Precyzja</h3>
				<p class="text-gray-600 text-base">Szyjemy i tniemy z dbałością o każdy detal, dbając o idealne proporcje.</p>
			</div>

			<div class="bg-white rounded-3xl p-10 shadow-sm flex flex-col justify-center border border-gray-100 animate-zoom delay-200">
				<svg class="w-10 h-10 text-stilco-accent mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
				<div class="mt-auto">
					<h3 class="text-2xl font-display font-semibold text-stilco-dark mb-3">Szybka Logistyka</h3>
					<p class="text-gray-600 text-base leading-relaxed">Odbiór nawet do 48h dzięki innowacyjnej prasie rolującej paczki przed błyskawicznym wysłaniem.</p>
				</div>
			</div>

			<div class="bg-gray-200 rounded-3xl overflow-hidden md:col-span-2 relative animate-zoom delay-300">
				<img src="<?php echo esc_url( stilco_get_theme_asset_uri( 'assets/images/delivery-materac.jpg' ) ); ?>" class="w-full h-full object-cover" alt="Szybka dostawa materaca">
			</div>
		</div>
	</div>
</section>
