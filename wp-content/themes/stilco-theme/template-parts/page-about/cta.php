<?php
/**
 * About page CTA section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="py-24 bg-white text-center">
	<div class="max-w-3xl mx-auto px-6">
		<h2 class="text-4xl font-serif font-bold text-stilco-dark mb-8">Zacznij wysypiać się już jutro.</h2>
		<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="inline-block bg-stilco-accent text-white font-medium text-lg px-12 py-5 rounded-full shadow-lg shadow-stilco-accent/40 transform hover:scale-105 transition-all duration-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-dark">
			Poznaj nasze bestsellery
		</a>
	</div>
</section>
