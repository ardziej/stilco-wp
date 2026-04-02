<?php
/**
 * Single product technology section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$attachment_ids = isset( $args['attachment_ids'] ) ? (array) $args['attachment_ids'] : array();
?>
<div class="mt-32 border-t border-gray-100 pt-32 animate-on-scroll">
	<div class="text-center max-w-3xl mx-auto mb-20">
		<h2 class="text-xs font-bold uppercase tracking-widest text-stilco-accent mb-4">Innowacja</h2>
		<h3 class="text-4xl md:text-5xl font-serif font-bold text-stilco-dark mb-6">Odkryj wnętrze materaca.</h3>
		<p class="text-xl text-gray-500 font-sans">Dwie precyzyjniej współpracujące ze sobą warstwy zapewniające The Perfect Sleep.</p>
	</div>

	<div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-24">
		<div class="order-2 md:order-1 ml-0 md:ml-12 lg:ml-24 max-w-md">
			<span class="inline-block px-3 py-1 bg-stilco-sand text-stilco-accent rounded-full text-xs font-bold tracking-widest uppercase mb-4">Warstwa 1</span>
			<h4 class="text-3xl font-display font-semibold text-stilco-dark mb-4">5 cm Pianki Visco</h4>
			<p class="text-gray-500 text-lg leading-relaxed mb-6">Górna warstwa, która dosłownie otula i zapamiętuje kształty. Pod wpływem ciepła ciała redukuje punkty napięcia zapobiegając drętwieniu kończyn.</p>
			<ul class="space-y-3 font-medium text-stilco-dark">
				<li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Termoelastyczna pamięć kształtu</li>
				<li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Zmniejszenie nacisku</li>
			</ul>
		</div>
		<div class="order-1 md:order-2">
			<?php if ( isset( $attachment_ids[0] ) ) : ?>
				<div class="rounded-l-[4rem] rounded-tr-[4rem] rounded-br-lg overflow-hidden shadow-2xl h-[400px]">
					<?php echo wp_get_attachment_image( $attachment_ids[0], 'large', false, array( 'class' => 'w-full h-full object-cover origin-bottom hover:scale-105 transition duration-700' ) ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-24">
		<div>
			<?php if ( isset( $attachment_ids[1] ) ) : ?>
				<div class="rounded-r-[4rem] rounded-tl-[4rem] rounded-bl-lg overflow-hidden shadow-2xl h-[400px]">
					<?php echo wp_get_attachment_image( $attachment_ids[1], 'large', false, array( 'class' => 'w-full h-full object-cover hover:scale-105 transition duration-700' ) ); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="mr-0 md:mr-12 lg:mr-24 max-w-md ml-auto">
			<span class="inline-block px-3 py-1 bg-stilco-sand text-stilco-accent rounded-full text-xs font-bold tracking-widest uppercase mb-4">Rdzeń materaca</span>
			<h4 class="text-3xl font-display font-semibold text-stilco-dark mb-4">15 cm Pianki HR40</h4>
			<p class="text-gray-500 text-lg leading-relaxed mb-6">Wysokoelastyczny, chłodzący fundament. Zapewnia dynamiczne podparcie cięższym partiom ciała oraz ułatwia poruszanie się w nocy. Doskonale oddycha.</p>
			<ul class="space-y-3 font-medium text-stilco-dark">
				<li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Prawidłowe ułożenie kręgosłupa</li>
				<li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Zaawansowane kanały wentylacyjne</li>
			</ul>
		</div>
	</div>
</div>
