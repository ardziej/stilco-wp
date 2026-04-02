<?php
/**
 * Mattress landing page helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a mattress landing image URI.
 *
 * @param string $filename Image filename inside assets/images.
 * @return string
 */
function stilco_get_mattress_landing_image_uri( $filename ) {
	return stilco_get_theme_asset_uri( 'assets/images/' . ltrim( $filename, '/' ) );
}

/**
 * Get mattress landing gallery images.
 *
 * @return array<int, string>
 */
function stilco_get_mattress_landing_gallery_images() {
	return array(
		'1.jpg',
		'2.jpg',
		'3.jpg',
		'4.jpg',
		'product-close.JPG',
		'20241102_130956.jpg',
		'20241102_133444.jpg',
		'20241102_141035.jpg',
	);
}

/**
 * Get mattress landing size options.
 *
 * @return array<int, array<string, mixed>>
 */
function stilco_get_mattress_landing_size_options() {
	return array(
		array(
			'label'     => '80x200',
			'price'     => 2595,
			'is_active' => false,
		),
		array(
			'label'     => '90x200',
			'price'     => 2808,
			'is_active' => false,
		),
		array(
			'label'     => '120x200',
			'price'     => 2888,
			'is_active' => true,
		),
		array(
			'label'     => '140x200',
			'price'     => 3782,
			'is_active' => false,
		),
		array(
			'label'     => '160x200',
			'price'     => 4455,
			'is_active' => false,
		),
		array(
			'label'     => '180x200',
			'price'     => 5091,
			'is_active' => false,
		),
	);
}

/**
 * Get mattress landing customer stories.
 *
 * @return array<int, array<string, string>>
 */
function stilco_get_mattress_landing_customer_stories() {
	return array(
		array(
			'image' => '20241102_130956.jpg',
			'title' => '"Koniec bólu pleców!"',
			'meta'  => 'Agnieszka (Rozmiar: 160x200)',
		),
		array(
			'image' => '20241102_133444.jpg',
			'title' => '"Śpimy jak w chmurach"',
			'meta'  => 'Marta i Tomek (Rozmiar: 180x200)',
		),
		array(
			'image' => '20241102_133839.jpg',
			'title' => '"Zakup Życia!"',
			'meta'  => 'Kamil (Rozmiar: 140x200)',
		),
	);
}
