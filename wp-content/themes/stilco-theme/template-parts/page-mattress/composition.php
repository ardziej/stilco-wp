<?php
/**
 * Mattress composition section, shared by the mattress landing and the product page.
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
$blocks = array(
	1 => array(
		'image'   => $image_1,
		'title'   => 'Górna warstwa: 5 cm pianki Visco',
		'text'    => 'Termoelastyczna piana (tzw. "memory foam") o gęstości 45 kg/m3. Pod wpływem ciepła ciała pianka ustępuje tam, gdzie nacisk jest największy: na biodrach i barkach.',
		'bullets' => array( 1 => 'Dopasowanie 1:1 do ciała', 2 => 'Eliminacja porannych drętwień', 3 => 'Zero nacisku zwrotnego' ),
	),
	2 => array(
		'image'   => $image_2,
		'title'   => 'Fundament: 15 cm bazy HR (High Resilience)',
		'text'    => 'Otwartokomórkowa piana wysokoelastyczna (40 kg/m3). Stanowi „kręgosłup” Twojego materaca. Zapobiega zapadaniu się ciała, gwarantując przewiewność i stabilne oparcie przez całą noc.',
		'bullets' => array( 1 => 'Wysoka oddychalność anty-podgrzewcza', 2 => 'Podstawa absorbująca wstrząsy dla 2 osób', 3 => 'Odporność na wygniatanie na lata' ),
	),
);
?>
<section id="technologia" class="py-24 bg-white overflow-hidden relative scroll-mt-28">
	<div class="max-w-7xl mx-auto px-6">
		<div class="text-center mb-16 max-w-2xl mx-auto">
			<span class="text-stilco-accent font-bold uppercase tracking-[0.12em] text-xs block"><?php echo esc_html( stilco_get_page_field( 'mattress_composition_eyebrow', 'Struktura', $page_id ) ); ?></span>
			<h2 class="pt-2 text-4xl md:text-6xl lg:text-[78px] lg:leading-[1.02] font-serif text-stilco-dark font-bold"><?php echo esc_html( stilco_get_page_field( 'mattress_composition_title', 'Zajrzyj do środka materaca', $page_id ) ); ?></h2>
		</div>

		<?php foreach ( $blocks as $n => $block ) : ?>
			<?php $image_first = 2 === $n; ?>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center animate-on-scroll <?php echo 1 === $n ? 'mb-24' : ''; ?>">
				<div class="order-2 <?php echo $image_first ? '' : 'md:order-1'; ?>">
					<h3 class="text-2xl md:text-[28px] leading-[1.1] font-display font-normal text-stilco-dark mb-4"><?php echo esc_html( stilco_get_page_field( "mattress_composition_block_{$n}_title", $block['title'], $page_id ) ); ?></h3>
					<p class="text-lg md:text-xl md:leading-[1.625] text-stilco-dark/80 font-sans mb-6">
						<?php echo esc_html( stilco_get_page_field( "mattress_composition_block_{$n}_text", $block['text'], $page_id ) ); ?>
					</p>
					<ul class="space-y-3 font-medium text-stilco-dark/80">
						<?php foreach ( $block['bullets'] as $i => $bullet ) : ?>
							<li class="flex items-center gap-3"><svg class="w-5 h-5 shrink-0 text-stilco-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <?php echo esc_html( stilco_get_page_field( "mattress_composition_block_{$n}_bullet_{$i}", $bullet, $page_id ) ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="order-1 <?php echo $image_first ? '' : 'md:order-2'; ?>">
					<div class="w-full aspect-[4/3] bg-stilco-sand rounded-[3rem] overflow-hidden shadow-xl transform <?php echo $image_first ? '-rotate-1' : 'rotate-1'; ?> hover:rotate-0 transition-transform duration-700">
						<img src="<?php echo esc_url( $block['image']['url'] ); ?>" alt="<?php echo esc_attr( $block['image']['alt'] ); ?>" class="w-full h-full object-cover" loading="lazy">
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
