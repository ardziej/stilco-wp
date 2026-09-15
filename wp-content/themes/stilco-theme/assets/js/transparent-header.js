document.addEventListener('DOMContentLoaded', function () {
	var header = document.getElementById('main-header');
	var logo = document.getElementById('logo-img');

	if (!header || !logo) {
		return;
	}

	// "light": bright hero, header keeps dark text at the top (new home design).
	// "dark": dark hero, header uses white text and an inverted logo at the top.
	var isLightHero = header.getAttribute('data-hero-tone') === 'light';
	var topClasses = isLightHero
		? ['bg-white/60', 'text-stilco-dark', 'border-white/40']
		: ['bg-white/5', 'text-white', 'border-white/10'];
	var scrolledClasses = ['bg-white/90', 'text-stilco-dark', 'border-transparent'];

	function onScroll() {
		if (window.scrollY > 50) {
			header.classList.remove.apply(header.classList, topClasses);
			header.classList.add.apply(header.classList, scrolledClasses);
			logo.classList.remove('invert', 'brightness-0');
		} else {
			header.classList.remove.apply(header.classList, scrolledClasses);
			header.classList.add.apply(header.classList, topClasses);

			if (!isLightHero) {
				logo.classList.add('invert', 'brightness-0');
			}
		}
	}

	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();
});
