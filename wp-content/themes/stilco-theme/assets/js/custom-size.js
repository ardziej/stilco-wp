/**
 * "Inny rozmiar" panel toggle on the product page.
 *
 * Opening the custom-size form hides the cart controls, so a visitor asking
 * for a quote cannot also add a standard size to the cart by accident.
 */
(function () {
	'use strict';

	function init() {
		var root = document.querySelector('[data-custom-size-root]');

		if (!root) {
			return;
		}

		var toggle = root.querySelector('[data-custom-size-toggle]');
		var panel = root.querySelector('[data-custom-size-panel="custom"]');
		// Only the price, delivery date and button go away; the size chips stay.
		var cart = document.querySelector('.variations_form .single_variation_wrap');

		if (!toggle || !panel) {
			return;
		}

		function setOpen(isOpen) {
			panel.hidden = !isOpen;
			toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
			root.classList.toggle('is-open', isOpen);

			if (cart) {
				cart.hidden = isOpen;
			}

			// A quote is not for a standard size: drop the chosen tile and its price.
			if (isOpen) {
				var reset = document.querySelector('.variations_form .reset_variations');

				if (reset) {
					reset.click();
				}

				document.querySelectorAll('.size-option.is-active').forEach(function (tile) {
					tile.classList.remove('is-active');
					tile.setAttribute('aria-pressed', 'false');
				});
			}
		}

		// The server renders the panel open after a submit, so mirror that state.
		setOpen(root.hasAttribute('data-custom-size-open'));

		toggle.addEventListener('click', function () {
			setOpen(panel.hidden);
		});

		// Picking a standard size goes back to the cart.
		document.addEventListener('click', function (event) {
			if (event.target.closest('.size-option:not(.size-option--custom)')) {
				setOpen(false);
			}
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
