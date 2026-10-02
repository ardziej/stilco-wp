<?php
/**
 * Blog helpers and assets.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check whether the current request is a blog-related view.
 *
 * @return bool
 */
function stilco_is_blog_context() {
	return is_home()
		|| ( is_singular( 'post' ) )
		|| is_category()
		|| is_tag()
		|| is_date()
		|| is_author()
		|| is_search()
		|| is_post_type_archive( 'post' );
}

/**
 * Check whether the current request is the posts index.
 *
 * @return bool
 */
function stilco_is_blog_index() {
	return is_home() && ! is_front_page();
}

/**
 * Get the canonical blog URL.
 *
 * @return string
 */
function stilco_get_blog_url() {
	$posts_page_id = (int) get_option( 'page_for_posts' );

	if ( $posts_page_id > 0 ) {
		return get_permalink( $posts_page_id );
	}

	return get_post_type_archive_link( 'post' ) ?: home_url( '/blog/' );
}

/**
 * Enqueue blog assets.
 *
 * @return void
 */
function stilco_enqueue_blog_assets() {
	if ( is_admin() || ! stilco_is_blog_context() ) {
		return;
	}

	wp_enqueue_style(
		'stilco-blog',
		stilco_get_theme_asset_uri( 'assets/css/blog.css' ),
		array( 'stilco-style' ),
		stilco_get_theme_asset_version( 'assets/css/blog.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_blog_assets', 130 );

/**
 * Get estimated reading time for a post.
 *
 * @param int|WP_Post|null $post Post object or ID.
 * @return int
 */
function stilco_get_post_reading_time( $post = null ) {
	$post = get_post( $post );

	if ( ! $post instanceof WP_Post ) {
		return 1;
	}

	$content    = wp_strip_all_tags( (string) $post->post_content );
	$word_count = str_word_count( wp_specialchars_decode( $content ) );
	$minutes    = (int) ceil( max( 1, $word_count ) / 180 );

	return max( 1, $minutes );
}

/**
 * Get a blog archive intro based on current context.
 *
 * @return string
 */
function stilco_get_blog_archive_intro() {
	if ( is_category() ) {
		$description = term_description();

		if ( $description ) {
			return wp_strip_all_tags( $description );
		}

		return 'Artykuły eksperckie skupione wokół jednego tematu, przygotowane z myślą o lepszym śnie i świadomym wyborze materaca.';
	}

	if ( is_search() ) {
		return 'Wyniki wyszukiwania w naszej strefie wiedzy o śnie, ergonomii i doborze materaca.';
	}

	return 'Eksperckie artykuły Stilco pomagają wybrać materac, poprawić higienę snu i lepiej zrozumieć materiały oraz ergonomię spania.';
}

/**
 * Get related blog posts.
 *
 * @param int $post_id Current post ID.
 * @param int $limit   Maximum items.
 * @return WP_Post[]
 */
function stilco_get_related_blog_posts( $post_id, $limit = 3 ) {
	$category_ids = wp_get_post_categories( $post_id );

	$query = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $limit,
			'post__not_in'        => array( $post_id ),
			'ignore_sticky_posts' => true,
			'category__in'        => ! empty( $category_ids ) ? $category_ids : array(),
		)
	);

	if ( empty( $query->posts ) ) {
		$query = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => $limit,
				'post__not_in'        => array( $post_id ),
				'ignore_sticky_posts' => true,
			)
		);
	}

	return $query->posts;
}

/**
 * Get recent blog posts.
 *
 * @param int $limit Maximum number of posts.
 * @return WP_Post[]
 */
function stilco_get_recent_blog_posts( $limit = 3 ) {
	return get_posts(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $limit,
			'ignore_sticky_posts' => true,
		)
	);
}
