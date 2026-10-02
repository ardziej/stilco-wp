/**
 * Scrolled state of the site header: a shadow once the page moves, plus the
 * background swap of the transparent home header. The header height never changes.
 */
export function initHeaderScrollState() {
	var header = document.querySelector('header');

	if (!header) {
		return;
	}

	var logo = document.getElementById('logo-img');
	// Only the transparent header carries data-hero-tone.
	// "light": bright hero, dark text at the top. "dark": dark hero, white text and an inverted logo.
	var tone = header.getAttribute('data-hero-tone');
	var topClasses = [];
	var scrolledClasses = ['shadow-md'];

	if (tone === 'light') {
		topClasses = ['bg-white/60', 'text-stilco-dark', 'border-white/40'];
	} else if (tone === 'dark') {
		topClasses = ['bg-white/5', 'text-white', 'border-white/10'];
	}

	if (tone) {
		scrolledClasses.push('bg-white/90', 'text-stilco-dark', 'border-transparent');
	}

	function update() {
		var isScrolled = window.scrollY > 50;

		header.classList.remove(...(isScrolled ? topClasses : scrolledClasses));
		header.classList.add(...(isScrolled ? scrolledClasses : topClasses));

		if (logo && tone === 'dark') {
			logo.classList.toggle('invert', !isScrolled);
			logo.classList.toggle('brightness-0', !isScrolled);
		}
	}

	window.addEventListener('scroll', update, { passive: true });
	update();
}
