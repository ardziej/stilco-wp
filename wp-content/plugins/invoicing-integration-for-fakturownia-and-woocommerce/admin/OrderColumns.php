<?php

namespace Devikit\Fakturownia\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Order Columns - add invoice status column
 */
class OrderColumns {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add column to orders list
		add_filter( 'manage_edit-shop_order_columns', [ $this, 'add_invoice_column' ] );
		add_action( 'manage_shop_order_posts_custom_column', [ $this, 'render_invoice_column' ], 10, 2 );
		
		// HPOS support
		add_filter( 'manage_woocommerce_page_wc-orders_columns', [ $this, 'add_invoice_column' ] );
		add_action( 'manage_woocommerce_page_wc-orders_custom_column', [ $this, 'render_invoice_column_hpos' ], 10, 2 );
		
		// Enqueue scripts for orders list
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Add invoice column
	 */
	public function add_invoice_column( $columns ) {
		$new_columns = [];
		
		foreach ( $columns as $key => $column ) {
			$new_columns[ $key ] = $column;
			
			if ( $key === 'order_status' || $key === 'order_total' ) {
				$new_columns['fakturownia_invoice'] = __( 'Fakturownia', 'invoicing-integration-for-fakturownia-and-woocommerce' );
			}
		}
		
		return $new_columns;
	}

	/**
	 * Render invoice column (CPT)
	 */
	public function render_invoice_column( $column, $post_id ) {
		if ( $column !== 'fakturownia_invoice' ) {
			return;
		}

		$order = wc_get_order( $post_id );
		if ( ! $order ) {
			return;
		}

		$this->render_column_content( $order );
	}

	/**
	 * Render invoice column (HPOS)
	 */
	public function render_invoice_column_hpos( $column, $order ) {
		if ( $column !== 'fakturownia_invoice' ) {
			return;
		}

		if ( ! $order instanceof \WC_Order ) {
			return;
		}

		$this->render_column_content( $order );
	}

