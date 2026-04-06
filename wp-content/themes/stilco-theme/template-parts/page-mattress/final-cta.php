<?php
/**
 * Mattress landing final CTA section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id  = get_queried_object_id();
$image    = stilco_override_media_alt(
	stilco_get_media_image_data(
		stilco_get_page_field( 'mattress_final_cta_image', '', $page_id ),
		stilco_get_mattress_landing_image_uri( 'image112.jpg' ),
		'Materac Stilco'
	),
	stilco_get_page_field( 'mattress_final_cta_image_alt', '', $page_id )
);
$cta_link = stilco_get_link_data( 'mattress_final_cta_text', 'mattress_final_cta_url', 'Zacznij wysypiać się już jutro', '#', $page_id );
?>
<section class="py-24 bg-stilco-dark text-center overflow-hidden relative">
	<div class="absolute inset-0 z-0 opacity-10 blur-xl">
		<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" class="w-full h-full object-cover">
	</div>
	<div class="max-w-4xl mx-auto px-6 relative z-10 animate-zoom">
		<h2 class="text-4xl md:text-6xl font-serif text-white font-bold mb-8 drop-shadow-lg"><?php echo esc_html( stilco_get_page_field( 'mattress_final_cta_title', '100 dni na podjęcie decyzji.', $page_id ) ); ?></h2>
		<p class="text-xl text-white/80 font-sans mb-12"><?php echo esc_html( stilco_get_page_field( 'mattress_final_cta_lead', 'Jeżeli materac nie poprawi jakości Twojego snu w ciągu 100 nocy, zwrócimy Ci pełną kwotę.', $page_id ) ); ?></p>
		<a href="<?php echo esc_url( $cta_link['url'] ); ?>" class="bg-stilco-accent text-white rounded-full px-16 py-6 text-xl font-medium shadow-xl hover:scale-105 transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-accent inline-block">
			<?php echo esc_html( $cta_link['label'] ); ?>
		</a>
	</div>
</section>
