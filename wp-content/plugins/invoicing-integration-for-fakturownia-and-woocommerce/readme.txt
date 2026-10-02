=== Invoicing Integration for Fakturownia and WooCommerce ===
Contributors: devikit
Tags: fakturownia, invoice, woocommerce, accounting, poland
Requires at least: 5.8
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.9
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Seamless integration between WooCommerce and Fakturownia accounting system for Polish businesses.

== Description ==

**Invoicing Integration for Fakturownia and WooCommerce** connects your WooCommerce store with the popular Fakturownia accounting system used by thousands of Polish businesses.

= Key Features (FREE) =

* **Manual Invoice Generation** - Create invoices directly from WooCommerce order edit screen
* **Edit Draft Before Issuing** - Review and modify invoice data before sending to Fakturownia
* **Customer Synchronization** - Automatically sync customer data with Fakturownia contractors
* **NIP Field Support** - Add NIP number field to checkout (also compatible with nip-field-woocommerce plugin)
* **WooCommerce Blocks Support** - Full compatibility with Gutenberg checkout blocks
* **Advanced VAT Mapping** - Map WooCommerce tax classes to Fakturownia VAT rates (23%, 8%, 5%, zw, np, 0%)
* **VAT Exemption Legal Basis** - Configure legal basis for VAT exemption (ZW rate)
* **Invoice Download** - Download invoices directly from WordPress admin
* **Customer Download** - Allow customers to download their invoices from My Account page
* **Detailed Logging** - Track all API communications for debugging
* **HPOS Compatible** - Full support for WooCommerce High-Performance Order Storage

= PRO Features =

* **Automatic Invoice Generation** - Create invoices automatically on order status change
* **Automatic Proforma Invoices** - Issue proformas automatically for pending orders
* **Receipt (Paragon) Support** - Automatic receipt generation for retail customers
* **Corrections (Korygujące)** - Create correction invoices for existing invoices
* **Email with PDF Attachments** - Send invoices directly to customers as PDF attachments
* **Bulk Document Generation** - Generate invoices, proformas, and receipts for multiple orders at once
* **Bulk Email Sending** - Send invoices and proformas by email to multiple customers in bulk operations
* **OSS/MOSS Support** - Automatic handling for digital services sold to EU customers
* **Reverse Charge Support** - Automatic reverse charge handling for B2B transactions
* **VIES Database Validation** - Validate EU VAT numbers using VIES database
* **Warehouse Integration** - Bi-directional stock sync between WooCommerce and Fakturownia
* **GTU Codes** - Add GTU codes to products and invoices
* **PKWiU Codes** - Add PKWiU codes for VAT exempt products

