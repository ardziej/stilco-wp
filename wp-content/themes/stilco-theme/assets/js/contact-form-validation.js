/**
 * Inline validation for the contact form (Contact Form 7).
 *
 * Shows an error next to e-mail / phone fields as soon as the user leaves
 * the field (or while typing, once the field has been touched), instead of
 * only after submit. Server-side CF7 validation stays untouched.
 */
(function () {
	'use strict';

	var MESSAGES = {
		email: 'Podaj poprawny adres e-mail, np. jan@przyklad.pl.',
		tel: 'Podaj poprawny numer telefonu (min. 9 cyfr).',
		required: 'To pole jest wymagane.'
	};

	function isValidEmail(value) {
		return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value);
	}

	function isValidPhone(value) {
		var digits = value.replace(/\D/g, '');
		return digits.length >= 9 && digits.length <= 15;
	}

	function validate(field) {
		var value = field.value.trim();
		var type = field.getAttribute('type');
		var required = field.hasAttribute('required') || field.getAttribute('aria-required') === 'true';

		if (!value) {
			return required ? MESSAGES.required : '';
		}

		if (type === 'email' && !isValidEmail(value)) {
			return MESSAGES.email;
		}

		if (type === 'tel' && !isValidPhone(value)) {
			return MESSAGES.tel;
		}

		return '';
	}

	function getMessageElement(field) {
		var id = field.id ? field.id + '-inline-error' : null;
		var element = id ? document.getElementById(id) : null;

		if (element) {
			return element;
		}

		element = document.createElement('span');
		element.className = 'stilco-inline-error';
		element.setAttribute('aria-live', 'polite');

		if (id) {
			element.id = id;
		}

		var wrap = field.closest('.wpcf7-form-control-wrap') || field.parentNode;
		wrap.appendChild(element);

		if (id) {
			var describedBy = field.getAttribute('aria-describedby');
			field.setAttribute('aria-describedby', describedBy ? describedBy + ' ' + id : id);
		}

		return element;
	}

	function render(field) {
		var message = validate(field);
		var element = getMessageElement(field);

		element.textContent = message;
		field.classList.toggle('is-invalid', message !== '');
		field.setAttribute('aria-invalid', message ? 'true' : 'false');
	}

	function bind(field) {
		var touched = false;

		field.addEventListener('blur', function () {
			touched = true;
			render(field);
		});

		field.addEventListener('input', function () {
			if (touched) {
				render(field);
			}
		});
	}

	function init() {
		var fields = document.querySelectorAll('.wpcf7-form input[type="email"], .wpcf7-form input[type="tel"]');

		fields.forEach(function (field) {
			if (!field.id) {
				field.id = 'stilco-field-' + Math.random().toString(36).slice(2, 8);
			}

			bind(field);
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
