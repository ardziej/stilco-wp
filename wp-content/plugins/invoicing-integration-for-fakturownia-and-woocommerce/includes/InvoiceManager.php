<?php

namespace Devikit\Fakturownia;

use Devikit\Fakturownia\Admin\Settings;
use Devikit\Fakturownia\Api\Client;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class InvoiceManager {

	/**
	 * @var Client
	 */
	private $api_client;

	/**
	 * @var Settings
	 */
	private $settings;

	/**
	 * Constructor
	 * 
	 * @param Client $api_client
	 * @param Settings $settings
	 */
	public function __construct( Client $api_client, Settings $settings ) {
		$this->api_client = $api_client;
		$this->settings = $settings;

		add_action( 'add_meta_boxes', [ $this, 'add_order_meta_box' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_metabox_scripts' ] );
		add_action( 'wp_ajax_devikit_fakturownia_get_draft', [ $this, 'ajax_get_draft' ] );
		add_action( 'wp_ajax_devikit_fakturownia_create_invoice', [ $this, 'ajax_create_invoice' ] );
		add_action( 'wp_ajax_devikit_fakturownia_save_invoice_params', [ $this, 'ajax_save_invoice_params' ] );
		add_action( 'wp_ajax_devikit_fakturownia_download_pdf', [ $this, 'ajax_download_pdf' ] );
		
		// Frontend invoice download
		add_action( 'woocommerce_view_order', [ $this, 'display_invoice_download_button' ], 20 );
		add_action( 'template_redirect', [ $this, 'handle_invoice_download' ] );
		
		// Retry handler
		add_action( 'devikit_fakturownia_retry_invoice', [ $this, 'retry_invoice_creation' ], 10, 1 );
		
		// Delayed invoice email handler (Action Scheduler)
		add_action( 'devikit_fakturownia_send_delayed_invoice_email', [ $this, 'handle_delayed_invoice_email' ], 10, 2 );
		
		// Allow PRO to hook into metabox
		add_action( 'devikit_fakturownia_metabox_after_invoice', '__return_false' );
	}

	/**
	 * Add Meta Box
	 */
	public function add_order_meta_box() {
		add_meta_box(
			'devikit_fakturownia_invoice',
			__( 'Fakturownia', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			[ $this, 'render_meta_box' ],
			'shop_order',
			'side',
			'high'
		);
		
		// HPOS support
		if ( class_exists( '\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController' ) && wc_get_container()->get( \Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled() ) {
			add_meta_box(
				'devikit_fakturownia_invoice',
				__( 'Fakturownia', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
				[ $this, 'render_meta_box' ],
				wc_get_page_screen_id( 'shop_order' ),
				'side',
				'high'
			);
		}
	}
	
	/**
	 * Enqueue metabox scripts
	 */
	public function enqueue_metabox_scripts( $hook_suffix ) {
		if ( ! in_array( $hook_suffix, [ 'post.php', 'woocommerce_page_wc-orders' ], true ) ) {
			return;
		}
		
		global $post;
		$order_id = null;
		
		if ( $post && $post->post_type === 'shop_order' ) {
			$order_id = $post->ID;
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Order ID from WP admin page context
		} elseif ( isset( $_GET['id'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Order ID from WP admin page context
			$order_id = absint( $_GET['id'] );
		}
		
		if ( ! $order_id ) {
			return;
		}
		
		wp_enqueue_script( 'jquery' );
		
		$metabox_js = "
			jQuery(document).ready(function($) {
				// Edit Draft Modal
				var draftModal = $('#devikit-fakturownia-draft-modal');
				var draftForm = $('#devikit-fakturownia-draft-form');
				
				// Save invoice parameters on change
				var saveTimeout;
				function saveInvoiceParams() {
					clearTimeout(saveTimeout);
					saveTimeout = setTimeout(function() {
						var orderId = $('#devikit-fakturownia-create-invoice').data('order-id');
						if (!orderId) {
							orderId = $('#devikit-fakturownia-retry-invoice').data('order-id');
						}
						
						if (!orderId) return;
						
						$.post(ajaxurl, {
							action: 'devikit_fakturownia_save_invoice_params',
							order_id: orderId,
							doc_type: 'invoice',
							paid_amount: $('#fakturownia-invoice-paid-amount').val() || '0.00',
							issue_date: $('#fakturownia-invoice-issue-date').val(),
							sale_date: $('#fakturownia-invoice-sale-date').val(),
							payment_deadline: $('#fakturownia-invoice-payment-deadline').val(),
							payment_method: $('#fakturownia-invoice-payment-method').val(),
							note: $('#fakturownia-invoice-note').val(),
							nonce: '" . esc_js( wp_create_nonce( 'fakturownia_save_invoice_params' ) ) . "'
						});
					}, 1000);
				}
				
				// Attach change handlers for invoice parameters
				$(document).on('change', '#fakturownia-invoice-paid-amount, #fakturownia-invoice-issue-date, #fakturownia-invoice-sale-date, #fakturownia-invoice-payment-deadline, #fakturownia-invoice-payment-method, #fakturownia-invoice-note', function() {
					saveInvoiceParams();
				});
				
				$('#devikit-fakturownia-create-invoice').on('click', function() {
					var \$btn = $(this);
					var orderId = \$btn.data('order-id');
					var \$loading = $('#devikit-fakturownia-loading');
					var \$error = $('#devikit-fakturownia-error');
					
					// Save parameters before creating invoice
					saveInvoiceParams();
					setTimeout(function() {
						// After saving params, create invoice
						\$btn.prop('disabled', true);
						\$loading.show();
						\$error.text('');

						$.post(ajaxurl, {
							action: 'devikit_fakturownia_create_invoice',
							order_id: orderId,
							nonce: '" . esc_js( wp_create_nonce( 'fakturownia_create_invoice' ) ) . "'
						}, function(response) {
							\$loading.hide();
							\$btn.prop('disabled', false);
							
							if (response.success) {
								location.reload();
							} else {
								\$error.text(response.data && response.data.message ? response.data.message : '" . esc_js( __( 'Failed to create invoice', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ) . "');
							}
						});
					});
				});
				
				// Close modal
				$('.devikit-fakturownia-modal-close, .devikit-fakturownia-modal-cancel').on('click', function() {
					draftModal.hide();
				});
				
				// Retry button
				$('#devikit-fakturownia-retry-invoice').on('click', function() {
					var \$btn = $(this);
					var orderId = \$btn.data('order-id');
					var \$loading = $('#devikit-fakturownia-loading');
					var \$error = $('#devikit-fakturownia-error');
					
					// Save parameters before retrying
					saveInvoiceParams();
					setTimeout(function() {
						\$btn.prop('disabled', true);
						\$loading.show();
						\$error.text('');

						$.post(ajaxurl, {
							action: 'devikit_fakturownia_create_invoice',
							order_id: orderId,
							nonce: '" . esc_js( wp_create_nonce( 'fakturownia_create_invoice' ) ) . "'
						}, function(response) {
							\$loading.hide();
							\$btn.prop('disabled', false);
							if (response.success) {
								location.reload();
							} else {
								\$error.text(response.data.message || '" . esc_js( __( 'Failed to create invoice', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ) . "');
							}
						});
					});
				});
				
				// Submit draft
				draftForm.on('submit', function(e) {
					e.preventDefault();
					var \$btn = $('#devikit-fakturownia-submit-draft');
					var \$loading = $('#devikit-fakturownia-draft-loading');
					var \$error = $('#devikit-fakturownia-draft-error');
					
					// Validate required fields
					var issueDate = $('#draft-issue-date').val();
					var sellDate = $('#draft-sell-date').val();
					var paymentTo = $('#draft-payment-to').val();
					
					if (!issueDate || !sellDate || !paymentTo) {
						\$error.text('" . esc_js( __( 'Please fill in all required fields (Issue Date, Sale Date, Payment Deadline)', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ) . "');
						return false;
					}
					
					\$btn.prop('disabled', true);
					\$loading.show();
					\$error.text('');
					
					// Collect form data
					var orderId = $('#devikit-fakturownia-create-invoice').data('order-id');
					var formData = {
						action: 'devikit_fakturownia_create_invoice',
						order_id: orderId,
						nonce: '" . esc_js( wp_create_nonce( 'fakturownia_create_invoice' ) ) . "',
						draft_data: {
							issue_date: issueDate,
							sell_date: sellDate,
							payment_to: paymentTo,
							payment_type: $('#draft-payment-type').val(),
							currency: $('#draft-currency').val(),
							positions: []
						}
					};
					
					$('#draft-positions tbody tr').each(function() {
						var row = $(this);
						formData.draft_data.positions.push({
							name: row.find('.draft-position-name').val(),
							quantity: parseFloat(row.find('.draft-position-quantity').val()) || 1,
							price_net: parseFloat(row.find('.draft-position-price').val()) || 0,
							tax: row.find('.draft-position-tax').val()
						});
					});
					
					$.post(ajaxurl, formData, function(response) {
						\$loading.hide();
						\$btn.prop('disabled', false);
						
						if (response.success) {
							location.reload();
						} else {
							\$error.text(response.data && response.data.message ? response.data.message : '" . esc_js( __( 'Failed to create invoice', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ) . "');
						}
					});
				});
				
				$('#devikit-fakturownia-download-pdf').on('click', function() {
					var \$btn = $(this);
					var invoiceId = \$btn.data('invoice-id');
					var invoiceNumber = \$btn.data('invoice-number');
					var originalText = \$btn.text();
					
					\$btn.prop('disabled', true).text('" . esc_js( __( 'Downloading...', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ) . "');
					
					$.post(ajaxurl, {
						action: 'devikit_fakturownia_download_pdf',
						invoice_id: invoiceId,
						invoice_number: invoiceNumber,
						nonce: '" . esc_js( wp_create_nonce( 'fakturownia_download_pdf' ) ) . "'
					}, function(response) {
						\$btn.prop('disabled', false).text(originalText);
						
						if (response.success && response.data.pdf) {
							var binary = atob(response.data.pdf);
							var array = new Uint8Array(binary.length);
							for (var i = 0; i < binary.length; i++) {
								array[i] = binary.charCodeAt(i);
							}
							var blob = new Blob([array], {type: 'application/pdf'});
							var url = window.URL.createObjectURL(blob);
							var a = document.createElement('a');
							a.href = url;
							a.download = response.data.filename;
							document.body.appendChild(a);
							a.click();
							window.URL.revokeObjectURL(url);
							document.body.removeChild(a);
						} else {
							alert(response.data.message || '" . esc_js( __( 'Failed to download PDF', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ) . "');
						}
					});
				});
				
				// Send invoice email (handled by PRO plugin via hook)
				$('#devikit-fakturownia-send-invoice-email').off('click').on('click', function(e) {
					e.preventDefault();
					
					var \$btn = $(this);
					var \$loading = $('#devikit-fakturownia-invoice-email-loading');
					var \$success = $('#devikit-fakturownia-invoice-email-success');
					var \$error = $('#devikit-fakturownia-invoice-email-error');
					
					if (!confirm('" . esc_js( __( 'Are you sure you want to send the invoice to the customer?', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ) . "')) {
						return false;
					}
					
					\$btn.prop('disabled', true).hide();
					\$loading.show();
					\$success.hide();
					\$error.text('');
					
					$.post(ajaxurl, {
						action: 'devikit_fakturownia_send_invoice_email',
						order_id: \$btn.data('order-id'),
						nonce: '" . esc_js( wp_create_nonce( 'fakturownia_send_invoice_email' ) ) . "'
					}, function(response) {
						\$loading.hide();
						if (response.success) {
							\$success.show();
							setTimeout(function() {
								\$btn.prop('disabled', false).show();
								\$success.hide();
							}, 3000);
						} else {
							\$btn.prop('disabled', false).show();
							\$error.text(response.data && response.data.message ? response.data.message : '" . esc_js( __( 'Unknown error', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ) . "');
						}
					}).fail(function(xhr, status, error) {
						\$loading.hide();
						\$btn.prop('disabled', false).show();
						\$error.text('AJAX Error: ' + error);
					});
					
					return false;
				});

				// Toggle parameters sections (invoice/proforma/receipt/bill/correction)
				if (window.devikitFakturowniaToggleRegistered) {
					return;
				}
				window.devikitFakturowniaToggleRegistered = true;

				$(document).on('click.fakturowniaToggle', '.devikit-fakturownia-toggle-params', function(e) {
					e.preventDefault();
					e.stopPropagation();
					e.stopImmediatePropagation();
					
					var btn = $(this);
					var docType = btn.attr('data-doc-type');
					if (!docType) {
						return false;
					}
					var paramsDiv = $('#devikit-fakturownia-params-' + docType);
					var label = btn.find('.devikit-params-label');
					var icon = btn.find('.devikit-params-icon');
					
					if (paramsDiv.length === 0) {
						return false;
					}
					
					if (paramsDiv.is(':hidden')) {
						paramsDiv.prev('.devikit-fakturownia-params-spacer').show();
						paramsDiv.slideDown(200);
						label.text('" . esc_js( __( 'Hide Parameters', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ) . "');
						icon.html(' ▲');
					} else {
						paramsDiv.slideUp(200, function() {
							paramsDiv.prev('.devikit-fakturownia-params-spacer').hide();
						});
						label.text('" . esc_js( __( 'Show Parameters', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ) . "');
						icon.html(' ▼');
					}
					return false;
				});
			});
		";
		
		wp_add_inline_script( 'jquery', $metabox_js );
	}

	/**
	 * Render Meta Box
	 */
	public function render_meta_box( $post_or_order_object ) {
		// Get order object (HPOS compatible)
		if ( is_numeric( $post_or_order_object ) ) {
			$order = wc_get_order( $post_or_order_object );
		} elseif ( $post_or_order_object instanceof \WC_Order ) {
			$order = $post_or_order_object;
		} else {
			$order = wc_get_order( $post_or_order_object->ID );
		}
		
		if ( ! $order ) {
			return;
		}
		
		// Show invoice section for all currencies (foreign invoices are handled automatically)
		// No need to hide for non-PLN - InvoiceManager now handles foreign invoices automatically

		$invoice_id = $order->get_meta( '_fakturownia_invoice_id' );
		$invoice_number = $order->get_meta( '_fakturownia_invoice_number' );

		// FREE must remain fully functional: do not hide invoice creation
		// based on PRO-only document type settings.
		$hide_invoice_section = false;
		
		// Get default invoice parameters
		$invoice_params = $this->get_default_document_params( $order, 'invoice' );
		$saved_paid_amount = $invoice_params['paid_amount'];
		$saved_issue_date = $invoice_params['issue_date'];
		$saved_sale_date = $invoice_params['sale_date'];
		$saved_payment_deadline = $invoice_params['payment_deadline'];
		$saved_payment_method = $invoice_params['payment_method'];
		$saved_invoice_note = $invoice_params['note'];

		?>
		<div class="devikit-fakturownia-metabox">
			<?php do_action( 'devikit_fakturownia_metabox_before_invoice', $order ); ?>
			<?php if ( $invoice_id ) : ?>
				<p>
					<strong><?php echo esc_html__( 'Invoice Created:', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong><br>
					<?php echo esc_html( $invoice_number ?: $invoice_id ); ?>
				</p>
				
				<?php
				// Check for total mismatch
				$has_total_mismatch = $order->get_meta( '_fakturownia_invoice_total_mismatch' ) === 'yes';
				if ( $has_total_mismatch ) {
					$order_total = floatval( $order->get_meta( '_fakturownia_invoice_order_total' ) );
					$invoice_total = floatval( $order->get_meta( '_fakturownia_invoice_fakturownia_total' ) );
					$difference = floatval( $order->get_meta( '_fakturownia_invoice_total_difference' ) );
					?>
					<div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; margin: 10px 0;">
						<p style="margin: 0; font-weight: bold; color: #856404;">
							<?php echo esc_html__( '⚠ Total Mismatch Detected', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
						</p>
						<p style="margin: 5px 0 0 0; color: #856404;">
						<?php 
						printf(
							/* translators: 1: Order total, 2: Invoice total, 3: Difference */
							esc_html__( 'Order total: %1$s | Invoice total: %2$s | Difference: %3$s', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
							number_format( $order_total, 2, ',', ' ' ),
							number_format( $invoice_total, 2, ',', ' ' ),
							number_format( $difference, 2, ',', ' ' )
						);
						?>
						</p>
						<p style="margin: 5px 0 0 0; color: #856404; font-size: 12px;">
							<?php echo esc_html__( 'Email was not sent automatically. Please review the invoice and send manually if needed.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
						</p>
					</div>
					<?php
				}
				?>
				
				<p>
					<a href="https://<?php echo esc_attr( $this->settings->get_option( 'subdomain' ) ); ?>.fakturownia.pl/invoices/<?php echo esc_attr( $invoice_id ); ?>" target="_blank" class="button">
						<?php echo esc_html__( 'View in Fakturownia', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
					</a>
				</p>
				
				<p>
					<button type="button" class="button" id="devikit-fakturownia-download-pdf" 
							data-invoice-id="<?php echo esc_attr( $invoice_id ); ?>"
							data-invoice-number="<?php echo esc_attr( $invoice_number ); ?>">
						<?php echo esc_html__( 'Download PDF', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
					</button>
				</p>
				<?php 
				// Email sending is a PRO feature. FREE should not validate licenses.
				$is_pro_installed = class_exists( 'Devikit\FakturowniaPro\Plugin' );
				?>
				<?php if ( ! $is_pro_installed ) : ?>
				<p style="margin-top:10px;">
					<button type="button" class="button" disabled style="opacity:0.5; cursor:not-allowed;">
						<?php echo esc_html__( 'Send Invoice by Email', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?> 🔒
					</button>
					<br>
					<small style="color:#666; margin-top:5px; display:block;">
						<?php 
						printf(
							/* translators: %s: Link to PRO version */
							esc_html__( 'Email sending available in %s', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
							'<a href="https://devikit.pl/produkt/fakturownia-woocommerce-pro/" target="_blank" style="color:#2271b1;">' . esc_html__( 'PRO version', 'invoicing-integration-for-fakturownia-and-woocommerce' ) . '</a>'
						);
						?>
					</small>
				</p>
				<?php endif; ?>
			<?php else : ?>
				<?php
				// Show last error if exists
				$last_error = $order->get_meta( '_fakturownia_last_error' );
				$retry_count = $order->get_meta( '_fakturownia_retry_count' );
				if ( $last_error ) :
				?>
					<div style="background:#fff3cd; border-left:4px solid #ffc107; padding:10px; margin-bottom:10px;">
						<strong><?php echo esc_html__( 'Last Error:', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong><br>
						<?php echo esc_html( $last_error ); ?>
					<?php if ( $retry_count ) : ?>
						<br><small><?php /* translators: %d: Retry attempt number */ printf( esc_html__( 'Retry attempt: %d', 'invoicing-integration-for-fakturownia-and-woocommerce' ), esc_html( $retry_count ) ); ?></small>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				
				<p><?php echo esc_html__( 'No invoice created yet.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
				
				<div style="margin-top:15px; margin-bottom:20px;">
					<div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
						<button type="button" class="button button-primary" id="devikit-fakturownia-create-invoice" data-order-id="<?php echo esc_attr( $order->get_id() ); ?>">
							<?php echo esc_html__( 'Create Invoice', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
						</button>
						<?php if ( $last_error ) : ?>
							<button type="button" class="button" id="devikit-fakturownia-retry-invoice" data-order-id="<?php echo esc_attr( $order->get_id() ); ?>">
								<?php echo esc_html__( 'Retry', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
							</button>
						<?php endif; ?>
					</div>
					<button type="button" class="button devikit-fakturownia-toggle-params" data-doc-type="invoice">
						<span class="devikit-params-label"><?php echo esc_html__( 'Show Parameters', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></span>
						<span class="devikit-params-icon"> ▼</span>
					</button>
				</div>
				<span id="devikit-fakturownia-loading" style="display:none;"><?php echo esc_html__( 'Creating invoice...', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></span>
				<p id="devikit-fakturownia-error" style="color:red; margin-top:10px;"></p>
			<?php endif; ?>
			
			<?php if ( ! $hide_invoice_section ) : ?>
			<!-- Spacer for parameters section -->
			<div class="devikit-fakturownia-params-spacer" style="display:none; height:10px;"></div>
			
			<!-- Invoice Parameters (Collapsible) -->
			<div id="devikit-fakturownia-params-invoice" class="devikit-fakturownia-params" style="display:none; padding-top: 8px; border-top: 1px solid #ddd;">
				<?php 
				$invoice_params = $this->get_default_document_params( $order, 'invoice' );
				$this->render_document_parameters( 
					$order, 
					'invoice', 
					$invoice_params['paid_amount'], 
					$invoice_params['issue_date'], 
					$invoice_params['sale_date'], 
					$invoice_params['payment_deadline'], 
					$invoice_params['payment_method'], 
					$invoice_params['note'] 
				); 
				?>
			</div>
			<?php endif; ?>
			
			<?php 
			// FREE should only detect if PRO is installed. PRO handles license validation internally.
			$pro_installed = class_exists( 'Devikit\FakturowniaPro\Plugin' );
			
			// Show grayed out sections in FREE if:
			// 1. PRO is NOT installed, OR
			// (When PRO is installed, it will render its own sections.)
			if ( ! $pro_installed ) {
				$proforma_id = $order->get_meta( '_fakturownia_proforma_id' );
				$proforma_number = $order->get_meta( '_fakturownia_proforma_number' );
				$correction_id = $order->get_meta( '_fakturownia_correction_id' );
				$correction_number = $order->get_meta( '_fakturownia_correction_number' );
				$receipt_id = $order->get_meta( '_fakturownia_receipt_id' );
				$receipt_number = $order->get_meta( '_fakturownia_receipt_number' );
				$bill_id = $order->get_meta( '_fakturownia_bill_id' );
				$bill_number = $order->get_meta( '_fakturownia_bill_number' );
				?>
				
				<!-- Proforma section (PRO) -->
				<div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd; opacity: 0.5;">
					<h4 style="margin-top: 0;"><?php echo esc_html__( 'Proforma Invoice', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h4>
					<?php if ( $proforma_id ) : ?>
						<p>
							<strong><?php echo esc_html__( 'Proforma Created:', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong><br>
							<?php echo esc_html( $proforma_number ?: $proforma_id ); ?>
						</p>
					<?php else : ?>
						<p>
							<button type="button" class="button" disabled style="opacity:0.5; cursor:not-allowed;">
								<?php echo esc_html__( 'Create Proforma', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?> 🔒
							</button>
							<br>
							<small style="color:#666; margin-top:5px; display:block;">
								<?php 
								printf(
									/* translators: %s: Link to PRO version */
									esc_html__( 'Proformas available in %s', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
									'<a href="https://devikit.pl/produkt/fakturownia-woocommerce-pro/" target="_blank" style="color:#2271b1;">' . esc_html__( 'PRO version', 'invoicing-integration-for-fakturownia-and-woocommerce' ) . '</a>'
								);
								?>
							</small>
						</p>
					<?php endif; ?>
				</div>
				
				<!-- Correction section (PRO) -->
				<div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd; opacity: 0.5;">
					<h4 style="margin-top: 0;"><?php echo esc_html__( 'Correction Invoice', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h4>
					<?php if ( $correction_id ) : ?>
						<p>
							<strong><?php echo esc_html__( 'Correction Created:', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong><br>
							<?php echo esc_html( $correction_number ?: $correction_id ); ?>
						</p>
					<?php else : ?>
						<p>
							<button type="button" class="button" disabled style="opacity:0.5; cursor:not-allowed;">
								<?php echo esc_html__( 'Create Correction', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?> 🔒
							</button>
							<br>
							<small style="color:#666; margin-top:5px; display:block;">
								<?php 
								printf(
									/* translators: %s: Link to PRO version */
									esc_html__( 'Corrections available in %s', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
									'<a href="https://devikit.pl/produkt/fakturownia-woocommerce-pro/" target="_blank" style="color:#2271b1;">' . esc_html__( 'PRO version', 'invoicing-integration-for-fakturownia-and-woocommerce' ) . '</a>'
								);
								?>
							</small>
						</p>
					<?php endif; ?>
				</div>
				
				<!-- Receipt section (PRO) -->
				<div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd; opacity: 0.5;">
					<h4 style="margin-top: 0;"><?php echo esc_html__( 'Receipt (Paragon)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h4>
					<?php if ( $receipt_id ) : ?>
						<p>
							<strong><?php echo esc_html__( 'Receipt Created:', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong><br>
							<?php echo esc_html( $receipt_number ?: $receipt_id ); ?>
						</p>
					<?php else : ?>
						<p>
							<button type="button" class="button" disabled style="opacity:0.5; cursor:not-allowed;">
								<?php echo esc_html__( 'Create Receipt', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?> 🔒
							</button>
							<br>
							<small style="color:#666; margin-top:5px; display:block;">
								<?php 
								printf(
									/* translators: %s: Link to PRO version */
									esc_html__( 'Receipts available in %s', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
									'<a href="https://devikit.pl/produkt/fakturownia-woocommerce-pro/" target="_blank" style="color:#2271b1;">' . esc_html__( 'PRO version', 'invoicing-integration-for-fakturownia-and-woocommerce' ) . '</a>'
								);
								?>
							</small>
						</p>
					<?php endif; ?>
				</div>
				
				<!-- Bill section (PRO) -->
				<div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd; opacity: 0.5;">
					<h4 style="margin-top: 0;"><?php echo esc_html__( 'Bill (Rachunek)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h4>
					<?php if ( $bill_id ) : ?>
						<p>
							<strong><?php echo esc_html__( 'Bill Created:', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong><br>
							<?php echo esc_html( $bill_number ?: $bill_id ); ?>
						</p>
					<?php else : ?>
						<p>
							<button type="button" class="button" disabled style="opacity:0.5; cursor:not-allowed;">
								<?php echo esc_html__( 'Create Bill', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?> 🔒
							</button>
							<br>
							<small style="color:#666; margin-top:5px; display:block;">
								<?php 
								printf(
									/* translators: %s: Link to PRO version */
									esc_html__( 'Bills available in %s', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
									'<a href="https://devikit.pl/produkt/fakturownia-woocommerce-pro/" target="_blank" style="color:#2271b1;">' . esc_html__( 'PRO version', 'invoicing-integration-for-fakturownia-and-woocommerce' ) . '</a>'
								);
								?>
							</small>
						</p>
					<?php endif; ?>
				</div>
				<?php
			}
			
			// Hook for PRO features - PRO decides internally based on its license status.
			if ( $pro_installed ) {
				do_action( 'devikit_fakturownia_metabox_after_invoice', $order );
			}
			?>
		</div>
		
		<!-- Edit Draft Modal -->
		<div id="devikit-fakturownia-draft-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:100000; overflow-y:auto;">
			<div style="background:#fff; margin:50px auto; max-width:900px; padding:20px; border-radius:4px; box-shadow:0 2px 10px rgba(0,0,0,0.3);">
				<h2 style="margin-top:0;"><?php echo esc_html__( 'Edit Invoice Draft', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
					<button type="button" class="devikit-fakturownia-modal-close" style="float:right; background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
				</h2>
				
				<form id="devikit-fakturownia-draft-form">
					<table class="form-table" style="width:100%;">
						<tr>
							<th><label><?php echo esc_html__( 'Issue Date', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></label></th>
							<td><input type="date" id="draft-issue-date" style="width:100%;" /></td>
						</tr>
						<tr>
							<th><label><?php echo esc_html__( 'Sale Date', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></label></th>
							<td><input type="date" id="draft-sell-date" style="width:100%;" /></td>
						</tr>
						<tr>
							<th><label><?php echo esc_html__( 'Payment Deadline', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></label></th>
							<td><input type="date" id="draft-payment-to" style="width:100%;" /></td>
						</tr>
						<tr>
							<th><label><?php echo esc_html__( 'Payment Type', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></label></th>
							<td>
								<select id="draft-payment-type" style="width:100%;">
									<option value=\"transfer\"><?php echo esc_html__( 'Transfer', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></option>
									<option value=\"cash\"><?php echo esc_html__( 'Cash', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></option>
									<option value=\"card\"><?php echo esc_html__( 'Card', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th><label><?php echo esc_html__( 'Currency', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></label></th>
							<td><input type="text" id="draft-currency" value=\"PLN\" maxlength=\"3\" style="width:100px;" /></td>
						</tr>
					</table>
					
					<h3><?php echo esc_html__( 'Positions', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h3>
					<table id="draft-positions" style="width:100%; border-collapse:collapse;">
						<thead>
							<tr style="background:#f5f5f5;">
								<th style="padding:8px; text-align:left;"><?php echo esc_html__( 'Name', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
								<th style="padding:8px; text-align:left;"><?php echo esc_html__( 'Quantity', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
								<th style="padding:8px; text-align:left;"><?php echo esc_html__( 'Price Net', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
								<th style="padding:8px; text-align:left;"><?php echo esc_html__( 'Tax', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					
					<p style="margin-top:20px;">
						<button type="submit" class="button button-primary" id="devikit-fakturownia-submit-draft">
							<?php echo esc_html__( 'Create Invoice', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
						</button>
						<button type="button" class="button devikit-fakturownia-modal-cancel" style="margin-left:10px;">
							<?php echo esc_html__( 'Cancel', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
						</button>
						<span id="devikit-fakturownia-draft-loading" style="display:none; margin-left:10px;"><?php echo esc_html__( 'Creating invoice...', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></span>
					</p>
					<p id="devikit-fakturownia-draft-error" style="color:red; margin-top:10px;"></p>
				</form>
			</div>
		</div>
		
		<?php
	}
	
	/**
	 * Get default document parameters for order
	 * 
	 * @param \WC_Order $order
	 * @param string $doc_type Document type (invoice, proforma, receipt, bill, correction)
	 * @return array
	 */
	private function get_default_document_params( $order, $doc_type ) {
		$settings = get_option( 'devikit_fakturownia_settings', [] );
		$pro_settings = get_option( 'devikit_fakturownia_pro_settings', [] );
		
		// Get saved values from order meta
		$meta_prefix = '_fakturownia_' . $doc_type . '_';
		$saved_paid_amount = $order->get_meta( $meta_prefix . 'paid_amount' );
		$saved_issue_date = $order->get_meta( $meta_prefix . 'issue_date' );
		$saved_sale_date = $order->get_meta( $meta_prefix . 'sale_date' );
		$saved_payment_deadline = $order->get_meta( $meta_prefix . 'payment_deadline' );
		$saved_payment_method = $order->get_meta( $meta_prefix . 'payment_method' );
		$saved_note = $order->get_meta( $meta_prefix . 'note' );
		
		// Set defaults based on document type
		$payment_days = isset( $settings['payment_deadline_days'] ) ? intval( $settings['payment_deadline_days'] ) : 7;
		
		if ( $doc_type === 'proforma' && isset( $pro_settings['proforma_payment_deadline_days'] ) ) {
			$payment_days = intval( $pro_settings['proforma_payment_deadline_days'] );
		} elseif ( $doc_type === 'receipt' && isset( $pro_settings['receipt_payment_deadline_days'] ) ) {
			$payment_days = intval( $pro_settings['receipt_payment_deadline_days'] );
		}
		
		// Issue date default
		if ( empty( $saved_issue_date ) ) {
			$saved_issue_date = current_time( 'Y-m-d' );
		}
		
		// Sale date default (not for proforma)
		if ( empty( $saved_sale_date ) && $doc_type !== 'proforma' ) {
			$saved_sale_date = $order->get_date_created()->date( 'Y-m-d' );
		}
		
		// Payment deadline default
		if ( empty( $saved_payment_deadline ) ) {
			$saved_payment_deadline = gmdate( 'Y-m-d', strtotime( $saved_issue_date . ' + ' . $payment_days . ' days' ) );
		}
		
		// Paid amount default
		if ( empty( $saved_paid_amount ) && $saved_paid_amount !== '0' && $saved_paid_amount !== 0 ) {
			$saved_paid_amount = $order->is_paid() ? $order->get_total() : '0.00';
		}
		
		// Payment method default
		if ( empty( $saved_payment_method ) ) {
			$wc_payment_method = $order->get_payment_method();
			$saved_payment_method = 'transfer'; // default
			if ( $wc_payment_method === 'cod' ) {
				$saved_payment_method = 'cash';
			} elseif ( $wc_payment_method === 'bacs' ) {
				$saved_payment_method = 'transfer';
			}
		}
		
		// Note default - use invoice notes from settings if no saved note
		if ( empty( $saved_note ) ) {
			$saved_note = isset( $settings['invoice_notes'] ) ? trim( $settings['invoice_notes'] ) : '';
		}
		
		return [
			'paid_amount' => $saved_paid_amount,
			'issue_date' => $saved_issue_date,
			'sale_date' => $saved_sale_date,
			'payment_deadline' => $saved_payment_deadline,
			'payment_method' => $saved_payment_method,
			'note' => $saved_note,
		];
	}
	
	/**
	 * Render document parameters (reusable for all document types)
	 * 
	 * @param \WC_Order $order
	 * @param string $doc_type Document type (invoice, proforma, receipt, bill, correction)
	 * @param string|float $paid_amount
	 * @param string $issue_date
	 * @param string $sale_date
	 * @param string $payment_deadline
	 * @param string $payment_method
	 * @param string $notes
	 */
	private function render_document_parameters( $order, $doc_type, $paid_amount = '', $issue_date = '', $sale_date = '', $payment_deadline = '', $payment_method = '', $notes = '' ) {
		$prefix = 'fakturownia-' . $doc_type;
		?>
		<p>
			<label for="<?php echo esc_attr( $prefix ); ?>-paid-amount" style="display:block; margin-bottom:5px;">
				<strong><?php echo esc_html__( 'Paid Amount', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong>
			</label>
			<input type="number" id="<?php echo esc_attr( $prefix ); ?>-paid-amount" step="0.01" min="0" value="<?php echo esc_attr( $paid_amount ); ?>" style="width:100%;" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $prefix ); ?>-issue-date" style="display:block; margin-bottom:5px;">
				<strong><?php echo esc_html__( 'Issue Date', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong>
			</label>
			<input type="date" id="<?php echo esc_attr( $prefix ); ?>-issue-date" value="<?php echo esc_attr( $issue_date ); ?>" style="width:100%;" />
		</p>
		
		<?php if ( $doc_type !== 'proforma' ) : ?>
		<p>
			<label for="<?php echo esc_attr( $prefix ); ?>-sale-date" style="display:block; margin-bottom:5px;">
				<strong><?php echo esc_html__( 'Sale Date', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong>
			</label>
			<input type="date" id="<?php echo esc_attr( $prefix ); ?>-sale-date" value="<?php echo esc_attr( $sale_date ); ?>" style="width:100%;" />
		</p>
		<?php endif; ?>
		
		<p>
			<label for="<?php echo esc_attr( $prefix ); ?>-payment-deadline" style="display:block; margin-bottom:5px;">
				<strong><?php echo esc_html__( 'Payment Deadline', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong>
			</label>
			<input type="date" id="<?php echo esc_attr( $prefix ); ?>-payment-deadline" value="<?php echo esc_attr( $payment_deadline ); ?>" style="width:100%;" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $prefix ); ?>-payment-method" style="display:block; margin-bottom:5px;">
				<strong><?php echo esc_html__( 'Payment Method', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong>
			</label>
			<select id="<?php echo esc_attr( $prefix ); ?>-payment-method" style="width:100%;">
				<option value="transfer" <?php selected( $payment_method, 'transfer' ); ?>><?php echo esc_html__( 'Transfer', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></option>
				<option value="cash" <?php selected( $payment_method, 'cash' ); ?>><?php echo esc_html__( 'Cash', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></option>
				<option value="card" <?php selected( $payment_method, 'card' ); ?>><?php echo esc_html__( 'Card', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></option>
				<option value="cod" <?php selected( $payment_method, 'cod' ); ?>><?php echo esc_html__( 'Cash on Delivery', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $prefix ); ?>-note" style="display:block; margin-bottom:5px;">
				<strong><?php echo esc_html__( 'Notes', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong>
			</label>
			<textarea id="<?php echo esc_attr( $prefix ); ?>-note" rows="3" style="width:100%;" placeholder="<?php esc_attr_e( 'Add notes that will be included in the document...', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>"><?php echo esc_textarea( $notes ); ?></textarea>
		</p>
		<?php
	}

	/**
	 * AJAX Get Draft
	 */
	public function ajax_get_draft() {
		check_ajax_referer( 'fakturownia_get_draft', 'nonce' );
		
		if ( ! current_user_can( 'edit_shop_orders' ) ) {
			wp_send_json_error( [ 'message' => __( 'Unauthorized', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		}

		$order_id = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : 0;
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			wp_send_json_error( [ 'message' => __( 'Order not found', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		}

		$client_id = $this->get_or_create_client( $order );
		if ( is_wp_error( $client_id ) ) {
			wp_send_json_error( [ 'message' => $client_id->get_error_message() ] );
		}

		$draft_data = $this->prepare_invoice_data( $order, $client_id );
		
		wp_send_json_success( $draft_data );
	}

	/**
	 * AJAX Create Invoice
	 */
	public function ajax_create_invoice() {
		check_ajax_referer( 'fakturownia_create_invoice', 'nonce' );
		
		if ( ! current_user_can( 'edit_shop_orders' ) ) {
			wp_send_json_error( [ 'message' => __( 'Unauthorized', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		}

		$order_id = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : 0;
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			wp_send_json_error( [ 'message' => __( 'Order not found', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		}

		// Check if draft data is provided (from modal)
		$draft_data = null;
		if ( isset( $_POST['draft_data'] ) && is_array( $_POST['draft_data'] ) ) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized in sanitize_draft_data method
			$draft_data = $this->sanitize_draft_data( wp_unslash( $_POST['draft_data'] ) );
		}

		$result = $this->create_invoice_for_order( $order, false, $draft_data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( [ 'message' => $result->get_error_message() ] );
		}

		wp_send_json_success();
	}
	
	/**
	 * Sanitize draft data
	 */
	private function sanitize_draft_data( $data ) {
		$sanitized = [];
		
		if ( isset( $data['issue_date'] ) ) {
			$sanitized['issue_date'] = sanitize_text_field( $data['issue_date'] );
		}
		if ( isset( $data['sell_date'] ) ) {
			$sanitized['sell_date'] = sanitize_text_field( $data['sell_date'] );
		}
		if ( isset( $data['payment_to'] ) ) {
			$sanitized['payment_to'] = sanitize_text_field( $data['payment_to'] );
		}
		if ( isset( $data['payment_type'] ) ) {
			$sanitized['payment_type'] = sanitize_text_field( $data['payment_type'] );
		}
		if ( isset( $data['currency'] ) ) {
			$sanitized['currency'] = sanitize_text_field( $data['currency'] );
		}
		if ( isset( $data['positions'] ) && is_array( $data['positions'] ) ) {
			$sanitized['positions'] = [];
			foreach ( $data['positions'] as $position ) {
				$sanitized['positions'][] = [
					'name'      => isset( $position['name'] ) ? sanitize_text_field( $position['name'] ) : '',
					'quantity'  => isset( $position['quantity'] ) ? floatval( $position['quantity'] ) : 1,
					'price_net' => isset( $position['price_net'] ) ? floatval( $position['price_net'] ) : 0,
					'tax'       => isset( $position['tax'] ) ? sanitize_text_field( $position['tax'] ) : '23',
				];
			}
		}
		
		return $sanitized;
	}

	/**
	 * AJAX Save Invoice Parameters
	 */
	public function ajax_save_invoice_params() {
		check_ajax_referer( 'fakturownia_save_invoice_params', 'nonce' );
		
		if ( ! current_user_can( 'edit_shop_orders' ) ) {
			wp_send_json_error( [ 'message' => __( 'Unauthorized', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		}

		$order_id = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : 0;
		$doc_type = isset( $_POST['doc_type'] ) ? sanitize_text_field( wp_unslash( $_POST['doc_type'] ) ) : 'invoice';
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			wp_send_json_error( [ 'message' => __( 'Order not found', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		}

		$meta_prefix = '_fakturownia_' . $doc_type . '_';
		
		// Save parameters
		if ( isset( $_POST['paid_amount'] ) ) {
			$order->update_meta_data( $meta_prefix . 'paid_amount', sanitize_text_field( wp_unslash( $_POST['paid_amount'] ) ) );
		}
		if ( isset( $_POST['issue_date'] ) ) {
			$order->update_meta_data( $meta_prefix . 'issue_date', sanitize_text_field( wp_unslash( $_POST['issue_date'] ) ) );
		}
		if ( isset( $_POST['sale_date'] ) ) {
			$order->update_meta_data( $meta_prefix . 'sale_date', sanitize_text_field( wp_unslash( $_POST['sale_date'] ) ) );
		}
		if ( isset( $_POST['payment_deadline'] ) ) {
			$order->update_meta_data( $meta_prefix . 'payment_deadline', sanitize_text_field( wp_unslash( $_POST['payment_deadline'] ) ) );
		}
		if ( isset( $_POST['payment_method'] ) ) {
			$order->update_meta_data( $meta_prefix . 'payment_method', sanitize_text_field( wp_unslash( $_POST['payment_method'] ) ) );
		}
		if ( isset( $_POST['note'] ) ) {
			$order->update_meta_data( $meta_prefix . 'note', sanitize_textarea_field( wp_unslash( $_POST['note'] ) ) );
		}
		
		$order->save();

		wp_send_json_success();
	}

	/**
	 * AJAX Download PDF
	 */
	public function ajax_download_pdf() {
		check_ajax_referer( 'fakturownia_download_pdf', 'nonce' );
		
		if ( ! current_user_can( 'edit_shop_orders' ) ) {
			wp_send_json_error( [ 'message' => __( 'Unauthorized', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		}

		$invoice_id = isset( $_POST['invoice_id'] ) ? absint( $_POST['invoice_id'] ) : 0;
		$invoice_number = isset( $_POST['invoice_number'] ) ? sanitize_text_field( wp_unslash( $_POST['invoice_number'] ) ) : 'invoice';

		if ( ! $invoice_id ) {
			wp_send_json_error( [ 'message' => __( 'Invoice ID missing', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		}

		$pdf_content = $this->api_client->download_invoice_pdf( $invoice_id );

		if ( is_wp_error( $pdf_content ) ) {
			$this->api_client->log( 'PDF Download Error: ' . $pdf_content->get_error_message(), 'error' );
			wp_send_json_error( [ 'message' => $pdf_content->get_error_message() ] );
		}

		if ( empty( $pdf_content ) ) {
			wp_send_json_error( [ 'message' => __( 'PDF content is empty', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		}

		wp_send_json_success( [ 
			'pdf' => base64_encode( $pdf_content ),
			'filename' => sanitize_file_name( $invoice_number ) . '.pdf'
		] );
	}

	/**
	 * Create Invoice for Order
	 * 
	 * @param \WC_Order $order
	 * @param bool $is_automatic Whether invoice is created automatically
	 * @param array|null $draft_data Optional draft data from modal
	 */
	public function create_invoice_for_order( $order, $is_automatic = false, $draft_data = null ) {
		$client_id = $this->get_or_create_client( $order );
		if ( is_wp_error( $client_id ) ) {
			return $client_id;
		}

		$invoice_data = $this->prepare_invoice_data( $order, $client_id, $draft_data );
		$invoice_data = apply_filters( 'devikit_fakturownia_invoice_data', $invoice_data, $order );
		
		$this->api_client->log( 'Invoice data prepared: ' . wp_json_encode( $invoice_data, JSON_UNESCAPED_UNICODE ), 'debug' );

		$response = $this->api_client->create_invoice( $invoice_data );

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			$error_code = $response->get_error_code();
			$error_data = $response->get_error_data();
			
			// Format error message for display (handle arrays and nested structures)
			$formatted_error = $this->format_error_for_display( $error_message );
			
			// Store formatted error in order meta for UI display
			$order->update_meta_data( '_fakturownia_last_error', $formatted_error );
			$order->update_meta_data( '_fakturownia_last_error_code', $error_code );
			$order->save();
			
			$order->add_order_note( __( 'Fakturownia Invoice Error: ', 'invoicing-integration-for-fakturownia-and-woocommerce' ) . $formatted_error );
			
			// Schedule retry for temporary failures (rate limit, timeout, etc.)
			if ( $this->should_retry_error( $error_code, $error_message ) ) {
				$retry_count = $order->get_meta( '_fakturownia_retry_count' ) ?: 0;
				if ( $retry_count < 3 ) {
					$delay = min( 300 * pow( 2, $retry_count ), 3600 ); // Exponential backoff: 5min, 10min, 20min, max 1h
					as_schedule_single_action( time() + $delay, 'devikit_fakturownia_retry_invoice', [ $order->get_id() ], 'fakturownia' );
					$order->update_meta_data( '_fakturownia_retry_count', $retry_count + 1 );
					$order->save();
				}
			}
			
			return $response;
		}
		
		// Clear error meta on success
		$order->delete_meta_data( '_fakturownia_last_error' );
		$order->delete_meta_data( '_fakturownia_last_error_code' );
		$order->delete_meta_data( '_fakturownia_retry_count' );

		// Log full API response for debugging
		$this->api_client->log( 'Invoice API response: ' . wp_json_encode( $response, JSON_UNESCAPED_UNICODE ), 'debug' );

		// API response structure: { "id": 123, "number": "FV/1/2024", "price_gross": "100.00", ... }
		if ( isset( $response['id'] ) ) {
			$invoice_id = $response['id'];
			$invoice_number = $response['number'] ?? $response['full_number'] ?? '';
			
			// Get invoice total from API response
			$invoice_total = 0;
			if ( isset( $response['price_gross'] ) ) {
				$invoice_total = floatval( $response['price_gross'] );
			} elseif ( isset( $response['total'] ) ) {
				$invoice_total = floatval( $response['total'] );
			} elseif ( isset( $response['price'] ) ) {
				$invoice_total = floatval( $response['price'] );
			}
			
			// Compare with order total (gross total including taxes)
			$order_total = floatval( $order->get_total() );
			$total_difference = abs( $invoice_total - $order_total );
			
			// Any difference, even 0.01 grosz, should be marked as mismatch
			// Use small tolerance (0.001) to account for float precision issues
			$has_total_mismatch = $total_difference >= 0.001;
			
			$order->update_meta_data( '_fakturownia_invoice_id', $invoice_id );
			$order->update_meta_data( '_fakturownia_invoice_number', $invoice_number );
			
			if ( $has_total_mismatch ) {
				$order->update_meta_data( '_fakturownia_invoice_total_mismatch', 'yes' );
				$order->update_meta_data( '_fakturownia_invoice_order_total', $order_total );
				$order->update_meta_data( '_fakturownia_invoice_fakturownia_total', $invoice_total );
				$order->update_meta_data( '_fakturownia_invoice_total_difference', $total_difference );
				$order->add_order_note( sprintf( 
					/* translators: 1: Invoice number/ID, 2: Order total, 3: Invoice total */
					__( 'Fakturownia Invoice created: %1$s (Total mismatch: Order %2$s vs Invoice %3$s)', 'invoicing-integration-for-fakturownia-and-woocommerce' ), 
					$invoice_number ?: $invoice_id,
					number_format( $order_total, 2, ',', ' ' ),
					number_format( $invoice_total, 2, ',', ' ' )
				) );
			} else {
				$order->delete_meta_data( '_fakturownia_invoice_total_mismatch' );
				$order->delete_meta_data( '_fakturownia_invoice_order_total' );
				$order->delete_meta_data( '_fakturownia_invoice_fakturownia_total' );
				$order->delete_meta_data( '_fakturownia_invoice_total_difference' );
				/* translators: %s: Invoice number/ID */
				$order->add_order_note( sprintf( __( 'Fakturownia Invoice created: %s', 'invoicing-integration-for-fakturownia-and-woocommerce' ), $invoice_number ?: $invoice_id ) );
			}
			
			$order->save();
			
			if ( ! $has_total_mismatch ) {
				$settings = get_option( 'devikit_fakturownia_settings', [] );
				$subdomain = isset( $settings['subdomain'] ) ? $settings['subdomain'] : '';
				
				$invoice_email_data = [
					'id' => $invoice_id,
					'number' => $invoice_number ?: $invoice_id,
					'url' => 'https://' . $subdomain . '.fakturownia.pl/invoices/' . $invoice_id,
				];
				
				$this->maybe_send_invoice_email( $order, $invoice_email_data, $is_automatic );
			}
			
			return $invoice_id;
		}

		return new \WP_Error( 'fakturownia_unknown_error', __( 'Unknown response from Fakturownia API', 'invoicing-integration-for-fakturownia-and-woocommerce' ) );
	}

	/**
	 * Get or Create Client
	 */
	public function get_or_create_client( $order ) {
		$nip = $order->get_meta( '_billing_nip' );
		if ( empty( $nip ) ) {
			$nip = $order->get_meta( 'vat_number' );
		}

		$is_company = ! empty( $order->get_billing_company() );
		$country = $order->get_billing_country();
		
		// Prepare street address - combine address_1 and address_2
		$street = $order->get_billing_address_1();
		$address_2 = $order->get_billing_address_2();
		if ( ! empty( $address_2 ) && '' !== trim( $address_2 ) ) {
			$street .= ' ' . $address_2;
		}
		
		$client_data = [
			'name'         => $is_company ? $order->get_billing_company() : trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ),
			'street'       => $street,
			'post_code'    => $order->get_billing_postcode(),
			'city'         => $order->get_billing_city(),
			'country'      => $country,
			'email'        => $order->get_billing_email(),
			'phone'        => $order->get_billing_phone(),
		];

		if ( ! empty( $nip ) ) {
			// For Polish NIP, remove non-numeric characters
			if ( $country === 'PL' ) {
				$client_data['tax_no'] = preg_replace( '/[^0-9]/', '', $nip );
			} else {
				// Keep original VAT with country prefix
				$client_data['tax_no'] = preg_replace( '/[\s\-]/', '', $nip );
			}
		}

		// Try to find existing client
		if ( ! empty( $client_data['tax_no'] ) ) {
			$existing = $this->api_client->get_client_by_tax_no( $client_data['tax_no'] );
			if ( $existing && isset( $existing['id'] ) ) {
				$this->api_client->log( 'Using existing client ID: ' . $existing['id'], 'debug' );
				// Update client data if needed
				$this->api_client->update_client( $existing['id'], $client_data );
				return intval( $existing['id'] );
			}
		}

		// Try to find by name
		$existing = $this->api_client->find_client( $client_data );
		if ( $existing && isset( $existing['id'] ) ) {
			$this->api_client->log( 'Using existing client ID: ' . $existing['id'], 'debug' );
			// Update client data if needed
			$this->api_client->update_client( $existing['id'], $client_data );
			return intval( $existing['id'] );
		}

		// Create new client
		$response = $this->api_client->create_client( $client_data );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		// Handle response structure: { "id": 123, ... }
		if ( isset( $response['id'] ) ) {
			return intval( $response['id'] );
		}
		
		return new \WP_Error( 'create_client_fail', __( 'Failed to create client - no ID in response', 'invoicing-integration-for-fakturownia-and-woocommerce' ) );
	}

	/**
	 * Prepare Invoice Data
	 * 
	 * @param \WC_Order $order
	 * @param int $client_id
	 * @param array|null $draft_data Optional draft data from modal (legacy support)
	 */
	private function prepare_invoice_data( $order, $client_id, $draft_data = null ) {
		$settings = get_option( 'devikit_fakturownia_settings', [] );
		
		// Priority: 1) Order meta (from metabox), 2) Draft data (legacy modal), 3) Defaults
		$meta_prefix = '_fakturownia_invoice_';
		$saved_issue_date = $order->get_meta( $meta_prefix . 'issue_date' );
		$saved_sale_date = $order->get_meta( $meta_prefix . 'sale_date' );
		$saved_payment_deadline = $order->get_meta( $meta_prefix . 'payment_deadline' );
		$saved_payment_method = $order->get_meta( $meta_prefix . 'payment_method' );
		$saved_paid_amount = $order->get_meta( $meta_prefix . 'paid_amount' );
		
		// Ensure variables are initialized
		if ( empty( $saved_issue_date ) ) {
			$saved_issue_date = '';
		}
		if ( empty( $saved_sale_date ) ) {
			$saved_sale_date = '';
		}
		if ( empty( $saved_payment_deadline ) ) {
			$saved_payment_deadline = '';
		}
		if ( empty( $saved_payment_method ) ) {
			$saved_payment_method = '';
		}
		if ( empty( $saved_paid_amount ) && $saved_paid_amount !== '0' && $saved_paid_amount !== 0 ) {
			$saved_paid_amount = '';
		}
		
		// Use order meta if available, otherwise fall back to draft_data or defaults
		if ( ! empty( $saved_issue_date ) ) {
			$invoice_date = $saved_issue_date;
		} elseif ( $draft_data && isset( $draft_data['issue_date'] ) ) {
			$invoice_date = $draft_data['issue_date'];
		} else {
			$invoice_date = current_time( 'Y-m-d' );
		}
		
		if ( ! empty( $saved_sale_date ) ) {
			$sale_date = $saved_sale_date;
		} elseif ( $draft_data && isset( $draft_data['sell_date'] ) ) {
			$sale_date = $draft_data['sell_date'];
		} else {
			$sale_date = $order->get_date_created()->date( 'Y-m-d' );
		}
		
		if ( ! empty( $saved_payment_deadline ) ) {
			$payment_date = $saved_payment_deadline;
		} elseif ( $draft_data && isset( $draft_data['payment_to'] ) ) {
			$payment_date = $draft_data['payment_to'];
		} else {
			$payment_days = isset( $settings['payment_deadline_days'] ) ? intval( $settings['payment_deadline_days'] ) : 7;
			$payment_date = gmdate( 'Y-m-d', strtotime( $invoice_date . ' + ' . $payment_days . ' days' ) );
		}
		
		if ( ! empty( $saved_payment_method ) ) {
			$payment_method = $saved_payment_method;
		} elseif ( $draft_data && isset( $draft_data['payment_type'] ) ) {
			$payment_method = $draft_data['payment_type'];
		} else {
			// Use WooCommerce payment method title
			$wc_payment_method = $order->get_payment_method();
			if ( WC()->payment_gateways() ) {
				$payment_gateways = WC()->payment_gateways->payment_gateways();
				if ( isset( $payment_gateways[ $wc_payment_method ] ) ) {
					$payment_method = $payment_gateways[ $wc_payment_method ]->get_title();
				} else {
					$payment_method = $order->get_payment_method_title() ?: 'transfer';
				}
			} else {
				$payment_method = $order->get_payment_method_title() ?: 'transfer';
			}
		}
		
		// Get currency (from draft_data if provided, otherwise from order)
		if ( $draft_data && isset( $draft_data['currency'] ) ) {
			$currency = $draft_data['currency'];
		} else {
			$currency = $order->get_currency();
			if ( empty( $currency ) ) {
				$currency = 'PLN';
			}
		}
		
		// Get paid amount from order meta
		$paid_amount = ! empty( $saved_paid_amount ) ? floatval( $saved_paid_amount ) : ( $order->is_paid() ? $order->get_total() : 0.0 );
		
		// Automatically detect if foreign invoice is needed (non-PLN currency)
		$is_foreign_currency = ( $currency && $currency !== 'PLN' );
		
		// Map payment method to readable name for API
		$payment_type_for_api = $this->get_real_payment_method_name( $payment_method );

		// When WooCommerce taxes are disabled, issue a non-VAT document type.
		// FREE should stay fully functional for non-VAT shops.
		$document_kind = wc_tax_enabled() ? 'vat' : 'invoice_without_vat';
		
		$data = [
			'kind'          => $document_kind,
			'sell_date'     => $sale_date,
			'issue_date'    => $invoice_date,
			'payment_to'    => $payment_date,
			'payment_type'  => $payment_type_for_api,
			'client_id'     => intval( $client_id ),
			'currency'      => $currency,
			'positions'     => [],
		];
		
		// Enable additional_info for VAT invoices (always enabled for VAT invoices)
		$data['additional_info'] = 1;
		
		// Add paid amount if set
		if ( $paid_amount > 0 ) {
			$data['paid'] = floatval( $paid_amount );
		}
		
		// Add foreign invoice fields if currency is not PLN
		if ( $is_foreign_currency ) {
			try {
				// Determine exchange rate source based on customer country
				$billing_country = $order->get_billing_country();
				if ( function_exists( 'WC' ) && WC() && WC()->countries ) {
					$eu_countries = WC()->countries->get_european_union_countries();
				} else {
					// Fallback if WooCommerce not fully loaded
					$eu_countries = [];
				}
				$exchange_kind = in_array( $billing_country, $eu_countries, true ) ? 'ecb' : 'nbp';
				
				$data['exchange_kind'] = $exchange_kind; // ECB for EU, NBP for others
				$data['exchange_currency'] = 'PLN'; // Convert to PLN
			} catch ( \Exception $e ) {
				$this->api_client->log( 'Error processing foreign currency: ' . $e->getMessage(), 'error' );
			}
		}

		// Add department ID if set
		if ( ! empty( $settings['department_id'] ) ) {
			$data['department_id'] = intval( $settings['department_id'] );
		}

		// Add issue place if set
		if ( ! empty( $settings['invoice_issue_place'] ) ) {
			$data['place'] = sanitize_text_field( $settings['invoice_issue_place'] );
		}

		// Add language based on settings
		$invoice_language = $this->get_invoice_language( $order, $settings );
		if ( ! empty( $invoice_language ) ) {
			$data['lang'] = sanitize_text_field( $invoice_language );
		}

		// Add VAT exemption basis if set and needed (on invoice level, not position level)
		$vat_exemption_basis = isset( $settings['vat_exemption_basis'] ) ? trim( $settings['vat_exemption_basis'] ) : '';
		if ( ! empty( $vat_exemption_basis ) ) {
			$data['exempt_tax_kind'] = sanitize_text_field( $vat_exemption_basis );
		}
		
		// Add custom notes (FREE + PRO feature)
		$notes = [];
		
		// Per-invoice note (from metabox parameters)
		$saved_invoice_note = $order->get_meta( $meta_prefix . 'note' );
		
		// If user has saved a custom note, use it (it replaces default notes)
		if ( ! empty( $saved_invoice_note ) ) {
			$notes[] = $this->replace_order_variables( $saved_invoice_note, $order );
		} else {
			// FREE: Default invoice notes from settings
			$default_notes = isset( $settings['invoice_notes'] ) ? trim( $settings['invoice_notes'] ) : '';
			if ( ! empty( $default_notes ) ) {
				$notes[] = $this->replace_order_variables( $default_notes, $order );
			}
			
			// PRO: Global and per-order notes
			if ( class_exists( 'Devikit\FakturowniaPro\Admin\ProSettings' ) ) {
				$pro_settings = get_option( 'devikit_fakturownia_pro_settings', [] );
				$global_note = isset( $pro_settings['global_invoice_note'] ) ? trim( $pro_settings['global_invoice_note'] ) : '';
				if ( ! empty( $global_note ) ) {
					$notes[] = $this->replace_order_variables( $global_note, $order );
				}
				
				// Per-order note (PRO feature - different from metabox note)
				$order_note = $order->get_meta( '_fakturownia_invoice_note' );
				if ( empty( $order_note ) ) {
					$order_note = '';
				}
				if ( ! empty( $order_note ) ) {
					$notes[] = $this->replace_order_variables( $order_note, $order );
				}
				
				// Revenue transaction marking (PRO feature)
				$mark_revenue = isset( $pro_settings['mark_revenue_transaction'] ) && $pro_settings['mark_revenue_transaction'] === 'yes';
				if ( $mark_revenue ) {
					// Fakturownia API may support 'paid' or 'paid_date' field to mark as revenue
					// Check if order is paid
					if ( $order->is_paid() ) {
						$data['paid'] = true;
						if ( $order->get_date_paid() ) {
							$data['paid_date'] = $order->get_date_paid()->date( 'Y-m-d' );
						}
					}
				}
			}
		}
		
		// Don't add VAT exemption legal basis to notes for invoices - it's already set as exempt_tax_kind at document level
		// This prevents duplication in the description field
		// For bills (rachunek), the basis is added in BillManager
		
		if ( ! empty( $notes ) ) {
			$data['description'] = implode( "\n", $notes );
		}
		
		// Add warehouse_id if warehouse is selected (PRO feature)
		if ( class_exists( 'Devikit\FakturowniaPro\Core\WarehouseIntegration' ) ) {
			$warehouse_settings = get_option( 'devikit_fakturownia_warehouse_settings', [] );
			$warehouse_id = isset( $warehouse_settings['warehouse_id'] ) ? intval( $warehouse_settings['warehouse_id'] ) : 0;
			if ( $warehouse_id > 0 ) {
				$data['warehouse_id'] = $warehouse_id;
			}
		}
		
		// Use draft positions if provided
		if ( $draft_data && isset( $draft_data['positions'] ) && ! empty( $draft_data['positions'] ) ) {
			foreach ( $draft_data['positions'] as $position ) {
				$quantity = floatval( $position['quantity'] );
				$price_net = round( floatval( $position['price_net'] ), 2 );
				$tax_symbol = $position['tax'];
				
				$position_data = [
					'name'             => $position['name'],
					'quantity'         => $quantity,
					'price_net'        => $price_net,
					'tax'              => $tax_symbol,
					'total_price_gross' => $this->calculate_total_price_gross( $price_net, $quantity, $tax_symbol ),
				];
				
				// VAT exemption basis is added at invoice level, not position level
				
				$data['positions'][] = $position_data;
			}
		} else {
			// Check if OSS is enabled and applicable (PRO feature)
			$is_oss = false;
			$is_reverse_charge = false;
			if ( class_exists( 'Devikit\FakturowniaPro\Core\MossManager' ) ) {
				$moss_manager = new \Devikit\FakturowniaPro\Core\MossManager();
				$is_oss = $moss_manager->is_oss( $order );
				$is_reverse_charge = $moss_manager->is_reverse_charge( $order );
			}
			
			// Track if we have ZW products with PKWiU (for additional_info_desc)
			$has_zw_with_pkwiu = false;
			
			// Check if warehouse sync is enabled (PRO feature)
			$warehouse_sync_enabled = false;
			if ( class_exists( 'Devikit\FakturowniaPro\Core\WarehouseIntegration' ) ) {
				$warehouse_settings = get_option( 'devikit_fakturownia_warehouse_settings', [] );
				$warehouse_sync_enabled = isset( $warehouse_settings['enable_warehouse_sync'] ) && $warehouse_settings['enable_warehouse_sync'] === 'yes';
			}
			
			// Use order items
			foreach ( $order->get_items() as $item ) {
				$product = $item->get_product();
				
				// For reverse charge, use "np" tax symbol
				if ( $is_reverse_charge ) {
					$tax_symbol = 'np';
				} else {
					$tax_symbol = $this->get_item_tax_symbol( $item, $product, $settings, $is_oss );
				}
				
				// Calculate unit price net
				$item_total = round( floatval( $item->get_total() ), 2 );
				$quantity = floatval( $item->get_quantity() );
				$unit_price_net = round( $item_total / $quantity, 2 );
				
				$position = [
					'quantity'         => $quantity,
					'price_net'        => $unit_price_net,
					'tax'              => $tax_symbol,
					'total_price_gross' => $this->calculate_total_price_gross( $unit_price_net, $quantity, $tax_symbol ),
				];
				
				// Use product_id from Fakturownia if warehouse sync is enabled and product has Fakturownia Product ID
				// This ensures product name comes from Fakturownia warehouse, not WooCommerce
				if ( $warehouse_sync_enabled && $product ) {
					$fakturownia_product_id = $product->get_meta( '_fakturownia_product_id' );
					
					// For variations, check parent product if variation doesn't have it
					if ( empty( $fakturownia_product_id ) && $product->is_type( 'variation' ) ) {
						$parent_id = $product->get_parent_id();
						$parent_product = wc_get_product( $parent_id );
						if ( $parent_product ) {
							$fakturownia_product_id = $parent_product->get_meta( '_fakturownia_product_id' );
						}
					}
					
					if ( ! empty( $fakturownia_product_id ) ) {
						$position['product_id'] = intval( $fakturownia_product_id );
						// Don't set 'name' when using product_id - Fakturownia will use product name from warehouse
					} else {
						// Fallback to name if no Fakturownia Product ID
						$position['name'] = $item->get_name();
					}
				} else {
					// Use name when warehouse sync is disabled
					$position['name'] = $item->get_name();
				}
				
				// VAT exemption basis is added at invoice level, not position level
				
				// Add lump sum (ryczałt) if enabled - this is ADDITIONAL to VAT, not replacing it
				$lump_sum_enable = isset( $settings['lump_sum_enable'] ) && $settings['lump_sum_enable'] === 'yes';
				if ( $lump_sum_enable ) {
					$variation_id = null;
					if ( $product->is_type( 'variation' ) ) {
						$variation_id = $product->get_id();
					} elseif ( $item->get_variation_id() ) {
						$variation_id = $item->get_variation_id();
					}
					
					$default_lump_sum = isset( $settings['lump_sum_default_value'] ) ? $settings['lump_sum_default_value'] : 'empty';
					$product_lump_sum = \Devikit\Fakturownia\LumpSumProductFields::get_product_lump_sum( $product, $variation_id, $default_lump_sum );
					
					// Only add lump_sum if it's set and not empty/none
					if ( ! empty( $product_lump_sum ) && $product_lump_sum !== 'none' && $product_lump_sum !== 'empty' ) {
						// Replace dot with comma for Fakturownia API
						$position['lump_sum_tax'] = str_replace( '.', ',', $product_lump_sum );
					}
				}
				
				// Add GTU code if available (PRO feature)
				if ( class_exists( 'Devikit\FakturowniaPro\Admin\ProductCodesManager' ) ) {
					$gtu_code = \Devikit\FakturowniaPro\Admin\ProductCodesManager::get_gtu_code( $product );
					if ( ! empty( $gtu_code ) ) {
						$position['gtu_code'] = $gtu_code;
					}
					
					// Add PKWiU code for ZW items (as additional_info)
					// PKWiU should be added when tax rate is 0 and product has zero tax class (VAT exempt)
					$tax_class = $product ? $product->get_tax_class() : '';
					$tax_class_zero = isset( $settings['tax_class_zero'] ) ? $settings['tax_class_zero'] : '_none_';
					$tax_class_zw = isset( $settings['tax_class_zw'] ) ? $settings['tax_class_zw'] : '_none_';
					$is_zw_product = ( $tax_symbol === 'zw' ) ||
									  ( ! empty( $tax_class ) && $tax_class_zw !== '_none_' && $tax_class_zw === $tax_class ) ||
									  ( $tax_symbol === '0' && ! empty( $tax_class ) && $tax_class_zero !== '_none_' && $tax_class_zero === $tax_class ) ||
									  ( ! wc_tax_enabled() );
					
					if ( $is_zw_product ) {
						$pkwiu_code = \Devikit\FakturowniaPro\Admin\ProductCodesManager::get_pkwiu_code( $product );
						if ( ! empty( $pkwiu_code ) ) {
							$position['additional_info'] = $pkwiu_code;
							$has_zw_with_pkwiu = true;
						}
					}
				}
				
				$data['positions'][] = $position;
			}
			
			// If we have ZW products with PKWiU, enable additional_info at invoice level
			if ( $has_zw_with_pkwiu ) {
				$data['additional_info'] = 1;
				$data['additional_info_desc'] = 'PKWiU';
			}
			
			// Add shipping as position (only if not using draft data)
			// Calculate highest lump sum from products for shipping (if lump sum is enabled)
			$lump_sum_for_shipping = null;
			$lump_sum_enable = isset( $settings['lump_sum_enable'] ) && $settings['lump_sum_enable'] === 'yes';
			if ( $lump_sum_enable ) {
				$lump_sums = [];
				foreach ( $order->get_items() as $item ) {
					$product = $item->get_product();
					if ( ! $product ) {
						continue;
					}
					
					$variation_id = null;
					if ( $product->is_type( 'variation' ) ) {
						$variation_id = $product->get_id();
					} elseif ( $item->get_variation_id() ) {
						$variation_id = $item->get_variation_id();
					}
					
					$default_lump_sum = isset( $settings['lump_sum_default_value'] ) ? $settings['lump_sum_default_value'] : 'empty';
					$product_lump_sum = \Devikit\Fakturownia\LumpSumProductFields::get_product_lump_sum( $product, $variation_id, $default_lump_sum );
					
					if ( ! empty( $product_lump_sum ) && $product_lump_sum !== 'none' && $product_lump_sum !== 'empty' ) {
						$lump_sums[] = (float) str_replace( ',', '.', $product_lump_sum );
					}
				}
				
				if ( ! empty( $lump_sums ) ) {
					$lump_sum_for_shipping = max( $lump_sums );
				}
			}
			
			$shipping_items = $order->get_items( 'shipping' );
			foreach ( $shipping_items as $shipping_item ) {
				if ( $shipping_item->get_total() > 0 ) {
					// For reverse charge, use "np" tax symbol for shipping too
					if ( $is_reverse_charge ) {
						$shipping_tax = 'np';
					} else {
						$shipping_tax = $this->get_shipping_tax_symbol( $shipping_item, $settings, $data['positions'], $is_oss );
					}
					$shipping_price_net = round( $shipping_item->get_total(), 2 );
					
					$position = [
						'name'             => ! empty( $shipping_item->get_name() ) ? $shipping_item->get_name() : __( 'Shipping', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
						'quantity'         => 1,
						'price_net'        => $shipping_price_net,
						'tax'              => $shipping_tax,
						'total_price_gross' => $this->calculate_total_price_gross( $shipping_price_net, 1, $shipping_tax ),
					];
					
					// Add lump sum for shipping if available (highest from products)
					if ( $lump_sum_for_shipping !== null ) {
						$position['lump_sum_tax'] = str_replace( '.', ',', (string) $lump_sum_for_shipping );
					}
					
					$data['positions'][] = $position;
				}
			}
		}

		// Apply MOSS/OSS data if enabled (PRO feature) - AFTER positions are created
		if ( class_exists( 'Devikit\FakturowniaPro\Core\MossManager' ) ) {
			$moss_manager = new \Devikit\FakturowniaPro\Core\MossManager();
			$moss_manager->maybe_add_oss_data( $data, $order );
		}

		return $data;
	}

	/**
	 * Calculate total price gross for position
	 * 
	 * @param float $price_net Net price per unit
	 * @param float $quantity Quantity
	 * @param string $tax_symbol Tax symbol (e.g., "23", "8", "5", "0", "zw", "np")
	 * @return float Total price gross
	 */
	private function calculate_total_price_gross( $price_net, $quantity, $tax_symbol ) {
		$total_net = $price_net * $quantity;
		
		// For exempt (zw) or non-taxable (np), gross equals net
		if ( $tax_symbol === 'zw' || $tax_symbol === 'np' ) {
			return round( $total_net, 2 );
		}
		
		// For numeric tax rates, calculate VAT
		$tax_rate = floatval( $tax_symbol );
		if ( $tax_rate > 0 ) {
			$total_gross = $total_net * ( 1 + ( $tax_rate / 100 ) );
			return round( $total_gross, 2 );
		}
		
		// Default: no tax
		return round( $total_net, 2 );
	}

	/**
	 * Get tax symbol for item (VAT rate)
	 * Note: Ryczałt (lump sum) is handled separately as additional field, not replacing VAT
	 */
	private function get_item_tax_symbol( $item, $product, $settings, $is_oss = false ) {
		// If taxes are disabled in WooCommerce, always return 'zw' (VAT exempt)
		if ( ! wc_tax_enabled() ) {
			return 'zw';
		}
		
		if ( ! $product ) {
			return '23';
		}
		
		// For OSS orders, use the tax rate from order item (already calculated by WooCommerce with OSS)
		// This preserves the OSS tax rate from the product
		if ( $is_oss ) {
			$total = $item->get_total();
			$tax = $item->get_total_tax();
			
			if ( $total > 0 && $tax > 0 ) {
				$rate = ( $tax / $total ) * 100;
				
				// Return the actual rate as string (e.g., "21" for 21%, "19" for 19%)
				// Round to nearest integer
				return (string) round( $rate );
			} elseif ( $total > 0 && $tax == 0 ) {
				return '0';
			}
			
			// Fallback: try to get rate from taxes array
			$taxes = $item->get_taxes();
			if ( ! empty( $taxes['total'] ) ) {
				foreach ( $taxes['total'] as $tax_id => $tax_amount ) {
					if ( ! empty( $tax_amount ) ) {
						$tax_rate = \WC_Tax::get_rate_percent( $tax_id );
						if ( $tax_rate ) {
							$rate = (float) str_replace( '%', '', $tax_rate );
							return (string) round( $rate );
						}
					}
				}
			}
			
			// Last fallback
			return '23';
		}
		
		// For non-OSS orders, use mapping from settings
		$tax_class = $product->get_tax_class();
		
		if ( ! empty( $tax_class ) ) {
			if ( isset( $settings['tax_class_zero'] ) && $settings['tax_class_zero'] !== '_none_' && $settings['tax_class_zero'] === $tax_class ) {
				return '0';
			}

			if ( isset( $settings['tax_class_zw'] ) && $settings['tax_class_zw'] !== '_none_' && $settings['tax_class_zw'] === $tax_class ) {
				return 'zw';
			}
			
			if ( isset( $settings['tax_class_np'] ) && $settings['tax_class_np'] !== '_none_' && $settings['tax_class_np'] === $tax_class ) {
				return 'np';
			}
		}
		
		$total = $item->get_total();
		$tax = $item->get_total_tax();
		
		if ( $total > 0 && $tax > 0 ) {
			$rate = ( $tax / $total ) * 100;
			
			if ( $rate >= 22 && $rate <= 24 ) return '23';
			if ( $rate >= 7 && $rate <= 9 ) return '8';
			if ( $rate >= 4 && $rate <= 6 ) return '5';
			if ( $rate < 1 ) return '0';
		} elseif ( $total > 0 && $tax == 0 ) {
			return '0';
		}
		
		return '23';
	}

	/**
	 * Get tax symbol for shipping
	 */
	private function get_shipping_tax_symbol( $item, $settings, $product_positions, $is_oss = false ) {
		// If taxes are disabled in WooCommerce, always return 'zw' (VAT exempt)
		if ( ! wc_tax_enabled() ) {
			return 'zw';
		}
		
		// For OSS orders, use the tax rate from shipping item (already calculated by WooCommerce with OSS)
		if ( $is_oss ) {
			$total = $item->get_total();
			$tax = $item->get_total_tax();
			
			if ( $total > 0 && $tax > 0 ) {
				$rate = ( $tax / $total ) * 100;
				// Return the actual rate as string (e.g., "21" for 21%, "19" for 19%)
				return (string) round( $rate );
			} elseif ( $total > 0 && $tax == 0 ) {
				return '0';
			}
			
			// Fallback: try to get rate from taxes array
			$taxes = $item->get_taxes();
			if ( ! empty( $taxes['total'] ) ) {
				foreach ( $taxes['total'] as $tax_id => $tax_amount ) {
					if ( ! empty( $tax_amount ) ) {
						$tax_rate = \WC_Tax::get_rate_percent( $tax_id );
						if ( $tax_rate ) {
							$rate = (float) str_replace( '%', '', $tax_rate );
							return (string) round( $rate );
						}
					}
				}
			}
			
			// Last fallback: use highest rate from product positions
			if ( ! empty( $product_positions ) ) {
				$rates = [];
				foreach ( $product_positions as $position ) {
					if ( isset( $position['tax'] ) && is_numeric( $position['tax'] ) ) {
						$rates[] = (float) $position['tax'];
					}
				}
				if ( ! empty( $rates ) ) {
					return (string) round( max( $rates ) );
				}
			}
			
			return '23';
		}
		
		if ( ! empty( $product_positions ) ) {
			$tax_symbols = [];
			$has_standard = false;
			
			foreach ( $product_positions as $position ) {
				if ( isset( $position['tax'] ) ) {
					$tax = $position['tax'];
					$tax_symbols[] = $tax;
					
					if ( in_array( $tax, [ '23', '8', '5' ] ) ) {
						$has_standard = true;
					}
				}
			}
			
			if ( $has_standard ) {
				if ( in_array( '23', $tax_symbols ) ) return '23';
				if ( in_array( '8', $tax_symbols ) ) return '8';
				if ( in_array( '5', $tax_symbols ) ) return '5';
			}
			
			$unique = array_unique( $tax_symbols );
			if ( count( $unique ) === 1 ) {
				$rate = $unique[0];
				if ( in_array( $rate, [ 'zw', 'np', '0' ] ) ) {
					return $rate;
				}
			}
		}
		
		$total = $item->get_total();
		$tax = $item->get_total_tax();
		
		if ( $total > 0 && $tax > 0 ) {
			$rate = ( $tax / $total ) * 100;
			
			if ( $rate >= 22 && $rate <= 24 ) return '23';
			if ( $rate >= 7 && $rate <= 9 ) return '8';
			if ( $rate >= 4 && $rate <= 6 ) return '5';
			if ( $rate < 1 ) return '0';
		} elseif ( $total > 0 && $tax == 0 ) {
			return '0';
		}
		
		return '23';
	}

	/**
	 * Display invoice download button on My Account order view
	 */
	public function display_invoice_download_button( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		// Verify user can view this order
		if ( ! current_user_can( 'view_order', $order_id ) && get_current_user_id() !== $order->get_customer_id() ) {
			return;
		}

		// Collect all available documents
		$documents = [];
		
		// Invoice
		$invoice_id = $order->get_meta( '_fakturownia_invoice_id' );
		$invoice_number = $order->get_meta( '_fakturownia_invoice_number' );
		if ( $invoice_id ) {
			$documents[] = [
				'type'   => 'invoice',
				'id'     => $invoice_id,
				'number' => $invoice_number,
				'label'  => __( 'Invoice', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			];
		}
		
		// Bill (Rachunek) - PRO feature
		if ( class_exists( 'Devikit\FakturowniaPro\Core\BillManager' ) ) {
			$bill_id = $order->get_meta( '_fakturownia_bill_id' );
			$bill_number = $order->get_meta( '_fakturownia_bill_number' );
			if ( $bill_id ) {
				$documents[] = [
					'type'   => 'bill',
					'id'     => $bill_id,
					'number' => $bill_number,
					'label'  => __( 'Bill', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
				];
			}
		}
		
		// Receipt (Paragon) - PRO feature
		if ( class_exists( 'Devikit\FakturowniaPro\Core\ReceiptManager' ) ) {
			$receipt_id = $order->get_meta( '_fakturownia_receipt_id' );
			$receipt_number = $order->get_meta( '_fakturownia_receipt_number' );
			if ( $receipt_id ) {
				$documents[] = [
					'type'   => 'receipt',
					'id'     => $receipt_id,
					'number' => $receipt_number,
					'label'  => __( 'Receipt', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
				];
			}
		}
		
		// Correction (Faktura korygująca) - PRO feature
		if ( class_exists( 'Devikit\FakturowniaPro\Admin\ProMetaBox' ) ) {
			$correction_id = $order->get_meta( '_fakturownia_correction_id' );
			$correction_number = $order->get_meta( '_fakturownia_correction_number' );
			if ( $correction_id ) {
				$documents[] = [
					'type'   => 'correction',
					'id'     => $correction_id,
					'number' => $correction_number,
					'label'  => __( 'Correction Invoice', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
				];
			}
		}
		
		// Display all available documents
		if ( ! empty( $documents ) ) {
			foreach ( $documents as $doc ) {
				$download_url = add_query_arg( [
					'action'       => 'download_fakturownia_document',
					'order_id'     => $order_id,
					'document_id'  => $doc['id'],
					'document_type' => $doc['type'],
					'_wpnonce'     => wp_create_nonce( 'download_fakturownia_document_' . $order_id . '_' . $doc['id'] ),
				], home_url( '/' ) );
				
				echo '<p class="fakturownia-' . esc_attr( $doc['type'] ) . '-download">';
				echo '<a href="' . esc_url( $download_url ) . '" class="button" target="_blank">';
				echo esc_html( $doc['label'] );
				if ( $doc['number'] ) {
					echo ' (' . esc_html( $doc['number'] ) . ')';
				}
				echo '</a>';
				echo '</p>';
			}
		}
	}

	/**
	 * Handle invoice download from frontend
	 */
	public function handle_invoice_download() {
		// Support both old action name (for backward compatibility) and new action name
		$action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
		
		if ( $action === 'download_fakturownia_invoice' ) {
			// Old action name - handle as invoice
			if ( ! isset( $_GET['order_id'], $_GET['invoice_id'], $_GET['_wpnonce'] ) ) {
				wp_die( esc_html__( 'Invalid request', 'invoicing-integration-for-fakturownia-and-woocommerce' ) );
			}
			
			$order_id = absint( $_GET['order_id'] );
			$document_id = absint( $_GET['invoice_id'] );
			$document_type = 'invoice';
			
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'download_fakturownia_invoice_' . $order_id ) ) {
				wp_die( esc_html__( 'Invalid security token', 'invoicing-integration-for-fakturownia-and-woocommerce' ) );
			}
		} elseif ( $action === 'download_fakturownia_document' ) {
			// New action name - handle all document types
			if ( ! isset( $_GET['order_id'], $_GET['document_id'], $_GET['document_type'], $_GET['_wpnonce'] ) ) {
				wp_die( esc_html__( 'Invalid request', 'invoicing-integration-for-fakturownia-and-woocommerce' ) );
			}
			
			$order_id = absint( $_GET['order_id'] );
			$document_id = absint( $_GET['document_id'] );
			$document_type = sanitize_text_field( wp_unslash( $_GET['document_type'] ) );
			
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'download_fakturownia_document_' . $order_id . '_' . $document_id ) ) {
				wp_die( esc_html__( 'Invalid security token', 'invoicing-integration-for-fakturownia-and-woocommerce' ) );
			}
		} else {
			return;
		}

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			wp_die( esc_html__( 'Order not found', 'invoicing-integration-for-fakturownia-and-woocommerce' ) );
		}

		// Verify user can view this order
		if ( ! current_user_can( 'view_order', $order_id ) && get_current_user_id() !== $order->get_customer_id() ) {
			wp_die( esc_html__( 'You do not have permission to view this document', 'invoicing-integration-for-fakturownia-and-woocommerce' ) );
		}

		// Verify document belongs to this order
		$meta_keys = [
			'invoice'    => [ '_fakturownia_invoice_id', '_fakturownia_invoice_number' ],
			'bill'       => [ '_fakturownia_bill_id', '_fakturownia_bill_number' ],
			'receipt'    => [ '_fakturownia_receipt_id', '_fakturownia_receipt_number' ],
			'correction' => [ '_fakturownia_correction_id', '_fakturownia_correction_number' ],
		];
		
		if ( ! isset( $meta_keys[ $document_type ] ) ) {
			wp_die( esc_html__( 'Invalid document type', 'invoicing-integration-for-fakturownia-and-woocommerce' ) );
		}
		
		$stored_document_id = $order->get_meta( $meta_keys[ $document_type ][0] );
		if ( $stored_document_id != $document_id ) {
			wp_die( esc_html__( 'Document does not belong to this order', 'invoicing-integration-for-fakturownia-and-woocommerce' ) );
		}

		// Download PDF (all document types use the same API endpoint)
		$pdf_content = $this->api_client->download_invoice_pdf( $document_id );

		if ( is_wp_error( $pdf_content ) ) {
			wp_die( esc_html( $pdf_content->get_error_message() ) );
		}

		// Get document number for filename
		$document_number = $order->get_meta( $meta_keys[ $document_type ][1] );
		$document_labels = [
			'invoice'    => __( 'Invoice', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'bill'       => __( 'Bill', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'receipt'    => __( 'Receipt', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'correction' => __( 'Correction', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
		];
		
		$filename_prefix = isset( $document_labels[ $document_type ] ) ? strtolower( $document_labels[ $document_type ] ) : 'document';
		$filename = $document_number ? sanitize_file_name( $document_number ) . '.pdf' : $filename_prefix . '-' . $document_id . '.pdf';

		header( 'Content-Type: application/pdf' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		header( 'Content-Length: ' . strlen( $pdf_content ) );
		header( 'Cache-Control: private, max-age=0, must-revalidate' );
		header( 'Pragma: public' );

		echo $pdf_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}
	
	/**
	 * Check if error should be retried
	 * 
	 * @param string $error_code Error code
	 * @param string $error_message Error message
	 * @return bool
	 */
	private function should_retry_error( $error_code, $error_message ) {
		// Retry temporary failures: rate limit, timeout, network errors
		$retryable_codes = [ 'http_request_failed', 'timeout', 'rate_limit' ];
		$retryable_messages = [ 'timeout', 'rate limit', 'temporary', 'network', 'connection' ];
		
		if ( in_array( $error_code, $retryable_codes, true ) ) {
			return true;
		}
		
		$message_lower = strtolower( $error_message );
		foreach ( $retryable_messages as $keyword ) {
			if ( strpos( $message_lower, $keyword ) !== false ) {
				return true;
			}
		}
		
		// Don't retry auth errors, validation errors, etc.
		return false;
	}

	/**
	 * Maybe send invoice email with optional delay for automatic creation.
	 *
	 * For automatic invoice creation the email is delayed by 5 minutes
	 * to allow KSeF (Krajowy System e-Faktur) to process the invoice.
	 * Manual creation sends the email immediately.
	 *
	 * @param \WC_Order $order
	 * @param array     $invoice_data  Array with id, number, url keys.
	 * @param bool      $is_automatic  Whether the invoice was created automatically.
	 */
	private function maybe_send_invoice_email( $order, $invoice_data, $is_automatic = false ) {
		if ( ! class_exists( 'Devikit\FakturowniaPro\Plugin' ) ) {
			return;
		}

		$pro_settings = get_option( 'devikit_fakturownia_pro_settings', [] );

		if ( empty( $pro_settings['send_invoice_email'] ) || $pro_settings['send_invoice_email'] !== 'yes' ) {
			return;
		}

		if ( $is_automatic ) {
			$delay_minutes = apply_filters( 'devikit_fakturownia_invoice_email_delay_minutes', 5 );

			as_schedule_single_action(
				time() + ( $delay_minutes * 60 ),
				'devikit_fakturownia_send_delayed_invoice_email',
				[
					'order_id'     => $order->get_id(),
					'invoice_data' => $invoice_data,
				],
				'devikit-fakturownia'
			);

			$order->add_order_note( sprintf(
				/* translators: %d: delay in minutes */
				__( 'Invoice email scheduled — will be sent in %d minutes (waiting for KSeF processing)', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
				$delay_minutes
			) );
		} else {
			WC()->mailer();
			do_action( 'devikit_fakturownia_invoice_generated', $order->get_id(), $invoice_data );
		}
	}

	/**
	 * Handle delayed invoice email (called by Action Scheduler after delay).
	 *
	 * @param int   $order_id
	 * @param array $invoice_data
	 */
	public function handle_delayed_invoice_email( $order_id, $invoice_data ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		WC()->mailer();
		do_action( 'devikit_fakturownia_invoice_generated', $order_id, $invoice_data );

		$order->add_order_note( __( 'Invoice email sent to customer (delayed — after KSeF processing)', 'invoicing-integration-for-fakturownia-and-woocommerce' ) );
	}

	/**
	 * Retry invoice creation (called by Action Scheduler)
	 * 
	 * @param int $order_id Order ID
	 */
	public function retry_invoice_creation( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}
		
		// Check if invoice already exists
		if ( $order->get_meta( '_fakturownia_invoice_id' ) ) {
			return;
		}
		
		$this->create_invoice_for_order( $order, true );
	}

	/**
	 * Get invoice language based on settings
	 *
	 * @param \WC_Order $order
	 * @param array $settings
	 * @return string Language code
	 */
	private function format_error_for_display( $error ) {
		if ( is_string( $error ) ) {
			return $error;
		}
		
		if ( is_array( $error ) ) {
			$errors = [];
			foreach ( $error as $key => $value ) {
				if ( is_array( $value ) ) {
					if ( isset( $value[0] ) && is_string( $value[0] ) ) {
						// Simple array of strings
						$errors[] = $key . ': ' . implode( ', ', $value );
					} else {
						// Nested array - recurse
						$nested = $this->format_error_for_display( $value );
						if ( ! empty( $nested ) ) {
							$errors[] = $key . ': ' . $nested;
						}
					}
				} else {
					$errors[] = $key . ': ' . $value;
				}
			}
			return implode( '; ', $errors );
		}
		
		return (string) $error;
	}

	/**
	 * Get invoice language
	 * 
	 * @param \WC_Order $order Order object
	 * @param array $settings Settings array (optional, will be loaded if not provided)
	 * @return string Language code
	 */
	public function get_invoice_language( $order, $settings = null ) {
		if ( $settings === null ) {
			$settings = get_option( 'devikit_fakturownia_settings', [] );
		}
		try {
			$language_mode = isset( $settings['invoice_language_mode'] ) ? $settings['invoice_language_mode'] : 'customer_country';
			
			switch ( $language_mode ) {
				case 'select':
					return isset( $settings['invoice_language_selected'] ) ? $settings['invoice_language_selected'] : 'pl';
				
				case 'customer_country':
				default:
					$country = $order->get_billing_country();
					return $this->get_language_from_country( $country );
			}
		} catch ( \Exception $e ) {
			$this->api_client->log( 'Error in get_invoice_language: ' . $e->getMessage(), 'error' );
			return 'pl'; // Default fallback
		}
	}

	/**
	 * Replace order variables in text
	 * 
	 * @param string $text Text with variables
	 * @param \WC_Order $order Order object
	 * @return string Text with variables replaced
	 */
	private function replace_order_variables( $text, $order ) {
		if ( ! $order ) {
			return $text;
		}
		
		$replacements = [
			'{order_id}' => $order->get_id(),
			'{order_number}' => $order->get_order_number(),
			'{order_date}' => $order->get_date_created() ? $order->get_date_created()->date_i18n( get_option( 'date_format' ) ) : '',
			'{order_date_raw}' => $order->get_date_created() ? $order->get_date_created()->date( 'Y-m-d' ) : '',
			'{customer_name}' => trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ),
			'{customer_first_name}' => $order->get_billing_first_name(),
			'{customer_last_name}' => $order->get_billing_last_name(),
			'{customer_email}' => $order->get_billing_email(),
			'{customer_phone}' => $order->get_billing_phone(),
			'{billing_company}' => $order->get_billing_company(),
			'{billing_nip}' => $order->get_meta( '_billing_nip' ) ?: $order->get_meta( 'vat_number' ),
			'{billing_address}' => $order->get_formatted_billing_address(),
			'{shipping_address}' => $order->get_formatted_shipping_address(),
			'{order_total}' => $order->get_total(),
			'{order_total_formatted}' => wc_price( $order->get_total(), [ 'currency' => $order->get_currency() ] ),
			'{payment_method}' => $order->get_payment_method_title(),
			'{shipping_method}' => $order->get_shipping_method(),
		];
		
		return str_replace( array_keys( $replacements ), array_values( $replacements ), $text );
	}

	/**
	 * Get real payment method name for API
	 * Maps payment method codes to readable names
	 *
	 * @param string $payment_method Payment method code or name
	 * @return string Payment method name for API
	 */
	private function get_real_payment_method_name( $payment_method ) {
		// Map payment method codes to readable names
		$payment_method_map = [
			'cod'      => __( 'Cash on Delivery', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'transfer' => __( 'Transfer', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'cash'    => __( 'Cash', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'card'    => __( 'Card', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
		];
		
		// If it's a known code, return mapped name
		if ( isset( $payment_method_map[ $payment_method ] ) ) {
			return $payment_method_map[ $payment_method ];
		}
		
		// Try to get title from WooCommerce payment gateway
		if ( WC()->payment_gateways() ) {
			$payment_gateways = WC()->payment_gateways->payment_gateways();
			if ( isset( $payment_gateways[ $payment_method ] ) ) {
				return $payment_gateways[ $payment_method ]->get_title();
			}
		}
		
		// Return as-is if no mapping found
		return $payment_method;
	}

	/**
	 * Map country code to language code
	 *
	 * @param string $country_code
	 * @return string Language code
	 */
	private function get_language_from_country( $country_code ) {
		$country_lang_map = [
			'PL' => 'pl',
			'GB' => 'en-GB',
			'US' => 'en',
			'DE' => 'de',
			'FR' => 'fr',
			'IT' => 'it',
			'ES' => 'es',
			'NL' => 'nl',
			'BE' => 'nl',
			'AT' => 'de',
			'CZ' => 'cz',
			'HU' => 'hu',
			'HR' => 'hr',
			'SK' => 'sk',
			'SI' => 'sl',
			'EE' => 'et',
			'RU' => 'ru',
			'TR' => 'tr',
			'CN' => 'cn',
			'AR' => 'ar',
			'SA' => 'ar',
			'AE' => 'ar',
			'IR' => 'fa',
		];
		
		return isset( $country_lang_map[ $country_code ] ) ? $country_lang_map[ $country_code ] : 'pl';
	}
}

