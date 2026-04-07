<?php
/**
 * Front page layers section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id = get_queried_object_id();
?>
<section class="py-24 bg-white border-t border-gray-100">
	<div class="max-w-7xl mx-auto px-6 text-center animate-on-scroll">
		<h2 class="text-3xl md:text-5xl font-display font-bold mb-4 text-stilco-dark"><?php echo esc_html( stilco_get_page_field( 'home_layers_title', 'Zajrzyj do środka', $page_id ) ); ?></h2>
		<p class="text-gray-600 max-w-2xl mx-auto mb-16 text-lg"><?php echo esc_html( stilco_get_page_field( 'home_layers_lead', 'Kompletna konstrukcja i mieszanka najwyższej jakości materiałów. Zaprojektowane i wyprodukowane w Polsce. Z myślą o Twoim komforcie snu.', $page_id ) ); ?></p>

		<div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-left">
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<?php
				$fallbacks = array(
					1 => array( 'assets/images/image179.jpg', 'Oddychający pokrowiec Stilco', 'Oddychający pokrowiec', 'Przewiewna, antyalergiczna tkanina z zamkiem rozdzielczym, która ułatwia dbanie o czystość i codzienny komfort snu.' ),
					2 => array( 'assets/images/image198.jpg', 'Pianka Visco Memory w świetle', 'Termoelastyczna bliskość', 'Niezwykle miękka warstwa Visco idealnie otulająca i dająca ukojenie mięśniom.' ),
					3 => array( 'assets/images/image205.jpg', 'Pianka wysokoelastyczna', 'Wsparcie i trwałość', 'Rdzeń z pianki HR dba o zachowanie naturalnych krzywizn kręgosłupa i sprawia, że materac to Twoja inwestycja w dobry sen przez wiele lat.' ),
				);
				$delays    = array( 1 => 'delay-100', 2 => 'delay-300', 3 => 'delay-500' );
				$fallback  = $fallbacks[ $i ];
				$image     = stilco_override_media_alt(
					stilco_get_media_image_data(
						stilco_get_page_field( "home_layer_{$i}_image", '', $page_id ),
						stilco_get_theme_asset_uri( $fallback[0] ),
						$fallback[1]
					),
					stilco_get_page_field( "home_layer_{$i}_image_alt", '', $page_id )
				);
				?>
			<div class="group animate-on-scroll <?php echo esc_attr( $delays[ $i ] ); ?>">
				<div class="bg-gray-50 h-64 rounded-3xl mb-6 relative overflow-hidden">
					<img src="<?php echo esc_url( $image['url'] ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="<?php echo esc_attr( $image['alt'] ); ?>">
				</div>
				<h3 class="text-xl font-bold font-display text-stilco-dark mb-2"><?php echo esc_html( stilco_get_page_field( "home_layer_{$i}_title", $fallback[2], $page_id ) ); ?></h3>
				<p class="text-gray-600 text-sm"><?php echo esc_html( stilco_get_page_field( "home_layer_{$i}_text", $fallback[3], $page_id ) ); ?></p>
			</div>
			<?php endfor; ?>
		</div>
	</div>
</section>
