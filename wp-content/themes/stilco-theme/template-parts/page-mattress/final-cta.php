<?php
/**
 * Mattress landing final CTA section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="py-24 bg-stilco-dark text-center overflow-hidden relative">
	<div class="absolute inset-0 z-0 opacity-10 blur-xl">
		<img src="<?php echo esc_url( stilco_get_mattress_landing_image_uri( 'image112.jpg' ) ); ?>" alt="Materac Stilco" class="w-full h-full object-cover">
	</div>
	<div class="max-w-4xl mx-auto px-6 relative z-10 animate-zoom">
		<h2 class="text-4xl md:text-6xl font-serif text-white font-bold mb-8 drop-shadow-lg">100 dni na podjęcie decyzji.</h2>
		<p class="text-xl text-white/80 font-sans mb-12">Jeżeli materac nie poprawi jakości Twojego snu w ciągu 100 nocy, zwrócimy Ci pełną kwotę.</p>
		<button type="button" class="bg-stilco-accent text-white rounded-full px-16 py-6 text-xl font-medium shadow-xl hover:scale-105 transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-accent">
			Zacznij wysypiać się już jutro
		</button>
	</div>
</section>
