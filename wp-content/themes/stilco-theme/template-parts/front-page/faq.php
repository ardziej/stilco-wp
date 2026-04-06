<?php
/**
 * Front page FAQ section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id   = get_queried_object_id();
$faq_items = stilco_get_front_page_faq_items();
?>
<section class="py-24 bg-stilco-light border-b border-gray-200" id="faq">
	<div class="max-w-3xl mx-auto px-6">
		<div class="text-center mb-16 animate-on-scroll">
			<h2 class="text-3xl md:text-5xl font-display font-bold mb-4 text-stilco-dark"><?php echo esc_html( stilco_get_page_field( 'home_faq_title', 'Masz pytania?', $page_id ) ); ?></h2>
			<p class="text-gray-600"><?php echo esc_html( stilco_get_page_field( 'home_faq_lead', 'Oto odpowiedzi na najczęściej zadawane pytania, by rozwiać wszelkie wątpliwości przed zakupem.', $page_id ) ); ?></p>
		</div>

		<div class="space-y-4 animate-on-scroll" id="faq-accordion">
			<?php foreach ( $faq_items as $faq_item ) : ?>
			<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden faq-item cursor-pointer text-left">
				<button type="button" class="faq-btn w-full px-6 py-5 flex justify-between items-center select-none focus:outline-none text-left" aria-expanded="false">
					<span class="font-display font-semibold text-stilco-dark text-lg"><?php echo esc_html( $faq_item['question'] ); ?></span>
					<svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 faq-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
					</svg>
				</button>
				<div class="faq-content overflow-hidden transition-all duration-300 max-h-0 text-gray-600 text-sm">
					<div class="faq-content-inner px-6 pb-5">
						<?php echo wp_kses_post( wpautop( $faq_item['answer'] ) ); ?>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
