jQuery(document).ready(function($) {
	$('.devikit-fakturownia-banner-remind-later').on('click', function() {
		$.post(ajaxurl, {
			action: 'devikit_fakturownia_dismiss_banner',
			action_type: 'remind_later',
			nonce: devikitFakturowniaWcBanner.nonce
		}, function(response) {
			if (response.success) {
				$('.devikit-fakturownia-pro-banner').fadeOut();
			}
		});
	});
	
	$('.devikit-fakturownia-banner-dismiss-permanent').on('click', function() {
		$.post(ajaxurl, {
			action: 'devikit_fakturownia_dismiss_banner',
			action_type: 'dismiss_permanent',
			nonce: devikitFakturowniaWcBanner.nonce
		}, function(response) {
			if (response.success) {
				$('.devikit-fakturownia-pro-banner').fadeOut();
			}
		});
	});
});



