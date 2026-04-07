<?php
/**
 * Front page B2B section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id  = get_queried_object_id();
$cta_link = stilco_get_link_data( 'home_b2b_cta_text', 'home_b2b_cta_url', 'Poznaj ofertę B2B', '/kontakt?context=b2b#formularz-b2b', $page_id );
?>
<section class="py-24 bg-stilco-sand text-stilco-dark relative">
	<div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
		<div class="animate-slide-left">
			<span class="text-stilco-accent font-medium uppercase tracking-widest text-sm mb-4 block"><?php echo esc_html( stilco_get_page_field( 'home_b2b_eyebrow', '#DlaBiznesu', $page_id ) ); ?></span>
			<h2 class="text-3xl md:text-5xl font-display font-bold mb-6"><?php echo esc_html( stilco_get_page_field( 'home_b2b_title', 'Z myślą o profesjonalistach, dbających o komfort i dobry sen swoich gości i klientów.', $page_id ) ); ?></h2>
			<p class="text-gray-700 text-lg mb-8 max-w-lg"><?php echo esc_html( stilco_get_page_field( 'home_b2b_lead', 'Niezależnie od tego, czy projektujesz wnętrza, wykańczasz mieszkania pod klucz czy zaopatrujesz hotele - znajdziemy dla Ciebie idealne rozwiązanie. Atrakcyjne modele rozliczeń, doradztwo, elastyczność dostaw na terenie całej Europy. A to wszystko skoncentrowane na doświadczeniach Klienta.', $page_id ) ); ?></p>
			<a href="<?php echo esc_url( $cta_link['url'] ); ?>" class="btn-primary bg-stilco-secondary text-white hover:bg-stilco-dark inline-block px-8 py-4 rounded-full font-medium transition-colors"><?php echo esc_html( $cta_link['label'] ); ?></a>
		</div>
		<div class="grid grid-cols-2 gap-4 animate-slide-right delay-200">
			<div class="bg-white rounded-3xl p-8 border border-white/50 shadow-sm text-center flex flex-col items-center justify-center">
				<span class="block text-4xl md:text-5xl font-bold text-stilco-accent mb-2"><?php echo esc_html( stilco_get_page_field( 'home_b2b_stat_1_value', '20+', $page_id ) ); ?></span>
				<span class="text-sm text-gray-500 font-medium tracking-wide uppercase"><?php echo esc_html( stilco_get_page_field( 'home_b2b_stat_1_label', 'Lat doświadczenia', $page_id ) ); ?></span>
			</div>
			<div class="bg-white rounded-3xl p-8 border border-white/50 shadow-sm text-center flex flex-col items-center justify-center">
				<span class="block text-4xl md:text-5xl font-bold text-stilco-accent mb-2"><?php echo esc_html( stilco_get_page_field( 'home_b2b_stat_2_value', '🌍', $page_id ) ); ?></span>
				<span class="text-sm text-gray-500 font-medium tracking-wide uppercase"><?php echo esc_html( stilco_get_page_field( 'home_b2b_stat_2_label', 'Działamy w całej Europie', $page_id ) ); ?></span>
			</div>
		</div>
	</div>
</section>
