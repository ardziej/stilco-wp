<?php
/**
 * Contact page hero section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id = get_queried_object_id();
$image   = stilco_override_media_alt(
	stilco_get_media_image_data(
		stilco_get_page_field( 'contact_hero_image', '', $page_id ),
		'https://upload.wikimedia.org/wikipedia/commons/e/e6/Zesp%C3%B3%C5%82_Zamku_Krzy%C5%BCackiego_MALBORK_01.jpg',
		'Zamek Krzyżacki w Malborku'
	),
	stilco_get_page_field( 'contact_hero_image_alt', '', $page_id )
);
?>
<section class="relative w-full h-[40vh] min-h-[300px] flex items-center justify-center -mt-[88px] pt-[88px]">
	<div class="absolute inset-0 w-full h-full z-0">
		<img src="<?php echo esc_url( $image['url'] ); ?>" class="w-full h-full object-cover" alt="<?php echo esc_attr( $image['alt'] ); ?>">
		<div class="absolute inset-0 bg-stilco-dark/60"></div>
	</div>

	<div class="relative z-10 text-center px-6 max-w-3xl mx-auto animate-on-scroll">
		<h1 class="text-5xl md:text-6xl font-serif text-white font-bold mb-4 tracking-tight drop-shadow-md">
			<?php echo esc_html( get_the_title( $page_id ) ?: 'Porozmawiajmy o dobrym śnie' ); ?>
		</h1>
	</div>
</section>
