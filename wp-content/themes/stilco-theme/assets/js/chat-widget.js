document.addEventListener('DOMContentLoaded', function () {
	var chatButton = document.getElementById('stilco-chat-button');
	var chatWindow = document.getElementById('stilco-chat-window');
	var chatClose = document.getElementById('stilco-chat-close');
	var chatForm = document.getElementById('stilco-chat-form');
	var chatInput = document.getElementById('stilco-chat-input');
	var chatMessages = document.getElementById('stilco-chat-messages');
	var actionButtons = document.querySelectorAll('.chat-action-btn');
	var waitingForOrderNumber = false;

	if (!chatButton || !chatWindow || !chatClose || !chatForm || !chatInput || !chatMessages) {
		return;
	}

	function scrollToBottom() {
		chatMessages.scrollTop = chatMessages.scrollHeight;
	}

	function removeActions() {
		var actionsContainer = document.getElementById('stilco-chat-actions');

		if (actionsContainer) {
			actionsContainer.classList.add('hidden');
		}
	}

	function appendUserMessage(text) {
		var msgDiv = document.createElement('div');

		removeActions();
		msgDiv.className = 'flex items-start justify-end space-x-2 mt-2';
		msgDiv.innerHTML = ''
			+ '<div class="bg-stilco-dark text-white p-3 rounded-2xl rounded-tr-none shadow-sm text-sm max-w-[85%] leading-relaxed">'
			+ text
			+ '</div>';

		chatMessages.appendChild(msgDiv);
		scrollToBottom();
	}

	function appendTypingIndicator() {
		var typingDiv = document.createElement('div');

		typingDiv.className = 'flex items-start space-x-2 mt-2';
		typingDiv.id = 'chat-typing-indicator';
		typingDiv.innerHTML = ''
			+ '<div class="w-7 h-7 rounded-full bg-stilco-accent flex-shrink-0 flex items-center justify-center mt-0.5 shadow-sm"><span class="text-xs text-white font-bold">S</span></div>'
			+ '<div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm flex items-center space-x-1 border border-gray-100">'
			+ '<div class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></div>'
			+ '<div class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></div>'
			+ '<div class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></div>'
			+ '</div>';

		chatMessages.appendChild(typingDiv);
		scrollToBottom();

		return typingDiv;
	}

	function removeTypingIndicator(indicator) {
		if (indicator && indicator.parentNode) {
			indicator.parentNode.removeChild(indicator);
		}
	}

	function appendBotMessage(text) {
		var msgDiv = document.createElement('div');

		msgDiv.className = 'flex items-start space-x-2 mt-2';
		msgDiv.innerHTML = ''
			+ '<div class="w-7 h-7 rounded-full bg-stilco-accent flex-shrink-0 flex items-center justify-center mt-0.5 shadow-sm"><span class="text-xs text-white font-bold">S</span></div>'
			+ '<div class="bg-white p-3.5 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-700 max-w-[85%] leading-relaxed border border-gray-100">'
			+ text
			+ '</div>';

		chatMessages.appendChild(msgDiv);
		scrollToBottom();
	}

	function toggleChat() {
		if (chatWindow.classList.contains('hidden')) {
			chatWindow.classList.remove('hidden');

			setTimeout(function () {
				chatWindow.classList.add('chat-window-active');
				chatInput.focus();
			}, 10);
		} else {
			chatWindow.classList.remove('chat-window-active');

			setTimeout(function () {
				chatWindow.classList.add('hidden');
			}, 300);
		}
	}

	function handleResponse(text) {
		var lowerText = text.toLowerCase();
		var response = 'Dziękuję za wiadomość! 👋 Obecnie jestem w fazie testów i poznaję nasz asortyment. Skontaktujemy się z Tobą najszybciej jak to możliwe.';
		var indicator;

		if (waitingForOrderNumber) {
			response = 'Sprawdzam status zamówienia <b>' + text + '</b>... <br><br>Super wieści! 🎉 Twoje zamówienie zostało już spakowane i wkrótce wyruszy w drogę. Numer listu przewozowego zaraz trafi na Twojego maila. 📦';
			waitingForOrderNumber = false;
		} else if (lowerText.includes('zamówieni') || lowerText.includes('zamowien') || lowerText.includes('gdzie') || lowerText.includes('status')) {
			response = 'Chętnie sprawdzę status Twojego zamówienia. Proszę, podaj mi jego numer (np. #12345):';
			waitingForOrderNumber = true;
		} else if (lowerText.includes('wymien') || lowerText.includes('zwrot')) {
			response = 'Pamiętaj, że u nas możesz testować materac przez 30 dni. Jeśli chcesz zgłosić zwrot, wyślij wiadomość na adres kontakt@stilco.pl, a my zajmiemy się resztą - wyślemy kuriera pod Twoje drzwi.';
		} else if (lowerText.includes('materac')) {
			response = 'W naszej ofercie posiadamy najwyższej jakości materace zapewniające idelane podparcie. Poszukujesz materaca do łóżka pojedynczego czy małżeńskiego?';
		}

		indicator = appendTypingIndicator();
		chatInput.disabled = true;

		setTimeout(function () {
			removeTypingIndicator(indicator);
			appendBotMessage(response);
			chatInput.disabled = false;
			chatInput.focus();
		}, 1200);
	}

	chatButton.addEventListener('click', toggleChat);
	chatClose.addEventListener('click', toggleChat);

	chatForm.addEventListener('submit', function (event) {
		var text = chatInput.value.trim();

		event.preventDefault();

		if (!text) {
			return;
		}

		chatInput.value = '';
		appendUserMessage(text);
		handleResponse(text);
	});

	actionButtons.forEach(function (button) {
		button.addEventListener('click', function () {
			var action = this.getAttribute('data-action');
			var text = this.innerText.replace(/[^\w\s\żźćńółęąśŻŹĆĄŚĘŁÓŃ\?]/gi, '').trim();
			var indicator = appendTypingIndicator();

			appendUserMessage(text);

			setTimeout(function () {
				removeTypingIndicator(indicator);

				if ('order' === action) {
					appendBotMessage('Aby sprawdzić status, będę potrzebował numeru Twojego zamówienia. Wpisz go proszę poniżej (np. #12345):');
					waitingForOrderNumber = true;
				} else if ('mattress' === action) {
					appendBotMessage('Świetnie! Nasze materace projektujemy z myślą o najwyższym komforcie. Jeśli szukasz czegoś dla par, idealnym wyborem będzie model z niezależnymi strefami nacisku. Chcesz, abym opowiedział o nim coś więcej?');
				} else if ('contact' === action) {
					appendBotMessage('Łączę z naszym doradcą... 🎧<br><br>W międzyczasie możesz krótko opisać, w czym potrzebujesz pomocy, abyśmy mogli lepiej się przygotować.');
				}
			}, 1000);
		});
	});
});
