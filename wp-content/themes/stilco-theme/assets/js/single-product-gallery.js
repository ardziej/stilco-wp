/**
 * Product page photos: carousel dots on phones and a shared lightbox.
 *
 * Any element with `data-pg-src` inside a `[data-pg-group]` opens the
 * lightbox with the photos of that group: the buy-box gallery, the detail
 * rows, the lifestyle collage. In the lightbox a click or tap zooms in and
 * the zoom follows the pointer; arrows, swipe and the keyboard move between
 * photos; Escape closes and focus goes back to the photo that opened it.
 */
(function () {
	'use strict';

	var lightbox = document.getElementById('product-lightbox');

	function initCarousel(group) {
		var track = group.querySelector('[data-pg-track]');
		var dots = group.querySelectorAll('.pg-dot');
		var counter = group.querySelector('[data-pg-counter-inline]');
		var total = track ? track.children.length : 0;
		var ticking = false;

		if (!track || total < 2) {
			return;
		}

		function update() {
			var index = Math.round(track.scrollLeft / track.clientWidth);

			ticking = false;
			dots.forEach(function (dot, i) {
				dot.classList.toggle('is-active', i === index);
			});

			if (counter) {
				counter.textContent = (index + 1) + ' / ' + total;
			}
		}

		track.addEventListener('scroll', function () {
			if (!ticking) {
				ticking = true;
				window.requestAnimationFrame(update);
			}
		}, { passive: true });
	}

	function initLightbox() {
		var stage = lightbox.querySelector('[data-pg-stage]');
		var image = lightbox.querySelector('[data-pg-image]');
		var counter = lightbox.querySelector('[data-pg-counter]');
		var caption = lightbox.querySelector('[data-pg-caption]');
		var thumbs = lightbox.querySelector('[data-pg-thumbs]');
		var closeButton = lightbox.querySelector('[data-pg-close]');
		var slides = [];
		var current = 0;
		var opener = null;
		var touchX = null;
		var touchMoved = false;

		function setZoom(on, event) {
			stage.classList.toggle('is-zoomed', on);

			if (on && event) {
				panTo(event);
			} else {
				image.style.transformOrigin = '';
			}
		}

		function panTo(event) {
			var rect = image.getBoundingClientRect();
			var point = event.touches ? event.touches[0] : event;
			var x = ((point.clientX - rect.left) / rect.width) * 100;
			var y = ((point.clientY - rect.top) / rect.height) * 100;

			image.style.transformOrigin = Math.max(0, Math.min(100, x)) + '% ' + Math.max(0, Math.min(100, y)) + '%';
		}

		function show(index) {
			var slide;

			current = (index + slides.length) % slides.length;
			slide = slides[current];
			setZoom(false);

			image.classList.add('is-loading');
			image.onload = function () {
				image.classList.remove('is-loading');
			};
			image.src = slide.src;
			image.alt = slide.alt;
			caption.textContent = slide.alt;
			counter.textContent = (current + 1) + ' / ' + slides.length;

			Array.prototype.forEach.call(thumbs.children, function (thumb, i) {
				thumb.classList.toggle('is-active', i === current);
				thumb.setAttribute('aria-current', i === current ? 'true' : 'false');
			});

			if (thumbs.children[current]) {
				thumbs.children[current].scrollIntoView({ block: 'nearest', inline: 'center' });
			}

			// Warm the cache for the neighbours so arrowing feels instant.
			[current + 1, current - 1].forEach(function (i) {
				new Image().src = slides[(i + slides.length) % slides.length].src;
			});
		}

		function open(items, index, trigger) {
			slides = items.map(function (el) {
				return {
					src: el.getAttribute('data-pg-src'),
					thumb: el.getAttribute('data-pg-thumb') || el.getAttribute('data-pg-src'),
					alt: el.getAttribute('data-pg-alt') || ''
				};
			});

			thumbs.innerHTML = '';
			slides.forEach(function (slide, i) {
				var button = document.createElement('button');

				button.type = 'button';
				button.className = 'pg-lightbox__thumb';
				button.setAttribute('aria-label', 'Zdjęcie ' + (i + 1));
				button.innerHTML = '<img src="' + slide.thumb + '" alt="" loading="lazy">';
				button.addEventListener('click', function () {
					show(i);
				});
				thumbs.appendChild(button);
			});

			lightbox.classList.toggle('is-single', slides.length < 2);
			opener = trigger;
			lightbox.hidden = false;
			void lightbox.offsetWidth;
			lightbox.classList.add('is-open');
			document.body.classList.add('pg-lightbox-open');
			show(index);
			closeButton.focus();
		}

		function close() {
			lightbox.classList.remove('is-open');
			document.body.classList.remove('pg-lightbox-open');
			setZoom(false);

			window.setTimeout(function () {
				lightbox.hidden = true;
				image.src = '';
			}, 250);

			if (opener) {
				opener.focus();
			}
		}

		document.querySelectorAll('[data-pg-group]').forEach(function (group) {
			var items = Array.prototype.slice.call(group.querySelectorAll('[data-pg-src]'));

			items.forEach(function (item, index) {
				item.addEventListener('click', function (event) {
					event.preventDefault();
					open(items, index, item);
				});
			});
		});

		lightbox.querySelector('[data-pg-prev]').addEventListener('click', function () {
			show(current - 1);
		});
		lightbox.querySelector('[data-pg-next]').addEventListener('click', function () {
			show(current + 1);
		});
		closeButton.addEventListener('click', close);

		image.addEventListener('click', function (event) {
			if (!touchMoved) {
				setZoom(!stage.classList.contains('is-zoomed'), event);
			}
		});

		stage.addEventListener('mousemove', function (event) {
			if (stage.classList.contains('is-zoomed')) {
				panTo(event);
			}
		});

		// Click on the dark area around the photo closes, like most galleries.
		stage.addEventListener('click', function (event) {
			if (event.target === stage) {
				close();
			}
		});

		stage.addEventListener('touchstart', function (event) {
			touchX = event.touches[0].clientX;
			touchMoved = false;
		}, { passive: true });

		stage.addEventListener('touchmove', function (event) {
			touchMoved = true;

			if (stage.classList.contains('is-zoomed')) {
				event.preventDefault();
				panTo(event);
			}
		}, { passive: false });

		stage.addEventListener('touchend', function (event) {
			var dx;

			if (touchX === null || stage.classList.contains('is-zoomed')) {
				touchX = null;
				return;
			}

			dx = event.changedTouches[0].clientX - touchX;
			touchX = null;

			if (Math.abs(dx) > 50) {
				show(dx < 0 ? current + 1 : current - 1);
			}

			// Let the click that follows a swipe through only if it was a tap.
			window.setTimeout(function () {
				touchMoved = false;
			}, 50);
		});

		document.addEventListener('keydown', function (event) {
			if (lightbox.hidden) {
				return;
			}

			if (event.key === 'Escape') {
				close();
			} else if (event.key === 'ArrowRight') {
				show(current + 1);
			} else if (event.key === 'ArrowLeft') {
				show(current - 1);
			} else if (event.key === 'Tab') {
				// Keep focus inside the dialog.
				var focusable = lightbox.querySelectorAll('button:not([disabled])');
				var first = focusable[0];
				var last = focusable[focusable.length - 1];

				if (event.shiftKey && document.activeElement === first) {
					event.preventDefault();
					last.focus();
				} else if (!event.shiftKey && document.activeElement === last) {
					event.preventDefault();
					first.focus();
				}
			}
		});
	}

	function init() {
		document.querySelectorAll('[data-pg-group]').forEach(initCarousel);

		if (lightbox) {
			initLightbox();
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
