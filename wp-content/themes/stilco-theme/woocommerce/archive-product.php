<?php
/**
 * Nadpisany Szablon Archiwum Produktów WooCommerce (Sklep)
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

<div class="bg-gray-50 py-12">
	<div class="max-w-7xl mx-auto px-6">
		<?php get_template_part( 'template-parts/woocommerce/archive/header' ); ?>

		<div class="flex flex-col lg:flex-row gap-8">
			<?php get_template_part( 'template-parts/woocommerce/archive/sidebar' ); ?>

			<main class="w-full lg:w-3/4 order-1 lg:order-2">
				<?php if ( woocommerce_product_loop() ) : ?>
					<div class="flex flex-col md:flex-row justify-between items-center mb-8 pb-4 border-b border-gray-200">
						<div class="text-sm text-gray-500 mb-4 md:mb-0">
							<?php do_action( 'woocommerce_before_shop_loop' ); ?>
						</div>
					</div>

					<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
						<?php if ( wc_get_loop_prop( 'total' ) ) : ?>
							<?php while ( have_posts() ) : ?>
								<?php
								the_post();
								global $product;
								get_template_part( 'template-parts/woocommerce/archive/product-card', null, array( 'product' => $product ) );
								?>
							<?php endwhile; ?>
						<?php endif; ?>
					</div>

					<div class="mt-12">
						<?php do_action( 'woocommerce_after_shop_loop' ); ?>
					</div>
				<?php else : ?>
					<?php do_action( 'woocommerce_no_products_found' ); ?>
				<?php endif; ?>
			</main>
		</div>
	</div>
</div>

<?php
get_footer( 'shop' );
