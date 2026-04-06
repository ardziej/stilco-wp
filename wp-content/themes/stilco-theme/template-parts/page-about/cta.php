<?php
/**
 * About page CTA section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id  = get_queried_object_id();
$cta_link = stilco_get_link_data( 'about_cta_text', 'about_cta_url', 'Poznaj nasze bestsellery', wc_get_page_permalink( 'shop' ), $page_id );
?>
<section class="py-24 bg-white text-center">
	<div class="max-w-3xl mx-auto px-6">
		<h2 class="text-4xl font-serif font-bold text-stilco-dark mb-8"><?php echo esc_html( stilco_get_page_field( 'about_cta_title', 'Zacznij wysypiać się już jutro.', $page_id ) ); ?></h2>
		<a href="<?php echo esc_url( $cta_link['url'] ); ?>" class="inline-block bg-stilco-accent text-white font-medium text-lg px-12 py-5 rounded-full shadow-lg shadow-stilco-accent/40 transform hover:scale-105 transition-all duration-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-dark">
			<?php echo esc_html( $cta_link['label'] ); ?>
		</a>
	</div>
</section>
