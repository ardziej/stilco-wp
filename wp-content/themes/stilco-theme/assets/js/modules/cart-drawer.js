export function initCartDrawer() {
	var cartToggleBtn = document.getElementById('cart-toggle-btn');
	var closeCartBtn = document.getElementById('close-cart-btn');
	var cartDrawer = document.getElementById('slide-over-cart');
	var cartPanel = document.getElementById('cart-panel');
	var cartBackdrop = document.getElementById('cart-backdrop');

	function openCart() {
		if (!cartDrawer || !cartPanel || !cartBackdrop) {
			return;
		}

		cartDrawer.classList.remove('hidden');

		setTimeout(function () {
			cartBackdrop.classList.add('opacity-100');
			cartBackdrop.classList.remove('opacity-0');
			cartPanel.classList.remove('translate-x-full');
			cartPanel.classList.add('translate-x-0');
		}, 10);
	}

	function closeCart() {
		if (!cartDrawer || !cartPanel || !cartBackdrop) {
			return;
		}

		cartBackdrop.classList.remove('opacity-100');
		cartBackdrop.classList.add('opacity-0');
		cartPanel.classList.remove('translate-x-0');
		cartPanel.classList.add('translate-x-full');

		setTimeout(function () {
			cartDrawer.classList.add('hidden');
		}, 500);
	}

	if (cartToggleBtn) {
		cartToggleBtn.addEventListener('click', openCart);
	}

	if (closeCartBtn) {
		closeCartBtn.addEventListener('click', closeCart);
	}

	if (cartBackdrop) {
		cartBackdrop.addEventListener('click', closeCart);
	}

	if (window.jQuery) {
		jQuery(document.body).on('added_to_cart', function () {
			openCart();
		});
	}
}
