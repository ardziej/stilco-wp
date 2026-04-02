<?php
/**
 * Highlighted review query helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fetch highlighted review comments for homepage sections.
 *
 * @param int $limit Maximum reviews to fetch.
 * @return array<int, WP_Comment>
 */
function stilco_get_highlighted_reviews( $limit = 4 ) {
	$args = array(
		'status'     => 'approve',
		'type'       => 'review',
		'number'     => $limit,
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key'     => 'rating',
				'value'   => '5',
				'compare' => '=',
			),
			array(
				'key'     => '_review_image_id',
				'compare' => 'EXISTS',
			),
		),
	);

	return get_comments( $args );
}
