document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('h1').forEach(function (heading) {
		if (heading.textContent.trim() === 'Kasa' || heading.textContent.trim() === 'Checkout') {
			heading.classList.add('hidden');
		}
	});
});

document.querySelectorAll('h1').forEach(function (heading) {
	if (heading.textContent.trim() === 'Kasa' || heading.textContent.trim() === 'Checkout') {
		heading.classList.add('hidden');
	}
});
