import { initScrollAnimations } from './modules/scroll-animations.js';
import { initHeaderScrollState } from './modules/header-scroll-state.js';
import { initCartDrawer } from './modules/cart-drawer.js';
import { initFaqAccordion } from './modules/faq-accordion.js';
import { initMobileMenu } from './modules/mobile-menu.js';

document.addEventListener('DOMContentLoaded', function () {
	initScrollAnimations();
	initHeaderScrollState();
	initCartDrawer();
	initFaqAccordion();
	initMobileMenu();
});
