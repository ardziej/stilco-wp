<?php
/**
 * Template Name: Strony Prawne i Tekstowe
 * 
 * Szablon zaprojektowany specjalnie pod treści takie jak
 * Regulamin, Polityka Prywatności, Zwroty, oferujący bardzo dużą czytelność,
 * wąską kolumnę tekstu i wyraźną typografię.
 */
get_header(); 
?>

<main class="w-full bg-stilco-sand min-h-screen pt-32 pb-24">
    
    <!-- Hero / Tytuł sekcji -->
    <section class="max-w-4xl mx-auto px-6 mb-12 text-center">
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-stilco-dark mb-6">
            <?php the_title(); ?>
        </h1>
        <div class="w-24 h-1 bg-stilco-accent mx-auto rounded-full"></div>
    </section>

    <!-- Kontent główny strony -->
    <section class="max-w-3xl mx-auto px-6">
        <article class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-white/50 prose prose-lg prose-stilco prose-headings:font-display prose-headings:font-bold prose-headings:text-stilco-dark prose-h2:text-2xl prose-h2:mt-10 prose-h2:mb-4 prose-p:text-gray-600 prose-p:leading-relaxed prose-a:text-stilco-accent prose-a:font-medium prose-a:underline prose-a:underline-offset-4 hover:prose-a:text-[#A84A34] prose-li:text-gray-600 w-full max-w-none">
            
            <?php while ( have_posts() ) : the_post(); ?>
                
                <?php 
                // Zabezpieczenie przed usunięciem domyślnych znaczników przez Markdown, jeśli jeszcze tego nie zrobił get_the_content()
                the_content(); 
                ?>
                
            <?php endwhile; ?>

        </article>
    </section>
    
</main>

<?php get_footer(); ?>
