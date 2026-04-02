<?php
/**
 * Theme setup and registrations.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Configure core and WooCommerce theme supports.
 *
 * @return void
 */
function stilco_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'stilco' ),
			'footer'  => __( 'Footer Menu', 'stilco' ),
		)
	);

	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 600,
			'single_image_width'    => 800,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 2,
				'max_rows'        => 8,
				'default_columns' => 4,
				'min_columns'     => 2,
				'max_columns'     => 5,
			),
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'stilco_setup' );

/**
 * Register widget areas.
 *
 * @return void
 */
function stilco_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Shop Sidebar', 'stilco' ),
			'id'            => 'shop-sidebar',
			'description'   => __( 'Add widgets here to appear in your shop sidebar.', 'stilco' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s mb-8 pb-6 border-b border-gray-100 last:border-b-0">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title font-serif font-medium text-lg mb-4 text-stilco-dark">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'stilco_widgets_init' );
