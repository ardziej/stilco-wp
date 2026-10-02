<?php
/**
 * Search results template.
 *
 * @package Stilco
 */

get_header();
?>
<main class="stilco-blog-shell min-h-screen pb-20">
	<section class="stilco-blog-hero pt-24 pb-18 text-white">
		<div class="max-w-7xl mx-auto px-6">
			<span class="inline-flex rounded-full border border-white/15 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-white/80">Wyszukiwarka</span>
			<h1 class="mt-6 text-4xl md:text-6xl font-display font-bold leading-tight text-white">Wyniki dla: <?php echo esc_html( get_search_query() ); ?></h1>
			<p class="mt-6 max-w-2xl text-lg leading-8 text-white/75"><?php echo esc_html( stilco_get_blog_archive_intro() ); ?></p>
		</div>
	</section>
	<section class="py-14">
		<div class="max-w-7xl mx-auto px-6">
			<?php if ( have_posts() ) : ?>
				<div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'template-parts/blog/post-card', null, array( 'post' => get_post() ) ); ?>
					<?php endwhile; ?>
				</div>
				<div class="mt-12">
					<?php the_posts_pagination(); ?>
				</div>
			<?php else : ?>
				<p class="rounded-[2rem] bg-white px-8 py-12 text-center text-gray-500 shadow-sm">Nie znaleźliśmy artykułów pasujących do zapytania.</p>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();
