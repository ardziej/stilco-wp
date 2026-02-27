<?php
/**
 * Template Name: O Nas (About)
 * 
 * Szablon strony o firmie. Wdraża wszystkie elementy omówione w about.md.
 */
get_header(); ?>

<main class="w-full">
    <!-- 1. Hero Banner -->
    <section class="relative w-full h-[60vh] min-h-[500px] flex items-center justify-center -mt-[88px] pt-[88px]">
        <!-- Tło zdjęcia -->
        <div class="absolute inset-0 w-full h-full z-0">
            <!-- Pamiętaj o podmianie na właściwe zdjęcie lifestylowe ze szwalni -->
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-mattress.jpg" class="w-full h-full object-cover" alt="Polska Szwalnia Stilco">
            <!-- Ciemny overlay -->
            <div class="absolute inset-0 bg-stilco-dark/40"></div>
        </div>
        
        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto animate-on-scroll">
            <h1 class="text-5xl md:text-7xl font-serif text-white font-bold mb-6 tracking-tight drop-shadow-md">
                Rodzinna firma z bogatym doświadczeniem.
            </h1>
            <p class="text-xl md:text-2xl text-white/90 font-light drop-shadow">
                Od 1994 roku tworzymy komfort, który każdy może poczuć.
            </p>
        </div>
    </section>

    <!-- 2. Założyciele - Daniel i Edyta -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div class="image-wrapper order-2 md:order-1 relative group animate-slide-left">
                <!-- Miękki cień za zdjęciem (efekt szałwii lub beżu) -->
                <div class="absolute -inset-4 bg-stilco-sand rounded-[3rem] transform -rotate-3 transition-transform group-hover:rotate-0 duration-700 ease-out z-0"></div>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/edyta-daniel.jpg" alt="Daniel i Edyta Stilco" class="relative z-10 rounded-[2rem] w-full h-auto object-cover shadow-xl aspect-square md:aspect-[4/5]">
            </div>
            
            <div class="content-wrapper order-1 md:order-2 animate-slide-right delay-200">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-stilco-dark mb-6">Stilco to nie korporacja,<br> to my – Daniel i Edyta.</h2>
                <div class="prose prose-lg text-gray-600 font-sans leading-relaxed">
                    <p>Każdy materac powielony w ofercie powstał z naszej bezsenności, i potrzeby stworzenia idealnego środowiska do regeneracji.</p>
                    <p>Po latach testowania i rozczarowań rynkiem ubrań, a potem łóżek – postanowiliśmy wziąć sprawy we własne ręce, w naszej wrocławskiej szwalni. To co zaczęło się jako poszukiwanie wygody dla nas samych, szybko przerodziło się w misję dostarczenia luksusowego snu każdemu Polakowi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Oś Czasu Powstawania Firmy -->
    <section class="py-24 bg-stilco-sand overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-display font-semibold text-center text-stilco-dark mb-16">Nasza Droga</h2>
            
            <!-- Horizontal Scroll Container na mobile, Grid na desktop -->
            <div class="flex overflow-x-auto md:grid md:grid-cols-4 gap-8 pb-8 snap-x">
                
                <!-- Krok 1 -->
                <div class="min-w-[80vw] md:min-w-0 snap-center relative pt-8 animate-on-scroll delay-100">
                    <div class="absolute top-0 left-0 w-full h-0.5 bg-stilco-accent/30 hidden md:block"></div>
                    <div class="absolute top-[-5px] left-0 w-3 h-3 rounded-full bg-stilco-accent hidden md:block"></div>
                    <span class="text-stilco-accent font-bold text-xl mb-2 block">1994</span>
                    <h3 class="font-display font-semibold text-lg text-stilco-dark mb-4">Narodziny firmy</h3>
                    <p class="text-sm text-gray-600">Zakładamy rodzinną firmę w Malborku. Dobre relacje i wzajemny szacunek stają się fundamentem naszej działalności.</p>
                </div>

                <!-- Krok 2 -->
                <div class="min-w-[80vw] md:min-w-0 snap-center relative pt-8 animate-on-scroll delay-200">
                    <div class="absolute top-0 left-0 w-full h-0.5 bg-stilco-accent/30 hidden md:block"></div>
                    <div class="absolute top-[-5px] left-0 w-3 h-3 rounded-full bg-stilco-accent hidden md:block"></div>
                    <span class="text-stilco-accent font-bold text-xl mb-2 block">2016</span>
                    <h3 class="font-display font-semibold text-lg text-stilco-dark mb-4">Pierwsze badania</h3>
                    <p class="text-sm text-gray-600">Rozpoczynamy intensywne prace badawcze nad właściwościami pianki poliuretanowej i włókien poliestrowych.</p>
                </div>

                <!-- Krok 3 -->
                <div class="min-w-[80vw] md:min-w-0 snap-center relative pt-8 animate-on-scroll delay-300">
                    <div class="absolute top-0 left-0 w-full h-0.5 bg-stilco-accent/30 hidden md:block"></div>
                    <div class="absolute top-[-5px] left-0 w-3 h-3 rounded-full bg-stilco-accent hidden md:block"></div>
                    <span class="text-stilco-accent font-bold text-xl mb-2 block">2023</span>
                    <h3 class="font-display font-semibold text-lg text-stilco-dark mb-4">Testy i doskonalenie</h3>
                    <p class="text-sm text-gray-600">Lata testów i iteracji. Każde wypełnienie sprawdzane pod kątem trwałości, sprężystości i komfortu snu.</p>
                </div>

                <!-- Krok 4 -->
                <div class="min-w-[80vw] md:min-w-0 snap-center relative pt-8 animate-on-scroll delay-400">
                    <div class="absolute top-0 left-0 w-full h-0.5 bg-stilco-accent/30 hidden md:block"></div>
                    <div class="absolute top-[-5px] left-0 w-3 h-3 rounded-full bg-stilco-accent hidden md:block"></div>
                    <span class="text-stilco-accent font-bold text-xl mb-2 block">Dziś</span>
                    <h3 class="font-display font-semibold text-lg text-stilco-dark mb-4">Globalny zasięg</h3>
                    <p class="text-sm text-gray-600">Dostarczamy produkty do odbiorców z Polski, Europy i całego świata. Komfort snu staje się standardem dostępnym dla każdego.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- 4. Bento Box: Co nas wyróżnia? -->
    <section class="py-24 bg-stilco-light">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl md:text-5xl font-serif font-bold text-center text-stilco-dark mb-16">Co nas wyróżnia?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-[250px]">
                
                <!-- Kafel 1 -->
                <div class="bg-white rounded-3xl p-8 shadow-sm flex flex-col justify-end border border-gray-100 md:col-span-2 relative overflow-hidden group animate-zoom">
                    <div class="relative z-10 w-full md:w-1/2">
                        <h3 class="text-2xl font-serif font-semibold text-stilco-dark mb-2">Pianki Premium</h3>
                        <p class="text-gray-600">Używamy droższych i trwalszych pianek kalibrowanych (np HR40 zamiast taniej T25). Zero kompromisów przy dnie łóżka.</p>
                    </div>
                </div>

                <!-- Kafel 2 (Z akcentem) -->
                <div class="bg-stilco-accent rounded-3xl p-8 shadow-md flex flex-col justify-center items-center text-center transform transition-transform hover:-translate-y-2 duration-500 animate-zoom delay-100">
                    <svg class="w-12 h-12 text-white mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 15.536c-1.171 1.952-3.07 1.952-4.242 0-1.172-1.953-1.172-5.119 0-7.072 1.171-1.952 3.07-1.952 4.242 0M8 10.5h4m-4 3h4m9-1.5a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-xl font-display font-semibold text-white mb-2">Mistrzowska Precyzja</h3>
                    <p class="text-white/80 text-sm">Szyjemy i tniemy z dbałością o każdy milimetr konstrukcji.</p>
                </div>

                <!-- Kafel 3 -->
                <div class="bg-white rounded-3xl p-8 shadow-sm flex flex-col justify-center border border-gray-100 animate-zoom delay-200">
                    <div class="mt-auto">
                        <h3 class="text-xl font-display font-semibold text-stilco-dark mb-2">Szybka Logistyka</h3>
                        <p class="text-gray-600 text-sm">Odbiór nawet do 48h dzięki innowacyjnej prasie rolującej paczki przed samym wysłaniem.</p>
                    </div>
                </div>

                 <!-- Kafel 4 (Delivery image) -->
                <div class="bg-gray-200 rounded-3xl overflow-hidden md:col-span-2 relative animate-zoom delay-300">
                     <img src="<?php echo get_template_directory_uri(); ?>/assets/images/delivery-materac.jpg" class="w-full h-full object-cover" alt="Szybka dostawa materaca">
                </div>
            </div>
        </div>
    </section>

    <!-- 5. CTA -->
    <section class="py-24 bg-white text-center">
        <div class="max-w-3xl mx-auto px-6">
            <h2 class="text-4xl font-serif font-bold text-stilco-dark mb-8">Zacznij wysypiać się już jutro.</h2>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="inline-block bg-stilco-accent text-white font-medium text-lg px-12 py-5 rounded-full shadow-lg shadow-stilco-accent/40 transform hover:scale-105 transition-all duration-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-dark">
                Poznaj nasze bestsellery
            </a>
        </div>
    </section>

</main>

<?php get_footer(); ?>
