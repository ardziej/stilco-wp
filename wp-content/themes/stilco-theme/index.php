<?php
/**
 * Domyślny szablon indeksowy (listing wpisów bloga).
 *
 * @package Stilco
 */

get_header(); ?>

<div class="max-w-7xl mx-auto px-6 py-16">
	<?php if ( is_archive() || is_search() ) : ?>
		<?php
		if ( is_search() ) {
			$listing_title = sprintf( 'Wyniki wyszukiwania: „%s”', get_search_query() );
		} elseif ( is_category() || is_tag() ) {
			$listing_title = single_term_title( '', false );
		} else {
			$listing_title = wp_strip_all_tags( get_the_archive_title() );
		}
		?>
		<header class="mx-auto mb-12 max-w-2xl text-center">
			<a href="<?php echo esc_url( home_url( '/strefa-wiedzy/#artykuly' ) ); ?>" class="text-xs font-bold uppercase tracking-[0.12em] text-[#a84a34] hover:underline">&larr; Wszystkie artykuły</a>
			<h1 class="pt-3 font-display text-4xl font-normal text-stilco-dark md:text-[52px] md:leading-[1.05]"><?php echo esc_html( $listing_title ); ?></h1>
			<?php if ( is_category() || is_tag() ) : ?>
				<?php $term_description = term_description(); ?>
				<?php if ( $term_description ) : ?>
					<div class="mt-4 text-lg text-stilco-dark/80"><?php echo wp_kses_post( $term_description ); ?></div>
				<?php endif; ?>
			<?php endif; ?>
		</header>
	<?php endif; ?>

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
