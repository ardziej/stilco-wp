<?php
/**
 * Single blog post template.
 *
 * @package Stilco
 */

get_header(); ?>

<main class="bg-gray-50 pb-0 pt-16 md:pt-24">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'mx-auto max-w-4xl px-6 pb-24' ); ?>>
			<header class="mb-10 text-center">
				<a href="<?php echo esc_url( home_url( '/strefa-wiedzy/' ) ); ?>" class="mb-4 inline-block text-sm font-medium uppercase tracking-widest text-stilco-accent hover:text-stilco-dark transition-colors">Strefa wiedzy</a>
				<h1 class="font-display text-4xl font-bold tracking-tight text-stilco-dark md:text-5xl"><?php the_title(); ?></h1>
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="mt-4 block text-sm text-gray-500"><?php echo esc_html( get_the_date() ); ?></time>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="mb-10 overflow-hidden rounded-3xl shadow-sm">
					<?php the_post_thumbnail( 'large', array( 'class' => 'h-auto w-full object-cover' ) ); ?>
				</figure>
			<?php endif; ?>

			<div class="prose prose-lg prose-stilco max-w-none rounded-3xl border border-gray-100 bg-white p-8 font-sans leading-relaxed text-stilco-dark shadow-sm md:p-12">
				<?php the_content(); ?>
			</div>
		</article>

		<?php
		get_template_part(
			'template-parts/blog/related-posts',
			null,
			array( 'posts' => stilco_get_related_posts( get_the_ID() ) )
		);
		?>
	<?php endwhile; ?>
</main>

<?php get_footer();
