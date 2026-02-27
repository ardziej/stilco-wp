<?php
/**
 * Template Name: Materac (Mattress Landing Page)
 * 
 * Szablon dedykowany dla strony produktu (Materac),
 * implementujący zaawansowany lejek sprzedażowy i opis korzyści.
 */
get_header(); 

// Zmienne ścieżek do obrazków
$img_dir = get_template_directory_uri() . '/assets/images/';
?>

<main class="w-full bg-stilco-light min-h-screen pb-32">
    
    <!-- 1. Moduł Główny (Buy Box) -->
    <section class="pt-32 pb-16 lg:pt-40 lg:pb-24">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-stretch">
            
            <!-- Lewa: Galeria (Sticky) -->
            <div class="relative lg:sticky lg:top-32 h-fit space-y-4 animate-slide-left" id="product-gallery">
                <!-- Odznaka Bestseller -->
                <div class="absolute top-4 right-4 z-10 bg-stilco-sand border border-white/50 shadow-sm text-stilco-dark text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-full hidden md:block">
                    Bestseller
                </div>
                
                <!-- Główne zdjęcie -->
                <div class="w-full aspect-square md:aspect-[4/3] rounded-[2rem] overflow-hidden bg-gray-100 shadow-sm border border-gray-100 mb-4 relative cursor-zoom-in group">
                    <img id="main-product-image" src="<?php echo esc_url($img_dir . '1.jpg'); ?>" alt="Materac Stilco w sypialni" class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-105 mix-blend-multiply">
                </div>
                
                <!-- Skrolowalne Miniaturki -->
                <div class="flex overflow-x-auto space-x-3 pb-4 snap-x no-scrollbar" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <?php 
                    $gallery_images = array(
                        '1.jpg', '2.jpg', '3.jpg', '4.jpg', 
                        'product-close.JPG', '20241102_130956.jpg', '20241102_133444.jpg', '20241102_141035.jpg'
                    );
                    foreach ($gallery_images as $index => $img): 
                        // Set the first image as active
                        $active_class = $index === 0 ? 'border-stilco-accent opacity-100' : 'border-transparent opacity-70 hover:opacity-100';
                    ?>
                    <button type="button" class="gallery-thumb flex-shrink-0 w-24 h-24 sm:w-28 sm:h-28 aspect-square rounded-2xl overflow-hidden bg-white cursor-pointer border-2 transition-all snap-center <?php echo $active_class; ?>" data-image="<?php echo esc_url($img_dir . $img); ?>">
                        <img src="<?php echo esc_url($img_dir . $img); ?>" class="w-full h-full object-cover" alt="Detal materaca">
                    </button>
                    <?php endforeach; ?>
                </div>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const mainImage = document.getElementById('main-product-image');
                    const thumbs = document.querySelectorAll('.gallery-thumb');

                    thumbs.forEach(thumb => {
                        thumb.addEventListener('click', function() {
                            // Update main image
                            const newSrc = this.getAttribute('data-image');
                            mainImage.style.opacity = '0.5';
                            
                            setTimeout(() => {
                                mainImage.src = newSrc;
                                mainImage.style.opacity = '1';
                            }, 150); // small fade effect

                            // Update active state on thumbs
                            thumbs.forEach(t => {
                                t.classList.remove('border-stilco-accent', 'opacity-100');
                                t.classList.add('border-transparent', 'opacity-70');
                            });
                            this.classList.remove('border-transparent', 'opacity-70');
                            this.classList.add('border-stilco-accent', 'opacity-100');
                        });
                    });
                });
                </script>
                
                <style>
                .no-scrollbar::-webkit-scrollbar {
                    display: none;
                }
                #main-product-image {
                    transition: opacity 0.15s ease-in-out, transform 0.5s ease-in-out;
                }
                </style>
            </div>

            <!-- Prawa: Obszar Zakupowy -->
            <div class="flex flex-col justify-center animate-slide-right delay-200">
                
                <!-- Oceny -->
                <div class="flex items-center space-x-2 mb-4">
                    <div class="flex text-stilco-accent">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-500 underline decoration-gray-300 underline-offset-4 cursor-pointer hover:text-stilco-dark transition-colors">4.9/5 (Czytaj opinie)</span>
                </div>

                <!-- Tytuł -->
                <h1 class="text-4xl lg:text-5xl font-serif text-stilco-dark font-bold mb-4 tracking-tight">Materac Stilco</h1>
                <p class="text-lg text-gray-600 mb-8 font-sans">Otulająca warstwa Visco i stabilna baza HR w symfonicznym duecie. Grubość 22 cm idealnego komfortu.</p>
                
                <!-- Cena dynamiczna -->
                <div class="mb-8 border-t border-gray-200 pt-6">
                    <span class="text-sm text-gray-500 font-medium tracking-wide uppercase block mb-1">Cena z dostawą</span>
                    <div class="flex items-baseline space-x-3">
                        <span id="price-display" class="text-4xl font-sans font-bold text-stilco-accent">2 888 zł</span>
                        <span class="text-sm text-gray-400 line-through hidden" id="old-price">3 100 zł</span>
                    </div>
                </div>

                <!-- Konfigurator Rozmiaru (Pills) -->
                <div class="mb-8">
                    <div class="flex justify-between items-end mb-4">
                        <span class="font-display font-semibold text-stilco-dark">Wybierz rozmiar:</span>
                        <a href="#" class="text-sm text-gray-500 hover:text-stilco-accent transition-colors underline underline-offset-2">Jak zmierzyć łóżko?</a>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-3">
                        <button type="button" class="size-btn border border-gray-300 bg-white text-stilco-dark py-3 px-2 rounded-full font-medium hover:border-stilco-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent transition-all" data-price="2595">80x200</button>
                        <button type="button" class="size-btn border border-gray-300 bg-white text-stilco-dark py-3 px-2 rounded-full font-medium hover:border-stilco-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent transition-all" data-price="2808">90x200</button>
                        <button type="button" class="size-btn border-2 border-stilco-accent bg-stilco-accent text-white py-3 px-2 rounded-full font-medium shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent transition-all" data-price="2888">120x200</button>
                        <button type="button" class="size-btn border border-gray-300 bg-white text-stilco-dark py-3 px-2 rounded-full font-medium hover:border-stilco-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent transition-all" data-price="3782">140x200</button>
                        <button type="button" class="size-btn border border-gray-300 bg-white text-stilco-dark py-3 px-2 rounded-full font-medium hover:border-stilco-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent transition-all" data-price="4455">160x200</button>
                        <button type="button" class="size-btn border border-gray-300 bg-white text-stilco-dark py-3 px-2 rounded-full font-medium hover:border-stilco-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent transition-all" data-price="5091">180x200</button>
                    </div>
                </div>

                <!-- Główne CTA -->
                <button type="button" class="w-full bg-stilco-accent text-white rounded-full py-5 text-xl font-medium shadow-lg shadow-stilco-accent/30 hover:scale-[1.02] hover:bg-[#A84A34] transition-all duration-300 mb-8 flex justify-center items-center group">
                    Dodaj do koszyka
                    <svg class="w-6 h-6 ml-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>

                <!-- Trust Badges (Mini Bento) -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 pt-6 border-t border-gray-100">
                    <div class="flex flex-col items-center justify-center p-4 bg-white rounded-2xl shadow-sm text-center">
                         <svg class="w-8 h-8 text-stilco-accent mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                         <span class="text-[11px] font-semibold text-gray-500 uppercase">100 nocy<br>testowych</span>
                    </div>
                    <div class="flex flex-col items-center justify-center p-4 bg-white rounded-2xl shadow-sm text-center">
                         <svg class="w-8 h-8 text-stilco-accent mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                         <span class="text-[11px] font-semibold text-gray-500 uppercase">10 lat<br>gwarancji</span>
                    </div>
                    <div class="flex flex-col items-center justify-center p-4 bg-white rounded-2xl shadow-sm text-center">
                         <svg class="w-8 h-8 text-stilco-accent mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                         <span class="text-[11px] font-semibold text-gray-500 uppercase">Darmowa<br>Dostawa</span>
                    </div>
                    <div class="flex flex-col items-center justify-center p-4 bg-white rounded-2xl shadow-sm text-center">
                        <svg class="w-8 h-8 text-stilco-accent mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                        <span class="text-[11px] font-semibold text-gray-500 uppercase">Polska<br>Produkcja</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. Przewagi Składu (Korzyści płynące ze struktury) -->
    <section class="py-24 bg-white overflow-hidden relative">
        <div class="absolute inset-0 bg-stilco-sand/30 transform -skew-y-2 origin-top-left -z-10"></div>
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16 max-w-2xl mx-auto">
                <span class="text-stilco-accent font-bold uppercase tracking-widest text-sm block mb-2">Struktura</span>
                <h2 class="text-4xl lg:text-5xl font-serif text-stilco-dark font-bold mb-6">Technologia zdrowego snu. Odkryj wnętrze.</h2>
            </div>

            <!-- Warstwa 1: Visco -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-24 animate-on-scroll">
                <div class="order-2 md:order-1">
                    <h3 class="text-3xl font-display font-semibold text-stilco-dark mb-4 drop-shadow-sm">Górna warstwa: 5 cm pianki Visco</h3>
                    <p class="text-lg text-gray-600 font-sans leading-relaxed mb-6">
                        Termoelastyczna piana (tzw. "memory foam") o gęstości 45 kg/m3. Pod wpływem Twojego ciepła, pianka ustępuje dokładnie tam, gdzie pojawia się największy nacisk – na biodrach i barkach. 
                    </p>
                    <ul class="space-y-3 font-medium text-gray-700">
                        <li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Idealne dopasowanie 1:1 do ciała</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Eliminacja porannych drętwień</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Zero nacisku zwrotnego</li>
                    </ul>
                </div>
                <!-- Używamy image198.jpg z maską obłą -->
                <div class="order-1 md:order-2">
                    <div class="w-full aspect-[4/3] rounded-[3rem] overflow-hidden shadow-xl transform rotate-1 hover:rotate-0 transition-transform duration-700">
                        <img src="<?php echo esc_url($img_dir . 'image198.jpg'); ?>" alt="Warstwa Visco" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <!-- Warstwa 2: Baza HR -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center animate-on-scroll delay-100">
                <!-- Używamy image205.jpg (przekrój lub piana bazowa) z lewej -->
                <div class="order-1">
                    <div class="w-full aspect-[4/3] rounded-[3rem] overflow-hidden shadow-xl transform -rotate-1 hover:rotate-0 transition-transform duration-700">
                        <img src="<?php echo esc_url($img_dir . 'image205.jpg'); ?>" alt="Baza HR" class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="order-2">
                    <h3 class="text-3xl font-display font-semibold text-stilco-dark mb-4 drop-shadow-sm">Fundament: 15 cm bazy HR (High Resilence)</h3>
                    <p class="text-lg text-gray-600 font-sans leading-relaxed mb-6">
                        Otwartokomórkowa piana wysokoelastyczna (40 kg/m3). Stanowi "kręgosłup" Twojego materaca. Zapobiega zapadaniu się ciała, gwarantując przewiewność i stabilne oparcie przez całą noc.
                    </p>
                    <ul class="space-y-3 font-medium text-gray-700">
                        <li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Wysoka oddychalność anty-podgrzewcza</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Podstawa absorbująca wstrząsy dla 2 osób</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Odporność na wygniatanie na lata</li>
                    </ul>
                </div>
            </div>

        </div>
    </section>

    <!-- 3. Opinie klientów wizualne -->
    <section class="py-24 bg-stilco-sand">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl md:text-5xl font-serif font-bold text-center text-stilco-dark mb-12">Historie naszych klientów</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Viedeo/Image placeholder 1 -->
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-white/50 animate-zoom">
                    <div class="aspect-[9/16] rounded-xl overflow-hidden mb-4 relative">
                        <img src="<?php echo esc_url($img_dir . '20241102_130956.jpg'); ?>" class="w-full h-full object-cover">
                        <!-- Play button icon -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="w-14 h-14 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex text-stilco-accent mb-2">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                    </div>
                    <p class="text-sm font-semibold mb-1 text-stilco-dark">"Koniec bólu pleców!"</p>
                    <p class="text-xs text-gray-500">Agnieszka (Rozmiar: 160x200)</p>
                </div>
                
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-white/50 animate-zoom delay-100">
                    <div class="aspect-[9/16] rounded-xl overflow-hidden mb-4 relative">
                        <img src="<?php echo esc_url($img_dir . '20241102_133444.jpg'); ?>" class="w-full h-full object-cover">
                        <!-- Play button icon -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="w-14 h-14 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex text-stilco-accent mb-2">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                    </div>
                    <p class="text-sm font-semibold mb-1 text-stilco-dark">"Śpimy jak w chmurach"</p>
                    <p class="text-xs text-gray-500">Marta i Tomek (Rozmiar: 180x200)</p>
                </div>
                
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-white/50 animate-zoom delay-200">
                    <div class="aspect-[9/16] rounded-xl overflow-hidden mb-4 relative">
                        <img src="<?php echo esc_url($img_dir . '20241102_133839.jpg'); ?>" class="w-full h-full object-cover">
                        <!-- Play button icon -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="w-14 h-14 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex text-stilco-accent mb-2">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                    </div>
                    <p class="text-sm font-semibold mb-1 text-stilco-dark">"Zakup Życia!"</p>
                    <p class="text-xs text-gray-500">Kamil (Rozmiar: 140x200)</p>
                </div>
            </div>
            
        </div>
    </section>

    <!-- 4. Przycisk "Kup Teraz" (Zamykający) na dawnym szałwiowym, teraz wykorzystamy accent/sand -->
    <section class="py-24 bg-stilco-dark text-center overflow-hidden relative">
        <div class="absolute inset-0 z-0 opacity-10 blur-xl">
           <img src="<?php echo esc_url($img_dir . 'image112.jpg'); ?>" class="w-full h-full object-cover">
        </div>
        <div class="max-w-4xl mx-auto px-6 relative z-10 animate-zoom">
            <h2 class="text-4xl md:text-6xl font-serif text-white font-bold mb-8 drop-shadow-lg">100 dni na podjęcie decyzji.</h2>
            <p class="text-xl text-white/80 font-sans mb-12">Jeżeli materac nie poprawi jakości Twojego snu w ciągu 100 nocy, zwrócimy Ci pełną kwotę.</p>
            <button type="button" class="bg-stilco-accent text-white rounded-full px-16 py-6 text-xl font-medium shadow-xl hover:scale-105 transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-accent">
                Zacznij wysypiać się już jutro
            </button>
        </div>
    </section>

    <!-- 5. Sticky Bottom Bar na konwersję (Mobile głównie) -->
    <div id="sticky-cart-bar" class="fixed bottom-0 left-0 w-full bg-white/80 backdrop-blur-xl border-t border-white/40 shadow-[0_-10px_30px_rgba(0,0,0,0.05)] z-40 transform translate-y-full transition-transform duration-500 py-3 px-6 hidden md:block">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100 hidden sm:block">
                    <img src="<?php echo esc_url($img_dir . 'image112.jpg'); ?>" class="w-full h-full object-cover">
                </div>
                <div>
                    <h4 class="font-serif font-bold text-stilco-dark text-sm sm:text-base">Materac Stilco</h4>
                    <span class="text-stilco-accent font-semibold text-sm">od 2 595 zł</span>
                </div>
            </div>
            <button type="button" class="bg-stilco-accent text-white font-medium py-3 px-8 rounded-full shadow-md hover:bg-[#A84A34] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-accent text-sm md:text-base whitespace-nowrap">
                Dodaj do koszyka
            </button>
        </div>
    </div>

</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sticky Cart Bar logic
        const stickyBar = document.getElementById('sticky-cart-bar');
        const triggerPoint = 600; // pixels down to trigger
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > triggerPoint) {
                stickyBar.classList.remove('translate-y-full');
            } else {
                stickyBar.classList.add('translate-y-full');
            }
        });

        // Pill select logic
        const buttons = document.querySelectorAll('.size-btn');
        const priceDisplay = document.getElementById('price-display');
        
        buttons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active classes
                buttons.forEach(b => {
                    b.classList.remove('border-2', 'border-stilco-accent', 'bg-stilco-accent', 'text-white', 'shadow-md');
                    b.classList.add('border', 'border-gray-300', 'bg-white', 'text-stilco-dark');
                });
                
                // Add active to clicked
                this.classList.remove('border', 'border-gray-300', 'bg-white', 'text-stilco-dark');
                this.classList.add('border-2', 'border-stilco-accent', 'bg-stilco-accent', 'text-white', 'shadow-md');
                
                // Update price
                const price = this.getAttribute('data-price');
                // Format price with spaces
                const formattedPrice = price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                priceDisplay.innerHTML = formattedPrice + ' zł';
            });
        });
    });
</script>

<?php get_footer(); ?>
