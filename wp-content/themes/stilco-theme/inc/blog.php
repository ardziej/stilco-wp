<?php
/**
 * Blog helpers: post cards and related posts.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Number of related posts shown under a single post (FigJam #19).
 */
const STILCO_RELATED_POSTS_COUNT = 3;

/**
 * Get related posts for a post.
 *
 * Prefers posts sharing a category, then fills up with the latest posts
 * so the section always shows up to STILCO_RELATED_POSTS_COUNT cards
 * when enough content exists.
 *
 * @param int $post_id Post ID.
 * @param int $limit   Number of posts.
 * @return array<int, WP_Post>
 */
function stilco_get_related_posts( $post_id, $limit = STILCO_RELATED_POSTS_COUNT ) {
	$post_id = absint( $post_id );
	$limit   = max( 1, absint( $limit ) );

	if ( ! $post_id ) {
		return array();
	}

	$base_args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	$related     = array();
	$category_ids = wp_get_post_categories( $post_id, array( 'fields' => 'ids' ) );

	if ( ! empty( $category_ids ) && ! is_wp_error( $category_ids ) ) {
		$related = get_posts(
			array_merge(
				$base_args,
				array(
					'posts_per_page' => $limit,
					'category__in'   => $category_ids,
				)
			)
		);
	}

	if ( count( $related ) < $limit ) {
		$exclude = array_merge( array( $post_id ), wp_list_pluck( $related, 'ID' ) );
		$fill    = get_posts(
			array_merge(
				$base_args,
				array(
					'posts_per_page' => $limit - count( $related ),
					'post__not_in'   => $exclude,
				)
			)
		);
		$related = array_merge( $related, $fill );
	}

	return $related;
}

/**
 * Get posts for the knowledge base ("Strefa wiedzy") listing.
 *
 * @param int $limit Number of posts, -1 for all.
 * @return WP_Query
 */
function stilco_get_knowledge_posts( $limit = 9 ) {
	return new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => (int) $limit,
			'ignore_sticky_posts' => true,
		)
	);
}
