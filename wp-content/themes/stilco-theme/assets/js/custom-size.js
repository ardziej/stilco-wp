/**
 * "Twój rozmiar" panel toggle on the product page.
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
		var cart = document.querySelector('[data-custom-size-panel="standard"]');

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
		}

		// The server renders the panel open after a submit, so mirror that state.
		setOpen(root.hasAttribute('data-custom-size-open'));

		toggle.addEventListener('click', function () {
			setOpen(panel.hidden);
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
