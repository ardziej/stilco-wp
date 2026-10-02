jQuery(document).ready(function ($) {
    var nipRow = $('#billing_nip_field');
    var wantInvoiceCheckbox = $('#billing_want_invoice');

    // Initial state
    if (wantInvoiceCheckbox.length > 0) {
        // Remove the initial hide class
        nipRow.removeClass('fakturownia-nip-hidden-initially');

        if (wantInvoiceCheckbox.is(':checked')) {
            nipRow.show();
        } else {
            nipRow.hide();
        }

        // Toggle on change
        wantInvoiceCheckbox.on('change', function () {
            if ($(this).is(':checked')) {
                nipRow.slideDown();
            } else {
                nipRow.slideUp();
                // Optional: clear NIP value when hiding
                // $('#billing_nip').val('');
            }
        });
    }
});

