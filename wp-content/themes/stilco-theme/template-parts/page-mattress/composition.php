<?php
/**
 * Mattress landing composition section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id = get_queried_object_id();
$image_1 = stilco_override_media_alt(
	stilco_get_media_image_data(
		stilco_get_page_field( 'mattress_composition_block_1_image', '', $page_id ),
		stilco_get_mattress_landing_image_uri( 'image198.jpg' ),
		'Warstwa Visco'
	),
	stilco_get_page_field( 'mattress_composition_block_1_image_alt', '', $page_id )
);
$image_2 = stilco_override_media_alt(
	stilco_get_media_image_data(
		stilco_get_page_field( 'mattress_composition_block_2_image', '', $page_id ),
		stilco_get_mattress_landing_image_uri( 'image205.jpg' ),
		'Baza HR'
	),
	stilco_get_page_field( 'mattress_composition_block_2_image_alt', '', $page_id )
);
?>
<section class="py-24 bg-white overflow-hidden relative">
	<div class="absolute inset-0 bg-stilco-sand/30 transform -skew-y-2 origin-top-left -z-10"></div>
	<div class="max-w-7xl mx-auto px-6">
		<div class="text-center mb-16 max-w-2xl mx-auto">
			<span class="text-stilco-accent font-bold uppercase tracking-widest text-sm block mb-2"><?php echo esc_html( stilco_get_page_field( 'mattress_composition_eyebrow', 'Struktura', $page_id ) ); ?></span>
			<h2 class="text-4xl lg:text-5xl font-serif text-stilco-dark font-bold mb-6"><?php echo esc_html( stilco_get_page_field( 'mattress_composition_title', 'Technologia zdrowego snu. Odkryj wnętrze.', $page_id ) ); ?></h2>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-24 animate-on-scroll">
			<div class="order-2 md:order-1">
				<h3 class="text-3xl font-display font-semibold text-stilco-dark mb-4 drop-shadow-sm"><?php echo esc_html( stilco_get_page_field( 'mattress_composition_block_1_title', 'Górna warstwa: 5 cm pianki Visco', $page_id ) ); ?></h3>
				<p class="text-lg text-gray-600 font-sans leading-relaxed mb-6">
					<?php echo esc_html( stilco_get_page_field( 'mattress_composition_block_1_text', 'Termoelastyczna piana (tzw. "memory foam") o gęstości 45 kg/m3. Pod wpływem Twojego ciepła, pianka ustępuje dokładnie tam, gdzie pojawia się największy nacisk - na biodrach i barkach.', $page_id ) ); ?>
				</p>
				<ul class="space-y-3 font-medium text-gray-700">
					<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
					<li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <?php echo esc_html( stilco_get_page_field( "mattress_composition_block_1_bullet_{$i}", array( 1 => 'Idealne dopasowanie 1:1 do ciała', 2 => 'Eliminacja porannych drętwień', 3 => 'Zero nacisku zwrotnego' )[ $i ], $page_id ) ); ?></li>
					<?php endfor; ?>
				</ul>
			</div>
			<div class="order-1 md:order-2">
				<div class="w-full aspect-[4/3] rounded-[3rem] overflow-hidden shadow-xl transform rotate-1 hover:rotate-0 transition-transform duration-700">
					<img src="<?php echo esc_url( $image_1['url'] ); ?>" alt="<?php echo esc_attr( $image_1['alt'] ); ?>" class="w-full h-full object-cover">
				</div>
			</div>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center animate-on-scroll delay-100">
			<div class="order-1">
				<div class="w-full aspect-[4/3] rounded-[3rem] overflow-hidden shadow-xl transform -rotate-1 hover:rotate-0 transition-transform duration-700">
					<img src="<?php echo esc_url( $image_2['url'] ); ?>" alt="<?php echo esc_attr( $image_2['alt'] ); ?>" class="w-full h-full object-cover">
				</div>
			</div>
			<div class="order-2">
				<h3 class="text-3xl font-display font-semibold text-stilco-dark mb-4 drop-shadow-sm"><?php echo esc_html( stilco_get_page_field( 'mattress_composition_block_2_title', 'Fundament: 15 cm bazy HR (High Resilence)', $page_id ) ); ?></h3>
				<p class="text-lg text-gray-600 font-sans leading-relaxed mb-6">
					<?php echo esc_html( stilco_get_page_field( 'mattress_composition_block_2_text', 'Otwartokomorkowa piana wysokoelastyczna (40 kg/m3). Stanowi "kregoslup" Twojego materaca. Zapobiega zapadaniu sie ciala, gwarantujac przewiewnosc i stabilne oparcie przez cala noc.', $page_id ) ); ?>
				</p>
				<ul class="space-y-3 font-medium text-gray-700">
					<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
					<li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <?php echo esc_html( stilco_get_page_field( "mattress_composition_block_2_bullet_{$i}", array( 1 => 'Wysoka oddychalnosc anty-podgrzewcza', 2 => 'Podstawa absorbujaca wstrzasy dla 2 osob', 3 => 'Odpornosc na wygniatanie na lata' )[ $i ], $page_id ) ); ?></li>
					<?php endfor; ?>
				</ul>
			</div>
		</div>
	</div>
</section>
