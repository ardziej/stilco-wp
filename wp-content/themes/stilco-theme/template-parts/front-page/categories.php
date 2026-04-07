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
		<h2 class="text-3xl md:text-5xl font-display font-bold mb-4"><?php echo esc_html( stilco_get_page_field( 'home_categories_title', 'Wybierz swój materac', $page_id ) ); ?></h2>
		<p class="text-gray-600 max-w-2xl mx-auto"><?php echo esc_html( stilco_get_page_field( 'home_categories_lead', 'Przejdź prosto do najważniejszych miejsc i wybierz to, czego potrzebujesz: konfigurator, akcesoria albo więcej informacji o materiałach.', $page_id ) ); ?></p>
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
		$category_1_link = stilco_get_link_data( 'home_category_1_cta_text', 'home_category_1_cta_url', 'Przejdź do konfiguratora →', '/produkt/materac-stilco/', $page_id );
		$category_2_link = stilco_get_link_data( 'home_category_2_cta_text', 'home_category_2_cta_url', 'Zobacz akcesoria →', '/akcesoria', $page_id );
		$category_3_link = stilco_get_link_data( 'home_category_3_cta_text', 'home_category_3_cta_url', 'Poznaj temat →', '/ekologiczne-materialy', $page_id );
		?>
		<div class="group cursor-pointer rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 animate-zoom border border-stilco-secondary/20">
			<div class="h-80 bg-stilco-sand relative overflow-hidden">
				<img src="<?php echo esc_url( $category_1_image['url'] ); ?>" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-700" alt="<?php echo esc_attr( $category_1_image['alt'] ); ?>">
			</div>
			<div class="p-8 bg-white text-center">
				<h3 class="text-2xl font-display font-bold mb-2 text-stilco-dark"><?php echo esc_html( stilco_get_page_field( 'home_category_1_title', 'Materace', $page_id ) ); ?></h3>
				<p class="text-sm text-gray-500 mb-6"><?php echo esc_html( stilco_get_page_field( 'home_category_1_text', 'Przejdź do konfiguratora i dobierz rozmiar oraz wariant najlepiej dopasowany do Twojego snu.', $page_id ) ); ?></p>
				<a href="<?php echo esc_url( $category_1_link['url'] ); ?>" class="text-stilco-secondary font-medium uppercase tracking-wider text-sm group-hover:text-stilco-dark transition-colors"><?php echo esc_html( $category_1_link['label'] ); ?></a>
			</div>
		</div>

		<div class="group cursor-pointer rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 animate-zoom delay-100 border border-stilco-secondary/20">
			<div class="h-80 bg-stilco-sand relative overflow-hidden">
				<img src="<?php echo esc_url( $category_2_image['url'] ); ?>" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-700" alt="<?php echo esc_attr( $category_2_image['alt'] ); ?>">
			</div>
			<div class="p-8 bg-white text-center">
				<h3 class="text-2xl font-display font-bold mb-2 text-stilco-dark"><?php echo esc_html( stilco_get_page_field( 'home_category_2_title', 'Akcesoria', $page_id ) ); ?></h3>
				<p class="text-sm text-gray-500 mb-6"><?php echo esc_html( stilco_get_page_field( 'home_category_2_text', 'Uzupelnij strefe snu o dodatki, ktore wspieraja wygode, higiene i codzienne uzytkowanie.', $page_id ) ); ?></p>
				<a href="<?php echo esc_url( $category_2_link['url'] ); ?>" class="text-stilco-secondary font-medium uppercase tracking-wider text-sm group-hover:text-stilco-dark transition-colors"><?php echo esc_html( $category_2_link['label'] ); ?></a>
			</div>
		</div>

		<div class="group cursor-pointer rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 animate-zoom delay-200 border border-stilco-secondary/20">
			<div class="h-80 bg-stilco-sand relative overflow-hidden flex items-center justify-center">
				<div class="text-center p-6 bg-stilco-light bg-opacity-90 w-full h-full flex flex-col items-center justify-center">
					<span class="block text-4xl mb-2 text-stilco-secondary"><?php echo esc_html( stilco_get_page_field( 'home_category_3_icon', '🌿', $page_id ) ); ?></span>
					<h3 class="text-xl font-display font-bold text-stilco-dark"><?php echo esc_html( stilco_get_page_field( 'home_category_3_title', 'Ekologiczne materiały', $page_id ) ); ?></h3>
					<p class="text-sm text-gray-600 mt-2"><?php echo esc_html( stilco_get_page_field( 'home_category_3_text', 'Tworzymy osobną podstronę o materiałach i odpowiedzialnej produkcji - tutaj poprowadzimy dalej, gdy content będzie gotowy.', $page_id ) ); ?></p>
				</div>
			</div>
			<div class="p-8 bg-stilco-secondary text-center">
				<a href="<?php echo esc_url( $category_3_link['url'] ); ?>" class="text-white font-medium uppercase tracking-wider text-sm"><?php echo esc_html( $category_3_link['label'] ); ?></a>
			</div>
		</div>
	</div>
</section>
