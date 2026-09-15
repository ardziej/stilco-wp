<?php
/**
 * Related posts under a single post (FigJam #19: three cards).
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$related_posts = isset( $args['posts'] ) ? (array) $args['posts'] : array();

if ( empty( $related_posts ) ) {
	return;
}
?>
<section class="border-t border-gray-200 bg-stilco-light py-20" aria-labelledby="related-posts-title">
	<div class="mx-auto max-w-7xl px-6">
		<div class="mb-12 text-center animate-on-scroll">
			<span class="mb-3 block text-sm font-medium uppercase tracking-widest text-stilco-accent">Strefa wiedzy</span>
			<h2 id="related-posts-title" class="font-display text-3xl font-bold text-stilco-dark md:text-4xl">Powiązane artykuły</h2>
		</div>
		<div class="grid grid-cols-1 gap-8 md:grid-cols-3">
			<?php
			foreach ( $related_posts as $related_post ) :
				setup_postdata( $GLOBALS['post'] = $related_post ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				get_template_part( 'template-parts/blog/card', null, array( 'heading_tag' => 'h3' ) );
			endforeach;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
