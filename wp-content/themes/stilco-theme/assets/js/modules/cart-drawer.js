// Opens in 300ms ease-out, closes in 200ms ease-in; CLOSE_MS must match duration-200 below.
var CLOSE_MS = 200;

export function initCartDrawer() {
	var cartToggleBtn = document.getElementById('cart-toggle-btn');
	var closeCartBtn = document.getElementById('close-cart-btn');
	var cartDrawer = document.getElementById('slide-over-cart');
	var cartPanel = document.getElementById('cart-panel');
	var cartBackdrop = document.getElementById('cart-backdrop');
	var hideTimer = null;

	if (!cartDrawer || !cartPanel || !cartBackdrop) {
		return;
	}

	function setTiming(isOpening) {
		[cartPanel, cartBackdrop].forEach(function (element) {
			element.classList.toggle('duration-300', isOpening);
			element.classList.toggle('ease-out', isOpening);
			element.classList.toggle('duration-200', !isOpening);
			element.classList.toggle('ease-in', !isOpening);
		});
	}

	function isOpen() {
		return !cartDrawer.classList.contains('hidden') && cartPanel.classList.contains('translate-x-0');
	}

	function openCart() {
		clearTimeout(hideTimer);
		setTiming(true);
		cartDrawer.classList.remove('hidden');

		setTimeout(function () {
			cartBackdrop.classList.add('opacity-100');
			cartBackdrop.classList.remove('opacity-0');
			cartPanel.classList.remove('translate-x-full');
			cartPanel.classList.add('translate-x-0');
		}, 10);

		if (cartToggleBtn) {
			cartToggleBtn.setAttribute('aria-expanded', 'true');
		}

		if (closeCartBtn) {
			closeCartBtn.focus({ preventScroll: true });
		}
	}

	function closeCart() {
		if (cartDrawer.classList.contains('hidden')) {
			return;
		}

		setTiming(false);
		cartBackdrop.classList.remove('opacity-100');
		cartBackdrop.classList.add('opacity-0');
		cartPanel.classList.remove('translate-x-0');
		cartPanel.classList.add('translate-x-full');

		hideTimer = setTimeout(function () {
			cartDrawer.classList.add('hidden');
		}, CLOSE_MS);

		if (cartToggleBtn) {
			cartToggleBtn.setAttribute('aria-expanded', 'false');
			cartToggleBtn.focus();
		}
	}

	if (cartToggleBtn) {
		cartToggleBtn.addEventListener('click', openCart);
	}

	if (closeCartBtn) {
		closeCartBtn.addEventListener('click', closeCart);
	}

	cartBackdrop.addEventListener('click', closeCart);

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape' && isOpen()) {
			closeCart();
		}
	});

	if (window.jQuery) {
		jQuery(document.body).on('added_to_cart', function () {
			openCart();
		});
	}
}
