<?php
/**
 * Global image lightbox (FigJam #4).
 *
 * Any element with a `data-lightbox="<full image url>"` attribute opens
 * the image in an overlay. The single product page keeps its own gallery
 * lightbox, so this one is not loaded there.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether the global lightbox should load on the current request.
 *
 * @return bool
 */
function stilco_should_load_global_lightbox() {
	if ( is_admin() ) {
		return false;
	}

	if ( function_exists( 'stilco_is_single_product_page' ) && stilco_is_single_product_page() ) {
		return false;
	}

	return true;
}

/**
 * Enqueue lightbox assets.
 *
 * @return void
 */
function stilco_enqueue_global_lightbox_assets() {
	if ( ! stilco_should_load_global_lightbox() ) {
		return;
	}

	wp_enqueue_style(
		'stilco-lightbox',
		stilco_get_theme_asset_uri( 'assets/css/lightbox.css' ),
		array( 'stilco-style' ),
		stilco_get_theme_asset_version( 'assets/css/lightbox.css' )
	);

	wp_enqueue_script(
		'stilco-lightbox',
		stilco_get_theme_asset_uri( 'assets/js/lightbox.js' ),
		array(),
		stilco_get_theme_asset_version( 'assets/js/lightbox.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_global_lightbox_assets', 130 );

/**
 * Render lightbox markup in the footer.
 *
 * @return void
 */
function stilco_render_global_lightbox_markup() {
	if ( ! stilco_should_load_global_lightbox() ) {
		return;
	}
	?>
	<div id="stilco-lightbox" class="stilco-lightbox" role="dialog" aria-modal="true" aria-label="Powiększone zdjęcie" hidden>
		<button type="button" class="stilco-lightbox__close" data-lightbox-close aria-label="Zamknij">
			<svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
		</button>
		<div class="stilco-lightbox__inner" data-lightbox-close>
			<img class="stilco-lightbox__image" src="" alt="">
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'stilco_render_global_lightbox_markup', 20 );
