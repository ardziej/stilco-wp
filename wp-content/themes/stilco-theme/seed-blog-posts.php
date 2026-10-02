<?php
/**
 * Seed blog posts from Markdown files.
 *
 * @package Stilco
 */

$stilco_wp_load_candidates = array(
	dirname( __DIR__, 3 ) . '/wp-load.php',
	dirname( __DIR__, 4 ) . '/wordpress/wp-load.php',
	dirname( __DIR__, 4 ) . '/wp-load.php',
);

$stilco_wp_load_path = '';

foreach ( $stilco_wp_load_candidates as $candidate ) {
	if ( file_exists( $candidate ) ) {
		$stilco_wp_load_path = $candidate;
		break;
	}
}

if ( '' === $stilco_wp_load_path ) {
	exit( "Nie znaleziono wp-load.php.\n" );
}

require_once $stilco_wp_load_path;
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

/**
 * Parse HTML comment front matter.
 *
 * @param string $content Raw file contents.
 * @return array<string, string>
 */
function stilco_parse_blog_frontmatter( $content ) {
	$meta = array();
	$content = preg_replace( '/^\xEF\xBB\xBF/', '', (string) $content );
	$content = ltrim( $content );

	if ( preg_match( '/^<!--\s*(.*?)\s*-->/s', $content, $matches ) ) {
		if ( preg_match_all( '/^([a-z_]+):\s*"([^"]*)"\s*$/miu', trim( $matches[1] ), $value_matches, PREG_SET_ORDER ) ) {
			foreach ( $value_matches as $value_match ) {
				$meta[ $value_match[1] ] = $value_match[2];
			}
		}
	}

	return $meta;
}

/**
 * Convert lightweight Markdown to HTML.
 *
 * @param string $markdown Markdown contents.
 * @return string
 */
function stilco_blog_markdown_to_html( $markdown ) {
	$markdown = preg_replace( '/^\xEF\xBB\xBF/', '', (string) $markdown );
	$markdown = ltrim( $markdown );
	$markdown = preg_replace( '/^<!--[\s\S]*?-->\s*/', '', $markdown );

	$blocks = preg_split( '/\R{2,}/u', trim( $markdown ) );
	$html   = array();

	foreach ( $blocks as $block ) {
		$block = trim( $block );

		if ( '' === $block ) {
			continue;
		}

		if ( preg_match( '/^# (.+)$/m', $block, $matches ) ) {
			$html[] = '<h2>' . esc_html( trim( $matches[1] ) ) . '</h2>';
			continue;
		}

		if ( preg_match( '/^## (.+)$/m', $block, $matches ) ) {
			$html[] = '<h2>' . esc_html( trim( $matches[1] ) ) . '</h2>';
			continue;
		}

		if ( preg_match( '/^### (.+)$/m', $block, $matches ) ) {
			$html[] = '<h3>' . esc_html( trim( $matches[1] ) ) . '</h3>';
			continue;
		}

		if ( 0 === strpos( $block, '> ' ) ) {
			$quote  = preg_replace( '/^>\s?/m', '', $block );
			$quote  = esc_html( trim( $quote ) );
			$html[] = '<blockquote><p>' . $quote . '</p></blockquote>';
			continue;
		}

		if ( preg_match( '/^- /m', $block ) ) {
			$items = preg_split( '/\R/u', $block );
			$list  = array();

			foreach ( $items as $item ) {
				$item = preg_replace( '/^- /', '', trim( $item ) );
				$list[] = '<li>' . wp_kses_post( stilco_blog_apply_inline_markdown( $item ) ) . '</li>';
			}

			$html[] = '<ul>' . implode( '', $list ) . '</ul>';
			continue;
		}

		$paragraph = preg_replace( '/\R+/u', ' ', $block );
		$html[]    = '<p>' . wp_kses_post( stilco_blog_apply_inline_markdown( trim( $paragraph ) ) ) . '</p>';
	}

	return implode( "\n", $html );
}

/**
 * Apply inline Markdown formatting.
 *
 * @param string $text Input text.
 * @return string
 */
