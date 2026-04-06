<?php
/**
 * Front page categories section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id = get_queried_object_id();
?>
<section id="kategorie" class="py-24 max-w-7xl mx-auto px-6">
	<div class="text-center mb-16 animate-on-scroll">
		<h2 class="text-3xl md:text-5xl font-display font-bold mb-4"><?php echo esc_html( stilco_get_page_field( 'home_categories_title', 'Wybierz perfekcję', $page_id ) ); ?></h2>
		<p class="text-gray-600 max-w-2xl mx-auto"><?php echo esc_html( stilco_get_page_field( 'home_categories_lead', 'Niezależnie od tego jak śpisz, mamy technologię dopasowaną do Twojego ciała.', $page_id ) ); ?></p>
	</div>

	<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
		<?php
		$category_1_image = stilco_override_media_alt(
			stilco_get_media_image_data(
				stilco_get_page_field( 'home_category_1_image', '', $page_id ),
				stilco_get_theme_asset_uri( 'assets/images/image268.jpg' ),
				'Dla dorosłych'
			),
			stilco_get_page_field( 'home_category_1_image_alt', '', $page_id )
		);
		$category_2_image = stilco_override_media_alt(
			stilco_get_media_image_data(
				stilco_get_page_field( 'home_category_2_image', '', $page_id ),
				stilco_get_theme_asset_uri( 'assets/images/image88.jpg' ),
				'Dla Dzieci'
			),
			stilco_get_page_field( 'home_category_2_image_alt', '', $page_id )
		);
		$category_1_link = stilco_get_link_data( 'home_category_1_cta_text', 'home_category_1_cta_url', 'Odkryj →', '#', $page_id );
		$category_2_link = stilco_get_link_data( 'home_category_2_cta_text', 'home_category_2_cta_url', 'Odkryj →', '#', $page_id );
		$category_3_link = stilco_get_link_data( 'home_category_3_cta_text', 'home_category_3_cta_url', 'Poznaj wartości →', '#', $page_id );
		?>
		<div class="group cursor-pointer rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 animate-zoom border border-stilco-secondary/20">
			<div class="h-80 bg-stilco-sand relative overflow-hidden">
				<img src="<?php echo esc_url( $category_1_image['url'] ); ?>" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-700" alt="<?php echo esc_attr( $category_1_image['alt'] ); ?>">
			</div>
			<div class="p-8 bg-white text-center">
				<h3 class="text-2xl font-display font-bold mb-2 text-stilco-dark"><?php echo esc_html( stilco_get_page_field( 'home_category_1_title', 'Dla Dorosłych', $page_id ) ); ?></h3>
				<p class="text-sm text-gray-500 mb-6"><?php echo esc_html( stilco_get_page_field( 'home_category_1_text', 'Elastyczność i dopasowanie premium dla każdej twardości.', $page_id ) ); ?></p>
				<a href="<?php echo esc_url( $category_1_link['url'] ); ?>" class="text-stilco-secondary font-medium uppercase tracking-wider text-sm group-hover:text-stilco-dark transition-colors"><?php echo esc_html( $category_1_link['label'] ); ?></a>
			</div>
		</div>

		<div class="group cursor-pointer rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 animate-zoom delay-100 border border-stilco-secondary/20">
			<div class="h-80 bg-stilco-sand relative overflow-hidden">
				<img src="<?php echo esc_url( $category_2_image['url'] ); ?>" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-700" alt="<?php echo esc_attr( $category_2_image['alt'] ); ?>">
			</div>
			<div class="p-8 bg-white text-center">
				<h3 class="text-2xl font-display font-bold mb-2 text-stilco-dark"><?php echo esc_html( stilco_get_page_field( 'home_category_2_title', 'Dla Dzieci', $page_id ) ); ?></h3>
				<p class="text-sm text-gray-500 mb-6"><?php echo esc_html( stilco_get_page_field( 'home_category_2_text', 'Bezpieczne materiały dla rosnących ciał.', $page_id ) ); ?></p>
				<a href="<?php echo esc_url( $category_2_link['url'] ); ?>" class="text-stilco-secondary font-medium uppercase tracking-wider text-sm group-hover:text-stilco-dark transition-colors"><?php echo esc_html( $category_2_link['label'] ); ?></a>
			</div>
		</div>

		<div class="group cursor-pointer rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 animate-zoom delay-200 border border-stilco-secondary/20">
			<div class="h-80 bg-stilco-sand relative overflow-hidden flex items-center justify-center">
				<div class="text-center p-6 bg-stilco-light bg-opacity-90 w-full h-full flex flex-col items-center justify-center">
					<span class="block text-4xl mb-2 text-stilco-secondary"><?php echo esc_html( stilco_get_page_field( 'home_category_3_icon', '🌿', $page_id ) ); ?></span>
					<h3 class="text-xl font-display font-bold text-stilco-dark"><?php echo esc_html( stilco_get_page_field( 'home_category_3_title', 'Ekologiczne Materiały', $page_id ) ); ?></h3>
					<p class="text-sm text-gray-600 mt-2"><?php echo esc_html( stilco_get_page_field( 'home_category_3_text', 'Dbałość o naturę oznacza czystszy sen w zgodzie ze środowiskiem.', $page_id ) ); ?></p>
				</div>
			</div>
			<div class="p-8 bg-stilco-secondary text-center">
				<a href="<?php echo esc_url( $category_3_link['url'] ); ?>" class="text-white font-medium uppercase tracking-wider text-sm"><?php echo esc_html( $category_3_link['label'] ); ?></a>
			</div>
		</div>
	</div>
</section>
