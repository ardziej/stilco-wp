<?php
/**
 * Front page hero section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="relative h-[95vh] min-h-[600px] w-full flex items-center justify-center bg-stilco-dark overflow-hidden">
	<div class="absolute inset-0">
		<img src="<?php echo esc_url( stilco_get_theme_asset_uri( 'assets/images/new-hero.png' ) ); ?>" alt="Materac Stilco" class="w-full h-full object-cover">
		<div class="absolute inset-0 bg-gradient-to-b from-stilco-dark/80 via-black/30 to-black/60"></div>
	</div>

	<div class="relative z-10 text-center max-w-4xl px-6 pb-20 animate-on-scroll">
		<span class="text-white/90 text-sm md:text-base tracking-widest uppercase font-semibold mb-4 block">Manufaktura Dobrego Snu</span>
		<h1 class="text-5xl md:text-7xl text-white font-serif font-bold mb-6 tracking-tight leading-tight">
			Zasypiaj szybciej.<br><span class="text-stilco-accent">Budź się wypoczęty.</span>
		</h1>
		<p class="text-lg md:text-xl text-gray-200 mb-10 max-w-2xl mx-auto font-sans">
			Oddychający materac hybrydowy dopasowujący się do kształtu Twojego ciała. 100% polska produkcja.
		</p>
		<div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
			<a href="/produkt/materac-stilco/" class="btn-primary w-full sm:w-auto text-lg rounded-full px-10 py-4 border-2 border-stilco-accent shadow-stilco-accent/40 shadow-xl bg-stilco-accent">
				Wybierz Materac
			</a>
			<a href="#dlaczego-my" class="btn-outline border-2 border-white text-white hover:bg-white hover:text-stilco-dark w-full sm:w-auto text-lg rounded-full px-10 py-4 bg-white/5 backdrop-blur-sm">
				Poznaj przewagi
			</a>
		</div>
	</div>

	<div class="absolute bottom-0 left-0 w-full border-t border-white/20 bg-stilco-dark/40 backdrop-blur-md text-white py-4 z-20 hidden md:block">
		<div class="max-w-7xl mx-auto px-6 flex justify-between items-center text-sm font-medium tracking-wide">
			<div class="flex items-center space-x-3">
				<svg class="w-6 h-6 text-stilco-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
				</svg>
				<span>100 Nocy na Test</span>
			</div>
			<div class="flex items-center space-x-3">
				<svg class="w-6 h-6 text-stilco-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
				</svg>
				<span>10 Lat Gwarancji</span>
			</div>
			<div class="flex items-center space-x-3">
				<svg class="w-6 h-6 text-stilco-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
				</svg>
				<span>Darmowa Dostawa</span>
			</div>
			<div class="flex items-center space-x-3">
				<svg class="w-6 h-6 text-stilco-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
				</svg>
				<span>Polska Produkcja</span>
			</div>
		</div>
	</div>
</section>

<div class="w-full bg-white py-8 flex items-center justify-center">
	<a href="#dlaczego-my" class="animate-bounce p-2 text-stilco-secondary hover:text-stilco-dark transition-colors flex flex-col items-center gap-2" aria-label="Przewiń wdół">
		<span class="text-sm font-medium tracking-widest uppercase">Odkryj więcej</span>
		<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
		</svg>
	</a>
</div>
