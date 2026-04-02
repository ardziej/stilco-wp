<?php
/**
 * FAQ page contact ribbon.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="bg-stilco-secondary py-16 mt-8 animate-zoom delay-300">
	<div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between text-stilco-dark">
		<div class="flex items-center space-x-6 mb-8 md:mb-0">
			<div class="w-16 h-16 rounded-full overflow-hidden border-2 border-white shadow-sm flex-shrink-0">
				<img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200&auto=format&fit=crop" class="w-full h-full object-cover" alt="Pracownik Wsparcia">
			</div>
			<div>
				<h3 class="text-2xl font-serif font-bold mb-1">Nadal masz pytania?</h3>
				<p class="text-stilco-dark/80 font-medium">Nasz dział wsparcia snu jest do Twojej dyspozycji.</p>
			</div>
		</div>

		<a href="/kontakt" class="w-full md:w-auto text-center bg-white text-stilco-dark font-medium px-10 py-5 rounded-full shadow-md hover:bg-gray-50 hover:text-stilco-accent transition-all transform hover:scale-105 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-dark">
			Napisz do nas
		</a>
	</div>
</section>
