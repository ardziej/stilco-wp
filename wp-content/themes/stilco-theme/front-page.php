<?php
/**
 * Szablon strony głównej
 */
get_header(); ?>

<!-- Hero Section -->
<section class="relative h-[95vh] min-h-[600px] w-full flex items-center justify-center bg-stilco-dark overflow-hidden">
    <!-- Główne zdjęcie lifestylowe materaca -->
    <div class="absolute inset-0">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/new-hero.png" alt="Materac Stilco" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-stilco-dark/80 via-black/30 to-black/60"></div>
    </div>
    
    <div class="relative z-10 text-center max-w-4xl px-6 pb-20 animate-on-scroll">
        <span class="text-white/90 text-sm md:text-base tracking-widest uppercase font-semibold mb-4 block">Manufaktura Dobrego Snu</span>
        <h1 class="text-5xl md:text-7xl text-white font-serif font-bold mb-6 tracking-tight leading-tight">
            Zasypiaj szybciej.<br/><span class="text-stilco-accent">Budź się wypoczęty.</span>
        </h1>
        <p class="text-lg md:text-xl text-gray-200 mb-10 max-w-2xl mx-auto font-sans">
            Oddychający materac hybrydowy dopasowujący się do kształtu Twojego ciała. 100% polska produkcja.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
            <a href="/produkt/materac-stilco/" class="btn-primary w-full sm:w-auto text-lg rounded-full px-10 py-4 border-2 border-stilco-accent shadow-stilco-accent/40 shadow-xl bg-stilco-accent">
                Wybierz Materac
            </a>
            <a href="#dlaczego-my" class="btn-outline border-2 border-white text-white hover:bg-white hover:text-stilco-dark w-full sm:w-auto text-lg rounded-full px-10 py-4 bg-white/5 backdrop-blur-sm">
                Poznaj przewagi
            </a>
        </div>
    </div>

    <!-- USP / Trust Signals Bar (Zintegrowane w Hero) -->
    <div class="absolute bottom-0 left-0 w-full border-t border-white/20 bg-stilco-dark/40 backdrop-blur-md text-white py-4 z-20 hidden md:block">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center text-sm font-medium tracking-wide">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-stilco-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                </svg>
                <span>Darmowa Dostawa</span>
            </div>
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-stilco-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
                <span>100 Nocy na Test</span>
            </div>
             <div class="flex items-center space-x-3">
                 <svg class="w-6 h-6 text-stilco-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>10 Lat Gwarancji</span>
            </div>
        </div>
    </div>
</section>

<!-- Scroll Indicator -->
<div class="w-full bg-white py-8 flex items-center justify-center">
    <a href="#dlaczego-my" class="animate-bounce p-2 text-stilco-secondary hover:text-stilco-dark transition-colors flex flex-col items-center gap-2" aria-label="Przewiń wdół">
        <span class="text-sm font-medium tracking-widest uppercase">Odkryj więcej</span>
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </a>
</div>

<!-- Koncept Dwie Strony -->
<section id="dlaczego-my" class="py-24 bg-stilco-sand relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="w-full lg:w-1/2 animate-slide-left">
                <span class="text-stilco-secondary font-medium tracking-widest uppercase text-sm mb-4 block">1 materac, 2 strony</span>
                <h2 class="text-3xl md:text-5xl font-display font-bold mb-6 text-stilco-dark">Zmieniaj twardość kiedy tylko zechcesz.</h2>
                <div class="space-y-6 text-gray-600">
                    <p class="text-lg">Stworzyliśmy materac, który dopasowuje się do Twoich zmieniających się potrzeb. Po jednej stronie znajdziesz miękką piankę Visco, po drugiej - piankę wysokoelastyczną dającą stabilne podparcie.</p>
                    <ul class="space-y-4 mt-8">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-stilco-secondary mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <div>
                                <h4 class="font-bold text-stilco-dark">Strona H2 (Średnio-miękka)</h4>
                                <p class="text-sm mt-1">Pianka Visco Memory - idealnie otula ciało, redukując nacisk. Doskonała dla osób lżejszych lub preferujących uczucie "zapadania się".</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-stilco-secondary mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <div>
                                <h4 class="font-bold text-stilco-dark">Strona H3 (Średnio-twarda)</h4>
                                <p class="text-sm mt-1">Pianka Wysokoelastyczna - zapewnia solidne podparcie kręgosłupa i większą sprężystość. Wybierana przez zwolenników twardszego podłoża.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-full lg:w-1/2 animate-slide-right delay-200">
                <div class="relative rounded-3xl overflow-hidden aspect-square shadow-2xl">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/image112.jpg" alt="Dwie strony materaca Stilco w jasnym świetle" class="w-full h-full object-cover">
                    <!-- Overlay indicator -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-stilco-dark/30 to-transparent flex items-end p-8">
                        <div class="text-white">
                            <p class="font-display font-medium text-xl">Stilco Dual Comfort</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Budowa Wewnętrzna / Warstwy -->
