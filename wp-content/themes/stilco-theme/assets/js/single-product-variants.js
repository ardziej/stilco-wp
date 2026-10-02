document.addEventListener('DOMContentLoaded', function () {
	var priceDisplay = document.querySelector('.price-display');
	var defaultPriceHtml = priceDisplay ? priceDisplay.innerHTML : '';

	// Mattress outline drawn to scale: 56px tall, width follows the size in cm.
	function sizeIcon(widthCm) {
		var w = Math.min(36, 6.4 + 0.164 * widthCm);
		var x = (44 - w) / 2;

		return '<svg class="size-option__icon" width="44" height="64" viewBox="0 0 44 64" aria-hidden="true" focusable="false">'
			+ '<rect x="' + x.toFixed(2) + '" y="4" width="' + w.toFixed(2) + '" height="56" rx="5.33"></rect>'
			+ '</svg>';
	}

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

				Array.from(select.options).forEach(function (opt) {
					var text;
					var parts;
					var width = 160;
					var btn;

					if (!opt.value) {
						return;
					}

					hasValidOptions = true;
					text = opt.innerText.replace(/cm/gi, '').trim();
					parts = text.split(/[xX×*]/);

					if (!isNaN(parseInt(parts[0], 10))) {
						width = parseInt(parts[0], 10);
					}

					btn = document.createElement('button');
					btn.type = 'button';
					btn.className = 'size-option';
					btn.dataset.value = opt.value;
					btn.setAttribute('aria-pressed', select.value === opt.value ? 'true' : 'false');
					btn.innerHTML = sizeIcon(width)
						+ '<span class="size-option__label">' + text.replace(/\s*[xX*]\s*/, '×') + '</span>'
						+ '<span class="size-option__unit">cm</span>';

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

				// "Twój rozmiar" is rendered next to the form so it works without JS;
				// with JS it joins the grid as the last tile, as in the design.
				customTile = document.querySelector('[data-custom-size-toggle]');

				if (customTile) {
					customTile.classList.add('size-option', 'size-option--custom');
					container.appendChild(customTile);
				}

				td.appendChild(container);
			});
		});
	}

	setTimeout(initCustomVariants, 100);

	if (window.jQuery) {
		jQuery('.variations_form')
			.on('woocommerce_update_variation_values', function () {
				setTimeout(initCustomVariants, 50);
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
