document.addEventListener('DOMContentLoaded', function () {
	var listContainer = document.getElementById('deliveries-list');

	if (!listContainer) {
		return;
	}

	var deliveriesEndpoint = listContainer.getAttribute('data-deliveries-endpoint');

	if (!deliveriesEndpoint) {
		return;
	}

	function updateDashboard() {
		fetch(deliveriesEndpoint)
			.then(function (response) {
				if (!response.ok) {
					throw new Error('Network error');
				}

				return response.json();
			})
			.then(function (data) {
				var lastUpdate = document.getElementById('last-update');

				listContainer.innerHTML = '';

				if (!data || data.length === 0) {
					listContainer.innerHTML = '<div class="empty-state">Brak zamówień z odroczonym terminem realizacji.</div>';
				} else {
					var today = new Date();
					today.setHours(0, 0, 0, 0);

					data.forEach(function (item) {
						var delDate = new Date(item.delivery_date);
						var msDiff = delDate.getTime() - today.getTime();
						var daysDiff = Math.ceil(msDiff / (1000 * 3600 * 24));
						var cardClass = 'order-card';
						var timeDesc = '';
						var statusMap = {
							processing: 'W trakcie',
							'on-hold': 'Wstrzymane'
						};
						var status = statusMap[item.status] || item.status;
						var card = document.createElement('div');

						if (daysDiff <= 3) {
							cardClass += ' urgent';
							timeDesc = daysDiff <= 0 ? (daysDiff === 0 ? 'Dzisiaj!' : 'Po terminie!') : (daysDiff === 1 ? 'Jutro!' : 'Za ' + daysDiff + ' dni');
						} else if (daysDiff <= 7) {
							cardClass += ' soon';
							timeDesc = 'Za ' + daysDiff + ' dni';
						} else {
							timeDesc = 'Za ' + daysDiff + ' dni';
						}

						card.className = cardClass;
						card.innerHTML = ''
							+ '<div>'
							+ '<div class="order-meta">Zamówienie <strong>#' + item.order_number + '</strong> &bull; ' + item.customer_name + ' &bull; ' + status + '</div>'
							+ '<div class="product-name">' + item.product_name + '<span class="quantity">x' + item.quantity + '</span></div>'
							+ '</div>'
							+ '<div class="delivery-info">'
							+ '<div class="countdown">' + timeDesc + '</div>'
							+ '<div class="delivery-date">' + item.delivery_date + '</div>'
							+ '</div>';

						listContainer.appendChild(card);
					});
				}

				if (lastUpdate) {
					lastUpdate.innerText = 'Ostatnia aktualizacja: ' + new Date().toLocaleTimeString();
				}
			})
			.catch(function (error) {
				var lastUpdate = document.getElementById('last-update');

				console.error('API Error:', error);

				if (lastUpdate) {
					lastUpdate.innerText = 'Błąd odświeżania. Próba ponowienia nastąpi automatycznie...';
				}
			});
	}

	updateDashboard();
	setInterval(updateDashboard, 60000);
});
