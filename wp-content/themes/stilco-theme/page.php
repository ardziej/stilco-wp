<?php
/**
 * Domyślny szablon stron (np. Kontakt, FAQ, O Nas)
 */
get_header(); ?>

<div class="bg-gray-50 min-h-screen py-24 pb-32">
    <div class="max-w-4xl mx-auto px-6">
        <article class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-gray-100 prose prose-lg prose-stilco max-w-none">
            <!-- Header wpisu -->
            <header class="mb-10 text-center">
                <h1 class="text-4xl md:text-5xl font-display font-bold text-stilco-dark tracking-tight">
                    <?php the_title(); ?>
                </h1>
            </header>
            
            <!-- Główny Content -->
            <div class="text-stilco-dark font-sans leading-relaxed">
                <?php
                while ( have_posts() ) :
                    the_post();
                    the_content();
                endwhile;
                ?>
            </div>
        </article>
    </div>
</div>

<?php get_footer();
