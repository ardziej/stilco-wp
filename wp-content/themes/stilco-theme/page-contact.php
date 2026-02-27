<?php
/**
 * Template Name: Kontakt (Contact)
 * 
 * Szablon strony kontaktowej.
 */
get_header(); ?>

<main class="w-full bg-stilco-light min-h-screen">
    
    <!-- 1. Hero Banner -->
    <section class="relative w-full h-[40vh] min-h-[300px] flex items-center justify-center -mt-[88px] pt-[88px]">
        <div class="absolute inset-0 w-full h-full z-0">
            <!-- Zdjęcie Zamku Krzyżackiego w Malborku -->
            <img src="https://upload.wikimedia.org/wikipedia/commons/e/e6/Zesp%C3%B3%C5%82_Zamku_Krzy%C5%BCackiego_MALBORK_01.jpg" class="w-full h-full object-cover" alt="Zamek Krzyżacki w Malborku">
            <div class="absolute inset-0 bg-stilco-dark/60"></div>
        </div>
        
        <div class="relative z-10 text-center px-6 max-w-3xl mx-auto animate-on-scroll">
            <h1 class="text-5xl md:text-6xl font-serif text-white font-bold mb-4 tracking-tight drop-shadow-md">
                Porozmawiajmy o dobrym śnie
            </h1>
        </div>
    </section>

    <!-- 2. Podwójna Kolumna: Formularz i Informacje -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-16">
            
            <!-- Lewa: Sticky Info -->
            <div class="lg:col-span-4 relative animate-slide-left">
                <div class="sticky top-32 space-y-8">
                    <div>
                        <h2 class="text-2xl font-display font-semibold text-stilco-dark mb-2">Jesteśmy tutaj</h2>
                        <p class="text-gray-600 font-sans">Pomagamy wybrać idealny materac i odpowiedzieć na każde pytanie dotyczące Twojego zamówienia.</p>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Daniel -->
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                            <p class="text-xs text-stilco-accent font-semibold uppercase tracking-wide mb-3">Daniel – współzałożyciel</p>
                            <div class="space-y-2">
                                <a href="tel:+48609675614" class="group flex items-center gap-3 hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-lg">
                                    <span class="bg-stilco-sand p-2 rounded-full text-stilco-accent group-hover:bg-stilco-accent group-hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </span>
                                    <span class="font-semibold text-stilco-dark group-hover:text-stilco-accent transition-colors">+48 609 675 614</span>
                                </a>
                                <a href="mailto:daniel@stilco.pl" class="group flex items-center gap-3 hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-lg">
                                    <span class="bg-stilco-sand p-2 rounded-full text-stilco-accent group-hover:bg-stilco-accent group-hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </span>
                                    <span class="font-semibold text-stilco-dark group-hover:text-stilco-accent transition-colors">daniel@stilco.pl</span>
                                </a>
                            </div>
                        </div>

                        <!-- Edyta -->
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                            <p class="text-xs text-stilco-accent font-semibold uppercase tracking-wide mb-3">Edyta – współzałożycielka</p>
                            <div class="space-y-2">
                                <a href="tel:+48695929675" class="group flex items-center gap-3 hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-lg">
                                    <span class="bg-stilco-sand p-2 rounded-full text-stilco-accent group-hover:bg-stilco-accent group-hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </span>
                                    <span class="font-semibold text-stilco-dark group-hover:text-stilco-accent transition-colors">+48 695 929 675</span>
                                </a>
                                <a href="mailto:edyta@stilco.pl" class="group flex items-center gap-3 hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-lg">
                                    <span class="bg-stilco-sand p-2 rounded-full text-stilco-accent group-hover:bg-stilco-accent group-hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </span>
                                    <span class="font-semibold text-stilco-dark group-hover:text-stilco-accent transition-colors">edyta@stilco.pl</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-stilco-sand rounded-3xl p-8 border border-stilco-secondary/30 mt-8">
                        <span class="block text-sm text-gray-500 mb-2 uppercase tracking-wide font-medium">Szwalnia i Produkcja</span>
                        <h3 class="font-display font-semibold text-stilco-dark text-lg mb-1">Stilco</h3>
                        <p class="text-gray-600">Daleka 122<br>82-200 Malbork, Polska</p>
                    </div>
                </div>
            </div>

            <!-- Prawa: Formularz (WCAG 2.2) -->
            <div class="lg:col-span-8 animate-slide-right delay-200">
                <div class="bg-white rounded-[2.5rem] p-8 md:p-14 shadow-xl border border-gray-100">
                    <h2 class="text-3xl font-serif font-semibold text-stilco-dark mb-8">Wyślij wiadomość bezpośrednią</h2>
                    
                    <form action="#" method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="relative">
                                <label for="name" class="block text-sm font-medium text-stilco-dark mb-2">Imię <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-stilco-dark focus:ring-2 focus:ring-stilco-accent focus:border-transparent outline-none transition-all focus:bg-white focus:shadow-sm" placeholder="Twoje imię">
                            </div>
                            
                            <div class="relative">
                                <label for="email" class="block text-sm font-medium text-stilco-dark mb-2">E-mail <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-stilco-dark focus:ring-2 focus:ring-stilco-accent focus:border-transparent outline-none transition-all focus:bg-white focus:shadow-sm" placeholder="twój.adres@email.pl">
                            </div>
                        </div>

                        <div class="relative">
                            <label for="phone" class="block text-sm font-medium text-stilco-dark mb-2">Numer telefonu (opcjonalnie)</label>
                            <input type="tel" id="phone" name="phone" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-stilco-dark focus:ring-2 focus:ring-stilco-accent focus:border-transparent outline-none transition-all focus:bg-white focus:shadow-sm" placeholder="+48 --- --- ---">
                        </div>

                        <div class="relative">
                            <label for="message" class="block text-sm font-medium text-stilco-dark mb-2">Szczegóły wiadomości <span class="text-red-500">*</span></label>
                            <textarea id="message" name="message" rows="6" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-stilco-dark focus:ring-2 focus:ring-stilco-accent focus:border-transparent outline-none transition-all focus:bg-white focus:shadow-sm resize-y" placeholder="Jak możemy Ci dzisiaj pomóc?"></textarea>
                        </div>

                        <div class="flex items-start mb-6">
                            <div class="flex items-center h-5 mt-1">
                                <input id="rodo" name="rodo" type="checkbox" required class="w-6 h-6 text-stilco-accent bg-gray-100 border-gray-300 rounded focus:ring-stilco-accent focus:ring-2 cursor-pointer">
                            </div>
                            <label for="rodo" class="ml-3 text-sm text-gray-600 w-full cursor-pointer leading-relaxed">
                                Wyrażam zgodę na przetwarzanie moich danych osobowych przez Stilco Sp. z o.o. w celu obsługi zapytania. Znam zasady z <a href="/polityka-prywatnosci" class="text-stilco-accent hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent">Polityki Prywatności</a>.
                            </label>
                        </div>

                        <button type="submit" class="w-full md:w-auto px-10 py-5 bg-stilco-accent text-white font-medium text-lg rounded-full shadow-lg shadow-stilco-accent/30 hover:bg-[#A84A34] transition-all transform hover:scale-105 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-dark">
                            Wyślij Wiadomość
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Interaktywna Mapa -->
    <section class="pb-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="w-full h-[500px] rounded-[3rem] overflow-hidden shadow-sm relative group animate-zoom delay-100">
                <!-- Fallback obraz mapy w razie blokady iframe / Dummy Iframe dla podglądu -->
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2371.5!2d19.0270!3d54.0290!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46fda0a0a0a0a0a0%3A0x0!2sDaleka+122%2C+82-200+Malbork!5e0!3m2!1spl!2spl!4v1700000000000!5m2!1spl!2spl" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="grayscale mix-blend-multiply opacity-80 group-hover:grayscale-0 group-hover:opacity-100 group-hover:mix-blend-normal transition-all duration-700"></iframe>
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-stilco-dark/80 to-transparent p-10 pointer-events-none">
                    <h3 class="text-white text-3xl font-serif font-bold mb-2">Zaufaj Lokalnej Jakości.</h3>
                    <p class="text-white/80">Wysyłamy na całą Europę prosto stąd.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. FAQ Ribbon -->
    <section class="bg-stilco-secondary/20 py-16 text-center border-t border-stilco-secondary/30">
        <div class="max-w-3xl mx-auto px-6">
            <h3 class="text-2xl font-serif text-stilco-dark font-semibold mb-4">Nie lubisz czekać na odpowiedź?</h3>
            <p class="text-gray-600 mb-8 max-w-lg mx-auto">Sprawdź nasze FAQ, w którym odpowiadamy wprost na 90% pytań dotyczących materaca i logistyki.</p>
            <a href="/faq" class="inline-block bg-white text-stilco-dark border border-gray-200 font-medium px-8 py-3 rounded-full hover:border-stilco-accent hover:text-stilco-accent transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent shadow-sm">
                Przejdź do Pytań i Odpowiedzi
            </a>
        </div>
    </section>

</main>

<?php get_footer(); ?>
