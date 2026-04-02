<?php
/**
 * Single product final CTA section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="mt-24 mb-16 relative bg-stilco-secondary rounded-[3rem] overflow-hidden shadow-xl border border-white p-12 lg:p-24 text-center animate-zoom group">
	<div class="absolute inset-0 bg-white/20 backdrop-blur-sm z-0"></div>
	<div class="absolute top-0 right-0 w-64 h-64 bg-white/40 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 transition-transform duration-1000 group-hover:scale-110"></div>
	<div class="absolute bottom-0 left-0 w-64 h-64 bg-stilco-accent/20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 transition-transform duration-1000 group-hover:scale-110"></div>

	<div class="relative z-10 max-w-2xl mx-auto">
		<h2 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-6 drop-shadow-sm">Spróbuj. Prześpisz się z decyzją.</h2>
		<p class="text-xl text-white/90 mb-10 font-medium">Brak stresu, pełen zwrot kosztów przez 100 nocy.</p>

		<button type="button" class="js-scroll-to-top inline-block bg-white text-stilco-accent font-bold text-lg px-12 py-6 rounded-full shadow-2xl hover:shadow-white/30 transform hover:-translate-y-2 transition-all duration-300 border-none outline-none">
			Wybierz swój wymiar
		</button>
	</div>
</div>
