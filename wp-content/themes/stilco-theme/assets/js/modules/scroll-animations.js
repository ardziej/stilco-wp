export function initScrollAnimations() {
	// Under reduced motion the CSS never hides these elements, so there is nothing to reveal.
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	var elements = document.querySelectorAll('.animate-on-scroll');

	if (typeof IntersectionObserver === 'undefined') {
		elements.forEach(function (element) {
			element.classList.add('is-revealed');
		});

		return;
	}

	var observer = new IntersectionObserver(
		function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) {
					return;
				}

				entry.target.classList.add('is-revealed');
				observer.unobserve(entry.target);
			});
		},
		{
			threshold: 0.1,
			rootMargin: '0px 0px -50px 0px'
		}
	);

	elements.forEach(function (element) {
		observer.observe(element);
	});
}
