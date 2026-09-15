<?php
/**
 * Domyślny szablon indeksowy (listing wpisów bloga).
 *
 * @package Stilco
 */

get_header(); ?>

<div class="max-w-7xl mx-auto px-6 py-16">
	<?php if ( have_posts() ) : ?>
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/blog/card' );
			endwhile;
			?>
		</div>

		<div class="mt-12 flex justify-center">
			<?php
			the_posts_pagination(
				array(
					'prev_text' => '<span class="px-4 py-2 border rounded-l-md hover:bg-gray-50">Poprzednia</span>',
					'next_text' => '<span class="px-4 py-2 border border-l-0 rounded-r-md hover:bg-gray-50">Następna</span>',
					'class'     => 'flex',
				)
			);
			?>
		</div>
	<?php else : ?>
		<p class="text-center text-xl text-gray-500">Brak wpisów do wyświetlenia.</p>
	<?php endif; ?>
</div>

<?php get_footer();