[Get PRO Version](https://devikit.pl/produkt/fakturownia-woocommerce-pro/)

= Requirements =

* WordPress 5.8 or higher
* WooCommerce 5.0 or higher
* PHP 7.4 or higher
* Active Fakturownia account with API access

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/invoicing-integration-for-fakturownia-and-woocommerce/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to WooCommerce → Fakturownia
4. Enter your Fakturownia API Token and Subdomain
5. Test the connection and configure settings

== Frequently Asked Questions ==

= Where do I find my Fakturownia API token? =

Log in to your Fakturownia account, go to Settings → API → Zobacz → Dodaj nowy token, and copy your API token.

= What is my Fakturownia subdomain? =

Your subdomain is the part before ".fakturownia.pl" in your Fakturownia URL. For example, if your URL is https://example.fakturownia.pl/, your subdomain is "example".

= Does this work with WooCommerce Blocks? =

Yes, the plugin is compatible with both classic checkout and WooCommerce Blocks checkout.

= Can customers download their invoices? =

Yes, customers can download invoices from the My Account → View Order page.

= Is this compatible with nip-field-woocommerce plugin? =

Yes! The plugin automatically detects if nip-field-woocommerce is active and uses it instead of adding its own NIP field.

= Can I edit invoice data before creating it? =

Yes! When you click "Show parameters", a modal will appear allowing you to edit invoice dates and payment terms before sending to Fakturownia.

== External services ==

This plugin relies on the Fakturownia API, a third-party accounting service, to generate invoices and manage customer data.

= What is Fakturownia API? =

Fakturownia is a Polish online accounting system that provides invoicing and accounting services for businesses. This plugin uses their official API to:
*   Create and manage customers (contractors) in your Fakturownia account.
*   Generate VAT invoices.
*   Retrieve invoice templates, VAT codes, and other accounting data from your Fakturownia account.
*   Download invoice PDFs.

= What data is sent and when? =

The plugin sends the following data to Fakturownia API (https://*.fakturownia.pl/) in these situations:

**When you click "Create Invoice" or when automatic invoice generation is triggered (PRO version):**
*   Customer billing information: first name, last name, company name, VAT number (NIP), email, phone number, billing address (street, city, postal code, country).
*   Order information: order items (product names, quantities, prices, VAT rates), shipping details, payment method, order date, order total.

**When the plugin connects to Fakturownia API (on settings page load or when needed):**
*   Your Fakturownia API Token (for authentication).
*   Requests to retrieve your account settings: invoice templates, VAT codes, warehouse data (PRO version).

**No data is sent automatically without your action.** The plugin only communicates with Fakturownia API when:
*   You manually create an invoice from the order screen.
*   You enable automatic invoice generation in PRO version.
*   You open the plugin settings page (to load account configuration).
*   Warehouse synchronization is enabled in PRO version (webhook).

= Service provider information =

*   **Service name:** Fakturownia API
*   **Service URL:** https://www.fakturownia.pl/
*   **API documentation:** https://github.com/fakturownia/API
*   **Terms of Service:** https://www.fakturownia.pl/regulamin
*   **Privacy Policy:** https://www.fakturownia.pl/polityka-prywatnosci

By using this plugin, you acknowledge that customer and order data will be transmitted to Fakturownia for invoice generation purposes. You are responsible for ensuring compliance with applicable data protection laws (including GDPR) and informing your customers about this data processing.

== Screenshots ==

1. Plugin settings
2. Plugin settings
3. Plugin settings
4. Plugin settings
5. Plugin settings
6. Plugin settings
7. Plugin settings
8. Plugin settings
9. Plugin settings
10. Plugin settings
11. Plugin settings
12. Plugin settings
13. Plugin settings
14. Plugin settings
15. Plugin settings
16. Plugin settings
17. Plugin settings
18. Plugin settings

== Changelog ==

= 1.0.9 =
* Added: Map a WooCommerce tax class to Fakturownia ZW (VAT exempt) separately from 0% and NP; translations updated

= 1.0.8 =
* Fixed: Variation fields (lump sum rate) now save correctly when clicking "Save changes" - nonce validation updated for AJAX save request

= 1.0.7 =
* Fixed: save_admin_order_nip - WooCommerce hook may pass WP_Post instead of WC_Order; use instanceof check to avoid "Call to undefined method WP_Post::update_meta_data()"

= 1.0.6 =
* Added: NIP is now editable in order admin (billing section) - same as address fields
* Improved: Removed duplicate read-only NIP display when editing order

= 1.0.5 =
* 5-minute delay for automatic invoice emails (KSeF processing)
* Manual invoice email sending remains immediate

= 1.0.4 =
* Add screenshots to readme and assets for WordPress.org

= 1.0.3 =
* Add workflow_dispatch for manual deploy to WordPress.org
* Expand .distignore to exclude build artifacts (like wfirma)

= 1.0.2 =
* Fix plugin name: "Invoicing Integration for Fakturownia and WooCommerce" (remove redundant "for")
* Remove .wordpress-org - SVN uses assets folder automatically

= 1.0.1 =
* Add plugin icons for WordPress.org (128x128, 256x256)

= 1.0.0 =
* Initial release
* Manual invoice generation with draft editing
* Customer synchronization
* Advanced VAT mapping with exemption basis
* Customer invoice download
* NIP field support with compatibility
* Invoice template selection
* Detailed API logging

== Upgrade Notice ==

= 1.0.9 =
Configure ZW (VAT exempt) tax class mapping under WooCommerce → Fakturownia → Invoicing when you need ZW distinct from 0% and NP.

= 1.0.8 =
Fixed variation fields (e.g. lump sum) not saving when clicking "Save changes" - updated nonce validation for AJAX requests.

= 1.0.7 =
Fixed compatibility when WooCommerce passes WP_Post instead of WC_Order to order save hooks.

= 1.0.6 =
NIP can now be edited in the order admin billing section, just like the address fields.

= 1.0.5 =
5-minute delay for automatic invoice emails (KSeF processing). Manual emails unchanged.

= 1.0.4 =
Added screenshots for WordPress.org plugin directory.

= 1.0.3 =
Maintenance release. No functional changes for users.

= 1.0.2 =
Plugin name fix and .distignore update.

= 1.0.1 =
Added plugin icons for WordPress.org directory.

= 1.0.0 =
Initial release of the plugin.

