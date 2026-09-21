<?php
/**
 * Front page hero section.
 *
 * Layout from Figma "Stilco — sklep" → "Home — Desktop 1440 — nowy header"
 * → "01 Hero — lokalny wariant": full-bleed bright lifestyle photo, copy in
 * the left column (desktop) or bottom (mobile), two CTAs and a short note.
 * The old dark hero with the trust bar and the "scroll down" button is gone
 * (FigJam #3: the button was questioned; the new design has none).
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id    = get_queried_object_id();
$hero_image = stilco_override_media_alt(
	stilco_get_media_image_data(
		stilco_get_page_field( 'home_hero_image', '', $page_id ),
		stilco_get_theme_asset_uri( 'assets/images/hero-lifestyle.jpg' ),
		'Kobieta odpoczywająca na materacu Stilco'
	),
	stilco_get_page_field( 'home_hero_image_alt', '', $page_id )
);
$primary_cta   = stilco_get_link_data( 'home_hero_primary_cta_text', 'home_hero_primary_cta_url', 'Kup materac', '/produkt/materac-stilco/', $page_id );
$secondary_cta = stilco_get_link_data( 'home_hero_secondary_cta_text', 'home_hero_secondary_cta_url', 'Dlaczego Stilco?', '#dlaczego-my', $page_id );
$hero_title    = stilco_get_page_field( 'home_hero_title', 'Twój dobry sen zaczyna się tutaj.', $page_id );
$hero_lead     = stilco_get_page_field( 'home_hero_lead', 'Wybierz rozmiar. My uszyjemy Twój materac w Malborku.', $page_id );
$hero_note     = stilco_get_page_field( 'home_hero_note', '100 nocy na test w Twoim domu.', $page_id );
?>
<section class="relative w-full min-h-[100svh] md:min-h-[640px] md:h-[calc(100vh-0px)] md:max-h-[1000px] flex items-end md:items-center bg-stilco-sand overflow-hidden">
	<div class="absolute inset-0">
		<img src="<?php echo esc_url( $hero_image['url'] ); ?>" alt="<?php echo esc_attr( $hero_image['alt'] ); ?>" class="w-full h-full object-cover object-[70%_30%] md:object-center" fetchpriority="high">
		<div class="absolute inset-0 bg-white/35"></div>
		<div class="absolute inset-0 bg-gradient-to-t from-white/90 via-white/40 to-transparent md:bg-gradient-to-r md:from-white/85 md:via-white/30 md:to-transparent"></div>
	</div>

	<div class="relative z-10 w-full max-w-7xl mx-auto px-6 md:px-12 pb-14 pt-32 md:py-24">
		<div class="max-w-xl animate-on-scroll">
			<h1 class="font-serif font-bold text-stilco-dark text-[2.75rem] leading-[1.05] md:text-6xl lg:text-7xl md:leading-[1.05] tracking-tight mb-6 max-w-[9ch] text-balance">
				<?php echo esc_html( $hero_title ); ?>
			</h1>
			<p class="text-lg md:text-xl text-gray-700 max-w-sm mb-8 font-sans">
				<?php echo esc_html( $hero_lead ); ?>
			</p>
			<div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
				<a href="<?php echo esc_url( $primary_cta['url'] ); ?>" class="btn-primary inline-flex items-center justify-center rounded-full bg-stilco-accent px-10 py-4 text-base font-semibold text-white shadow-xl shadow-stilco-accent/30 hover:bg-stilco-dark transition-colors duration-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-dark">
					<?php echo esc_html( $primary_cta['label'] ); ?>
				</a>
				<a href="<?php echo esc_url( $secondary_cta['url'] ); ?>" class="inline-flex items-center justify-center rounded-full border-2 border-stilco-dark/70 bg-white/70 px-9 py-[0.875rem] text-base font-semibold text-stilco-dark backdrop-blur-sm hover:bg-stilco-dark hover:text-white transition-colors duration-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-accent">
					<?php echo esc_html( $secondary_cta['label'] ); ?>
				</a>
			</div>
			<?php if ( $hero_note ) : ?>
				<p class="mt-6 text-sm text-gray-700"><?php echo esc_html( $hero_note ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
