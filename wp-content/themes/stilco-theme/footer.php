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
            <span>Przelewy24</span>
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

<!-- Chat Widget -->
<div id="stilco-chat-widget" class="fixed bottom-6 right-6 z-50 font-sans">
    <!-- Chat Button -->
    <button id="stilco-chat-button" class="w-14 h-14 bg-stilco-accent text-white rounded-full shadow-lg flex items-center justify-center hover:bg-[#a84a34] transition-all hover:scale-105 focus:outline-none focus:ring-4 focus:ring-stilco-accent/30 group">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden group-hover:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
        </svg>
    </button>

    <!-- Chat Window -->
    <div id="stilco-chat-window" class="hidden absolute bottom-20 right-0 w-[350px] bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100 flex-col opacity-0 scale-95 transition-all duration-300 origin-bottom-right" style="height: 500px; max-height: calc(100vh - 120px);">
        <!-- Header -->
        <div class="bg-stilco-dark text-white p-4 flex justify-between items-center rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-stilco-accent flex items-center justify-center shadow-inner">
                    <span class="text-sm font-bold">S</span>
                </div>
                <div>
                    <h4 class="font-medium text-sm leading-tight">Ekspert Stilco</h4>
                    <p class="text-xs text-gray-300 opacity-90">Odpowiada natychmiast</p>
                </div>
            </div>
            <button id="stilco-chat-close" class="text-gray-400 hover:text-white transition-colors focus:outline-none p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div id="stilco-chat-messages" class="flex-1 p-4 overflow-y-auto bg-gray-50 flex flex-col space-y-4">
            <!-- Bot Message -->
            <div class="flex items-start space-x-2">
                <div class="w-7 h-7 rounded-full bg-stilco-accent flex-shrink-0 flex items-center justify-center mt-0.5 shadow-sm">
                    <span class="text-xs text-white font-bold">S</span>
                </div>
                <div class="bg-white p-3.5 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-700 max-w-[85%] leading-relaxed border border-gray-100">
                    Cześć! 👋 Jestem wirtualnym asystentem Stilco. W czym mogę Ci dzisiaj pomóc?
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex flex-wrap gap-2 pt-2" id="stilco-chat-actions">
                <button class="chat-action-btn text-[13px] bg-white border border-stilco-accent text-stilco-accent px-4 py-2 rounded-full hover:bg-stilco-accent hover:text-white transition-colors shadow-sm" data-action="order">📦 Gdzie jest moje zamówienie?</button>
                <button class="chat-action-btn text-[13px] bg-white border border-stilco-accent text-stilco-accent px-4 py-2 rounded-full hover:bg-stilco-accent hover:text-white transition-colors shadow-sm" data-action="mattress">🛏️ Jaki materac wybrać?</button>
                <button class="chat-action-btn text-[13px] bg-white border border-stilco-accent text-stilco-accent px-4 py-2 rounded-full hover:bg-stilco-accent hover:text-white transition-colors shadow-sm" data-action="contact">💬 Kontakt z człowiekiem</button>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white border-t border-gray-100">
            <form id="stilco-chat-form" class="flex items-center space-x-2 relative">
                <input type="text" id="stilco-chat-input" placeholder="Napisz wiadomość..." class="flex-1 bg-gray-50 border border-gray-200 rounded-full pl-4 pr-10 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-stilco-accent/30 focus:border-stilco-accent transition-all">
                <button type="submit" class="absolute right-1 w-8 h-8 rounded-full bg-stilco-accent text-white flex items-center justify-center hover:bg-[#a84a34] transition-colors focus:outline-none flex-shrink-0 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform rotate-90 ml-0.5 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* Animacje dla czatu */
@keyframes chatShow {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.chat-window-active {
    display: flex !important;
    animation: chatShow 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Scrollbar dla czatu */
#stilco-chat-messages::-webkit-scrollbar {
    width: 4px;
}
#stilco-chat-messages::-webkit-scrollbar-track {
    background: transparent;
}
#stilco-chat-messages::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 4px;
}

