<?php
/**
 * Single product hero section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product = isset( $args['product'] ) ? $args['product'] : null;
$main_image_id = isset( $args['main_image_id'] ) ? (int) $args['main_image_id'] : 0;
$attachment_ids = isset( $args['attachment_ids'] ) ? (array) $args['attachment_ids'] : array();
$all_image_ids = isset( $args['all_image_ids'] ) ? (array) $args['all_image_ids'] : array();

if ( ! $product instanceof WC_Product ) {
	return;
}
?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start mb-24">
	<div class="product-gallery sticky top-28 animate-slide-left relative">
		<div class="absolute -top-4 -right-4 md:-right-6 z-10 bg-stilco-sand border border-white/60 shadow-md text-stilco-dark text-xs font-bold uppercase tracking-widest px-4 md:px-6 py-2 md:py-3 rounded-full transform rotate-3">
			Bestseller
		</div>

		<div id="product-main-image-container" class="main-image bg-white rounded-[2rem] overflow-hidden shadow-lg mb-6 border border-gray-100 group relative cursor-zoom-in">
			<?php if ( $main_image_id ) : ?>
				<?php
				$full_img_url = wp_get_attachment_image_url( $main_image_id, 'full' );
				echo wp_get_attachment_image(
					$main_image_id,
					'woocommerce_single',
					false,
					array(
						'id'              => 'product-main-image',
						'class'           => 'w-full h-[50vh] md:h-[60vh] object-cover transition-transform duration-300',
						'data-full-image' => $full_img_url,
						'data-index'      => '0',
					)
				);
				?>
				<div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition duration-300 pointer-events-none flex items-center justify-center">
					<span class="bg-white/80 text-stilco-dark backdrop-blur-sm px-4 py-2 rounded-full opacity-0 group-hover:opacity-100 transition duration-300 transform translate-y-4 group-hover:translate-y-0 text-sm font-bold shadow-sm flex items-center shadow-lg"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>Powiększ</span>
				</div>
			<?php else : ?>
				<img id="product-main-image" src="<?php echo esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ); ?>" alt="Brak zdjęcia" class="w-full h-[60vh] object-cover" />
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $all_image_ids ) && count( $all_image_ids ) > 1 ) : ?>
			<div class="thumbnails flex gap-4 md:gap-6 overflow-x-auto pb-4 scrollbar-hide snap-x">
				<?php foreach ( $all_image_ids as $index => $image_id ) : ?>
					<?php
					$single_img_url = wp_get_attachment_image_url( $image_id, 'woocommerce_single' );
					$single_img_srcset = wp_get_attachment_image_srcset( $image_id, 'woocommerce_single' );
					$full_img_url = wp_get_attachment_image_url( $image_id, 'full' );
					?>
					<div class="thumbnail-item flex-none w-24 md:w-32 snap-start bg-gray-100 rounded-2xl overflow-hidden shadow-sm cursor-pointer border-2 <?php echo 0 === $index ? 'border-stilco-accent' : 'border-transparent'; ?> hover:border-stilco-accent transition-all duration-300 relative group aspect-square"
						data-single-url="<?php echo esc_attr( $single_img_url ); ?>"
						data-single-srcset="<?php echo esc_attr( $single_img_srcset ? $single_img_srcset : '' ); ?>"
						data-full-url="<?php echo esc_attr( $full_img_url ); ?>"
						data-index="<?php echo esc_attr( (string) $index ); ?>">
						<?php echo wp_get_attachment_image( $image_id, 'thumbnail', false, array( 'class' => 'w-full h-full object-cover transition duration-500 group-hover:scale-110 pointer-events-none' ) ); ?>
						<div class="absolute inset-0 bg-stilco-dark/0 group-hover:bg-stilco-dark/10 transition duration-300 pointer-events-none"></div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="product-configurator w-full animate-zoom delay-200">
		<h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-stilco-dark mb-4 tracking-tight leading-tight">
			<?php the_title(); ?>
		</h1>

		<div class="flex items-center space-x-3 mb-8 pb-8 border-b border-gray-100">
			<div class="flex text-stilco-accent text-lg">
				★★★★★
			</div>
			<span class="text-sm font-bold text-stilco-dark">4.9/5 (128 sprawdzonych opinii)</span>
			<a href="#reviews" class="text-sm text-gray-400 hover:text-stilco-accent underline underline-offset-4 decoration-dotted transition-colors">
				(Zobacz oceny)
			</a>
		</div>

		<div class="mb-10">
			<div class="text-3xl lg:text-4xl font-sans text-stilco-dark font-semibold mb-4 flex items-baseline space-x-4">
				<span class="price-display text-stilco-accent tracking-tighter"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
				<?php if ( $product->is_on_sale() ) : ?>
					<span class="bg-stilco-accent/10 text-stilco-accent text-xs px-3 py-1 rounded-full font-bold uppercase tracking-widest">Promocja</span>
				<?php endif; ?>
			</div>
			<div class="prose text-gray-500 font-sans leading-relaxed text-lg mb-8">
				<?php the_excerpt(); ?>
			</div>
		</div>

		<div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 mb-10 relative overflow-hidden">
			<div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-stilco-accent/5 to-transparent rounded-bl-full -z-0"></div>
			<div class="relative z-10">
				<h3 class="font-display font-semibold text-xl text-stilco-dark mb-6">Wymiar materaca</h3>
				<div class="woo-custom-variations-form">
					<?php woocommerce_template_single_add_to_cart(); ?>
				</div>
			</div>
		</div>

		<div class="bg-stilco-secondary/10 rounded-3xl p-6 border border-stilco-secondary/20 mt-8 mb-8">
			<div class="grid grid-cols-1 gap-6">
				<div class="flex items-center space-x-4">
					<div class="bg-white p-3 rounded-2xl text-stilco-accent shadow-sm shrink-0">
						<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
					</div>
					<div>
						<h4 class="font-bold text-stilco-dark text-sm mb-1">Darmowa dostawa i zwrot</h4>
						<p class="text-xs text-gray-500">Bezproblemowa logistyka wprost pod Twoje drzwi.</p>
					</div>
				</div>
				<div class="flex items-center space-x-4">
					<div class="bg-white p-3 rounded-2xl text-stilco-accent shadow-sm shrink-0">
						<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
					</div>
					<div>
						<h4 class="font-bold text-stilco-dark text-sm mb-1">100 dni próbnych nocy</h4>
						<p class="text-xs text-gray-500">Przetestuj w swoim domu bez zbędnego stresu.</p>
					</div>
				</div>
				<div class="flex items-center space-x-4">
					<div class="bg-white p-3 rounded-2xl text-stilco-accent shadow-sm shrink-0">
						<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
					</div>
					<div>
						<h4 class="font-bold text-stilco-dark text-sm mb-1">Do 15 lat gwarancji</h4>
						<p class="text-xs text-gray-500">Tworzymy materace, które ułatwią Ci życie na lata.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
