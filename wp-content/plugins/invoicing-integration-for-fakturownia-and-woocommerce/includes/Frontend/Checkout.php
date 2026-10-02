<?php

namespace Devikit\Fakturownia\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Checkout integration - handle NIP field visibility
 */
class Checkout {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'woocommerce_checkout_init', [ $this, 'init_checkout' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_checkout_scripts' ] );
	}

	/**
	 * Initialize checkout
	 */
	public function init_checkout() {
		add_action( 'woocommerce_checkout_process', [ $this, 'validate_nip_field' ] );
	}

	/**
	 * Validate NIP field
	 */
	public function validate_nip_field() {
		// If another NIP plugin is active, let it handle validation
		if ( $this->is_other_nip_plugin_active() ) {
			return;
		}
		
		$settings = get_option( 'devikit_fakturownia_settings', [] );
		
		$nip_required = isset( $settings['nip_required'] ) && $settings['nip_required'] === 'yes';
		$validate_format = isset( $settings['validate_nip_format'] ) && $settings['validate_nip_format'] === 'yes';
		
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Called by WooCommerce checkout validation hook which handles nonce
		$nip = isset( $_POST['billing_nip'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_nip'] ) ) : '';
		
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Called by WooCommerce checkout validation hook which handles nonce
		$want_invoice = isset( $_POST['billing_want_invoice'] ) && $_POST['billing_want_invoice'] === '1';
		
		// If checkbox mode is enabled and checkbox is checked, NIP is required
		$nip_display_mode = isset( $settings['nip_display_mode'] ) ? $settings['nip_display_mode'] : 'always';
		if ( $nip_display_mode === 'checkbox' && $want_invoice && $nip_required && empty( $nip ) ) {
			wc_add_notice( __( 'NIP is required when requesting an invoice.', 'invoicing-integration-for-fakturownia-and-woocommerce' ), 'error' );
		}
		
		// If always visible mode and NIP is required
		if ( $nip_display_mode === 'always' && $nip_required && empty( $nip ) ) {
			wc_add_notice( __( 'NIP is required.', 'invoicing-integration-for-fakturownia-and-woocommerce' ), 'error' );
		}
		
		if ( ! empty( $nip ) && $validate_format ) {
			$nip_clean = preg_replace( '/[^0-9]/', '', $nip );
			if ( strlen( $nip_clean ) !== 10 ) {
				wc_add_notice( __( 'NIP must have exactly 10 digits.', 'invoicing-integration-for-fakturownia-and-woocommerce' ), 'error' );
			}
		}
	}

	/**
	 * Check if another NIP plugin is active
	 */
	private function is_other_nip_plugin_active() {
		$nip_plugins = [
			'nip-field-for-woocommerce/nip-field-for-woocommerce.php',
			'nip-field-for-woocommerce-pro/nip-field-for-woocommerce-pro.php',
			'flexible-invoices/flexible-invoices.php',
			'woocommerce-eu-vat-number/woocommerce-eu-vat-number.php',
		];

		foreach ( $nip_plugins as $plugin ) {
			if ( is_plugin_active( $plugin ) ) {
				return true;
			}
		}

		return apply_filters( 'devikit_fakturownia_nip_field_available', false );
	}

	/**
	 * Enqueue checkout scripts
	 */
	public function enqueue_checkout_scripts() {
		if ( ! is_checkout() ) {
			return;
		}
		
		$settings = get_option( 'devikit_fakturownia_settings', [] );
		$nip_display_mode = isset( $settings['nip_display_mode'] ) ? $settings['nip_display_mode'] : 'always';
		
		if ( $nip_display_mode === 'checkbox' ) {
			wp_enqueue_script(
				'devikit-fakturownia-checkout',
				DEVIKIT_FAKTUROWNIA_URL . 'assets/js/checkout.js',
				[ 'jquery' ],
				DEVIKIT_FAKTUROWNIA_VERSION,
				true
			);
		}
	}
}

