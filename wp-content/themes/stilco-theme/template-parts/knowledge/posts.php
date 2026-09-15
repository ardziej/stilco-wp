<?php
/**
 * Knowledge base: blog posts grid below the FAQ.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id = get_queried_object_id();
$query   = isset( $args['query'] ) && $args['query'] instanceof WP_Query ? $args['query'] : null;
?>
<section id="artykuly" class="border-t border-gray-200 bg-white py-24" aria-labelledby="knowledge-posts-title">
	<div class="mx-auto max-w-7xl px-6">
		<div class="mb-16 text-center animate-on-scroll">
			<span class="mb-4 block text-sm font-medium uppercase tracking-widest text-stilco-accent"><?php echo esc_html( stilco_get_page_field( 'knowledge_posts_eyebrow', 'Artykuły', $page_id ) ); ?></span>
			<h2 id="knowledge-posts-title" class="mb-4 font-display text-3xl font-bold text-stilco-dark md:text-5xl"><?php echo esc_html( stilco_get_page_field( 'knowledge_posts_title', 'Czytaj więcej o dobrym śnie', $page_id ) ); ?></h2>
			<p class="mx-auto max-w-2xl text-gray-600"><?php echo esc_html( stilco_get_page_field( 'knowledge_posts_lead', 'Porady o wyborze materaca, pielęgnacji pokrowca i zdrowym śnie, pisane przez ludzi, którzy ten materac szyją.', $page_id ) ); ?></p>
		</div>

		<?php if ( $query && $query->have_posts() ) : ?>
			<div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					get_template_part( 'template-parts/blog/card', null, array( 'heading_tag' => 'h3' ) );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<p class="py-12 text-center text-gray-500"><?php echo esc_html( stilco_get_page_field( 'knowledge_posts_empty', 'Pierwsze artykuły pojawią się wkrótce.', $page_id ) ); ?></p>
		<?php endif; ?>
	</div>
</section>
