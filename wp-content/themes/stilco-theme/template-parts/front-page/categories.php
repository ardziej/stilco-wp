<?php
/**
 * Front page categories section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section id="kategorie" class="py-24 max-w-7xl mx-auto px-6">
	<div class="text-center mb-16 animate-on-scroll">
		<h2 class="text-3xl md:text-5xl font-display font-bold mb-4">Wybierz perfekcję</h2>
		<p class="text-gray-600 max-w-2xl mx-auto">Niezależnie od tego jak śpisz, mamy technologię dopasowaną do Twojego ciała.</p>
	</div>

	<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
		<div class="group cursor-pointer rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 animate-zoom border border-stilco-secondary/20">
			<div class="h-80 bg-stilco-sand relative overflow-hidden">
				<img src="<?php echo esc_url( stilco_get_theme_asset_uri( 'assets/images/image268.jpg' ) ); ?>" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-700" alt="Dla dorosłych">
			</div>
			<div class="p-8 bg-white text-center">
				<h3 class="text-2xl font-display font-bold mb-2 text-stilco-dark">Dla Dorosłych</h3>
				<p class="text-sm text-gray-500 mb-6">Elastyczność i dopasowanie premium dla każdej twardości.</p>
				<span class="text-stilco-secondary font-medium uppercase tracking-wider text-sm group-hover:text-stilco-dark transition-colors">Odkryj →</span>
			</div>
		</div>

		<div class="group cursor-pointer rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 animate-zoom delay-100 border border-stilco-secondary/20">
			<div class="h-80 bg-stilco-sand relative overflow-hidden">
				<img src="<?php echo esc_url( stilco_get_theme_asset_uri( 'assets/images/image88.jpg' ) ); ?>" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-700" alt="Dla Dzieci">
			</div>
			<div class="p-8 bg-white text-center">
				<h3 class="text-2xl font-display font-bold mb-2 text-stilco-dark">Dla Dzieci</h3>
				<p class="text-sm text-gray-500 mb-6">Bezpieczne materiały dla rosnących ciał.</p>
				<span class="text-stilco-secondary font-medium uppercase tracking-wider text-sm group-hover:text-stilco-dark transition-colors">Odkryj →</span>
			</div>
		</div>

		<div class="group cursor-pointer rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 animate-zoom delay-200 border border-stilco-secondary/20">
			<div class="h-80 bg-stilco-sand relative overflow-hidden flex items-center justify-center">
				<div class="text-center p-6 bg-stilco-light bg-opacity-90 w-full h-full flex flex-col items-center justify-center">
					<span class="block text-4xl mb-2 text-stilco-secondary">🌿</span>
					<h3 class="text-xl font-display font-bold text-stilco-dark">Ekologiczne Materiały</h3>
					<p class="text-sm text-gray-600 mt-2">Dbałość o naturę oznacza czystszy sen w zgodzie ze środowiskiem.</p>
				</div>
			</div>
			<div class="p-8 bg-stilco-secondary text-center">
				<span class="text-white font-medium uppercase tracking-wider text-sm">Poznaj wartości →</span>
			</div>
		</div>
	</div>
</section>
