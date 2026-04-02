<?php
/**
 * Front page dual comfort section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section id="dlaczego-my" class="py-24 bg-stilco-sand relative overflow-hidden">
	<div class="max-w-7xl mx-auto px-6">
		<div class="flex flex-col lg:flex-row items-center gap-16">
			<div class="w-full lg:w-1/2 animate-slide-left">
				<span class="text-stilco-secondary font-medium tracking-widest uppercase text-sm mb-4 block">1 materac, 2 strony</span>
				<h2 class="text-3xl md:text-5xl font-display font-bold mb-6 text-stilco-dark">Zmieniaj twardość kiedy tylko zechcesz.</h2>
				<div class="space-y-6 text-gray-600">
					<p class="text-lg">Stworzyliśmy materac, który dopasowuje się do Twoich zmieniających się potrzeb. Po jednej stronie znajdziesz miękką piankę Visco, po drugiej - piankę wysokoelastyczną dającą stabilne podparcie.</p>
					<ul class="space-y-4 mt-8">
						<li class="flex items-start">
							<svg class="h-6 w-6 text-stilco-secondary mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
							</svg>
							<div>
								<h4 class="font-bold text-stilco-dark">Strona H2 (Średnio-miękka)</h4>
								<p class="text-sm mt-1">Pianka Visco Memory - idealnie otula ciało, redukując nacisk. Doskonała dla osób lżejszych lub preferujących uczucie "zapadania się".</p>
							</div>
						</li>
						<li class="flex items-start">
							<svg class="h-6 w-6 text-stilco-secondary mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
							</svg>
							<div>
								<h4 class="font-bold text-stilco-dark">Strona H3 (Średnio-twarda)</h4>
								<p class="text-sm mt-1">Pianka Wysokoelastyczna - zapewnia solidne podparcie kręgosłupa i większą sprężystość. Wybierana przez zwolenników twardszego podłoża.</p>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="w-full lg:w-1/2 animate-slide-right delay-200">
				<div class="relative rounded-3xl overflow-hidden aspect-square shadow-2xl">
					<img src="<?php echo esc_url( stilco_get_theme_asset_uri( 'assets/images/image112.jpg' ) ); ?>" alt="Dwie strony materaca Stilco w jasnym świetle" class="w-full h-full object-cover">
					<div class="absolute inset-0 bg-gradient-to-tr from-stilco-dark/30 to-transparent flex items-end p-8">
						<div class="text-white">
							<p class="font-display font-medium text-xl">Stilco Dual Comfort</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
