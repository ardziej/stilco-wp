<?php
/**
 * Single product page helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get all gallery image IDs for the single product hero.
 *
 * @param WC_Product $product Product object.
 * @return array<int, int>
 */
function stilco_get_single_product_all_image_ids( $product ) {
	if ( ! $product instanceof WC_Product ) {
		return array();
	}

	$main_image_id = $product->get_image_id();
	$attachment_ids = $product->get_gallery_image_ids();
	$all_image_ids = array();

	if ( $main_image_id ) {
		$all_image_ids[] = $main_image_id;
	}

	if ( ! empty( $attachment_ids ) ) {
		$all_image_ids = array_merge( $all_image_ids, $attachment_ids );
	}

	return array_values( array_unique( array_map( 'intval', $all_image_ids ) ) );
}

/**
 * Prepare review statistics and pagination data for a single product.
 *
 * @param int $product_id Product ID.
 * @return array<string, mixed>
 */
function stilco_get_single_product_reviews_data( $product_id ) {
	$product_id = absint( $product_id );

	if ( ! $product_id ) {
		return array(
			'product_id'       => 0,
			'reviews_per_page' => 10,
			'current_page'     => 1,
			'offset'           => 0,
			'all_reviews'      => array(),
			'paged_reviews'    => array(),
			'total_reviews'    => 0,
			'total_pages'      => 1,
			'rating_counts'    => array( 5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0 ),
			'avg_rating'       => 0,
		);
	}

	$reviews_per_page = 10;
	$current_page = isset( $_GET['review_page'] ) ? max( 1, absint( wp_unslash( $_GET['review_page'] ) ) ) : 1;
	$offset = ( $current_page - 1 ) * $reviews_per_page;

	$all_reviews_args = array(
		'post_id' => $product_id,
		'status'  => 'approve',
		'type'    => 'review',
	);

	$all_reviews = get_comments( $all_reviews_args );
	$total_reviews = count( $all_reviews );
	$total_pages = $total_reviews > 0 ? (int) ceil( $total_reviews / $reviews_per_page ) : 1;
	$rating_counts = array( 5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0 );
	$rating_sum = 0;

	foreach ( $all_reviews as $review ) {
		$rating = (int) get_comment_meta( $review->comment_ID, 'rating', true );

		if ( $rating >= 1 && $rating <= 5 ) {
			$rating_counts[ $rating ]++;
			$rating_sum += $rating;
		}
	}

	$paged_reviews_args = array(
		'post_id' => $product_id,
		'status'  => 'approve',
		'type'    => 'review',
		'number'  => $reviews_per_page,
		'offset'  => $offset,
		'orderby' => 'comment_date',
		'order'   => 'DESC',
	);

	return array(
		'product_id'       => $product_id,
		'reviews_per_page' => $reviews_per_page,
		'current_page'     => $current_page,
		'offset'           => $offset,
		'all_reviews'      => $all_reviews,
		'paged_reviews'    => get_comments( $paged_reviews_args ),
		'total_reviews'    => $total_reviews,
		'total_pages'      => $total_pages,
		'rating_counts'    => $rating_counts,
		'avg_rating'       => $total_reviews > 0 ? round( $rating_sum / $total_reviews, 1 ) : 0,
	);
}

/**
 * Get default review form args for the single product template.
 *
 * @return array<string, string>
 */
function stilco_get_single_product_review_form_defaults() {
	return array(
		'title_reply'          => '',
		'title_reply_before'   => '',
		'title_reply_after'    => '',
		'comment_notes_before' => '',
	);
}

/**
 * Get a normalized CSS class for review fill widths.
 *
 * @param float|int $percentage Fill percentage.
 * @return string
 */
function stilco_get_review_fill_class( $percentage ) {
	$percentage = max( 0, min( 100, (int) round( (float) $percentage ) ) );

	return 'review-fill-' . $percentage;
}

/**
 * Check whether single product UI assets should load.
 *
 * @return bool
 */
function stilco_is_single_product_page() {
	return function_exists( 'is_product' ) && is_product();
}

/**
 * Enqueue single product UI assets.
 *
 * @return void
 */
function stilco_enqueue_single_product_assets() {
	if ( is_admin() || ! stilco_is_single_product_page() ) {
		return;
	}

	wp_enqueue_style(
		'stilco-single-product',
		stilco_get_theme_asset_uri( 'assets/css/single-product.css' ),
		array( 'stilco-style' ),
		stilco_get_theme_asset_version( 'assets/css/single-product.css' )
	);

	wp_enqueue_style(
		'stilco-single-product-gallery',
		stilco_get_theme_asset_uri( 'assets/css/single-product-gallery.css' ),
		array( 'stilco-single-product' ),
		stilco_get_theme_asset_version( 'assets/css/single-product-gallery.css' )
	);

	wp_enqueue_style(
		'stilco-single-product-reviews',
		stilco_get_theme_asset_uri( 'assets/css/single-product-reviews.css' ),
		array( 'stilco-single-product' ),
		stilco_get_theme_asset_version( 'assets/css/single-product-reviews.css' )
	);

	wp_enqueue_style(
		'stilco-single-product-variants',
		stilco_get_theme_asset_uri( 'assets/css/single-product-variants.css' ),
		array( 'stilco-single-product-gallery', 'stilco-single-product-reviews' ),
		stilco_get_theme_asset_version( 'assets/css/single-product-variants.css' )
	);

	wp_enqueue_script(
		'stilco-single-product',
		stilco_get_theme_asset_uri( 'assets/js/single-product.js' ),
		array( 'jquery' ),
		stilco_get_theme_asset_version( 'assets/js/single-product.js' ),
		true
	);

	wp_enqueue_script(
		'stilco-single-product-variants',
		stilco_get_theme_asset_uri( 'assets/js/single-product-variants.js' ),
		array( 'jquery' ),
		stilco_get_theme_asset_version( 'assets/js/single-product-variants.js' ),
		true
	);

	wp_enqueue_script(
		'stilco-single-product-gallery',
		stilco_get_theme_asset_uri( 'assets/js/single-product-gallery.js' ),
		array(),
		stilco_get_theme_asset_version( 'assets/js/single-product-gallery.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_single_product_assets', 130 );

/**
 * Render single product lightbox markup.
 *
 * @return void
 */
function stilco_render_single_product_lightbox_markup() {
	?>
	<div class="pg-lightbox" id="product-lightbox" role="dialog" aria-modal="true" aria-label="Galeria zdjęć" hidden>
		<div class="pg-lightbox__bar">
			<span class="pg-lightbox__counter" data-pg-counter>1 / 1</span>
			<button type="button" class="pg-lightbox__btn" data-pg-close aria-label="Zamknij galerię">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 18L18 6M6 6l12 12"/></svg>
			</button>
		</div>

		<div class="pg-lightbox__stage" data-pg-stage>
			<button type="button" class="pg-lightbox__btn pg-lightbox__nav pg-lightbox__nav--prev" data-pg-prev aria-label="Poprzednie zdjęcie">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 19l-7-7 7-7"/></svg>
			</button>
			<img class="pg-lightbox__img" data-pg-image src="" alt="">
			<button type="button" class="pg-lightbox__btn pg-lightbox__nav pg-lightbox__nav--next" data-pg-next aria-label="Następne zdjęcie">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
			</button>
		</div>

		<p class="pg-lightbox__caption" data-pg-caption></p>
		<p class="pg-lightbox__tip">Kliknij zdjęcie, aby je przybliżyć</p>
		<div class="pg-lightbox__thumbs" data-pg-thumbs></div>
	</div>
	<?php
}
