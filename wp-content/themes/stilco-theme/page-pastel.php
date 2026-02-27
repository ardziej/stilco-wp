<?php
/**
 * Template Name: Pastelowy Ogólny (O Nas, FAQ, Kontakt)
 */
get_header(); ?>

<div class="bg-stilco-light min-h-screen py-24 pb-32">
    <div class="max-w-4xl mx-auto px-6">
        <article class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-stilco-secondary/20 prose prose-lg prose-stilco max-w-none">
            <!-- Header wpisu -->
            <header class="mb-10 text-center">
                <h1 class="text-4xl md:text-5xl font-display font-bold text-stilco-dark tracking-tight">
                    <?php the_title(); ?>
                </h1>
            </header>
            
            <!-- Główny Content (Tu wpada treść z Markdown prztworzona przez WP) -->
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
