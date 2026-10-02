<?php
/**
 * Front page dual comfort section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id = get_queried_object_id();
$image   = stilco_override_media_alt(
	stilco_get_media_image_data(
		stilco_get_page_field( 'home_dual_image', '', $page_id ),
		stilco_get_theme_asset_uri( 'assets/images/dual-comfort-side.jpg' ),
		'Materac Stilco od boku: biała strona White u góry, granatowa strona Blue u dołu'
	),
	stilco_get_page_field( 'home_dual_image_alt', '', $page_id )
);
// Same field the removed "Materac Stilco" category card used, so an admin-set URL keeps working.
$product_url = stilco_get_page_field( 'home_category_1_cta_url', '/produkt/materac-stilco/', $page_id );
?>
<section id="dlaczego-my" class="py-24 bg-stilco-light relative overflow-hidden">
	<div class="max-w-7xl mx-auto px-6">
		<div class="flex flex-col lg:flex-row items-center gap-16">
			<div class="w-full lg:w-1/2 animate-on-scroll">
				<span class="text-stilco-accent tracking-[0.12em] uppercase text-xs mb-4 block"><?php echo esc_html( stilco_get_page_field( 'home_dual_eyebrow', 'Jeden materac. Wiele możliwości.', $page_id ) ); ?></span>
				<h2 class="text-4xl md:text-[52px] md:leading-[1.02] font-display font-normal mb-6 text-stilco-dark"><?php echo esc_html( stilco_get_page_field( 'home_dual_title', 'Dopasowany do Twoich potrzeb.', $page_id ) ); ?></h2>
				<div class="text-stilco-dark/80">
					<p class="text-lg md:text-xl md:leading-[1.625]"><?php echo esc_html( stilco_get_page_field( 'home_dual_lead', 'Przez lata pracowaliśmy nad materacem, który spełniałby nasze oczekiwania w kwestii komfortowego odpoczynku, zdrowego ciała i dobranej mieszanki materiałów. Efektem jest materac, który dziś z dumą proponujemy Tobie. Każda z dwóch stron daje inne doświadczenia. Każda dopasowana do tego, czego potrzebujesz dla najlepszej regeneracji.', $page_id ) ); ?></p>
					<ul class="space-y-4 mt-8">
						<li class="flex items-start">
							<svg class="h-6 w-6 text-stilco-accent mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
							</svg>
							<div>
								<h3 class="font-display text-[22px] leading-[1.2] text-stilco-dark"><?php echo esc_html( stilco_get_page_field( 'home_dual_item_1_title', 'White', $page_id ) ); ?></h3>
								<p class="text-sm leading-relaxed mt-1"><?php echo esc_html( stilco_get_page_field( 'home_dual_item_1_text', 'Bazująca na piankach Visco Memory - idealnie otula ciało, redukując nacisk, bez uczucia „zapadania”. Doskonała dla osób preferujących uczucie dopasowania i bliskości.', $page_id ) ); ?></p>
							</div>
						</li>
						<li class="flex items-start">
							<svg class="h-6 w-6 text-stilco-accent mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
							</svg>
							<div>
								<h3 class="font-display text-[22px] leading-[1.2] text-stilco-dark"><?php echo esc_html( stilco_get_page_field( 'home_dual_item_2_title', 'Blue', $page_id ) ); ?></h3>
								<p class="text-sm leading-relaxed mt-1"><?php echo esc_html( stilco_get_page_field( 'home_dual_item_2_text', 'Podstawą są pianki wysokoelastyczne - zapewniają solidne podparcie kręgosłupa i większą sprężystość. Wybierana przez zwolenników snu na twardszym podłożu.', $page_id ) ); ?></p>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="w-full lg:w-1/2 animate-on-scroll">
				<a href="<?php echo esc_url( $product_url ); ?>" aria-label="Zobacz Materac Stilco" class="block relative rounded-3xl overflow-hidden aspect-square shadow-2xl focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-accent">
					<?php // Morphs into the product gallery photo on the way to the configurator (app-motion.css). ?>
					<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" class="w-full h-full object-cover vt-mattress-photo">
					<div class="absolute inset-0 bg-gradient-to-tr from-stilco-dark/30 to-transparent flex items-end p-8">
						<div class="text-white">
							<p class="font-display font-medium text-xl"><?php echo esc_html( stilco_get_page_field( 'home_dual_badge', 'Stilco Dual Comfort', $page_id ) ); ?></p>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
</section>
