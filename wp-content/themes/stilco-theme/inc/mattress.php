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
 * @param int|null $post_id Optional page ID.
 * @return array<int, array{url:string,alt:string,id:int}>
 */
function stilco_get_mattress_landing_gallery_images( $post_id = null ) {
	$fallback_files = array(
		array(
			'file' => '1.jpg',
			'alt'  => 'Materac Stilco w sypialni',
		),
		array(
			'file' => '2.jpg',
			'alt'  => 'Detal materaca',
		),
		array(
			'file' => '3.jpg',
			'alt'  => 'Detal materaca',
		),
		array(
			'file' => '4.jpg',
			'alt'  => 'Detal materaca',
		),
		array(
			'file' => 'product-close.JPG',
			'alt'  => 'Detal materaca',
		),
		array(
			'file' => '20241102_130956.jpg',
			'alt'  => 'Detal materaca',
		),
		array(
			'file' => '20241102_133444.jpg',
			'alt'  => 'Detal materaca',
		),
		array(
			'file' => '20241102_141035.jpg',
			'alt'  => 'Detal materaca',
		),
	);

	$images = array();

	foreach ( $fallback_files as $index => $fallback ) {
		$field_index = $index + 1;
		$image       = stilco_get_media_image_data(
			stilco_get_page_field( "mattress_gallery_{$field_index}_image", '', $post_id ),
			stilco_get_mattress_landing_image_uri( $fallback['file'] ),
			$fallback['alt']
		);
		$image       = stilco_override_media_alt(
			$image,
			stilco_get_page_field( "mattress_gallery_{$field_index}_image_alt", '', $post_id )
		);
		$images[]    = $image;
	}

	return $images;
}

/**
 * Get mattress landing size options.
 *
 * @param int|null $post_id Optional page ID.
 * @return array<int, array<string, mixed>>
 */
function stilco_get_mattress_landing_size_options( $post_id = null ) {
	$fallback = array(
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

	$options = array();

	foreach ( $fallback as $index => $item ) {
		$field_index = $index + 1;
		$label       = stilco_get_page_field( "mattress_size_{$field_index}_label", $item['label'], $post_id );
		$price       = stilco_get_page_field( "mattress_size_{$field_index}_price", $item['price'], $post_id );
		$is_active   = stilco_get_page_field( "mattress_size_{$field_index}_active", $item['is_active'] ? '1' : '', $post_id );

		$options[] = array(
			'label'     => (string) $label,
			'price'     => (int) $price,
			'is_active' => ! empty( $is_active ),
		);
	}

	return $options;
}

/**
 * Get mattress landing customer stories.
 *
 * @param int|null $post_id Optional page ID.
 * @return array<int, array<string, string>>
 */
function stilco_get_mattress_landing_customer_stories( $post_id = null ) {
	$fallback = array(
		array(
			'image' => array(
				'url' => stilco_get_mattress_landing_image_uri( '20241102_130956.jpg' ),
				'alt' => '"Koniec bólu pleców!"',
				'id'  => 0,
			),
			'title' => '"Koniec bólu pleców!"',
			'meta'  => 'Agnieszka (Rozmiar: 160x200)',
		),
		array(
			'image' => array(
				'url' => stilco_get_mattress_landing_image_uri( '20241102_133444.jpg' ),
				'alt' => '"Śpimy jak w chmurach"',
				'id'  => 0,
			),
			'title' => '"Śpimy jak w chmurach"',
			'meta'  => 'Marta i Tomek (Rozmiar: 180x200)',
		),
		array(
			'image' => array(
				'url' => stilco_get_mattress_landing_image_uri( '20241102_133839.jpg' ),
				'alt' => '"Zakup Życia!"',
				'id'  => 0,
			),
			'title' => '"Zakup Życia!"',
			'meta'  => 'Kamil (Rozmiar: 140x200)',
		),
	);

	$stories = array();

	foreach ( $fallback as $index => $item ) {
		$field_index = $index + 1;
		$image       = stilco_get_media_image_data(
			stilco_get_page_field( "mattress_story_{$field_index}_image", '', $post_id ),
			$item['image']['url'],
			$item['image']['alt']
		);
		$image       = stilco_override_media_alt(
			$image,
			stilco_get_page_field( "mattress_story_{$field_index}_image_alt", '', $post_id )
		);

		$stories[] = array(
			'image' => $image,
			'title' => (string) stilco_get_page_field( "mattress_story_{$field_index}_title", $item['title'], $post_id ),
			'meta'  => (string) stilco_get_page_field( "mattress_story_{$field_index}_meta", $item['meta'], $post_id ),
		);
	}

	return $stories;
}
