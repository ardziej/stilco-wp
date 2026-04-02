document.addEventListener('DOMContentLoaded', function () {
	var thumbnails = document.querySelectorAll('.thumbnail-item');
	var mainImage = document.getElementById('product-main-image');
	var mainImageContainer = document.getElementById('product-main-image-container');
	var lightbox = document.getElementById('product-lightbox');
	var lightboxImg = document.getElementById('lightbox-image');
	var lightboxClose = document.getElementById('lightbox-close');
	var lightboxNext = document.getElementById('lightbox-next');
	var lightboxPrev = document.getElementById('lightbox-prev');
	var lightboxCounter = document.getElementById('lightbox-counter');
	var currentGalleryIndex = 0;
	var totalImages = thumbnails.length || (mainImage ? 1 : 0);
	var touchStartX = 0;
	var touchEndX = 0;

	function swapMainImage(element) {
		var singleUrl;
		var singleSrcset;
		var fullUrl;
		var index;

		if (!mainImage) {
			return;
		}

		singleUrl = element.getAttribute('data-single-url');
		singleSrcset = element.getAttribute('data-single-srcset');
		fullUrl = element.getAttribute('data-full-url');
		index = parseInt(element.getAttribute('data-index'), 10);
		currentGalleryIndex = index;
		mainImage.classList.add('is-image-loading');

		setTimeout(function () {
			mainImage.src = singleUrl;

			if (singleSrcset) {
				mainImage.srcset = singleSrcset;
			} else {
				mainImage.removeAttribute('srcset');
			}

			mainImage.setAttribute('data-full-image', fullUrl);
			mainImage.setAttribute('data-index', index);
			mainImage.classList.remove('is-image-loading');
		}, 150);

		thumbnails.forEach(function (thumb) {
			thumb.classList.remove('border-stilco-accent');
			thumb.classList.add('border-transparent');
		});

		element.classList.add('border-stilco-accent');
		element.classList.remove('border-transparent');
	}

	function updateLightboxContent() {
		if (!lightboxImg || !lightboxCounter) {
			return;
		}

		if (thumbnails.length > 0) {
			var thumb = thumbnails[currentGalleryIndex];
			var fullUrl = thumb.getAttribute('data-full-url');

			lightboxImg.src = fullUrl;
			swapMainImage(thumb);
			thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
		} else if (mainImage) {
			lightboxImg.src = mainImage.getAttribute('data-full-image') || mainImage.src;
		}

		lightboxCounter.innerText = (currentGalleryIndex + 1) + ' / ' + totalImages;

		if (totalImages <= 1) {
			if (lightboxNext) {
				lightboxNext.classList.add('hidden');
			}

			if (lightboxPrev) {
				lightboxPrev.classList.add('hidden');
			}
		} else {
			if (lightboxNext) {
				lightboxNext.classList.remove('hidden');
			}

			if (lightboxPrev) {
				lightboxPrev.classList.remove('hidden');
			}
		}
	}

	function openLightbox(index) {
		if (!lightbox || !lightboxImg || totalImages === 0) {
			return;
		}

		currentGalleryIndex = index;
		updateLightboxContent();
		lightbox.classList.add('lb-visible');
		void lightbox.offsetWidth;
		lightbox.classList.add('lb-open');
		lightboxImg.classList.add('lb-zoom');
		document.body.classList.add('product-lightbox-open');
	}

	function closeLightbox() {
		if (!lightbox || !lightboxImg) {
			return;
		}

		lightbox.classList.remove('lb-open');
		lightboxImg.classList.remove('lb-zoom');

		setTimeout(function () {
			lightbox.classList.remove('lb-visible');
			document.body.classList.remove('product-lightbox-open');
		}, 300);
	}

	function nextImage(event) {
		if (event) {
			event.stopPropagation();
		}

		if (totalImages <= 1) {
			return;
		}

		currentGalleryIndex = (currentGalleryIndex + 1) % totalImages;
		updateLightboxContent();
	}

	function prevImage(event) {
		if (event) {
			event.stopPropagation();
		}

		if (totalImages <= 1) {
			return;
		}

		currentGalleryIndex = (currentGalleryIndex - 1 + totalImages) % totalImages;
		updateLightboxContent();
	}

	function handleSwipe() {
		var diff = touchStartX - touchEndX;

		if (Math.abs(diff) > 50) {
			if (diff > 0) {
				nextImage();
			} else {
				prevImage();
			}
		}
	}

	thumbnails.forEach(function (thumbnail) {
		thumbnail.addEventListener('click', function () {
			swapMainImage(thumbnail);
		});
	});

	if (mainImageContainer) {
		mainImageContainer.addEventListener('click', function () {
			openLightbox(currentGalleryIndex);
		});
	}

	if (lightboxClose) {
		lightboxClose.addEventListener('click', closeLightbox);
	}

	if (lightboxNext) {
		lightboxNext.addEventListener('click', nextImage);
	}

	if (lightboxPrev) {
		lightboxPrev.addEventListener('click', prevImage);
	}

	if (lightbox) {
		lightbox.addEventListener('click', function (event) {
			if ((event.target === lightbox || event.target.closest('.flex-1')) && event.target !== lightboxImg && !event.target.closest('button')) {
				closeLightbox();
			}
		});

		lightbox.addEventListener('touchstart', function (event) {
			touchStartX = event.changedTouches[0].screenX;
		}, { passive: true });

		lightbox.addEventListener('touchend', function (event) {
			touchEndX = event.changedTouches[0].screenX;
			handleSwipe();
		}, { passive: true });
	}

	document.addEventListener('keydown', function (event) {
		if (lightbox && lightbox.classList.contains('lb-visible')) {
			if (event.key === 'Escape') {
				closeLightbox();
			}

			if (event.key === 'ArrowRight') {
				nextImage();
			}

			if (event.key === 'ArrowLeft') {
				prevImage();
			}
		}
	});
});
