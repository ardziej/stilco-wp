<?php
/**
 * Product page: lifestyle photo collage from the brand photo shoot.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Grid placement per photo: large (2×2), tall (1×2), then two squares.
$photos = array(
	array( 'lifestyle-reading-blanket.jpg', 'Kobieta czytająca książkę na materacu Stilco w sypialni', 'col-span-2 aspect-[4/3] lg:aspect-auto lg:row-span-2' ),
	array( 'lifestyle-reading-tall.jpg', 'Sypialnia z materacem Stilco i kobietą czytającą w łóżku', 'row-span-2 lg:row-span-2' ),
	array( 'lifestyle-reading-close.jpg', 'Wieczorne czytanie w łóżku z materacem Stilco', 'aspect-square lg:aspect-auto' ),
	array( 'lifestyle-corner-blanket.jpg', 'Narożnik materaca Stilco z metką i narzutą', 'aspect-square lg:aspect-auto' ),
);
?>
<section class="py-24 md:py-32 bg-stilco-sand" aria-labelledby="w-sypialni-title">
	<div class="max-w-7xl mx-auto px-6">
		<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
			<div class="max-w-xl">
				<span class="text-[#a84a34] font-bold uppercase tracking-[0.12em] text-xs block">Na co dzień</span>
				<h2 id="w-sypialni-title" class="pt-2 text-4xl md:text-[52px] md:leading-[1.02] font-display font-normal text-stilco-dark">Stilco w Twojej sypialni</h2>
			</div>
			<button type="button" class="js-scroll-to-top self-start md:self-auto inline-flex items-center gap-2 rounded-full border-2 border-stilco-dark px-7 py-3 font-semibold text-stilco-dark transition-colors hover:bg-stilco-dark hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-accent">
				Wybierz swój rozmiar
				<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
			</button>
		</div>

		<div class="grid grid-cols-2 lg:grid-cols-4 lg:grid-rows-2 lg:h-[640px] gap-3 md:gap-4" data-pg-group="lifestyle">
			<?php foreach ( $photos as $photo ) : ?>
				<?php $url = stilco_get_theme_asset_uri( 'assets/images/' . $photo[0] ); ?>
				<button type="button" class="group relative overflow-hidden rounded-[1.5rem] bg-white/50 cursor-zoom-in <?php echo esc_attr( $photo[2] ); ?> focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-accent"
					data-pg-src="<?php echo esc_url( $url ); ?>"
					data-pg-alt="<?php echo esc_attr( $photo[1] ); ?>"
					aria-label="<?php echo esc_attr( 'Powiększ: ' . $photo[1] ); ?>">
					<img src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $photo[1] ); ?>" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
				</button>
			<?php endforeach; ?>
		</div>
	</div>
</section>
