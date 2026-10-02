<?php

namespace Devikit\Fakturownia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * NIP Field Handler
 * Adds NIP field to checkout if not provided by another plugin
 */
class NipField {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp', [ $this, 'maybe_add_nip_field' ] );
		add_filter( 'woocommerce_customer_meta_fields', [ $this, 'add_nip_to_user_profile' ] );
		add_action( 'woocommerce_admin_order_data_after_billing_address', [ $this, 'display_nip_in_admin_order' ] );
		// Editable NIP in order admin (same as address fields) - always, regardless of which plugin provides checkout NIP.
		add_filter( 'woocommerce_admin_billing_fields', [ $this, 'add_editable_nip_to_admin_billing' ], 20 );
		add_action( 'woocommerce_process_shop_order_meta', [ $this, 'save_admin_order_nip' ], 10, 2 );
	}

	/**
	 * Maybe add NIP field if not exists from other plugin
	 */
	public function maybe_add_nip_field() {
		$settings = get_option( 'devikit_fakturownia_settings', [] );
		$add_nip = isset( $settings['add_nip_field'] ) ? $settings['add_nip_field'] : 'yes';
		
		if ( $add_nip !== 'yes' ) {
			return;
		}

		if ( $this->is_nip_field_available() ) {
			return;
		}

		add_filter( 'woocommerce_billing_fields', [ $this, 'add_nip_field' ], 20 );
		add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'save_nip_field' ] );
		add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'save_want_invoice_checkbox' ] );
		add_filter( 'woocommerce_order_formatted_billing_address', [ $this, 'display_nip_in_address' ], 10, 2 );
		
		// Only add {nip} placeholder system if NOT using blocks checkout
		if ( ! $this->site_uses_block_checkout() ) {
			add_filter( 'woocommerce_formatted_address_replacements', [ $this, 'address_replacements' ], 10, 2 );
			add_filter( 'woocommerce_localisation_address_formats', [ $this, 'address_formats' ] );
		}
		
	}

	/**
	 * Check if NIP field is already available from another plugin
	 */
	private function is_nip_field_available() {
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
	 * Add NIP field to billing fields
	 */
	public function add_nip_field( $fields ) {
		$settings = get_option( 'devikit_fakturownia_settings', [] );
		$nip_display_mode = isset( $settings['nip_display_mode'] ) ? $settings['nip_display_mode'] : 'always';
		$checkbox_label = isset( $settings['invoice_checkbox_label'] ) && ! empty( $settings['invoice_checkbox_label'] ) 
			? $settings['invoice_checkbox_label'] 
			: __( 'I want an invoice', 'invoicing-integration-for-fakturownia-and-woocommerce' );

		// Add checkbox if mode is 'checkbox'
		if ( $nip_display_mode === 'checkbox' ) {
			$checkbox_field = [
				'type'    => 'checkbox',
				'label'   => $checkbox_label,
				'class'   => [ 'form-row-wide', 'create-invoice-checkbox' ],
				'priority' => 24, // Before NIP (25)
			];

			if ( isset( $fields['billing_company'] ) ) {
				$new_billing_fields = [];
				foreach ( $fields as $key => $field ) {
					if ( $key === 'billing_company' ) {
						$new_billing_fields['billing_want_invoice'] = $checkbox_field;
					}
					$new_billing_fields[ $key ] = $field;
				}
				$fields = $new_billing_fields;
			} else {
				$fields['billing_want_invoice'] = $checkbox_field;
			}
		}

		// Check if NIP field is already added by another plugin
		$common_keys = [ 'billing_company_nip', 'billing_nip', 'billing_tax_number', 'billing_vat', 'vat_number' ];
		
		$nip_exists = false;
		foreach ( $common_keys as $key ) {
			if ( isset( $fields[ $key ] ) ) {
				$nip_exists = $key;
				break;
			}
		}

		// If NIP field already exists, don't add our own
		if ( $nip_exists ) {
			return $fields;
		}

		// Check if NIP is required
		$nip_required = isset( $settings['nip_required'] ) && $settings['nip_required'] === 'yes';
		
		$nip_field_config = [
			'type'        => 'text',
			'label'       => __( 'NIP', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'placeholder' => __( 'Enter NIP', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'required'    => $nip_required,
			'class'       => [ 'form-row-wide', 'fakturownia-nip-field' ],
			'clear'       => true,
			'priority'    => 25,
		];
		
		// Add class to hide NIP field initially if checkbox mode is enabled
		if ( $nip_display_mode === 'checkbox' ) {
			$nip_field_config['class'][] = 'fakturownia-nip-hidden-initially';
		}

		// Insert before Company Name if exists
		if ( isset( $fields['billing_company'] ) ) {
			$new_billing_fields = [];
			foreach ( $fields as $key => $field ) {
				if ( $key === 'billing_company' ) {
					$new_billing_fields['billing_nip'] = $nip_field_config;
				}
				$new_billing_fields[ $key ] = $field;
			}
			$fields = $new_billing_fields;
		} else {
			$fields['billing_nip'] = $nip_field_config;
		}

		return $fields;
	}

	/**
	 * Save NIP field to order meta
	 */
	public function save_nip_field( $order_id ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Called by WooCommerce checkout hook which handles nonce
		if ( ! empty( $_POST['billing_nip'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Called by WooCommerce checkout hook which handles nonce
			$nip = sanitize_text_field( wp_unslash( $_POST['billing_nip'] ) );
			
			$order = wc_get_order( $order_id );
			$order->update_meta_data( '_billing_nip', $nip );
			$order->save();
		}
	}

	/**
	 * Save want invoice checkbox value
	 */
	public function save_want_invoice_checkbox( $order_id ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Called by WooCommerce checkout hook which handles nonce
		if ( isset( $_POST['billing_want_invoice'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Called by WooCommerce checkout hook which handles nonce
			$want_invoice = sanitize_text_field( wp_unslash( $_POST['billing_want_invoice'] ) );
			
			$order = wc_get_order( $order_id );
			if ( $order ) {
				$order->update_meta_data( '_billing_want_invoice', $want_invoice );
				$order->save();
			}
		}
	}

	/**
	 * Display NIP in formatted address
	 */
	public function display_nip_in_address( $address, $order ) {
		$nip = $order->get_meta( '_billing_nip' );
		if ( $nip ) {
			$address['nip'] = $nip;
		}
		return $address;
	}

	/**
	 * Address replacements
	 */
	public function address_replacements( $replacements, $args ) {
		$replacements['{nip}'] = isset( $args['nip'] ) ? __( 'NIP: ', 'invoicing-integration-for-fakturownia-and-woocommerce' ) . $args['nip'] : '';
		return $replacements;
	}

	/**
	 * Address formats
	 */
	public function address_formats( $formats ) {
		foreach ( $formats as $country => $format ) {
			$formats[ $country ] = $format . "\n{nip}";
		}
		return $formats;
	}

	/**
	 * Add editable NIP field to admin order billing section (same as address fields)
	 */
	public function add_editable_nip_to_admin_billing( $fields ) {
		if ( isset( $fields['nip'] ) ) {
			return $fields;
		}
		$fields['nip'] = [
			'label' => __( 'NIP', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'show'  => true,
		];
		return $fields;
	}

	/**
	 * Save NIP when order is updated in admin
	 *
	 * @param int       $order_id Order ID.
	 * @param \WC_Order $order    Order object (optional, for HPOS compatibility).
	 */
	public function save_admin_order_nip( $order_id, $order = null ) {
		// Hook may pass WP_Post in some WooCommerce versions - always use WC_Order
		if ( ! $order instanceof \WC_Order ) {
			$order = wc_get_order( $order_id );
		}
		if ( ! $order ) {
			return;
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Called by WooCommerce save hook which verifies nonce
		if ( isset( $_POST['_billing_nip'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Called by WooCommerce save hook which verifies nonce
			$nip = sanitize_text_field( wp_unslash( $_POST['_billing_nip'] ) );
			$order->update_meta_data( '_billing_nip', $nip );
			$order->save();
		}
	}

	/**
	 * Add NIP field to user profile in admin
	 */
	public function add_nip_to_user_profile( $fields ) {
		if ( isset( $fields['billing']['fields']['billing_nip'] ) ) {
			return $fields;
		}
		
		$settings = get_option( 'devikit_fakturownia_settings', [] );
		$add_nip = isset( $settings['add_nip_field'] ) ? $settings['add_nip_field'] : 'yes';
		
		if ( $add_nip !== 'yes' ) {
			return $fields;
		}
		
		if ( isset( $fields['billing']['fields'] ) ) {
			if ( isset( $fields['billing']['fields']['billing_company'] ) ) {
				$new_fields = [];
				
				foreach ( $fields['billing']['fields'] as $key => $field ) {
					$new_fields[ $key ] = $field;
					
					if ( $key === 'billing_company' ) {
						$new_fields['billing_nip'] = [
							'label'       => __( 'NIP', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
							'description' => '',
						];
					}
				}
				
				$fields['billing']['fields'] = $new_fields;
			} else {
				$fields['billing']['fields'] = array_merge(
					[
						'billing_nip' => [
							'label'       => __( 'NIP', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
							'description' => '',
						],
					],
					$fields['billing']['fields']
				);
			}
		}
		
		return $fields;
	}

	/**
	 * Display admin order extras (e.g. "I want an invoice").
	 * NIP is shown only in the editable billing form via woocommerce_admin_billing_fields.
	 *
	 * @param \WC_Order $order Order object.
	 */
	public function display_nip_in_admin_order( $order ) {
		$want_invoice = $order->get_meta( '_billing_want_invoice' );
		if ( $want_invoice !== '' ) {
			$status = ( $want_invoice === '1' ) ? __( 'Yes', 'invoicing-integration-for-fakturownia-and-woocommerce' ) : __( 'No', 'invoicing-integration-for-fakturownia-and-woocommerce' );
			echo '<p><strong>' . esc_html__( 'I want an invoice', 'invoicing-integration-for-fakturownia-and-woocommerce' ) . ':</strong> ' . esc_html( $status ) . '</p>';
		}
	}

	/**
	 * Check if site uses block checkout
	 */
	private function site_uses_block_checkout() {
		$checkout_page_id = wc_get_page_id( 'checkout' );
		if ( ! $checkout_page_id ) {
			return false;
		}
		
		return has_block( 'woocommerce/checkout', $checkout_page_id );
	}
}

