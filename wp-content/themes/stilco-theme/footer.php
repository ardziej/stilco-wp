</div> <!-- Zamykanie main-content z header.php -->

<?php if ( ! (function_exists('is_checkout') && is_checkout() && ! is_wc_endpoint_url('order-received')) ) : ?>
<footer class="bg-stilco-sand text-stilco-dark pt-24 pb-12 px-6 md:px-12 mt-20 border-t border-stilco-secondary/20 rounded-t-[3rem]">
    <!-- Newsletter Warstwa Górna -->
    <div class="max-w-4xl mx-auto text-center mb-24">
        <h2 class="text-4xl md:text-6xl font-serif font-bold mb-6 text-stilco-dark tracking-tight leading-tight">Obudź się z pomysłem na lepszy sen.</h2>
        <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">Odbierz 10% rabatu na pierwsze zamówienie. Zapisz się do naszego biuletynu pełnego wskazówek jak podnieść jakość wypoczynku.</p>
        <form class="flex flex-col sm:flex-row max-w-lg mx-auto gap-3">
            <input type="email" placeholder="Twój adres e-mail" aria-label="Adres e-mail"
                class="w-full px-6 py-4 bg-white border border-gray-200 rounded-full text-stilco-dark focus:ring-2 focus:ring-stilco-accent focus:border-transparent outline-none shadow-sm transition-all focus:shadow-md">
            <button type="submit"
                class="bg-stilco-accent px-8 py-4 rounded-full font-medium text-white hover:bg-[#A84A34] transition-colors whitespace-nowrap shadow-lg shadow-stilco-accent/30 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-dark">
                Zapisz się
            </button>
        </form>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 border-t border-stilco-dark/10 pt-16">
        <!-- Kolumna 1: Brand -->
        <div class="footer-brand space-y-4">
            <h3 class="text-3xl font-display font-bold tracking-tighter">STILCO</h3>
            <p class="text-sm text-gray-600 leading-relaxed pr-6">Twoje królestwo snu. Projektujemy organiczne materace dla idealnego, nocnego wypoczynku we dwoje. Polska produkcja.</p>
        </div>

        <!-- Kolumna 2: Sklep -->
        <div class="footer-links">
            <h4 class="font-display font-semibold text-lg mb-6 text-stilco-dark">Sklep</h4>
            <ul class="space-y-3 text-sm text-gray-600 font-medium">
                <li><a href="/sklep" class="hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm px-1 -mx-1">Materace</a></li>
                <li><a href="/kategoria/akcesoria" class="hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm px-1 -mx-1">Akcesoria</a></li>
                <li><a href="/karty-podarunkowe" class="hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm px-1 -mx-1">Karty Podarunkowe</a></li>
            </ul>
        </div>

        <!-- Kolumna 3: Firma -->
        <div class="footer-company">
            <h4 class="font-display font-semibold text-lg mb-6 text-stilco-dark">Firma</h4>
            <ul class="space-y-3 text-sm text-gray-600 font-medium">
                <li><a href="/o-nas" class="hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm px-1 -mx-1">O nas</a></li>
                <li><a href="/kontakt" class="hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm px-1 -mx-1">Kontakt</a></li>
                <li><a href="/opinie" class="hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm px-1 -mx-1">Opinie klientów</a></li>
            </ul>
        </div>

        <!-- Kolumna 4: Pomoc -->
        <div class="footer-contact">
            <h4 class="font-display font-semibold text-lg mb-6 text-stilco-dark">Wsparcie</h4>
            <ul class="space-y-3 text-sm text-gray-600 font-medium">
                <li><a href="/faq" class="hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm px-1 -mx-1">FAQ & Pytania</a></li>
                <li><a href="/zwroty-i-reklamacje" class="hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm px-1 -mx-1">Dostawa i 30 Dni Testu</a></li>
                <li><a href="/gwarancja" class="hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm px-1 -mx-1">Realizacja Gwarancji</a></li>
            </ul>
        </div>
    </div>

    <!-- Warstwa Dolna (Prawa autorskie i płatności) -->
    <div
        class="max-w-7xl mx-auto border-t border-stilco-dark/10 mt-16 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 gap-6">
        <p class="font-medium text-gray-400">&copy; <?php echo date('Y'); ?> Stilco. Wszelkie prawa zastrzeżone.<br/><span class="text-stilco-dark/60 mt-1 block">Wyprodukowano z ❤️ w Polsce.</span></p>
        
        <div class="flex items-center space-x-4 opacity-50 grayscale hover:grayscale-0 transition-all">
            <!-- Miejsce na SVG logotypów płatności (BLIK, Visa, itp.) -->
            <span>🛡️ Bezpieczne Płatności SSL</span>
            <span>BLIK</span>
            <span>Payu</span>
            <span>Visa/Mastercard</span>
        </div>

        <div class="flex space-x-6">
            <a href="/regulamin" class="hover:text-stilco-dark transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm">Regulamin</a>
            <a href="/polityka-prywatnosci" class="hover:text-stilco-dark transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm">Polityka prywatności</a>
        </div>
    </div>
</footer>
<?php endif; ?>

<!-- Off-Canvas Cart -->
<div id="slide-over-cart" class="relative z-50 hidden" aria-labelledby="slide-over-title" role="dialog"
    aria-modal="true">
    <!-- Background backdrop -->
    <div id="cart-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity opacity-0 z-40"></div>

    <div class="fixed inset-0 overflow-hidden z-50 pointer-events-none">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <!-- Drawer panel -->
                <div id="cart-panel"
                    class="pointer-events-auto w-screen max-w-md transform transition ease-in-out duration-500 translate-x-full">
                    <div class="flex h-full flex-col bg-white shadow-2xl">
                        <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                            <div class="flex items-start justify-between">
                                <h2 class="text-2xl font-display font-medium text-stilco-dark" id="slide-over-title">
                                    Twój Koszyk</h2>
                                <div class="ml-3 flex h-7 items-center">
                                    <button type="button" id="close-cart-btn"
                                        class="relative -m-2 p-2 text-gray-400 hover:text-gray-500 transition-colors">
                                        <span class="absolute -inset-0.5"></span>
                                        <span class="sr-only">Zamknij koszyk</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-8">
                                <div class="flow-root">
                                    <!-- Miejsce na wc_mini_cart() -->
                                    <div class="widget_shopping_cart_content h-full">
                                        <?php 
                                        if ( function_exists( 'woocommerce_mini_cart' ) ) {
                                            woocommerce_mini_cart(); 
                                        } else {
                                            echo '<p class="text-gray-500 p-6 text-center">Dodatek WooCommerce nie jest jeszcze aktywny.</p>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>

</html>