	/**
	 * Render column content
	 */
	private function render_column_content( $order ) {
		$order_id = $order->get_id();
		$documents = [];
		
		// Collect all documents
		// Invoice
		$invoice_id = $order->get_meta( '_fakturownia_invoice_id' );
		if ( ! empty( $invoice_id ) ) {
			$invoice_number = $order->get_meta( '_fakturownia_invoice_number' );
			$has_mismatch = $order->get_meta( '_fakturownia_invoice_total_mismatch' ) === 'yes';
			$documents[] = [
				'type' => 'invoice',
				'id' => $invoice_id,
				'number' => $invoice_number ?: $invoice_id,
				'label' => __( 'Invoice', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
				'has_mismatch' => $has_mismatch,
			];
		}
		
		// Proforma (PRO)
		if ( class_exists( 'Devikit\FakturowniaPro\Admin\ProMetaBox' ) ) {
			$proforma_id = $order->get_meta( '_fakturownia_proforma_id' );
			if ( ! empty( $proforma_id ) ) {
				$proforma_number = $order->get_meta( '_fakturownia_proforma_number' );
				$has_mismatch = $order->get_meta( '_fakturownia_proforma_total_mismatch' ) === 'yes';
				$documents[] = [
					'type' => 'proforma',
					'id' => $proforma_id,
					'number' => $proforma_number ?: $proforma_id,
					'label' => __( 'Proforma', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
					'has_mismatch' => $has_mismatch,
				];
			}
		}
		
		// Receipt (PRO) - only if PRO is active
		if ( class_exists( 'Devikit\FakturowniaPro\Core\ReceiptManager' ) ) {
			$receipt_id = $order->get_meta( '_fakturownia_receipt_id' );
			if ( ! empty( $receipt_id ) ) {
				$receipt_number = $order->get_meta( '_fakturownia_receipt_number' );
				$has_mismatch = $order->get_meta( '_fakturownia_receipt_total_mismatch' ) === 'yes';
				$documents[] = [
					'type' => 'receipt',
					'id' => $receipt_id,
					'number' => $receipt_number ?: $receipt_id,
					'label' => __( 'Receipt', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
					'has_mismatch' => $has_mismatch,
				];
			}
		}
		
		// Correction (PRO)
		$correction_id = $order->get_meta( '_fakturownia_correction_id' );
		if ( ! empty( $correction_id ) ) {
			$correction_number = $order->get_meta( '_fakturownia_correction_number' );
			$has_mismatch = $order->get_meta( '_fakturownia_correction_total_mismatch' ) === 'yes';
			$documents[] = [
				'type' => 'correction',
				'id' => $correction_id,
				'number' => $correction_number ?: $correction_id,
				'label' => __( 'Correction', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
				'has_mismatch' => $has_mismatch,
			];
		}
		
		// Bill (PRO)
		$bill_id = $order->get_meta( '_fakturownia_bill_id' );
		if ( ! empty( $bill_id ) ) {
			$bill_number = $order->get_meta( '_fakturownia_bill_number' );
			$has_mismatch = $order->get_meta( '_fakturownia_bill_total_mismatch' ) === 'yes';
			$documents[] = [
				'type' => 'bill',
				'id' => $bill_id,
				'number' => $bill_number ?: $bill_id,
				'label' => __( 'Bill', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
				'has_mismatch' => $has_mismatch,
			];
		}
		
		if ( empty( $documents ) ) {
			echo '<span style="color: #999;">—</span>';
			return;
		}
		
		// Check if any document has mismatch
		$has_any_mismatch = false;
		foreach ( $documents as $doc ) {
			if ( ! empty( $doc['has_mismatch'] ) ) {
				$has_any_mismatch = true;
				break;
			}
		}
		
		// Display all documents one below the other
		$container_style = $has_any_mismatch ? 'line-height: 1.8; background-color: #ffeaea; padding: 4px 6px; border-left: 3px solid #dc3232;' : 'line-height: 1.8;';
		echo '<div style="' . esc_attr( $container_style ) . '">';
		foreach ( $documents as $doc ) {
			$display_text = $doc['number'];
			if ( strlen( $display_text ) > 20 ) {
				$display_text = substr( $display_text, 0, 17 ) . '...';
			}
			
			// Use red color if mismatch, otherwise default blue
			$link_color = ! empty( $doc['has_mismatch'] ) ? '#dc3232' : '#2271b1';
			if ( ! empty( $doc['has_mismatch'] ) ) {
				/* translators: %s: Document type label (e.g., Invoice, Receipt) */
				$link_title = sprintf( __( 'Download %s PDF (⚠ Total mismatch)', 'invoicing-integration-for-fakturownia-and-woocommerce' ), $doc['label'] );
			} else {
				/* translators: %s: Document type label (e.g., Invoice, Receipt) */
				$link_title = sprintf( __( 'Download %s PDF', 'invoicing-integration-for-fakturownia-and-woocommerce' ), $doc['label'] );
			}
			
			echo '<div style="margin-bottom: 4px;">';
			echo '<a href="#" class="fakturownia-download-pdf-link" ';
			echo 'data-invoice-id="' . esc_attr( $doc['id'] ) . '" ';
			echo 'data-invoice-number="' . esc_attr( $doc['number'] ) . '" ';
			echo 'data-order-id="' . esc_attr( $order_id ) . '" ';
			echo 'data-doc-type="' . esc_attr( $doc['type'] ) . '" ';
			echo 'title="' . esc_attr( $link_title ) . '" ';
			echo 'style="color: ' . esc_attr( $link_color ) . '; text-decoration: none; font-weight: ' . ( ! empty( $doc['has_mismatch'] ) ? 'bold' : 'normal' ) . ';">';
			echo esc_html( $display_text );
			echo '</a>';
			echo '</div>';
		}
		echo '</div>';
	}
	
	/**
	 * Enqueue scripts for orders list
	 */
	public function enqueue_scripts( $hook_suffix ) {
		// Only load on orders list pages
		if ( $hook_suffix !== 'edit.php' && $hook_suffix !== 'woocommerce_page_wc-orders' ) {
			return;
		}
		
		// Check if we're on orders page
		global $post_type;
		if ( $hook_suffix === 'edit.php' && $post_type !== 'shop_order' ) {
			return;
		}
		
		wp_enqueue_script( 'jquery' );
		wp_add_inline_script( 'jquery', $this->get_inline_script() );
	}
	
	/**
	 * Get inline JavaScript for PDF download
	 */
	private function get_inline_script() {
		$downloading_text = esc_js( __( 'Downloading...', 'invoicing-integration-for-fakturownia-and-woocommerce' ) );
		$download_error   = esc_js( __( 'Failed to download PDF', 'invoicing-integration-for-fakturownia-and-woocommerce' ) );
		$nonce            = esc_js( wp_create_nonce( 'fakturownia_download_pdf' ) );

		return "jQuery(function($){
			$(document).on('click', '.fakturownia-download-pdf-link', function(e){
				e.preventDefault();
				var \$link = $(this);
				var invoiceId = \$link.data('invoice-id');
				var invoiceNumber = \$link.data('invoice-number');
				var originalText = \$link.text();

				\$link.css('opacity', '0.5').text('{$downloading_text}');

				$.post(ajaxurl, {
					action: 'devikit_fakturownia_download_pdf',
					invoice_id: invoiceId,
					invoice_number: invoiceNumber,
					nonce: '{$nonce}'
				}, function(response){
					\$link.css('opacity', '1').text(originalText);

					if (response && response.success && response.data && response.data.pdf) {
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
						alert((response && response.data && response.data.message) ? response.data.message : '{$download_error}');
					}
				}).fail(function(){
					\$link.css('opacity', '1').text(originalText);
					alert('{$download_error}');
				});
			});
		});";
	}
}

