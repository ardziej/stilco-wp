/**
 * Global image lightbox.
 *
 * Opens any element with `data-lightbox="<url>"` in an overlay.
 * Closes on backdrop click, close button, or Escape. Returns focus to
 * the element that opened it.
 */
(function () {
	'use strict';

	function init() {
		var lightbox = document.getElementById('stilco-lightbox');
		var triggers = document.querySelectorAll('[data-lightbox]');

		if (!lightbox || triggers.length === 0) {
			return;
		}

		var image = lightbox.querySelector('.stilco-lightbox__image');
		var closeButton = lightbox.querySelector('.stilco-lightbox__close');
		var lastTrigger = null;

		function open(trigger) {
			var url = trigger.getAttribute('data-lightbox');

			if (!url) {
				return;
			}

			lastTrigger = trigger;
			image.src = url;
			image.alt = trigger.getAttribute('alt') || trigger.getAttribute('data-lightbox-alt') || '';
			lightbox.hidden = false;
			// Force a frame so the transition runs.
			void lightbox.offsetWidth;
			lightbox.classList.add('is-open');
			document.body.classList.add('stilco-lightbox-open');
			closeButton.focus();
		}

		function close() {
			if (lightbox.hidden) {
				return;
			}

			lightbox.classList.remove('is-open');
			document.body.classList.remove('stilco-lightbox-open');

			window.setTimeout(function () {
				lightbox.hidden = true;
				image.src = '';

				if (lastTrigger && typeof lastTrigger.focus === 'function') {
					lastTrigger.focus();
				}
			}, 250);
		}

		triggers.forEach(function (trigger) {
			trigger.classList.add('stilco-lightbox-trigger');

			if (!trigger.hasAttribute('tabindex')) {
				trigger.setAttribute('tabindex', '0');
			}

			if (!trigger.hasAttribute('role')) {
				trigger.setAttribute('role', 'button');
			}

			trigger.addEventListener('click', function (event) {
				event.preventDefault();
				open(trigger);
			});

			trigger.addEventListener('keydown', function (event) {
				if (event.key === 'Enter' || event.key === ' ') {
					event.preventDefault();
					open(trigger);
				}
			});
		});

		lightbox.addEventListener('click', function (event) {
			if (event.target.closest('[data-lightbox-close]') && event.target !== image) {
				close();
			}
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && !lightbox.hidden) {
				close();
			}
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
