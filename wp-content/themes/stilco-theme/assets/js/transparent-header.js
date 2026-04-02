document.addEventListener('DOMContentLoaded', function () {
	var header = document.getElementById('main-header');
	var logo = document.getElementById('logo-img');

	if (!header || !logo) {
		return;
	}

	function onScroll() {
		if (window.scrollY > 50) {
			header.classList.remove('bg-white/5', 'text-white', 'border-white/10');
			header.classList.add('bg-white/90', 'text-stilco-dark', 'border-transparent');
			logo.classList.remove('invert', 'brightness-0');
		} else {
			header.classList.add('bg-white/5', 'text-white', 'border-white/10');
			header.classList.remove('bg-white/90', 'text-stilco-dark', 'border-transparent');
			logo.classList.add('invert', 'brightness-0');
		}
	}

	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();
});
