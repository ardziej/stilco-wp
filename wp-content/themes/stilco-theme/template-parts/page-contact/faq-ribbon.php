<?php
/**
 * Contact page FAQ ribbon.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id  = get_queried_object_id();
$cta_link = stilco_get_link_data( 'contact_faq_cta_text', 'contact_faq_cta_url', 'Przejdź do Pytań i Odpowiedzi', '/faq', $page_id );
?>
<section class="bg-stilco-secondary/20 py-16 text-center border-t border-stilco-secondary/30">
	<div class="max-w-3xl mx-auto px-6">
		<h3 class="text-2xl font-serif text-stilco-dark font-semibold mb-4"><?php echo esc_html( stilco_get_page_field( 'contact_faq_title', 'Nie lubisz czekać na odpowiedź?', $page_id ) ); ?></h3>
		<p class="text-gray-600 mb-8 max-w-lg mx-auto"><?php echo esc_html( stilco_get_page_field( 'contact_faq_lead', 'Sprawdź nasze FAQ, w którym odpowiadamy wprost na 90% pytań dotyczących materaca i logistyki.', $page_id ) ); ?></p>
		<a href="<?php echo esc_url( $cta_link['url'] ); ?>" class="inline-block bg-white text-stilco-dark border border-gray-200 font-medium px-8 py-3 rounded-full hover:border-stilco-accent hover:text-stilco-accent transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent shadow-sm">
			<?php echo esc_html( $cta_link['label'] ); ?>
		</a>
	</div>
</section>
