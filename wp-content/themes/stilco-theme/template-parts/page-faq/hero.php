<?php
/**
 * FAQ page hero section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id = get_queried_object_id();
$image   = stilco_override_media_alt(
	stilco_get_media_image_data(
		stilco_get_page_field( 'faq_hero_image', '', $page_id ),
		'https://images.unsplash.com/photo-1541188495357-ad2ce22fa4ea?q=80&w=2070&auto=format&fit=crop',
		'Poranek w łóżku Stilco'
	),
	stilco_get_page_field( 'faq_hero_image_alt', '', $page_id )
);
?>
<section class="relative w-full h-[50vh] min-h-[400px] flex items-center justify-center -mt-[88px] pt-[88px]">
	<div class="absolute inset-0 w-full h-full z-0">
		<img src="<?php echo esc_url( $image['url'] ); ?>" class="w-full h-full object-cover grayscale-[30%]" alt="<?php echo esc_attr( $image['alt'] ); ?>">
		<div class="absolute inset-0 bg-stilco-dark/30 backdrop-blur-[2px]"></div>
	</div>

	<div class="relative z-10 text-center px-6 max-w-4xl mx-auto animate-on-scroll">
		<h1 class="text-5xl md:text-7xl font-serif text-white font-bold mb-6 tracking-tight drop-shadow-lg">
			<?php echo esc_html( get_the_title( $page_id ) ?: 'Jak możemy Ci pomóc?' ); ?>
		</h1>
		<p class="text-xl md:text-2xl text-white font-medium drop-shadow-md">
			<?php echo esc_html( stilco_get_page_field( 'faq_hero_lead', 'Zebraliśmy odpowiedzi na 99% pytań, które spędzają Ci sen z powiek.', $page_id ) ); ?>
		</p>
	</div>
</section>