.typing-dot {
    animation: typing 1.4s infinite ease-in-out both;
}
.typing-dot:nth-child(1) { animation-delay: -0.32s; }
.typing-dot:nth-child(2) { animation-delay: -0.16s; }
@keyframes typing {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatButton = document.getElementById('stilco-chat-button');
    const chatWindow = document.getElementById('stilco-chat-window');
    const chatClose = document.getElementById('stilco-chat-close');
    const chatForm = document.getElementById('stilco-chat-form');
    const chatInput = document.getElementById('stilco-chat-input');
    const chatMessages = document.getElementById('stilco-chat-messages');
    const actionButtons = document.querySelectorAll('.chat-action-btn');

    let waitingForOrderNumber = false;

    // Toggle chat window
    function toggleChat() {
        if (chatWindow.classList.contains('hidden')) {
            chatWindow.classList.remove('hidden');
            setTimeout(() => {
                chatWindow.classList.add('chat-window-active');
                chatInput.focus();
            }, 10);
        } else {
            chatWindow.classList.remove('chat-window-active');
            setTimeout(() => {
                chatWindow.classList.add('hidden');
            }, 300);
        }
    }

    chatButton.addEventListener('click', toggleChat);
    chatClose.addEventListener('click', toggleChat);

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function removeActions() {
        const actionsContainer = document.getElementById('stilco-chat-actions');
        if (actionsContainer) {
            actionsContainer.style.display = 'none';
        }
    }

    function appendUserMessage(text) {
        removeActions();
        const msgDiv = document.createElement('div');
        msgDiv.className = 'flex items-start justify-end space-x-2 mt-2';
        msgDiv.innerHTML = `
            <div class="bg-stilco-dark text-white p-3 rounded-2xl rounded-tr-none shadow-sm text-sm max-w-[85%] leading-relaxed">
                ${text}
            </div>
        `;
        chatMessages.appendChild(msgDiv);
        scrollToBottom();
    }

    function appendTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'flex items-start space-x-2 mt-2';
        typingDiv.id = 'chat-typing-indicator';
        typingDiv.innerHTML = `
            <div class="w-7 h-7 rounded-full bg-stilco-accent flex-shrink-0 flex items-center justify-center mt-0.5 shadow-sm">
                <span class="text-xs text-white font-bold">S</span>
            </div>
            <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm flex items-center space-x-1 border border-gray-100">
                <div class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></div>
            </div>
        `;
        
        chatMessages.appendChild(typingDiv);
        scrollToBottom();
        return typingDiv;
    }

    function removeTypingIndicator(indicator) {
        if (indicator && indicator.parentNode) {
            indicator.parentNode.removeChild(indicator);
        }
    }

    function appendBotMessage(text) {
        const msgDiv = document.createElement('div');
        msgDiv.className = 'flex items-start space-x-2 mt-2';
        msgDiv.innerHTML = `
            <div class="w-7 h-7 rounded-full bg-stilco-accent flex-shrink-0 flex items-center justify-center mt-0.5 shadow-sm">
                <span class="text-xs text-white font-bold">S</span>
            </div>
            <div class="bg-white p-3.5 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-700 max-w-[85%] leading-relaxed border border-gray-100">
                ${text}
            </div>
        `;
        chatMessages.appendChild(msgDiv);
        scrollToBottom();
    }

    function handleResponse(text) {
        const lowerText = text.toLowerCase();
        let response = "Dziękuję za wiadomość! 👋 Obecnie jestem w fazie testów i poznaję nasz asortyment. Skontaktujemy się z Tobą najszybciej jak to możliwe.";
        
        if (waitingForOrderNumber) {
            response = 'Sprawdzam status zamówienia <b>' + text + '</b>... <br><br>Super wieści! 🎉 Twoje zamówienie zostało już spakowane i wkrótce wyruszy w drogę. Numer listu przewozowego zaraz trafi na Twojego maila. 📦';
            waitingForOrderNumber = false;
        } else if (lowerText.includes('zamówieni') || lowerText.includes('zamowien') || lowerText.includes('gdzie') || lowerText.includes('status')) {
            response = 'Chętnie sprawdzę status Twojego zamówienia. Proszę, podaj mi jego numer (np. #12345):';
            waitingForOrderNumber = true;
        } else if (lowerText.includes('wymien') || lowerText.includes('zwrot')) {
            response = 'Pamiętaj, że u nas możesz testować materac przez 30 dni. Jeśli chcesz zgłosić zwrot, wyślij wiadomość na adres kontakt@stilco.pl, a my zajmiemy się resztą - wyślemy kuriera pod Twoje drzwi.';
        } else if (lowerText.includes('materac')) {
            response = 'W naszej ofercie posiadamy najwyższej jakości materace zapewniające idelane podparcie. Poszukujesz materaca do łóżka pojedynczego czy małżeńskiego?';
        }

        const indicator = appendTypingIndicator();
        chatInput.disabled = true;
        
        setTimeout(() => {
            removeTypingIndicator(indicator);
            appendBotMessage(response);
            chatInput.disabled = false;
            chatInput.focus();
        }, 1200);
    }

    // Handle form submit
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const text = chatInput.value.trim();
        if (!text) return;
        
        chatInput.value = '';
        appendUserMessage(text);
        handleResponse(text);
    });

    // Handle quick actions
    actionButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const text = this.innerText.replace(/[^\w\s\żźćńółęąśŻŹĆĄŚĘŁÓŃ\?]/gi, '').trim(); // Remove emojis for user message
            
            appendUserMessage(text);

            if (action === 'order') {
                const indicator = appendTypingIndicator();
                setTimeout(() => {
                    removeTypingIndicator(indicator);
                    appendBotMessage('Aby sprawdzić status, będę potrzebował numeru Twojego zamówienia. Wpisz go proszę poniżej (np. #12345):');
                    waitingForOrderNumber = true;
                }, 1000);
            } else if (action === 'mattress') {
                const indicator = appendTypingIndicator();
                setTimeout(() => {
                    removeTypingIndicator(indicator);
                    appendBotMessage('Świetnie! Nasze materace projektujemy z myślą o najwyższym komforcie. Jeśli szukasz czegoś dla par, idealnym wyborem będzie model z niezależnymi strefami nacisku. Chcesz, abym opowiedział o nim coś więcej?');
                }, 1000);
            } else if (action === 'contact') {
                const indicator = appendTypingIndicator();
                setTimeout(() => {
                    removeTypingIndicator(indicator);
                    appendBotMessage('Łączę z naszym doradcą... 🎧<br><br>W międzyczasie możesz krótko opisać, w czym potrzebujesz pomocy, abyśmy mogli lepiej się przygotować.');
                }, 1000);
            }
        });
    });
});
</script>

<?php wp_footer(); ?>
</body>

</html>