document.addEventListener('DOMContentLoaded', function () {
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

				if (!td) {
					return;
				}

				var container = document.createElement('div');
				var hasValidOptions = false;

				container.className = 'custom-size-selector grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-6 mb-8';

				Array.from(select.options).forEach(function (opt) {
					var text;
					var width = 160;
					var length = 200;
					var parts;
					var ratio;
					var baseHeight = 56;
					var rectWidth;
					var btn;

					if (!opt.value) {
						return;
					}

					hasValidOptions = true;
					text = opt.innerText.replace(/cm/gi, '').trim();
					parts = text.split(/[xX*]/);

					if (parts.length >= 2) {
						width = parseInt(parts[0], 10);
						length = parseInt(parts[1], 10);
					} else if (!isNaN(parseInt(text, 10))) {
						width = parseInt(text, 10);
					}

					ratio = width / length;
					rectWidth = baseHeight * ratio;

					btn = document.createElement('div');
					btn.className = 'size-option cursor-pointer group flex flex-col items-center justify-between p-4 rounded-xl border-2 border-transparent bg-gray-50 hover:bg-white hover:border-stilco-accent hover:shadow-md transition-all duration-300 relative min-h-[110px]';
					btn.dataset.value = opt.value;
					btn.innerHTML = ''
						+ '<div class="bed-visual bg-white border border-gray-200 rounded-md group-hover:border-stilco-accent group-hover:bg-stilco-accent/5 transition-all duration-300 shadow-sm flex items-center justify-center p-1 mb-4">'
						+ '<div class="bed-visual-frame relative overflow-hidden shadow-inner">'
						+ '<svg class="bed-visual-svg" width="' + rectWidth + '" height="' + baseHeight + '" viewBox="0 0 100 56" aria-hidden="true" focusable="false" preserveAspectRatio="none">'
						+ '<rect class="bed-visual-surface" x="0.5" y="0.5" width="99" height="55" rx="2"></rect>'
						+ '</svg>'
						+ '<div class="bed-pattern absolute inset-0"></div>'
						+ '</div>'
						+ '</div>'
						+ '<span class="font-bold text-gray-700 text-sm group-hover:text-stilco-accent transition-colors text-center leading-tight">' + text + ' cm</span>';

					function setActive(el) {
						var visual = el.querySelector('.bed-visual');
						var label = el.querySelector('span');
						var frame = el.querySelector('.bed-visual-frame');

						el.classList.add('is-active');
						el.classList.add('border-stilco-accent', 'bg-white', 'shadow-md');
						el.classList.remove('border-transparent', 'bg-gray-50');
						visual.classList.add('border-stilco-accent', 'bg-stilco-accent/5');
						visual.classList.remove('border-gray-200');
						frame.classList.remove('border-gray-200', 'from-gray-50', 'to-gray-100');
						frame.classList.add('border-stilco-accent/30', 'from-stilco-accent/5', 'to-stilco-accent/10');
						label.classList.add('text-stilco-accent');
						label.classList.remove('text-gray-700');
					}

					function setInactive(el) {
						var visual = el.querySelector('.bed-visual');
						var label = el.querySelector('span');
						var frame = el.querySelector('.bed-visual-frame');

						el.classList.remove('is-active');
						el.classList.remove('border-stilco-accent', 'bg-white', 'shadow-md');
						el.classList.add('border-transparent', 'bg-gray-50');
						visual.classList.remove('border-stilco-accent', 'bg-stilco-accent/5');
						visual.classList.add('border-gray-200');
						frame.classList.add('border-gray-200', 'from-gray-50', 'to-gray-100');
						frame.classList.remove('border-stilco-accent/30', 'from-stilco-accent/5', 'to-stilco-accent/10');
						label.classList.add('text-gray-700');
						label.classList.remove('text-stilco-accent');
					}

					if (select.value === opt.value) {
						setActive(btn);
					}

					btn.addEventListener('click', function (event) {
						event.preventDefault();
						container.querySelectorAll('.size-option').forEach(function (el) {
							setInactive(el);
						});
						setActive(btn);
						select.value = opt.value;

						if (window.jQuery) {
							jQuery(select).trigger('change');
						} else {
							select.dispatchEvent(new Event('change', { bubbles: true }));
						}
					});

					container.appendChild(btn);
				});

				if (hasValidOptions) {
					td.appendChild(container);
				}
			});
		});
	}

	setTimeout(initCustomVariants, 100);

	if (window.jQuery) {
		jQuery('.variations_form').on('woocommerce_update_variation_values', function () {
			setTimeout(initCustomVariants, 50);
		});
	}
});
