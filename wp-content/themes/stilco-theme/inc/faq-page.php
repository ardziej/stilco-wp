<?php
/**
 * FAQ page helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get FAQ page terms.
 *
 * @return array<int, WP_Term>
 */
function stilco_get_faq_page_terms() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'faq_category',
			'hide_empty' => true,
		)
	);

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return array();
	}

	return $terms;
}

/**
 * Get FAQ posts for a term.
 *
 * @param int $term_id FAQ term ID.
 * @return WP_Query
 */
function stilco_get_faq_posts_for_term( $term_id ) {
	return new WP_Query(
		array(
			'post_type'      => 'faq',
			'posts_per_page' => -1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'faq_category',
					'field'    => 'term_id',
					'terms'    => absint( $term_id ),
				),
			),
		)
	);
}

/**
 * Check whether the current request renders the dedicated FAQ page.
 *
 * @return bool
 */
function stilco_is_faq_page() {
	return ( function_exists( 'is_page_template' ) && is_page_template( 'page-faq.php' ) )
		|| ( function_exists( 'is_page' ) && is_page( 'faq' ) );
}

/**
 * Enqueue FAQ page assets.
 *
 * @return void
 */
function stilco_enqueue_faq_page_assets() {
	if ( is_admin() || ! stilco_is_faq_page() ) {
		return;
	}

	wp_enqueue_style(
		'stilco-faq-page',
		stilco_get_theme_asset_uri( 'assets/css/faq-page.css' ),
		array( 'stilco-style' ),
		stilco_get_theme_asset_version( 'assets/css/faq-page.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_faq_page_assets', 130 );
