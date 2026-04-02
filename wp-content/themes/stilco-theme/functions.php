<?php
/**
 * Stilco Theme Functions
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stilco_theme = wp_get_theme( get_template() );

if ( ! defined( 'STILCO_THEME_DIR' ) ) {
	define( 'STILCO_THEME_DIR', get_template_directory() );
}

if ( ! defined( 'STILCO_THEME_URI' ) ) {
	define( 'STILCO_THEME_URI', get_template_directory_uri() );
}

if ( ! defined( 'STILCO_THEME_VERSION' ) ) {
	define( 'STILCO_THEME_VERSION', $stilco_theme->get( 'Version' ) ?: '1.0.0' );
}

$stilco_includes = array(
	'inc/helpers.php',
	'inc/context.php',
	'inc/setup.php',
	'inc/assets.php',
	'inc/woocommerce.php',
	'inc/checkout.php',
	'inc/review-images.php',
	'inc/review-highlights.php',
	'inc/reviews.php',
	'inc/front-page.php',
	'inc/faq-page.php',
	'inc/mattress.php',
	'inc/product-page.php',
	'inc/dashboard.php',
	'inc/dashboard-assets.php',
	'inc/frontend.php',
	'inc/transparent-header.php',
	'inc/chat-widget.php',
	'inc/mattress-page-ui.php',
	'inc/contact-page.php',
	'inc/cpt-faq.php',
	'inc/delivery-date.php',
	'inc/deliveries-endpoint.php',
);

foreach ( $stilco_includes as $stilco_include ) {
	$stilco_include_path = STILCO_THEME_DIR . '/' . $stilco_include;

	if ( file_exists( $stilco_include_path ) ) {
		require_once $stilco_include_path;
	}
}
