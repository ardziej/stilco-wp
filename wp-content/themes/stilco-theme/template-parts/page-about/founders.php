<?php
/**
 * About page founders section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id = get_queried_object_id();
$image   = stilco_override_media_alt(
	stilco_get_media_image_data(
		stilco_get_page_field( 'about_founders_image', '', $page_id ),
		stilco_get_theme_asset_uri( 'assets/images/edyta-daniel.jpg' ),
		'Daniel i Edyta Stilco'
	),
	stilco_get_page_field( 'about_founders_image_alt', '', $page_id )
);
?>
<section class="py-24 bg-white">
	<div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
		<div class="image-wrapper order-2 md:order-1 relative group animate-slide-left">
			<div class="absolute -inset-4 bg-stilco-sand rounded-[3rem] transform -rotate-3 transition-transform group-hover:rotate-0 duration-700 ease-out z-0"></div>
			<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" class="relative z-10 rounded-[2rem] w-full h-auto object-cover shadow-xl aspect-square md:aspect-[4/5]">
		</div>

		<div class="content-wrapper order-1 md:order-2 animate-slide-right delay-200">
			<h2 class="text-4xl md:text-5xl font-serif font-bold text-stilco-dark mb-6"><?php echo wp_kses( stilco_get_page_field( 'about_founders_title', 'Stilco to nie korporacja,<br> to my – Daniel i Edyta.', $page_id ), array( 'br' => array() ) ); ?></h2>
			<div class="prose prose-lg text-gray-600 font-sans leading-relaxed">
				<?php
				echo wp_kses_post(
					stilco_get_page_field(
						'about_founders_body',
						'<p>Każdy materac z naszej oferty powstał z własnej bezsenności i potrzeby stworzenia dobrego miejsca do regeneracji.</p><p>Po latach testowania i rozczarowań rynkiem ubrań, a potem łóżek, wzięliśmy sprawy we własne ręce w naszej malborskiej szwalni. To, co zaczęło się od szukania wygody dla nas samych, przerodziło się w firmę, która szyje materace dla całej Polski.</p>',
						$page_id
					)
				);
				?>
			</div>
		</div>
	</div>
</section>
