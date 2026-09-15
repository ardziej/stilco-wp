<?php
/**
 * Front page categories section.
 *
 * Two tiles (FigJam #5): the mattress configurator and "Why Stilco"
 * (advantages, structure, technology on the product page). The former
 * accessories tile was removed because there are no accessories yet.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id = get_queried_object_id();

$category_1_image = stilco_override_media_alt(
	stilco_get_media_image_data(
		stilco_get_page_field( 'home_category_1_image', '', $page_id ),
		stilco_get_theme_asset_uri( 'assets/images/image268.jpg' ),
		'Materac Stilco w sypialni'
	),
	stilco_get_page_field( 'home_category_1_image_alt', '', $page_id )
);
$category_3_image = stilco_override_media_alt(
	stilco_get_media_image_data(
		stilco_get_page_field( 'home_category_3_image', '', $page_id ),
		stilco_get_theme_asset_uri( 'assets/images/dual-comfort-side.jpg' ),
		'Przekrój materaca Stilco: dwie strony, dwie pianki'
	),
	stilco_get_page_field( 'home_category_3_image_alt', '', $page_id )
);
$category_1_link = stilco_get_link_data( 'home_category_1_cta_text', 'home_category_1_cta_url', 'Przejdź do konfiguratora →', '/produkt/materac-stilco/', $page_id );
$category_3_link = stilco_get_link_data( 'home_category_3_cta_text', 'home_category_3_cta_url', 'Poznaj przewagi →', '/produkt/materac-stilco/#technologia', $page_id );

$tiles = array(
	array(
		'image' => $category_1_image,
		'title' => stilco_get_page_field( 'home_category_1_title', 'Materac Stilco', $page_id ),
		'text'  => stilco_get_page_field( 'home_category_1_text', 'Przejdź do konfiguratora i dobierz rozmiar najlepiej dopasowany do Twojego łóżka.', $page_id ),
		'link'  => $category_1_link,
		'delay' => '',
	),
	array(
		'image' => $category_3_image,
		'title' => stilco_get_page_field( 'home_category_3_title', 'Dlaczego Stilco?', $page_id ),
		'text'  => stilco_get_page_field( 'home_category_3_text', 'Przewagi naszego materaca, budowa warstw i technikalia. Zobacz, czym różnimy się od materaca z sieciówki.', $page_id ),
		'link'  => $category_3_link,
		'delay' => 'delay-100',
	),
);
?>
<section id="kategorie" class="py-24 max-w-7xl mx-auto px-6">
	<div class="text-center mb-16 animate-on-scroll">
		<h2 class="text-3xl md:text-5xl font-display font-bold mb-4"><?php echo esc_html( stilco_get_page_field( 'home_categories_title', 'Wybierz swój materac', $page_id ) ); ?></h2>
		<p class="text-gray-600 max-w-2xl mx-auto"><?php echo esc_html( stilco_get_page_field( 'home_categories_lead', 'Przejdź prosto do konfiguratora albo sprawdź, dlaczego warto wybrać Stilco.', $page_id ) ); ?></p>
	</div>

	<div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
		<?php foreach ( $tiles as $tile ) : ?>
		<a href="<?php echo esc_url( $tile['link']['url'] ); ?>" class="group block rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 animate-zoom border border-stilco-secondary/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-accent <?php echo esc_attr( $tile['delay'] ); ?>">
			<div class="h-80 bg-stilco-sand relative overflow-hidden">
				<img src="<?php echo esc_url( $tile['image']['url'] ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="<?php echo esc_attr( $tile['image']['alt'] ); ?>">
			</div>
			<div class="p-8 bg-white text-center">
				<h3 class="text-2xl font-display font-bold mb-2 text-stilco-dark"><?php echo esc_html( $tile['title'] ); ?></h3>
				<p class="text-sm text-gray-500 mb-6"><?php echo esc_html( $tile['text'] ); ?></p>
				<span class="text-stilco-secondary font-medium uppercase tracking-wider text-sm group-hover:text-stilco-dark transition-colors"><?php echo esc_html( $tile['link']['label'] ); ?></span>
			</div>
		</a>
		<?php endforeach; ?>
	</div>
</section>
