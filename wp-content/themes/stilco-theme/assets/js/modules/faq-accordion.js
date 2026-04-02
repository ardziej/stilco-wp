export function initFaqAccordion() {
	var faqItems = document.querySelectorAll('.faq-item');

	if (faqItems.length === 0) {
		return;
	}

	function setItemState(item, isOpen) {
		var button = item.querySelector('.faq-btn');

		item.classList.toggle('is-open', isOpen);

		if (button) {
			button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		}
	}

	faqItems.forEach(function (item) {
		var button = item.querySelector('.faq-btn');

		if (!button) {
			return;
		}

		button.addEventListener('click', function () {
			var isOpen = item.classList.contains('is-open');

			faqItems.forEach(function (otherItem) {
				if (otherItem === item) {
					return;
				}

				setItemState(otherItem, false);
			});

			setItemState(item, !isOpen);
		});
	});
}
