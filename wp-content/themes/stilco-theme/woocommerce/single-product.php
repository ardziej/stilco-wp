<?php
/**
 * Nadpisany szablon pojedynczego produktu (Zoptymalizowany pod Konfigurator)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

<?php while ( have_posts() ) : ?>
	<?php the_post(); ?>
	<?php global $product; ?>
	<?php
	$main_image_id = $product instanceof WC_Product ? $product->get_image_id() : 0;
	$attachment_ids = $product instanceof WC_Product ? $product->get_gallery_image_ids() : array();
	$all_image_ids = stilco_get_single_product_all_image_ids( $product );
	$reviews_data = stilco_get_single_product_reviews_data( get_the_ID() );
	?>

	<div class="bg-stilco-light py-12 md:py-24">
		<div class="max-w-7xl mx-auto px-6">
			<?php
			get_template_part(
				'template-parts/woocommerce/single-product/hero',
				null,
				array(
					'product'       => $product,
					'main_image_id' => $main_image_id,
					'attachment_ids'=> $attachment_ids,
					'all_image_ids' => $all_image_ids,
				)
			);
			?>

			<?php get_template_part( 'template-parts/woocommerce/single-product/reviews', null, $reviews_data ); ?>
			<?php get_template_part( 'template-parts/woocommerce/single-product/technology', null, array( 'attachment_ids' => $attachment_ids ) ); ?>
			<?php get_template_part( 'template-parts/woocommerce/single-product/final-cta' ); ?>
            
			<?php get_template_part( 'template-parts/woocommerce/single-product/related-products' ); ?>
		</div>
	</div>

	<?php get_template_part( 'template-parts/woocommerce/single-product/assets' ); ?>
    
<?php endwhile; // end of the loop. ?>

<?php
get_footer( 'shop' );
