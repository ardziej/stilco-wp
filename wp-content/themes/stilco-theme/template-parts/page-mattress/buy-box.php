<?php
/**
 * Mattress landing buy box section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gallery_images = stilco_get_mattress_landing_gallery_images();
$size_options   = stilco_get_mattress_landing_size_options();
?>
<section class="pt-32 pb-16 lg:pt-40 lg:pb-24">
	<div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-stretch">
		<div class="relative lg:sticky lg:top-32 h-fit space-y-4 animate-slide-left" id="product-gallery">
			<div class="absolute top-4 right-4 z-10 bg-stilco-sand border border-white/50 shadow-sm text-stilco-dark text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-full hidden md:block">
				Bestseller
			</div>

			<div class="w-full aspect-square md:aspect-[4/3] rounded-[2rem] overflow-hidden bg-gray-100 shadow-sm border border-gray-100 mb-4 relative cursor-zoom-in group">
				<img id="main-product-image" src="<?php echo esc_url( stilco_get_mattress_landing_image_uri( $gallery_images[0] ) ); ?>" alt="Materac Stilco w sypialni" class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-105 mix-blend-multiply">
			</div>

			<div class="flex overflow-x-auto space-x-3 pb-4 snap-x no-scrollbar">
				<?php foreach ( $gallery_images as $index => $image ) : ?>
					<?php $active_class = 0 === $index ? 'border-stilco-accent opacity-100' : 'border-transparent opacity-70 hover:opacity-100'; ?>
					<button type="button" class="gallery-thumb flex-shrink-0 w-24 h-24 sm:w-28 sm:h-28 aspect-square rounded-2xl overflow-hidden bg-white cursor-pointer border-2 transition-all snap-center <?php echo esc_attr( $active_class ); ?>" data-image="<?php echo esc_url( stilco_get_mattress_landing_image_uri( $image ) ); ?>">
						<img src="<?php echo esc_url( stilco_get_mattress_landing_image_uri( $image ) ); ?>" class="w-full h-full object-cover" alt="Detal materaca">
					</button>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="flex flex-col justify-center animate-slide-right delay-200">
			<div class="flex items-center space-x-2 mb-4">
				<div class="flex text-stilco-accent">
					<svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
					<svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
					<svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
					<svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
					<svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
				</div>
				<span class="text-sm font-medium text-gray-500 underline decoration-gray-300 underline-offset-4 cursor-pointer hover:text-stilco-dark transition-colors">4.9/5 (Czytaj opinie)</span>
			</div>

			<h1 class="text-4xl lg:text-5xl font-serif text-stilco-dark font-bold mb-4 tracking-tight">Materac Stilco</h1>
			<p class="text-lg text-gray-600 mb-8 font-sans">Otulająca warstwa Visco i stabilna baza HR w symfonicznym duecie. Grubość 22 cm idealnego komfortu.</p>

			<div class="mb-8 border-t border-gray-200 pt-6">
				<span class="text-sm text-gray-500 font-medium tracking-wide uppercase block mb-1">Cena z dostawą</span>
				<div class="flex items-baseline space-x-3">
					<span id="price-display" class="text-4xl font-sans font-bold text-stilco-accent">2 888 zł</span>
					<span class="text-sm text-gray-400 line-through hidden" id="old-price">3 100 zł</span>
				</div>
			</div>

			<div class="mb-8">
				<div class="flex justify-between items-end mb-4">
					<span class="font-display font-semibold text-stilco-dark">Wybierz rozmiar:</span>
					<a href="#" class="text-sm text-gray-500 hover:text-stilco-accent transition-colors underline underline-offset-2">Jak zmierzyć łóżko?</a>
				</div>

				<div class="grid grid-cols-3 gap-3">
					<?php foreach ( $size_options as $size_option ) : ?>
						<?php
						$button_classes = $size_option['is_active']
							? 'border-2 border-stilco-accent bg-stilco-accent text-white py-3 px-2 rounded-full font-medium shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent transition-all'
							: 'border border-gray-300 bg-white text-stilco-dark py-3 px-2 rounded-full font-medium hover:border-stilco-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent transition-all';
						?>
						<button type="button" class="size-btn <?php echo esc_attr( $button_classes ); ?>" data-price="<?php echo esc_attr( (string) $size_option['price'] ); ?>">
							<?php echo esc_html( $size_option['label'] ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			</div>

			<button type="button" class="w-full bg-stilco-accent text-white rounded-full py-5 text-xl font-medium shadow-lg shadow-stilco-accent/30 hover:scale-[1.02] hover:bg-[#A84A34] transition-all duration-300 mb-8 flex justify-center items-center group">
				Dodaj do koszyka
				<svg class="w-6 h-6 ml-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
			</button>

			<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 pt-6 border-t border-gray-100">
				<div class="flex flex-col items-center justify-center p-6 bg-white rounded-2xl shadow-sm border border-gray-50 text-center hover:-translate-y-1 transition-transform duration-300">
					<svg class="w-10 h-10 text-stilco-accent mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
					<span class="text-sm font-bold text-stilco-dark uppercase tracking-wide leading-tight">100 nocy<br><span class="text-xs text-gray-500 font-medium">testowych</span></span>
				</div>
				<div class="flex flex-col items-center justify-center p-6 bg-white rounded-2xl shadow-sm border border-gray-50 text-center hover:-translate-y-1 transition-transform duration-300">
					<svg class="w-10 h-10 text-stilco-accent mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
					<span class="text-sm font-bold text-stilco-dark uppercase tracking-wide leading-tight">10 lat<br><span class="text-xs text-gray-500 font-medium">gwarancji</span></span>
				</div>
				<div class="flex flex-col items-center justify-center p-6 bg-white rounded-2xl shadow-sm border border-gray-50 text-center hover:-translate-y-1 transition-transform duration-300">
					<svg class="w-10 h-10 text-stilco-accent mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
					<span class="text-sm font-bold text-stilco-dark uppercase tracking-wide leading-tight">Darmowa<br><span class="text-xs text-gray-500 font-medium">Dostawa</span></span>
				</div>
				<div class="flex flex-col items-center justify-center p-6 bg-white rounded-2xl shadow-sm border border-gray-50 text-center hover:-translate-y-1 transition-transform duration-300">
					<svg class="w-10 h-10 text-stilco-accent mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
					<span class="text-sm font-bold text-stilco-dark uppercase tracking-wide leading-tight">Polska<br><span class="text-xs text-gray-500 font-medium">Produkcja</span></span>
				</div>
			</div>
		</div>
	</div>
</section>
