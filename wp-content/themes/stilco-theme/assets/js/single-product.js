document.addEventListener('DOMContentLoaded', function () {
	var reviewForm = document.getElementById('commentform');

	if (reviewForm) {
		reviewForm.setAttribute('enctype', 'multipart/form-data');
	}

	document.querySelectorAll('.js-scroll-to-top').forEach(function (button) {
		button.addEventListener('click', function () {
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});
	});
});
