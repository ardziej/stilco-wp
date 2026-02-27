<?php
/**
 * Template Name: FAQ
 * 
 * Szablon strony z często zadawanymi pytaniami w formie interaktywnych akordeonów.
 */
get_header(); ?>

<main class="w-full bg-[#FAFAFA] min-h-screen">
    
    <!-- 1. Hero Banner -->
    <section class="relative w-full h-[50vh] min-h-[400px] flex items-center justify-center -mt-[88px] pt-[88px]">
        <div class="absolute inset-0 w-full h-full z-0">
            <!-- Jasne wideo/zdjęcie z poranną kawą w tle -->
            <img src="https://images.unsplash.com/photo-1541188495357-ad2ce22fa4ea?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover grayscale-[30%]" alt="Poranek w łóżku Stilco">
            <!-- Lżejszy overlay, by dostosować do jasnego obrazu -->
            <div class="absolute inset-0 bg-stilco-dark/30 backdrop-blur-[2px]"></div>
        </div>
        
        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto animate-on-scroll">
            <h1 class="text-5xl md:text-7xl font-serif text-white font-bold mb-6 tracking-tight drop-shadow-lg">
                Jak możemy Ci pomóc?
            </h1>
            <p class="text-xl md:text-2xl text-white font-medium drop-shadow-md">
                Zebraliśmy odpowiedzi na 99% pytań, które spędzają Ci sen z powiek.
            </p>
        </div>
    </section>

    <!-- 2. Główne FAQ -->
    <section class="py-24">
        <div class="max-w-4xl mx-auto px-6">
            <!-- Wrapper dla wszystkich akordeonów, do kontrolowanego JS -->
            <div class="faq-accordion space-y-16">
                
                <?php
                // Pobieramy wszystkie niepuste kategorie FAQ
                $terms = get_terms( array(
                    'taxonomy' => 'faq_category',
                    'hide_empty' => true,
                ) );

                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    $delay_group = 0;
                    foreach ( $terms as $term ) {
                        ?>
                        <!-- Grupa: <?php echo esc_html( $term->name ); ?> -->
                        <div>
                            <h2 class="text-2xl font-display font-semibold text-stilco-dark mb-8 uppercase tracking-widest text-sm border-b border-gray-200 pb-2">
                                <?php echo esc_html( $term->name ); ?>
                            </h2>
                            <div class="space-y-4">
                                <?php
                                $faqs = new WP_Query( array(
                                    'post_type'      => 'faq',
                                    'posts_per_page' => -1,
                                    'tax_query'      => array(
                                        array(
                                            'taxonomy' => 'faq_category',
                                            'field'    => 'term_id',
                                            'terms'    => $term->term_id,
                                        ),
                                    ),
                                ) );

                                if ( $faqs->have_posts() ) {
                                    $delay_item = 0;
                                    while ( $faqs->have_posts() ) {
                                        $faqs->the_post();
                                        // Generujemy klasę opóźnienia dla animacji
                                        $delay_class = $delay_item > 0 ? 'delay-' . ($delay_item * 100) : '';
                                        ?>
                                        <!-- Pytanie: <?php the_title_attribute(); ?> -->
                                        <details class="group bg-stilco-sand rounded-3xl border border-white/50 shadow-sm open:shadow-md transition-all duration-300 animate-slide-left <?php echo esc_attr( $delay_class ); ?>">
                                            <summary class="flex justify-between items-center font-display font-semibold text-lg cursor-pointer list-none p-6 md:px-8 text-stilco-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-3xl">
                                                <?php the_title(); ?>
                                                <span class="transition duration-300 group-open:rotate-180 text-stilco-accent bg-white rounded-full p-2 shadow-sm">
                                                    <svg fill="none" class="w-5 h-5" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                </span>
                                            </summary>
                                            <div class="text-gray-600 px-6 md:px-8 pb-8 pt-0 font-sans leading-relaxed pointer-events-auto">
                                                <?php echo apply_filters( 'the_content', get_the_content() ); ?>
                                            </div>
                                        </details>
                                        <?php
                                        $delay_item++;
                                    }
                                    wp_reset_postdata();
                                } else {
                                    echo '<p class="text-gray-500">Brak pytań w tej kategorii.</p>';
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        $delay_group++;
                    }
                } else {
                    echo '<p class="text-center text-gray-500 py-12">Wkrótce pojawią się tu najczęściej zadawane pytania.</p>';
                }
                ?>

            </div>
        </div>
    </section>

    <!-- 3. Sekcja Zamykająca (Contact Ribbon) -->
    <section class="bg-stilco-secondary py-16 mt-8 animate-zoom delay-300">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between text-stilco-dark">
            <div class="flex items-center space-x-6 mb-8 md:mb-0">
                <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-white shadow-sm flex-shrink-0">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200&auto=format&fit=crop" class="w-full h-full object-cover" alt="Pracownik Wsparcia">
                </div>
                <div>
                    <h3 class="text-2xl font-serif font-bold mb-1">Nadal masz pytania?</h3>
                    <p class="text-stilco-dark/80 font-medium">Nasz dział wsparcia snu jest do Twojej dyspozycji.</p>
                </div>
            </div>
            
            <a href="/kontakt" class="w-full md:w-auto text-center bg-white text-stilco-dark font-medium px-10 py-5 rounded-full shadow-md hover:bg-gray-50 hover:text-stilco-accent transition-all transform hover:scale-105 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-dark">
                Napisz do nas
            </a>
        </div>
    </section>

</main>

<style>
/* Reset dla details w safari */
details > summary {
  list-style: none;
}
details > summary::-webkit-details-marker {
  display: none;
}
</style>

<?php get_footer(); ?>
