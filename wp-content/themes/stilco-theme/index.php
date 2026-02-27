<?php
/**
 * Domyślny szablon indeksowy
 */

get_header(); ?>

<div class="max-w-7xl mx-auto px-6 py-16">
    <?php if ( have_posts() ) : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300' ); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="mb-4 overflow-hidden rounded-xl">
                            <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-64 object-cover transform hover:scale-105 transition-transform duration-500' ) ); ?>
                        </div>
                    <?php endif; ?>
                    <header class="mb-4">
                        <?php the_title( '<h2 class="text-2xl font-display font-bold mb-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
                        <div class="text-sm text-gray-500">
                            <?php echo get_the_date(); ?>
                        </div>
                    </header>
                    <div class="text-gray-600 line-clamp-3">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        
        <div class="mt-12 flex justify-center">
            <?php the_posts_pagination(array(
                'prev_text' => '<span class="px-4 py-2 border rounded-l-md hover:bg-gray-50">Poprzednia</span>',
                'next_text' => '<span class="px-4 py-2 border border-l-0 rounded-r-md hover:bg-gray-50">Następna</span>',
                'class' => 'flex',
            )); ?>
        </div>
    <?php else : ?>
        <p class="text-center text-xl text-gray-500">Brak wpisów do wyświetlenia.</p>
    <?php endif; ?>
</div>

<?php get_footer();
