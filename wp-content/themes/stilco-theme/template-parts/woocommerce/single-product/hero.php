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
$avg_rating = isset( $args['avg_rating'] ) ? (float) $args['avg_rating'] : 0.0;
$total_reviews = isset( $args['total_reviews'] ) ? (int) $args['total_reviews'] : 0;

if ( ! $product instanceof WC_Product ) {
	return;
}

$benefits = array(
	array(
		'title' => '100 nocy',
		'note'  => 'testowych',
		'icon'  => '<path d="M20 5a10.6 10.6 0 0 0 15 15A15 15 0 1 1 20 5Z"/>',
	),
	array(
		'title' => '5 lat',
		'note'  => 'gwarancji',
		'icon'  => '<path d="M33.33 21.67c0 8.33-5.83 12.5-12.76 14.91a1.67 1.67 0 0 1-1.12-.02C12.5 34.17 6.67 30 6.67 21.67V10a1.67 1.67 0 0 1 1.66-1.67c3.34 0 7.5-2 10.4-4.53a1.95 1.95 0 0 1 2.54 0c2.92 2.55 7.06 4.53 10.4 4.53A1.67 1.67 0 0 1 33.33 10Z"/><path d="m15 20 3.33 3.33L25 16.67"/>',
	),
	array(
		'title' => 'Darmowa',
		'note'  => 'dostawa',
		'icon'  => '<path d="M23.33 30V10a3.33 3.33 0 0 0-3.33-3.33H6.67A3.33 3.33 0 0 0 3.33 10v18.33A1.67 1.67 0 0 0 5 30h3.33M25 30H15M31.67 30H35a1.67 1.67 0 0 0 1.67-1.67v-6.08a1.67 1.67 0 0 0-.37-1.04l-5.8-7.25a1.67 1.67 0 0 0-1.3-.63h-5.87"/><circle cx="28.33" cy="30" r="3.33"/><circle cx="11.67" cy="30" r="3.33"/>',
	),
	array(
		'title' => 'Polska',
		'note'  => 'produkcja',
		'icon'  => '<path d="M25 35V21.67A1.67 1.67 0 0 0 23.33 20h-6.66A1.67 1.67 0 0 0 15 21.67V35"/><path d="M5 16.67a3.33 3.33 0 0 1 1.18-2.55L17.85 4.12a3.33 3.33 0 0 1 4.3 0l11.67 10a3.33 3.33 0 0 1 1.18 2.55v15A3.33 3.33 0 0 1 31.67 35H8.33A3.33 3.33 0 0 1 5 31.67Z"/>',
	),
);
?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start mb-24">
	<?php
	// Desktop: collage of the first five photos, the fifth showing how many more there are.
	// Phone: swipeable carousel of all of them. Every photo opens the lightbox.
	$gallery_ids = $all_image_ids ? $all_image_ids : ( $main_image_id ? array( $main_image_id ) : array() );
	$gallery_total = count( $gallery_ids );
	$collage_size  = 5;
	$hidden_count  = max( 0, $gallery_total - $collage_size );
	?>
	<div class="product-gallery relative animate-slide-left" data-pg-group="product">
		<?php if ( $gallery_ids ) : ?>
			<div class="pg-track" data-pg-track>
				<?php foreach ( $gallery_ids as $index => $image_id ) : ?>
					<?php
					$alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
					$alt = $alt ? $alt : get_the_title();
					?>
					<button type="button" class="pg-item<?php echo $index >= $collage_size ? ' pg-item--extra' : ''; ?>"
						data-pg-src="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'full' ) ); ?>"
						data-pg-thumb="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'thumbnail' ) ); ?>"
						data-pg-alt="<?php echo esc_attr( $alt ); ?>"
						aria-label="<?php echo esc_attr( sprintf( 'Powiększ zdjęcie %d z %d', $index + 1, $gallery_total ) ); ?>">
						<?php
						echo wp_get_attachment_image(
							$image_id,
							0 === $index ? 'large' : 'woocommerce_single',
							false,
							array(
								'class'   => 'pg-item__img',
								'alt'     => $alt,
								'loading' => 0 === $index ? 'eager' : 'lazy',
								'sizes'   => 0 === $index ? '(min-width: 1024px) 576px, 100vw' : '(min-width: 1024px) 282px, 100vw',
							)
						);
						?>
						<?php if ( $collage_size - 1 === $index && $hidden_count > 0 ) : ?>
							<span class="pg-item__more" aria-hidden="true">
								+<?php echo esc_html( (string) $hidden_count ); ?>
								<?php echo esc_html( 1 === $hidden_count ? 'zdjęcie' : ( $hidden_count < 5 ? 'zdjęcia' : 'zdjęć' ) ); ?>
							</span>
						<?php endif; ?>
					</button>
				<?php endforeach; ?>
			</div>

			<span class="pg-badge">Bestseller</span>

			<span class="pg-hint" aria-hidden="true">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
				<span class="pg-hint__desktop">Kliknij, aby powiększyć</span>
				<span class="pg-hint__mobile" data-pg-counter-inline>1 / <?php echo esc_html( (string) $gallery_total ); ?></span>
			</span>

			<?php if ( $gallery_total > 1 ) : ?>
				<div class="pg-dots" aria-hidden="true">
					<?php for ( $dot = 0; $dot < $gallery_total; $dot++ ) : ?>
						<span class="pg-dot<?php echo 0 === $dot ? ' is-active' : ''; ?>"></span>
					<?php endfor; ?>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<img src="<?php echo esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ); ?>" alt="" class="w-full aspect-square rounded-[2rem] object-cover" />
		<?php endif; ?>
	</div>

	<div class="product-configurator w-full animate-zoom delay-200">
		<?php if ( $total_reviews > 0 ) : ?>
			<div class="flex items-center gap-2 pb-4">
				<div class="flex text-stilco-accent" aria-hidden="true">
					<?php for ( $star = 1; $star <= 5; $star++ ) : ?>
						<svg class="w-5 h-5 <?php echo $star <= round( $avg_rating ) ? '' : 'opacity-25'; ?>" viewBox="0 0 20 20" fill="currentColor" stroke="currentColor" stroke-width="1.67" stroke-linejoin="round"><path d="M9.6 1.91a.45.45 0 0 1 .8 0l1.92 3.9a2.3 2.3 0 0 0 1.33 1l4.3.6a.45.45 0 0 1 .25.76l-3.11 3.03a2.3 2.3 0 0 0-.51 1.57l.73 4.28a.45.45 0 0 1-.64.47l-3.85-2.02a2.3 2.3 0 0 0-1.64 0l-3.85 2.02a.45.45 0 0 1-.64-.47l.74-4.28a2.3 2.3 0 0 0-.51-1.57L1.8 8.16a.45.45 0 0 1 .25-.76l4.3-.63a2.3 2.3 0 0 0 1.33-.96Z"/></svg>
					<?php endfor; ?>
				</div>
				<a href="#reviews" class="text-sm font-medium text-stilco-dark/70 underline underline-offset-2 hover:text-stilco-accent transition-colors">
					<?php
					/* translators: %s: average rating, e.g. 4.3 */
					printf( esc_html__( '%s/5 (Czytaj opinie)', 'stilco' ), esc_html( number_format_i18n( $avg_rating, 1 ) ) );
					?>
				</a>
			</div>
		<?php endif; ?>

		<h1 class="text-5xl md:text-6xl lg:text-[78px] font-serif font-bold text-stilco-dark pb-4 leading-[1.02]">
			<?php the_title(); ?>
		</h1>

		<div class="text-lg lg:text-xl text-stilco-dark/80 leading-relaxed lg:leading-[1.625] pb-8 [&_p]:m-0">
			<?php the_excerpt(); ?>
		</div>

		<div class="border-t border-stilco-dark/10 pt-6 pb-8">
			<p class="text-sm font-medium uppercase tracking-[0.025em] text-stilco-dark/70">Cena z dostawą</p>
			<div class="mt-1 flex items-baseline gap-4">
				<span class="price-display text-3xl lg:text-4xl font-bold text-stilco-accent leading-10"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
				<?php if ( $product->is_on_sale() ) : ?>
					<span class="bg-stilco-accent/10 text-stilco-accent text-xs px-3 py-1 rounded-full font-bold uppercase tracking-widest">Promocja</span>
				<?php endif; ?>
			</div>
		</div>

		<div class="pb-8">
			<h2 class="font-display font-semibold text-base text-stilco-dark">Wymiar materaca</h2>
			<div class="woo-custom-variations-form">
				<?php woocommerce_template_single_add_to_cart(); ?>
			</div>
			<?php get_template_part( 'template-parts/woocommerce/single-product/custom-size' ); ?>
		</div>

		<ul class="grid grid-cols-2 sm:grid-cols-4 gap-4 border-t border-stilco-dark/10 pt-6">
			<?php foreach ( $benefits as $benefit ) : ?>
				<li class="flex flex-col items-center justify-center text-center bg-white border border-stilco-dark/5 rounded-2xl shadow-sm p-6">
					<svg class="w-10 h-10 mb-3 text-stilco-accent" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="3.33" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?php echo $benefit['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG markup. ?></svg>
					<span class="text-sm font-bold uppercase tracking-[0.025em] text-stilco-dark leading-snug"><?php echo esc_html( $benefit['title'] ); ?></span>
					<span class="text-xs font-medium uppercase tracking-[0.025em] text-stilco-dark/70"><?php echo esc_html( $benefit['note'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
