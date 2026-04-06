<?php
/**
 * About page hero section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id = get_queried_object_id();
$image   = stilco_override_media_alt(
	stilco_get_media_image_data(
		stilco_get_page_field( 'about_hero_image', '', $page_id ),
		stilco_get_theme_asset_uri( 'assets/images/hero-mattress.jpg' ),
		'Polska Szwalnia Stilco'
	),
	stilco_get_page_field( 'about_hero_image_alt', '', $page_id )
);
?>
<section class="relative w-full h-[60vh] min-h-[500px] flex items-center justify-center -mt-[88px] pt-[88px]">
	<div class="absolute inset-0 w-full h-full z-0">
		<img src="<?php echo esc_url( $image['url'] ); ?>" class="w-full h-full object-cover" alt="<?php echo esc_attr( $image['alt'] ); ?>">
		<div class="absolute inset-0 bg-stilco-dark/40"></div>
	</div>

	<div class="relative z-10 text-center px-6 max-w-4xl mx-auto animate-on-scroll">
		<h1 class="text-5xl md:text-7xl font-serif text-white font-bold mb-6 tracking-tight drop-shadow-md">
			<?php echo esc_html( get_the_title( $page_id ) ?: 'Rodzinna firma z bogatym doświadczeniem.' ); ?>
		</h1>
		<p class="text-xl md:text-2xl text-white/90 font-light drop-shadow">
			<?php echo esc_html( stilco_get_page_field( 'about_hero_lead', 'Od 1994 roku tworzymy komfort, który każdy może poczuć.', $page_id ) ); ?>
		</p>
	</div>
</section>
