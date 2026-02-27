// Drawer Logic
document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicjalizacja zaawansowanych animacji scrolla (Intersection Observer)
    const animationSelectors = [
        { class: '.animate-on-scroll', activeClass: 'animate-fade-in-up' },
        { class: '.animate-slide-left', activeClass: 'animate-fade-in-left' },
        { class: '.animate-slide-right', activeClass: 'animate-fade-in-right' },
        { class: '.animate-zoom', activeClass: 'animate-zoom-in' }
    ];

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Determine which animation to apply based on initial class
                animationSelectors.forEach(selector => {
                    if (entry.target.classList.contains(selector.class.replace('.', ''))) {
                        entry.target.classList.remove('opacity-0');
                        entry.target.classList.add(selector.activeClass);
                    }
                });
                observer.unobserve(entry.target);
            }
        });
    }, { 
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px" // Aktywuje się nieco wczesniej niz na samym spodzie paska
    });

    animationSelectors.forEach(selector => {
        const elements = document.querySelectorAll(selector.class);
        elements.forEach(el => observer.observe(el));
    });

    // 2. Prosty skrypt na header - pomniejszanie przy scrollu
    const header = document.querySelector('header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('shadow-md');
                header.classList.replace('py-4', 'py-2');
            } else {
                header.classList.remove('shadow-md');
                header.classList.replace('py-2', 'py-4');
            }
        });
    }

    // 3. Off-Canvas Cart Toggle Logic
    const cartToggleBtn = document.getElementById('cart-toggle-btn');
    const closeCartBtn = document.getElementById('close-cart-btn');
    const cartDrawer = document.getElementById('slide-over-cart');
    const cartPanel = document.getElementById('cart-panel');
    const cartBackdrop = document.getElementById('cart-backdrop');

    const openCart = () => {
        if (!cartDrawer) return;
        cartDrawer.classList.remove('hidden');
        // setTimeout aby CSS transition załapało zmiane z hidden na block
        setTimeout(() => {
            cartBackdrop.classList.add('opacity-100');
            cartBackdrop.classList.remove('opacity-0');
            cartPanel.classList.remove('translate-x-full');
            cartPanel.classList.add('translate-x-0');
        }, 10);
    };

    const closeCart = () => {
        if (!cartDrawer) return;
        cartBackdrop.classList.remove('opacity-100');
        cartBackdrop.classList.add('opacity-0');
        cartPanel.classList.remove('translate-x-0');
        cartPanel.classList.add('translate-x-full');

        setTimeout(() => {
            cartDrawer.classList.add('hidden');
        }, 500); // 500ms transition time
    };

    if (cartToggleBtn) cartToggleBtn.addEventListener('click', openCart);
    if (closeCartBtn) closeCartBtn.addEventListener('click', closeCart);
    if (cartBackdrop) cartBackdrop.addEventListener('click', closeCart);

    // Otwarcia szuflady po udanym dodaniu Ajax do koszyka woo
    jQuery(document.body).on('added_to_cart', function () {
        openCart();
    });

    // 4. FAQ Accordion Logic
    const faqItems = document.querySelectorAll('.faq-item');
    if (faqItems.length > 0) {
        faqItems.forEach(item => {
            const btn = item.querySelector('.faq-btn');
            const content = item.querySelector('.faq-content');
            const icon = item.querySelector('.faq-icon');

            btn.addEventListener('click', () => {
                const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';

                // Zamknij wszystkie
                faqItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        const otherContent = otherItem.querySelector('.faq-content');
                        const otherIcon = otherItem.querySelector('.faq-icon');
                        otherContent.style.maxHeight = '0px';
                        otherIcon.style.transform = 'rotate(0deg)';
                        otherIcon.classList.replace('text-stilco-accent', 'text-gray-400');
                    }
                });

                if (isOpen) {
                    content.style.maxHeight = '0px';
                    icon.style.transform = 'rotate(0deg)';
                    icon.classList.replace('text-stilco-accent', 'text-gray-400');
                } else {
                    content.style.maxHeight = content.scrollHeight + 'px';
                    icon.style.transform = 'rotate(180deg)';
                    icon.classList.replace('text-gray-400', 'text-stilco-accent');
                }
            });
        });
    }
});
