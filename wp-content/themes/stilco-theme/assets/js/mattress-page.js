document.addEventListener('DOMContentLoaded', function () {
	var mainImage = document.getElementById('main-product-image');
	var thumbs = document.querySelectorAll('.gallery-thumb');
	var stickyBar = document.getElementById('sticky-cart-bar');
	var buttons = document.querySelectorAll('.size-btn');
	var priceDisplay = document.getElementById('price-display');
	var triggerPoint = 600;

	thumbs.forEach(function (thumb) {
		thumb.addEventListener('click', function () {
			var newSrc = this.getAttribute('data-image');

			if (!mainImage || !newSrc) {
				return;
			}

			mainImage.classList.add('is-image-loading');

			setTimeout(function () {
				mainImage.src = newSrc;
				mainImage.classList.remove('is-image-loading');
			}, 150);

			thumbs.forEach(function (item) {
				item.classList.remove('border-stilco-accent', 'opacity-100');
				item.classList.add('border-transparent', 'opacity-70');
			});

			thumb.classList.remove('border-transparent', 'opacity-70');
			thumb.classList.add('border-stilco-accent', 'opacity-100');
		});
	});

	if (stickyBar) {
		window.addEventListener('scroll', function () {
			if (window.scrollY > triggerPoint) {
				stickyBar.classList.remove('translate-y-full');
			} else {
				stickyBar.classList.add('translate-y-full');
			}
		});
	}

	buttons.forEach(function (button) {
		button.addEventListener('click', function () {
			buttons.forEach(function (item) {
				item.classList.remove('border-2', 'border-stilco-accent', 'bg-stilco-accent', 'text-white', 'shadow-md');
				item.classList.add('border', 'border-gray-300', 'bg-white', 'text-stilco-dark');
			});

			button.classList.remove('border', 'border-gray-300', 'bg-white', 'text-stilco-dark');
			button.classList.add('border-2', 'border-stilco-accent', 'bg-stilco-accent', 'text-white', 'shadow-md');

			if (priceDisplay) {
				var price = button.getAttribute('data-price');
				var formattedPrice = price ? price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') : '';

				priceDisplay.innerHTML = formattedPrice + ' zł';
			}
		});
	});
});
