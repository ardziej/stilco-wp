/**
 * Mobile menu toggle for the main header.
 */
export function initMobileMenu() {
	const toggle = document.getElementById('mobile-menu-toggle');
	const menu = document.getElementById('mobile-menu');

	if (!toggle || !menu) {
		return;
	}

	const setOpen = (isOpen) => {
		menu.classList.toggle('hidden', !isOpen);
		toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		toggle.setAttribute('aria-label', isOpen ? 'Zamknij menu' : 'Otwórz menu');
	};

	toggle.addEventListener('click', () => {
		setOpen(menu.classList.contains('hidden'));
	});

	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && !menu.classList.contains('hidden')) {
			setOpen(false);
			toggle.focus();
		}
	});

	document.addEventListener('click', (event) => {
		if (menu.classList.contains('hidden')) {
			return;
		}

		if (!menu.contains(event.target) && !toggle.contains(event.target)) {
			setOpen(false);
		}
	});
}