<section class="py-24 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-6 text-center animate-on-scroll">
        <h2 class="text-3xl md:text-5xl font-display font-bold mb-4 text-stilco-dark">Zajrzyj do środka</h2>
        <p class="text-gray-600 max-w-2xl mx-auto mb-16 text-lg">Najwyższa jakość polskich materiałów, zamknięta w przemyślanej konstrukcji, która pracuje dla Twojego zdrowia.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-left">
            <div class="group animate-on-scroll delay-100">
                <div class="bg-gray-50 h-64 rounded-3xl mb-6 relative overflow-hidden">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/image179.jpg" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="Oddychający Pokrowiec">
                </div>
                <h3 class="text-xl font-bold font-display text-stilco-dark mb-2">1. Oddychający Pokrowiec</h3>
                <p class="text-gray-600 text-sm">Przewiewna, antyalergiczna tkanina w jasnym kremowym odcieniu, tkana z myślą o cyrkulacji powietrza. Zamek 360° ułatwia pielęgnację i pranie.</p>
            </div>
            <div class="group animate-on-scroll delay-300">
                <div class="bg-gray-50 h-64 rounded-3xl mb-6 relative overflow-hidden">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/image198.jpg" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="Pianka Visco Memory w słońcu">
                </div>
                <h3 class="text-xl font-bold font-display text-stilco-dark mb-2">2. Termoelastyczna Bliskość</h3>
                <p class="text-gray-600 text-sm">Niezwykle miękka warstwa Visco idealnie otulająca i dająca ukojenie mięśniom po długim dniu zabawy i obowiązków.</p>
            </div>
            <div class="group animate-on-scroll delay-500">
                <div class="bg-gray-50 h-64 rounded-3xl mb-6 relative overflow-hidden">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/image205.jpg" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="Pianka Wysokoelastyczna">
                </div>
                <h3 class="text-xl font-bold font-display text-stilco-dark mb-2">3. Wsparcie i Trwałość</h3>
                <p class="text-gray-600 text-sm">Rdzeń z pianki HR dba o zachowanie naturalnych krzywizn kręgosłupa i sprawia, że materac posłuży Wam przez długie lata w doskonałej formie.</p>
            </div>
        </div>
    </div>
</section>

<!-- Produkty / Kategorie (Przykładowa statyczna struktura do podpięcia query WP) -->
<section id="kategorie" class="py-24 max-w-7xl mx-auto px-6">
    <div class="text-center mb-16 animate-on-scroll">
        <h2 class="text-3xl md:text-5xl font-display font-bold mb-4">Wybierz perfekcję</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Niezależnie od tego jak śpisz, mamy technologię dopasowaną do Twojego ciała.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Zastępcze kafelki kategorii, domyślnie dynamiczne woo -->
        <div class="group cursor-pointer rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 animate-zoom border border-stilco-secondary/20">
            <div class="h-80 bg-stilco-sand relative overflow-hidden">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/image268.jpg" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-700" alt="Dla dorosłych">
            </div>
            <div class="p-8 bg-white text-center">
                <h3 class="text-2xl font-display font-bold mb-2 text-stilco-dark">Dla Dorosłych</h3>
                <p class="text-sm text-gray-500 mb-6">Elastyczność i dopasowanie premium dla każdej twardości.</p>
                <span class="text-stilco-secondary font-medium uppercase tracking-wider text-sm group-hover:text-stilco-dark transition-colors">Odkryj →</span>
            </div>
        </div>

        <div class="group cursor-pointer rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 animate-zoom delay-100 border border-stilco-secondary/20">
            <div class="h-80 bg-stilco-sand relative overflow-hidden">
                 <img src="<?php echo get_template_directory_uri(); ?>/assets/images/image88.jpg" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-700" alt="Dla Dzieci">
            </div>
            <div class="p-8 bg-white text-center">
                <h3 class="text-2xl font-display font-bold mb-2 text-stilco-dark">Dla Dzieci</h3>
                <p class="text-sm text-gray-500 mb-6">Bezpieczne materiały dla rosnących ciał.</p>
                <span class="text-stilco-secondary font-medium uppercase tracking-wider text-sm group-hover:text-stilco-dark transition-colors">Odkryj →</span>
            </div>
        </div>

        <div class="group cursor-pointer rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 animate-zoom delay-200 border border-stilco-secondary/20">
            <div class="h-80 bg-stilco-sand relative overflow-hidden flex items-center justify-center">
                <div class="text-center p-6 bg-stilco-light bg-opacity-90 w-full h-full flex flex-col items-center justify-center">
                    <span class="block text-4xl mb-2 text-stilco-secondary">🌿</span>
                    <h3 class="text-xl font-display font-bold text-stilco-dark">Ekologiczne Materiały</h3>
                    <p class="text-sm text-gray-600 mt-2">Dbałość o naturę oznacza czystszy sen w zgodzie ze środowiskiem.</p>
                </div>
            </div>
            <div class="p-8 bg-stilco-secondary text-center">
                <span class="text-white font-medium uppercase tracking-wider text-sm">Poznaj wartości →</span>
            </div>
        </div>
    </div>
