export function initScrollAnimations() {
	var animationSelectors = [
		{ className: '.animate-on-scroll', activeClass: 'animate-fade-in-up' },
		{ className: '.animate-slide-left', activeClass: 'animate-fade-in-left' },
		{ className: '.animate-slide-right', activeClass: 'animate-fade-in-right' },
		{ className: '.animate-zoom', activeClass: 'animate-zoom-in' }
	];

	if (typeof IntersectionObserver === 'undefined') {
		animationSelectors.forEach(function (selector) {
			document.querySelectorAll(selector.className).forEach(function (element) {
				element.classList.remove('opacity-0');
				element.classList.add(selector.activeClass);
			});
		});

		return;
	}

	var observer = new IntersectionObserver(
		function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) {
					return;
				}

				animationSelectors.forEach(function (selector) {
					if (entry.target.classList.contains(selector.className.replace('.', ''))) {
						entry.target.classList.remove('opacity-0');
						entry.target.classList.add(selector.activeClass);
					}
				});

				observer.unobserve(entry.target);
			});
		},
		{
			threshold: 0.1,
			rootMargin: '0px 0px -50px 0px'
		}
	);

	animationSelectors.forEach(function (selector) {
		document.querySelectorAll(selector.className).forEach(function (element) {
			observer.observe(element);
		});
	});
}
