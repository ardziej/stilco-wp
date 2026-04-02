export function initHeaderScrollState() {
	var header = document.querySelector('header');

	if (!header) {
		return;
	}

	window.addEventListener('scroll', function () {
		if (window.scrollY > 50) {
			header.classList.add('shadow-md');
			header.classList.replace('py-4', 'py-2');
		} else {
			header.classList.remove('shadow-md');
			header.classList.replace('py-2', 'py-4');
		}
	});
}
