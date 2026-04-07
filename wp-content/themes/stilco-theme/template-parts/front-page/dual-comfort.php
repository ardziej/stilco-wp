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
		stilco_get_theme_asset_uri( 'assets/images/image112.jpg' ),
		'Dwie strony materaca Stilco w jasnym świetle'
	),
	stilco_get_page_field( 'home_dual_image_alt', '', $page_id )
);
?>
<section id="dlaczego-my" class="py-24 bg-stilco-sand relative overflow-hidden">
	<div class="max-w-7xl mx-auto px-6">
		<div class="flex flex-col lg:flex-row items-center gap-16">
			<div class="w-full lg:w-1/2 animate-slide-left">
				<span class="text-stilco-secondary font-medium tracking-widest uppercase text-sm mb-4 block"><?php echo esc_html( stilco_get_page_field( 'home_dual_eyebrow', 'Jeden materac. Wiele możliwości.', $page_id ) ); ?></span>
				<h2 class="text-3xl md:text-5xl font-display font-bold mb-6 text-stilco-dark"><?php echo esc_html( stilco_get_page_field( 'home_dual_title', 'Dopasowany do Twoich potrzeb - dla dobrego snu.', $page_id ) ); ?></h2>
				<div class="space-y-6 text-gray-600">
					<p class="text-lg"><?php echo esc_html( stilco_get_page_field( 'home_dual_lead', 'Przez lata pracowaliśmy nad materacem, który spełniałby nasze oczekiwania w kwestii komfortowego odpoczynku, zdrowego ciała i dobranej mieszanki materiałów. Efektem jest materac, który dziś z dumą proponujemy Tobie. Każda z dwóch stron daje inne doświadczenia. Każda dopasowana do tego, czego potrzebujesz dla najlepszej regeneracji.', $page_id ) ); ?></p>
					<ul class="space-y-4 mt-8">
						<li class="flex items-start">
							<svg class="h-6 w-6 text-stilco-secondary mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
							</svg>
							<div>
								<h4 class="font-bold text-stilco-dark"><?php echo esc_html( stilco_get_page_field( 'home_dual_item_1_title', 'White', $page_id ) ); ?></h4>
								<p class="text-sm mt-1"><?php echo esc_html( stilco_get_page_field( 'home_dual_item_1_text', 'Bazująca na piankach Visco Memory - idealnie otula ciało, redukując nacisk, bez uczucia „zapadania”. Doskonała dla osób preferujących uczucie dopasowania i bliskości.', $page_id ) ); ?></p>
							</div>
						</li>
						<li class="flex items-start">
							<svg class="h-6 w-6 text-stilco-secondary mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
							</svg>
							<div>
								<h4 class="font-bold text-stilco-dark"><?php echo esc_html( stilco_get_page_field( 'home_dual_item_2_title', 'Blue', $page_id ) ); ?></h4>
								<p class="text-sm mt-1"><?php echo esc_html( stilco_get_page_field( 'home_dual_item_2_text', 'Podstawą są pianki wysokoelastyczne - zapewniają solidne podparcie kręgosłupa i większą sprężystość. Wybierana przez zwolenników snu na twardszym podłożu.', $page_id ) ); ?></p>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="w-full lg:w-1/2 animate-slide-right delay-200">
				<div class="relative rounded-3xl overflow-hidden aspect-square shadow-2xl">
					<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" class="w-full h-full object-cover">
					<div class="absolute inset-0 bg-gradient-to-tr from-stilco-dark/30 to-transparent flex items-end p-8">
						<div class="text-white">
							<p class="font-display font-medium text-xl"><?php echo esc_html( stilco_get_page_field( 'home_dual_badge', 'Stilco Dual Comfort', $page_id ) ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
