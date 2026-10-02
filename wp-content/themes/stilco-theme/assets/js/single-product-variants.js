document.addEventListener('DOMContentLoaded', function () {
	var priceDisplay = document.querySelector('.price-display');
	var defaultPriceHtml = priceDisplay ? priceDisplay.innerHTML : '';

	function initCustomVariants() {
		var forms = document.querySelectorAll('.variations_form');

		if (forms.length === 0) {
			return;
		}

		forms.forEach(function (form) {
			var selects = form.querySelectorAll('table.variations select');

			selects.forEach(function (select) {
				if (select.dataset.customInit === 'true') {
					return;
				}

				select.dataset.customInit = 'true';

				var td = select.closest('td.value');
				var container;
				var hasValidOptions = false;
				var customTile;

				if (!td) {
					return;
				}

				container = document.createElement('div');
				container.className = 'custom-size-selector';
				container.setAttribute('role', 'group');
				container.setAttribute('aria-labelledby', 'size-picker-label');

				Array.from(select.options).forEach(function (opt) {
					var btn;

					if (!opt.value) {
						return;
					}

					hasValidOptions = true;

					btn = document.createElement('button');
					btn.type = 'button';
					btn.className = 'size-option';
					btn.dataset.value = opt.value;
					btn.setAttribute('aria-pressed', select.value === opt.value ? 'true' : 'false');
					btn.innerHTML = '<span class="size-option__label">'
						+ opt.innerText.replace(/cm/gi, '').trim().replace(/\s*[xX*]\s*/, '×')
						+ '</span><span class="size-option__unit">cm</span>';

					if (select.value === opt.value) {
						btn.classList.add('is-active');
					}

					btn.addEventListener('click', function (event) {
						event.preventDefault();
						container.querySelectorAll('.size-option').forEach(function (el) {
							el.classList.remove('is-active');
							el.setAttribute('aria-pressed', 'false');
						});
						btn.classList.add('is-active');
						btn.setAttribute('aria-pressed', 'true');
						select.value = opt.value;

						if (window.jQuery) {
							jQuery(select).trigger('change');
						} else {
							select.dispatchEvent(new Event('change', { bubbles: true }));
						}
					});

					container.appendChild(btn);
				});

				if (!hasValidOptions) {
					return;
				}

				// "Inny rozmiar" is rendered next to the form so it works without JS;
				// with JS it joins the grid as the last, full-width chip.
				customTile = document.querySelector('[data-custom-size-toggle]');

				if (customTile) {
					customTile.classList.add('size-option', 'size-option--custom');
					container.appendChild(customTile);
				}

				td.appendChild(container);
			});
		});
	}

	// The add-to-cart button says what is missing. WooCommerce marks it with
	// .wc-variation-selection-needed until a size is chosen; we only read that.
	var addButton = document.querySelector('form.cart .single_add_to_cart_button');
	var addLabel = addButton ? addButton.textContent.trim() : '';

	function needsSize() {
		return !!addButton && addButton.classList.contains('wc-variation-selection-needed');
	}

	function syncAddLabel() {
		var label = needsSize() ? 'Wybierz wymiar' : addLabel;

		if (addButton) {
			addButton.textContent = label;
		}
	}

	function focusSizePicker() {
		var picker = document.querySelector('.custom-size-selector');
		var chip = picker ? picker.querySelector('.size-option') : null;

		if (!chip) {
			return;
		}

		picker.scrollIntoView({
			block: 'center',
			behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth'
		});
		chip.focus({ preventScroll: true });
	}

	if (addButton) {
		// Instead of WooCommerce's "choose an option" alert, take the visitor to the sizes.
		addButton.addEventListener('click', function (event) {
			if (needsSize()) {
				event.preventDefault();
				event.stopPropagation();
				focusSizePicker();
			}
		}, true);
	}

	setTimeout(function () {
		initCustomVariants();
		syncAddLabel();
	}, 100);

	if (window.jQuery) {
		jQuery('.variations_form')
			.on('woocommerce_update_variation_values', function () {
				setTimeout(initCustomVariants, 50);
			})
			.on('show_variation hide_variation reset_data', function () {
				// WooCommerce toggles the button classes in its own handlers; read them after.
				setTimeout(syncAddLabel, 0);
			})
			.on('found_variation', function (event, variation) {
				if (priceDisplay && variation.price_html) {
					priceDisplay.innerHTML = variation.price_html;
				}
			})
			.on('reset_data', function () {
				if (priceDisplay) {
					priceDisplay.innerHTML = defaultPriceHtml;
				}
			});
	}
});
