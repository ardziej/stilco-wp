<?php
/**
 * Front page layers section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="py-24 bg-white border-t border-gray-100">
	<div class="max-w-7xl mx-auto px-6 text-center animate-on-scroll">
		<h2 class="text-3xl md:text-5xl font-display font-bold mb-4 text-stilco-dark">Zajrzyj do środka</h2>
		<p class="text-gray-600 max-w-2xl mx-auto mb-16 text-lg">Najwyższa jakość polskich materiałów, zamknięta w przemyślanej konstrukcji, która pracuje dla Twojego zdrowia.</p>

		<div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-left">
			<div class="group animate-on-scroll delay-100">
				<div class="bg-gray-50 h-64 rounded-3xl mb-6 relative overflow-hidden">
					<img src="<?php echo esc_url( stilco_get_theme_asset_uri( 'assets/images/image179.jpg' ) ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="Oddychający Pokrowiec">
				</div>
				<h3 class="text-xl font-bold font-display text-stilco-dark mb-2">1. Oddychający Pokrowiec</h3>
				<p class="text-gray-600 text-sm">Przewiewna, antyalergiczna tkanina w jasnym kremowym odcieniu, tkana z myślą o cyrkulacji powietrza. Zamek 360° ułatwia pielęgnację i pranie.</p>
			</div>
			<div class="group animate-on-scroll delay-300">
				<div class="bg-gray-50 h-64 rounded-3xl mb-6 relative overflow-hidden">
					<img src="<?php echo esc_url( stilco_get_theme_asset_uri( 'assets/images/image198.jpg' ) ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="Pianka Visco Memory w słońcu">
				</div>
				<h3 class="text-xl font-bold font-display text-stilco-dark mb-2">2. Termoelastyczna Bliskość</h3>
				<p class="text-gray-600 text-sm">Niezwykle miękka warstwa Visco idealnie otulająca i dająca ukojenie mięśniom po długim dniu zabawy i obowiązków.</p>
			</div>
			<div class="group animate-on-scroll delay-500">
				<div class="bg-gray-50 h-64 rounded-3xl mb-6 relative overflow-hidden">
					<img src="<?php echo esc_url( stilco_get_theme_asset_uri( 'assets/images/image205.jpg' ) ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="Pianka Wysokoelastyczna">
				</div>
				<h3 class="text-xl font-bold font-display text-stilco-dark mb-2">3. Wsparcie i Trwałość</h3>
				<p class="text-gray-600 text-sm">Rdzeń z pianki HR dba o zachowanie naturalnych krzywizn kręgosłupa i sprawia, że materac posłuży Wam przez długie lata w doskonałej formie.</p>
			</div>
		</div>
	</div>
</section>