</section>

<!-- Highlighted Reviews -->
<section class="py-24 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <span class="text-stilco-accent font-medium tracking-widest uppercase text-sm mb-4 block">Prawdziwe historie</span>
            <h2 class="text-3xl md:text-5xl font-display font-bold mb-4 text-stilco-dark">Głos tysięcy wyspanych</h2>
            <p class="text-gray-500 max-w-2xl mx-auto text-lg">Zobacz, jak Materac Stilco zmienia życia na lepsze. Sprawdzone opinie naszych klientów.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-on-scroll delay-200">
            <?php
            if ( function_exists('stilco_get_highlighted_reviews') ) {
                $highlighted_reviews = stilco_get_highlighted_reviews(4);
                if ( $highlighted_reviews ) :
                    foreach ( $highlighted_reviews as $review ) :
                        $image_id = get_comment_meta( $review->comment_ID, '_review_image_id', true );
                        $rating   = get_comment_meta( $review->comment_ID, 'rating', true ) ?: 5;
                ?>
                <div class="bg-stilco-light rounded-3xl p-8 border border-gray-100 flex flex-col justify-between hover:shadow-xl transition-all duration-300">
                    <div>
                        <div class="flex items-center space-x-1 text-stilco-accent mb-4">
                            <?php for($i=0; $i<$rating; $i++) echo '<svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>'; ?>
                        </div>
                        <?php if ( $image_id ) : ?>
                            <div class="mb-6 rounded-2xl overflow-hidden aspect-[4/3] bg-gray-100 relative">
                                <?php echo wp_get_attachment_image( $image_id, 'medium', false, array('class' => 'w-full h-full object-cover hover:scale-105 transition duration-500') ); ?>
                            </div>
                        <?php endif; ?>
                        <p class="text-gray-700 italic mb-6 leading-relaxed">"<?php echo wp_trim_words( $review->comment_content, 20 ); ?>"</p>
                    </div>
                    <div class="flex items-center justify-between mt-auto pt-6 border-t border-gray-200">
                        <div>
                            <span class="block font-bold text-stilco-dark"><?php echo esc_html( $review->comment_author ); ?></span>
                            <span class="block text-xs text-gray-400 mt-1 flex items-center"><svg class="w-3 h-3 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Zweryfikowany zakup</span>
                        </div>
                    </div>
                </div>
                <?php 
                    endforeach;
                else :
                ?>
                    <p class="text-center col-span-full text-gray-500 py-8 text-sm">Nowe opinie pojawią się wkrótce.</p>
                <?php 
                endif; 
            }
            ?>
        </div>
        <div class="text-center mt-12">
            <a href="/produkt/materac-stilco/#reviews" class="inline-block border-2 border-stilco-accent text-stilco-accent hover:bg-stilco-accent hover:text-white font-bold px-10 py-4 rounded-full transition-colors duration-300">
                Zobacz wszystkie opinie
            </a>
        </div>
    </div>
</section>

