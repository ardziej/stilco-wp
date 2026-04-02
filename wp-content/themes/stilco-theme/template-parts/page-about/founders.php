<?php
/**
 * About page founders section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="py-24 bg-white">
	<div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
		<div class="image-wrapper order-2 md:order-1 relative group animate-slide-left">
			<div class="absolute -inset-4 bg-stilco-sand rounded-[3rem] transform -rotate-3 transition-transform group-hover:rotate-0 duration-700 ease-out z-0"></div>
			<img src="<?php echo esc_url( stilco_get_theme_asset_uri( 'assets/images/edyta-daniel.jpg' ) ); ?>" alt="Daniel i Edyta Stilco" class="relative z-10 rounded-[2rem] w-full h-auto object-cover shadow-xl aspect-square md:aspect-[4/5]">
		</div>

		<div class="content-wrapper order-1 md:order-2 animate-slide-right delay-200">
			<h2 class="text-4xl md:text-5xl font-serif font-bold text-stilco-dark mb-6">Stilco to nie korporacja,<br> to my – Daniel i Edyta.</h2>
			<div class="prose prose-lg text-gray-600 font-sans leading-relaxed">
				<p>Każdy materac powielony w ofercie powstał z naszej bezsenności, i potrzeby stworzenia idealnego środowiska do regeneracji.</p>
				<p>Po latach testowania i rozczarowań rynkiem ubrań, a potem łóżek – postanowiliśmy wziąć sprawy we własne ręce, w naszej wrocławskiej szwalni. To co zaczęło się jako poszukiwanie wygody dla nas samych, szybko przerodziło się w misję dostarczenia luksusowego snu każdemu Polakowi.</p>
			</div>
		</div>
	</div>
</section>
