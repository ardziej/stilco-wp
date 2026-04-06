<?php
/**
 * About page timeline section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id  = get_queried_object_id();
$fallback = array(
	1 => array( '1994', 'Narodziny firmy', 'Zakładamy rodzinną firmę w Malborku. Dobre relacje i wzajemny szacunek stają się fundamentem naszej działalności.' ),
	2 => array( '2016', 'Pierwsze badania', 'Rozpoczynamy intensywne prace badawcze nad właściwościami pianki poliuretanowej i włókien poliestrowych.' ),
	3 => array( '2023', 'Testy i doskonalenie', 'Lata testów i iteracji. Każde wypełnienie sprawdzane pod kątem trwałości, sprężystości i komfortu snu.' ),
	4 => array( 'Dziś', 'Globalny zasięg', 'Dostarczamy produkty do odbiorców z Polski, Europy i całego świata. Komfort snu staje się standardem dostępnym dla każdego.' ),
);
$delays   = array( 1 => 'delay-100', 2 => 'delay-200', 3 => 'delay-300', 4 => 'delay-400' );
?>
<section class="py-24 bg-stilco-sand overflow-hidden">
	<div class="max-w-7xl mx-auto px-6">
		<h2 class="text-3xl md:text-4xl font-display font-semibold text-center text-stilco-dark mb-16"><?php echo esc_html( stilco_get_page_field( 'about_timeline_title', 'Nasza Droga', $page_id ) ); ?></h2>

		<div class="flex overflow-x-auto md:grid md:grid-cols-4 gap-8 pb-8 snap-x">
			<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
			<div class="min-w-[80vw] md:min-w-0 snap-center relative pt-8 animate-on-scroll <?php echo esc_attr( $delays[ $i ] ); ?>">
				<div class="absolute top-0 left-0 w-full h-0.5 bg-stilco-accent/30 hidden md:block"></div>
				<div class="absolute top-[-5px] left-0 w-3 h-3 rounded-full bg-stilco-accent hidden md:block"></div>
				<span class="text-stilco-accent font-bold text-xl mb-2 block"><?php echo esc_html( stilco_get_page_field( "about_timeline_{$i}_year", $fallback[ $i ][0], $page_id ) ); ?></span>
				<h3 class="font-display font-semibold text-lg text-stilco-dark mb-4"><?php echo esc_html( stilco_get_page_field( "about_timeline_{$i}_title", $fallback[ $i ][1], $page_id ) ); ?></h3>
				<p class="text-sm text-gray-600"><?php echo esc_html( stilco_get_page_field( "about_timeline_{$i}_text", $fallback[ $i ][2], $page_id ) ); ?></p>
			</div>
			<?php endfor; ?>
		</div>
	</div>
</section>
