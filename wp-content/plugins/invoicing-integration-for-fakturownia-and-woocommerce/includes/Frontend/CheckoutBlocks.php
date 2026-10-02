<?php

namespace Devikit\Fakturownia\Frontend;

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class handling NIP field in WooCommerce Gutenberg blocks
 */
class CheckoutBlocks implements IntegrationInterface {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Initialize only if WooCommerce Blocks is available
		add_action( 'woocommerce_blocks_loaded', [ $this, 'init_blocks_integration' ] );
	}

	/**
	 * Initialize blocks integration
	 */
	public function init_blocks_integration() {
		// Check if WooCommerce Blocks is available
		if ( ! class_exists( 'Automattic\WooCommerce\Blocks\Package' ) ) {
			return;
		}

		// Register integration with WooCommerce Blocks
		add_action( 'woocommerce_blocks_checkout_block_registration', [ $this, 'register_checkout_blocks_integration' ] );
		
		// Register additional checkout field for newer WooCommerce versions (8.8+)
		// Register always - WooCommerce will decide where to show it (works for both blocks and classic)
		if ( function_exists( 'woocommerce_register_additional_checkout_field' ) && 
			 version_compare( WC_VERSION, '8.8.0', '>=' ) ) {
			add_action( 'woocommerce_init', [ $this, 'register_additional_checkout_fields' ], 20 );
		}
		
		// Store API hooks for validation and saving
		add_action( 'woocommerce_store_api_checkout_update_order_meta', [ $this, 'save_fields_from_blocks' ] );
		add_action( 'woocommerce_store_api_checkout_order_processed', [ $this, 'save_fields_from_blocks' ] );
		
		// Fallback for older versions
		add_action( 'woocommerce_checkout_order_processed', [ $this, 'save_fields_from_blocks_fallback' ], 10, 3 );
		
		// Copy NIP to standard format for compatibility
		add_action( 'woocommerce_checkout_order_processed', [ $this, 'copy_nip_to_standard_format' ], 20, 1 );
		add_action( 'woocommerce_store_api_checkout_order_processed', [ $this, 'copy_nip_to_standard_format' ], 20, 1 );
		
		// My Account integration for blocks (only if site uses blocks)
		if ( $this->site_uses_block_checkout() ) {
			// Remove classic NIP field from My Account when using blocks
			// WooCommerce 8.8+ automatically adds fields registered via woocommerce_register_additional_checkout_field to My Account
			add_filter( 'woocommerce_billing_fields', [ $this, 'remove_classic_nip_from_my_account' ], 999 );
			
			add_action( 'woocommerce_customer_save_address', [ $this, 'save_nip_field_my_account_blocks' ], 10, 2 );
		}
		
		// Add NIP to formatted billing address (HTML modification, like nip-field-for-woocommerce)
		// This avoids using {nip} placeholder system which causes issues in blocks checkout
		add_filter( 'woocommerce_order_get_formatted_billing_address', [ $this, 'add_nip_to_formatted_billing_address' ], 10, 3 );
	}

	/**
	 * Register checkout blocks integration
	 */
	public function register_checkout_blocks_integration( $registry ) {
		$registry->register( new self() );
	}

	/**
	 * The name of the integration.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'devikit-fakturownia-blocks';
	}

	/**
	 * When called invokes any initialization/setup for the integration.
	 */
	public function initialize() {
		// VIES verification is handled by PRO plugin
	}

	/**
	 * Returns an array of script handles to enqueue in the frontend context.
	 *
	 * @return string[]
	 */
	public function get_script_handles() {
		// VIES scripts are handled by PRO plugin
		return [];
	}

	/**
	 * Returns an array of script handles to enqueue in the editor context.
	 *
	 * @return string[]
	 */
	public function get_editor_script_handles() {
		return [];
	}

	/**
	 * An array of key, value pairs of data made available to the block on the client side.
	 *
	 * @return array
	 */
	public function get_script_data() {
		return [];
	}

	/**
	 * Check if site uses block checkout
	 */
	private function site_uses_block_checkout() {
		$checkout_page_id = wc_get_page_id( 'checkout' );
		if ( ! $checkout_page_id ) {
			return false;
		}
		
		// Check if checkout page has blocks
		return has_block( 'woocommerce/checkout', $checkout_page_id );
	}

	/**
	 * Check if another NIP plugin is active
	 *
	 * @return bool
	 */
	private function is_other_nip_plugin_active() {
		// Include plugin.php if not already loaded
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		
		// Check for common NIP plugins
		$nip_plugins = [
			'woocommerce-nip-field/woocommerce-nip-field.php',
			'nip-field-woocommerce/nip-field-woocommerce.php',
			'nip-field-for-woocommerce/nip-field-for-woocommerce.php',
			'flexible-invoices-woocommerce/flexible-invoices-woocommerce.php',
			'woocommerce-ifirma/woocommerce-ifirma.php',
			'woocommerce-fakturownia/woocommerce-fakturownia.php',
			'invoicing-integration-for-infakt-and-woocommerce/invoicing-integration-for-infakt-and-woocommerce.php',
		];
		
		foreach ( $nip_plugins as $plugin ) {
			if ( is_plugin_active( $plugin ) ) {
				return true;
			}
		}
		
		return false;
	}

	/**
	 * Register additional checkout fields for blocks
	 */
	public function register_additional_checkout_fields() {
		// Check if WooCommerce version supports this function
		if ( ! function_exists( 'woocommerce_register_additional_checkout_field' ) ) {
			return;
		}

		// If another NIP plugin is active, don't register our field
		if ( $this->is_other_nip_plugin_active() ) {
			return;
		}

		$settings = get_option( 'devikit_fakturownia_settings', [] );
		$nip_required = isset( $settings['nip_required'] ) && $settings['nip_required'] === 'yes';
		$validate_format = isset( $settings['validate_nip_format'] ) && $settings['validate_nip_format'] === 'yes';
		
		// Prepare attributes for NIP field
		$attributes = [];
		if ( $validate_format ) {
			$attributes['pattern'] = '[0-9]{10}';
		}
		
		// Register NIP field - always visible in Gutenberg blocks
		try {
			woocommerce_register_additional_checkout_field(
				[
					'id'                => 'devikit-fakturownia/billing_nip',
					'label'             => __( 'NIP', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
					'location'          => 'address',
					'type'              => 'text',
					'required'          => $nip_required,
					'index'             => 20, // After first name, before company field
					'attributes'        => $attributes,
					'sanitize_callback' => [ $this, 'sanitize_nip_field' ],
					'validate_callback' => [ $this, 'validate_nip_field' ],
				]
			);
		} catch ( \Exception $e ) {
			// Log error for debugging using WooCommerce logger
			if ( function_exists( 'wc_get_logger' ) ) {
				wc_get_logger()->error( 'Fakturownia: Failed to register NIP field: ' . $e->getMessage(), [ 'source' => 'invoicing-integration-for-fakturownia-and-woocommerce' ] );
			}
		}
	}

	/**
	 * Sanitize NIP field
	 */
	public function sanitize_nip_field( $value ) {
		return sanitize_text_field( $value );
	}

	/**
	 * Validate NIP field
	 */
	public function validate_nip_field( $value, $errors ) {
		// If another NIP plugin is active, let it handle validation
		if ( $this->is_other_nip_plugin_active() ) {
			return;
		}
		
		$settings = get_option( 'devikit_fakturownia_settings', [] );
		$nip_required = isset( $settings['nip_required'] ) && $settings['nip_required'] === 'yes';
		$validate_format = isset( $settings['validate_nip_format'] ) && $settings['validate_nip_format'] === 'yes';
		
		// If field is required and empty
		if ( $nip_required && empty( $value ) ) {
			$errors->add(
				'billing_nip_required',
				__( 'Pole NIP jest wymagane.', 'invoicing-integration-for-fakturownia-and-woocommerce' )
			);
			return;
		}
		
		// Validate format if not empty
		if ( ! empty( $value ) && $validate_format ) {
			// Remove only spaces and dashes (allowed separators)
			$clean_nip = preg_replace( '/[\s\-]/', '', $value );
			
			// Check if contains ONLY digits and has exactly 10
			if ( ! preg_match( '/^[0-9]{10}$/', $clean_nip ) ) {
				$errors->add(
					'billing_nip_invalid',
					__( 'Numer NIP musi zawierać dokładnie 10 cyfr.', 'invoicing-integration-for-fakturownia-and-woocommerce' )
				);
			}
		}
		
		// VIES validation is handled by PRO plugin
	}

	/**
	 * Save fields from blocks to order
	 */
	public function save_fields_from_blocks( $order ) {
		if ( ! $order ) {
			return;
		}

		// Save NIP field (only if another plugin didn't already save it)
		$existing_nip = $order->get_meta( '_billing_nip' );
		if ( empty( $existing_nip ) ) {
			$nip_value = $this->get_nip_value_from_request();
			if ( ! empty( $nip_value ) ) {
				$order->update_meta_data( '_billing_nip', sanitize_text_field( $nip_value ) );
			}
		}

		$order->save();
	}

	/**
	 * Get NIP value from various request sources
	 */
	private function get_nip_value_from_request() {
		// phpcs:disable WordPress.Security.NonceVerification.Missing -- This is called within WooCommerce checkout/order processing context
		$nip_value = '';
		
		// Check different possible data sources
		if (
			isset( $_POST['extensions'] ) && is_array( $_POST['extensions'] )
			&& isset( $_POST['extensions']['devikit-fakturownia'] ) && is_array( $_POST['extensions']['devikit-fakturownia'] )
			&& isset( $_POST['extensions']['devikit-fakturownia']['billing_nip'] )
		) {
			$nip_value = sanitize_text_field( wp_unslash( $_POST['extensions']['devikit-fakturownia']['billing_nip'] ) );
		} elseif ( isset( $_POST['billing_nip'] ) ) {
			$nip_value = sanitize_text_field( wp_unslash( $_POST['billing_nip'] ) );
		} elseif ( isset( $_POST['devikit-fakturownia/billing_nip'] ) ) {
			$nip_value = sanitize_text_field( wp_unslash( $_POST['devikit-fakturownia/billing_nip'] ) );
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Called by WooCommerce Blocks API which handles validation
		} elseif ( isset( $_REQUEST['devikit-fakturownia/billing_nip'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Called by WooCommerce Blocks API which handles validation
			$nip_value = sanitize_text_field( wp_unslash( $_REQUEST['devikit-fakturownia/billing_nip'] ) );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		return $nip_value;
	}

	/**
	 * Save fields fallback for older versions
	 */
	public function save_fields_from_blocks_fallback( $order_id, $posted_data, $order ) {
		// Only proceed if this is from blocks checkout
		if ( ! $this->site_uses_block_checkout() ) {
			return;
		}

		if ( ! $order ) {
			return;
		}

		// Save NIP field (only if another plugin didn't already save it)
		$existing_nip = $order->get_meta( '_billing_nip' );
		if ( empty( $existing_nip ) ) {
			$nip_value = $this->get_nip_value_from_request();
			if ( ! empty( $nip_value ) ) {
				$order->update_meta_data( '_billing_nip', sanitize_text_field( $nip_value ) );
			}
		}
		
		$order->save();
	}

	/**
	 * Copy NIP from WooCommerce 8.8+ format to standard format for compatibility
	 */
	public function copy_nip_to_standard_format( $order ) {
		// Handle both order ID and order object
		if ( is_numeric( $order ) ) {
			$order = wc_get_order( $order );
		}
		
		if ( ! $order ) {
			return;
		}
		
		// Check if standard _billing_nip already exists and has value
		$existing_nip = $order->get_meta( '_billing_nip' );
		if ( ! empty( $existing_nip ) ) {
			return; // Already has standard format, no need to copy
		}
		
		// Try to get NIP from WooCommerce 8.8+ format (our plugin)
		$wc_nip_value = $order->get_meta( '_wc_billing/devikit-fakturownia/billing_nip' );
		
		// Also try other plugin's format
		if ( empty( $wc_nip_value ) ) {
			$wc_nip_value = $order->get_meta( '_wc_billing/nip-field-wc/billing_nip' );
		}
		
		if ( ! empty( $wc_nip_value ) ) {
			// Copy to standard format for compatibility
			$order->update_meta_data( '_billing_nip', sanitize_text_field( $wc_nip_value ) );
			
			// Remove the blocks-specific meta to avoid duplicate rendering
			$order->delete_meta_data( '_wc_billing/devikit-fakturownia/billing_nip' );
			$order->delete_meta_data( '_wc_billing/nip-field-wc/billing_nip' );
			
			$order->save();
		}
	}

	/**
	 * Remove classic NIP field from My Account when using blocks
	 * WooCommerce 8.8+ automatically adds fields registered via woocommerce_register_additional_checkout_field
	 */
	public function remove_classic_nip_from_my_account( $fields ) {
		// Only on My Account pages
		if ( is_account_page() && ! is_admin() ) {
			// Remove classic billing_nip field to prevent duplication
			if ( isset( $fields['billing_nip'] ) ) {
				unset( $fields['billing_nip'] );
			}
		}
		return $fields;
	}

	/**
	 * Save NIP field in My Account address form (blocks system)
	 */
	public function save_nip_field_my_account_blocks( $user_id, $load_address ) {
		// Only handle billing address
		if ( $load_address !== 'billing' ) {
			return;
		}

		// If another NIP plugin is active, let it handle saving
		if ( $this->is_other_nip_plugin_active() ) {
			return;
		}

		// Get NIP value from POST data (WooCommerce 8.8+ format)
		$nip_value = '';
		// phpcs:disable WordPress.Security.NonceVerification.Missing -- This is called within WooCommerce My Account save address context
		if ( isset( $_POST['devikit-fakturownia/billing_nip'] ) ) {
			$nip_value = sanitize_text_field( wp_unslash( $_POST['devikit-fakturownia/billing_nip'] ) );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		// Save to user meta
		if ( ! empty( $nip_value ) ) {
			update_user_meta( $user_id, 'billing_nip', $nip_value );
		} else {
			delete_user_meta( $user_id, 'billing_nip' );
		}
	}

	/**
	 * Add NIP to formatted billing address (HTML modification)
	 * This method works like nip-field-for-woocommerce - modifies already formatted HTML address
	 * instead of using {nip} placeholder system which causes issues in blocks checkout
	 */
	public function add_nip_to_formatted_billing_address( $address, $raw_address, $order ) {
		// Only process on checkout page with blocks
		if ( ! is_checkout() || ! $this->site_uses_block_checkout() ) {
			return $address;
		}

		// Get NIP value
		$nip = '';
		if ( $order ) {
			$nip = $order->get_meta( '_billing_nip' );
			if ( empty( $nip ) ) {
				$nip = $order->get_meta( '_wc_billing/devikit-fakturownia/billing_nip' );
			}
		}

		// If still empty, try to get from request/session (for blocks checkout during form filling)
		if ( empty( $nip ) ) {
			$nip = $this->get_nip_value_from_request();
		}

		// Check if NIP is already in the address to prevent duplication
		if ( ! empty( $nip ) && strpos( $address, 'NIP: ' . $nip ) === false && strpos( $address, 'NIP:' ) === false ) {
			// Remove any {nip} placeholder that might be there
			$address = preg_replace( '/\s*\{nip\}\s*/', '', $address );
			$address = preg_replace( '/\s*,\s*\{nip\}\s*/', '', $address );
			$address = preg_replace( '/\s*\{nip\}\s*,/', '', $address );
			$address = preg_replace( '/\s*&#123;nip&#125;\s*/', '', $address );
			
			// Add NIP before company name if exists
			if ( ! empty( $raw_address['company'] ) ) {
				$address = str_replace(
					$raw_address['company'],
					__( 'NIP', 'invoicing-integration-for-fakturownia-and-woocommerce' ) . ': ' . esc_html( $nip ) . '<br>' . $raw_address['company'],
					$address
				);
			} else {
				// If no company name, add NIP at beginning of address
				$address = __( 'NIP', 'invoicing-integration-for-fakturownia-and-woocommerce' ) . ': ' . esc_html( $nip ) . '<br>' . $address;
			}
		} else {
			// Even if NIP is empty, remove any {nip} placeholder that might be there
			$address = preg_replace( '/\s*\{nip\}\s*/', '', $address );
			$address = preg_replace( '/\s*,\s*\{nip\}\s*/', '', $address );
			$address = preg_replace( '/\s*\{nip\}\s*,/', '', $address );
			$address = preg_replace( '/\s*&#123;nip&#125;\s*/', '', $address );
			
			// Clean up any double commas or trailing commas
			$address = preg_replace( '/,\s*,/', ',', $address );
			$address = preg_replace( '/,\s*$/', '', $address );
			$address = trim( $address );
	}

		return $address;
	}
}
