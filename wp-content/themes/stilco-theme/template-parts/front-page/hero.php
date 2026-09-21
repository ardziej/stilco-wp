<?php
/**
 * Front page hero section.
 *
 * Photo and copy come from Figma "Stilco — sklep" → "01 Hero — lokalny wariant".
 * Review comment J2 asked for a slightly shorter hero with the copy and CTA
 * centred (left-aligned copy made people scroll past the button), an eyebrow
 * line above the headline, and "Zamów materac" on the primary button.
 * J11 asked for the trust bar to come back, with three items.
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
$primary_cta   = stilco_get_link_data( 'home_hero_primary_cta_text', 'home_hero_primary_cta_url', 'Zamów materac', '/produkt/materac-stilco/', $page_id );
$secondary_cta = stilco_get_link_data( 'home_hero_secondary_cta_text', 'home_hero_secondary_cta_url', 'Dlaczego Stilco?', '#dlaczego-my', $page_id );
$hero_eyebrow  = stilco_get_page_field( 'home_hero_eyebrow', 'Manufaktura dobrego snu', $page_id );
$hero_title    = stilco_get_page_field( 'home_hero_title', 'Twój dobry sen zaczyna się tutaj.', $page_id );
$hero_lead     = stilco_get_page_field( 'home_hero_lead', 'Wybierz rozmiar. My uszyjemy Twój materac w Malborku.', $page_id );
$hero_note     = stilco_get_page_field( 'home_hero_note', '100 nocy na test w Twoim domu.', $page_id );
?>
<section class="relative w-full min-h-[92svh] md:min-h-[600px] md:h-[86vh] md:max-h-[880px] flex items-end md:items-center justify-center bg-stilco-sand overflow-hidden">
	<div class="absolute inset-0">
		<img src="<?php echo esc_url( $hero_image['url'] ); ?>" alt="<?php echo esc_attr( $hero_image['alt'] ); ?>" class="w-full h-full object-cover object-[70%_30%] md:object-center" fetchpriority="high">
		<div class="absolute inset-0 bg-white/45"></div>
		<div class="absolute inset-0 bg-gradient-to-t from-white/95 via-white/55 to-white/25"></div>
	</div>

	<div class="relative z-10 w-full max-w-3xl mx-auto px-6 pb-14 pt-32 md:py-20 text-center animate-on-scroll">
		<span class="mb-5 block text-xs md:text-sm font-semibold uppercase tracking-[0.22em] text-stilco-accent"><?php echo esc_html( $hero_eyebrow ); ?></span>
		<h1 class="font-serif font-bold text-stilco-dark text-[2.75rem] leading-[1.05] md:text-6xl lg:text-7xl md:leading-[1.05] tracking-tight mb-6 text-balance">
			<?php echo esc_html( $hero_title ); ?>
		</h1>
		<p class="mx-auto max-w-xl text-lg md:text-xl text-gray-700 mb-9 font-sans">
			<?php echo esc_html( $hero_lead ); ?>
		</p>
		<div class="flex flex-col sm:flex-row sm:items-center sm:justify-center gap-3 sm:gap-4">
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
</section>

<?php get_template_part( 'template-parts/front-page/trust-bar' ); ?>