<!-- B2B / Architekci -->
<section class="py-24 bg-stilco-sand text-stilco-dark relative">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
        <div class="animate-slide-left">
            <span class="text-stilco-accent font-medium uppercase tracking-widest text-sm mb-4 block">Dla Profesjonalistów</span>
            <h2 class="text-3xl md:text-5xl font-display font-bold mb-6">Projektujesz wnętrza?</h2>
            <p class="text-gray-700 text-lg mb-8 max-w-lg">Zapraszamy do współpracy architektów, projektantów oraz hotele. Oferujemy atrakcyjne modele rozliczeń, kompleksowe doradztwo oraz materace, które zachwycą Twoich klientów i gości.</p>
            <a href="/kontakt" class="btn-primary bg-stilco-secondary text-white hover:bg-stilco-dark inline-block px-8 py-4 rounded-full font-medium transition-colors">Poznaj ofertę B2B</a>
        </div>
        <div class="grid grid-cols-2 gap-4 animate-slide-right delay-200">
            <div class="bg-white rounded-3xl p-8 border border-white/50 shadow-sm text-center flex flex-col items-center justify-center">
                <span class="block text-4xl md:text-5xl font-bold text-stilco-accent mb-2">10+</span>
                <span class="text-sm text-gray-500 font-medium tracking-wide uppercase">Lat doświadczenia</span>
            </div>
            <div class="bg-white rounded-3xl p-8 border border-white/50 shadow-sm text-center flex flex-col items-center justify-center">
                <span class="block text-4xl md:text-5xl font-bold text-stilco-accent mb-2">100%</span>
                <span class="text-sm text-gray-500 font-medium tracking-wide uppercase">Polska produkcja</span>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-24 bg-stilco-light border-b border-gray-200" id="faq">
    <div class="max-w-3xl mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-3xl md:text-5xl font-display font-bold mb-4 text-stilco-dark">Masz pytania?</h2>
            <p class="text-gray-600">Oto odpowiedzi na najczęściej zadawane pytania, by rozwiać wszelkie wątpliwości przed zakupem.</p>
        </div>
        
        <div class="space-y-4 animate-on-scroll" id="faq-accordion">
            <!-- Pytanie 1 -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden faq-item cursor-pointer text-left">
                <div class="faq-btn w-full px-6 py-5 flex justify-between items-center select-none focus:outline-none">
                    <span class="font-display font-semibold text-stilco-dark text-lg">Jak długo trwa dostawa?</span>
                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 faq-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
                <div class="faq-content overflow-hidden transition-all duration-300 max-h-0 text-gray-600 text-sm">
                    <div class="px-6 pb-5">
                       Standardowy czas realizacji to od 2 do 5 dni roboczych. Materace wysyłamy na płasko lub starannie zrolowane w grubym kartonie, korzystając z zaufanych firm kurierskich.
                    </div>
                </div>
            </div>
            <!-- Pytanie 2 -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden faq-item cursor-pointer text-left">
                <div class="faq-btn w-full px-6 py-5 flex justify-between items-center select-none focus:outline-none">
                    <span class="font-display font-semibold text-stilco-dark text-lg">Jak działa 100 nocy na próbę?</span>
                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 faq-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
                <div class="faq-content overflow-hidden transition-all duration-300 max-h-0 text-gray-600 text-sm">
                    <div class="px-6 pb-5">
                       Od momentu doręczenia masz 100 dni na testowanie materaca w domowych warunkach. Ciało potrzebuje około 3-4 tygodni, by przyzwyczaić się do nowego podparcia. Jeśli po tym czasie materac nadal Ci nie odpowiada, skontaktuj się z nami – zorganizujemy darmowy zwrot.
                    </div>
                </div>
            </div>
            <!-- Pytanie 3 -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden faq-item cursor-pointer text-left">
                <div class="faq-btn w-full px-6 py-5 flex justify-between items-center select-none focus:outline-none">
                    <span class="font-display font-semibold text-stilco-dark text-lg">Jak prać pokrowiec?</span>
                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 faq-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
                <div class="faq-content overflow-hidden transition-all duration-300 max-h-0 text-gray-600 text-sm">
                    <div class="px-6 pb-5">
                       Pokrowiec posiada zamek 360°, co pozwala na odpięcie górnej lub dolnej warstwy niezależnie. Możesz prać go w pralce w temperaturze do 40°C używając delikatnych detergentów. Susz tradycyjnie, nie wolno suszyć w suszarce bębnowej.
                    </div>
                </div>
            </div>
            <!-- Pytanie 4 -->
             <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden faq-item cursor-pointer text-left">
                <div class="faq-btn w-full px-6 py-5 flex justify-between items-center select-none focus:outline-none">
                    <span class="font-display font-semibold text-stilco-dark text-lg">Czy materac jest dwustronny?</span>
                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 faq-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
                <div class="faq-content overflow-hidden transition-all duration-300 max-h-0 text-gray-600 text-sm">
                    <div class="px-6 pb-5">
                       Tak! Nasz model posiada dwie różne strony twardości. Strona z pianką Visco to odczucie "otulenia" (H2), a druga strona (H3) z pianką HR zapewnia stabilniejsze, nieco twardsze podparcie. Ty decydujesz, jak wolisz spać.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer();