function stilco_blog_apply_inline_markdown( $text ) {
	$text = esc_html( $text );
	$text = preg_replace( '/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text );
	$text = preg_replace( '/\*(.*?)\*/', '<em>$1</em>', $text );

	return $text;
}

/**
 * Resolve a blog asset path from front matter.
 *
 * @param string $relative_path Relative asset path.
 * @return string
 */
function stilco_resolve_blog_asset_path( $relative_path ) {
	$relative_path = ltrim( (string) $relative_path, '/' );

	$candidates = array(
		__DIR__ . '/' . $relative_path,
		dirname( __DIR__, 1 ) . '/' . $relative_path,
		dirname( __DIR__, 3 ) . '/' . $relative_path,
	);

	foreach ( $candidates as $candidate ) {
		if ( file_exists( $candidate ) ) {
			return $candidate;
		}
	}

	return '';
}

/**
 * Import an image into the WordPress media library if needed.
 *
 * @param string $absolute_path Absolute source path.
 * @param int    $parent_post_id Parent post ID.
 * @return int
 */
function stilco_import_blog_image( $absolute_path, $parent_post_id ) {
	$filename = basename( $absolute_path );

	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'posts_per_page' => 1,
			'post_status'    => 'inherit',
			'meta_query'     => array(
				array(
					'key'     => '_wp_attached_file',
					'value'   => $filename,
					'compare' => 'LIKE',
				),
			),
		)
	);

	if ( ! empty( $existing ) ) {
		return (int) $existing[0]->ID;
	}

	$upload = wp_upload_bits( $filename, null, file_get_contents( $absolute_path ) );

	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$filetype   = wp_check_filetype( $upload['file'], null );
	$attachment = array(
		'post_mime_type' => $filetype['type'] ?? 'image/jpeg',
		'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$attachment_id = wp_insert_attachment( $attachment, $upload['file'], $parent_post_id );

	if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
		return 0;
	}

	$metadata = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
	wp_update_attachment_metadata( $attachment_id, $metadata );

	return (int) $attachment_id;
}

$blog_dir = dirname( dirname( dirname( __DIR__ ) ) ) . '/docs/blog';
$files    = glob( $blog_dir . '/*.md' );

if ( empty( $files ) ) {
	exit( "Brak plików w docs/blog.\n" );
}

$blog_page = get_page_by_path( 'blog' );

if ( ! $blog_page ) {
	$blog_page_id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_title'   => 'Blog',
			'post_name'    => 'blog',
			'post_status'  => 'publish',
			'post_content' => 'Sekcja blogowa Stilco.',
		)
	);
} else {
	$blog_page_id = $blog_page->ID;
}

if ( ! is_wp_error( $blog_page_id ) && (int) get_option( 'page_for_posts' ) !== (int) $blog_page_id ) {
	update_option( 'page_for_posts', (int) $blog_page_id );
}

echo "Seedowanie bloga...\n";

foreach ( $files as $file ) {
	$content = file_get_contents( $file );

	if ( false === $content ) {
		continue;
	}

	$meta       = stilco_parse_blog_frontmatter( $content );
	$title      = $meta['title'] ?? basename( $file, '.md' );
	$slug       = $meta['slug'] ?? sanitize_title( $title );
	$excerpt    = $meta['excerpt'] ?? '';
	$categories = ! empty( $meta['categories'] ) ? array_map( 'trim', explode( ',', $meta['categories'] ) ) : array( 'Poradniki' );
	$tags       = ! empty( $meta['tags'] ) ? array_map( 'trim', explode( ',', $meta['tags'] ) ) : array();

	$post_data = array(
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_excerpt' => $excerpt,
		'post_content' => stilco_blog_markdown_to_html( $content ),
		'post_status'  => 'publish',
		'post_type'    => 'post',
	);

	$existing_post = get_page_by_path( $slug, OBJECT, 'post' );

	if ( $existing_post ) {
		$post_data['ID'] = $existing_post->ID;
		$post_id         = wp_update_post( $post_data );
		echo "Zaktualizowano wpis: {$title}\n";
	} else {
		$post_id = wp_insert_post( $post_data );
		echo "Dodano wpis: {$title}\n";
	}

	if ( is_wp_error( $post_id ) ) {
		continue;
	}

	wp_set_post_categories(
		$post_id,
		array_map(
			static function ( $category_name ) {
				$term = term_exists( $category_name, 'category' );

				if ( ! $term ) {
					$term = wp_insert_term( $category_name, 'category' );
				}

				if ( is_wp_error( $term ) ) {
					return 0;
				}

				return (int) $term['term_id'];
			},
			$categories
		)
	);

	if ( ! empty( $tags ) ) {
		wp_set_post_terms( $post_id, $tags, 'post_tag' );
	}

	if ( ! empty( $meta['featured_image'] ) ) {
		$image_path = stilco_resolve_blog_asset_path( $meta['featured_image'] );

		if ( $image_path ) {
			$attachment_id = stilco_import_blog_image( $image_path, $post_id );

			if ( $attachment_id > 0 ) {
				set_post_thumbnail( $post_id, $attachment_id );
			}
		}
	}
}

echo "Blog gotowy.\n";
