<?php

namespace Devikit\Fakturownia\Admin;

use Devikit\Fakturownia\Api\Client;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings {

	/**
	 * Option name
	 */
	const OPTION_NAME = 'devikit_fakturownia_settings';

	/**
	 * Settings
	 * 
	 * @var array
	 */
	private $settings;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->settings = get_option( self::OPTION_NAME, [] );
		add_action( 'admin_menu', [ $this, 'add_menu_page' ], 60 );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
		add_action( 'wp_ajax_devikit_fakturownia_test_connection', [ $this, 'ajax_test_connection' ] );
		
		// Add Pro upgrade banner
		add_action( 'admin_notices', [ $this, 'display_pro_upgrade_banner' ] );
		
		// AJAX handlers for banner actions
		add_action( 'wp_ajax_devikit_fakturownia_dismiss_banner', [ $this, 'ajax_dismiss_banner' ] );
	}

	/**
	 * Get defaults
	 */
	private function get_defaults() {
		return [
			'api_token'             => '',
			'subdomain'             => '',
			'department_id'         => '',
			'nip_display_mode'      => 'always',
			'invoice_checkbox_label' => '',
			'nip_required'          => 'no',
			'validate_nip_format'   => 'yes',
			'payment_deadline_days' => '7',
			'invoice_issue_place'   => '',
			'invoice_language_mode'  => 'customer_country',
			'invoice_language_selected' => 'pl',
			'invoice_notes'         => '',
			'lump_sum_enable'       => 'no',
			'lump_sum_default_value' => 'empty',
			'tax_class_zero'         => '_none_',
			'tax_class_zw'          => '_none_',
			'tax_class_np'          => '_none_',
			'enable_debug_logging'  => 'no',
			'vat_exemption_basis'   => '',
			// Used when WooCommerce taxes are disabled. FREE defaults to "invoice_without_vat".
			// PRO may offer additional document types.
			'no_vat_document_type'   => 'invoice_without_vat', // 'bill' or 'invoice_without_vat'
		];
	}

	/**
	 * Add menu page
	 */
	public function add_menu_page() {
		add_submenu_page(
			'woocommerce',
			__( 'Fakturownia', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			__( 'Fakturownia', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'manage_woocommerce',
			'devikit-fakturownia',
			[ $this, 'render_page' ]
		);
	}

	/**
	 * Register settings
	 */
	public function register_settings() {
		register_setting( 'devikit_fakturownia_group', self::OPTION_NAME, [
			'type'              => 'array',
			'sanitize_callback' => [ $this, 'sanitize_settings' ],
		] );
	}

	/**
	 * Sanitize settings
	 */
	public function sanitize_settings( $input ) {
		$old_settings = get_option( self::OPTION_NAME, [] );
		
		if ( ! is_array( $input ) ) {
			return $old_settings;
		}

		// Normalize slashed input coming from options.php.
		$input = wp_unslash( $input );
		$defaults = $this->get_defaults();
		
		$allowed_yes_no = [ 'yes', 'no' ];
		$allowed_nip_display_mode = [ 'always', 'checkbox' ];
		$allowed_invoice_language_mode = [ 'customer_country', 'select' ];
		$allowed_invoice_language_codes = [ 'pl', 'en', 'en-GB', 'ar', 'cn', 'cz', 'de', 'es', 'et', 'fa', 'fr', 'hu', 'hr', 'it', 'nl', 'ru', 'sk', 'sl', 'tr' ];
		$allowed_lump_sum_default_values = [ 'empty', '3', '5.5', '8.5', '10', '12', '12.5', '14', '15', '17' ];
		$allowed_no_vat_document_types = [ 'bill', 'invoice_without_vat' ];
		
		// Allowed WooCommerce tax class slugs (select).
		$allowed_tax_classes = [ '_none_' ];
		if ( function_exists( 'wc_get_product_tax_class_options' ) ) {
			$tax_class_options = wc_get_product_tax_class_options();
			if ( is_array( $tax_class_options ) ) {
				$allowed_tax_classes = array_merge( $allowed_tax_classes, array_keys( $tax_class_options ) );
			}
		}
		
		// Define which checkboxes belong to which tab.
		// This ensures we only reset checkboxes for the tab being saved.
		$tab_checkboxes = [
			'settings'  => [],
			'documents' => [ 'lump_sum_enable' ],
			'checkout'  => [ 'nip_required', 'validate_nip_format' ],
			'advanced'  => [ 'enable_debug_logging' ],
		];
		
		// Define which non-checkbox fields indicate each tab submission.
		// Note: PRO injects OSS fields into the checkout tab (stored in FREE option),
		// so tab detection MUST NOT rely on tax_class_oss markers.
		$tab_indicators = [
			'settings'  => [ 'api_token', 'subdomain', 'department_id' ],
			'documents' => [
				'tax_class_zero',
				'tax_class_zw',
				'tax_class_np',
				'tax_class_oss',
				'payment_deadline_days',
				'invoice_issue_place',
				'invoice_language_mode',
				'invoice_language_selected',
				'invoice_notes',
				'lump_sum_default_value',
				'vat_exemption_basis',
				'no_vat_document_type',
			],
			'checkout'  => [ 'nip_display_mode', 'invoice_checkbox_label' ],
			'advanced'  => [ '_advanced_tab' ],
		];
		
		// Forced tab marker from UI forms (prevents cross-tab checkbox resets).
		$forced_tab = null;
		if ( isset( $input['_tab'] ) ) {
			$maybe_tab = is_scalar( $input['_tab'] ) ? sanitize_key( (string) $input['_tab'] ) : '';
			if ( $maybe_tab && isset( $tab_indicators[ $maybe_tab ] ) ) {
				$forced_tab = $maybe_tab;
			}
			unset( $input['_tab'] );
		}
		
		// Determine which tab is being saved
		$current_tab = null;
		if ( $forced_tab !== null ) {
			$current_tab = $forced_tab;
		} else {
			foreach ( $tab_indicators as $tab => $fields ) {
				foreach ( $fields as $field ) {
					if ( isset( $input[ $field ] ) ) {
						$current_tab = $tab;
						break 2;
					}
				}
			}
		}
		
		// If we determined the tab, only reset checkboxes for that tab
		if ( $current_tab !== null && isset( $tab_checkboxes[ $current_tab ] ) ) {
			foreach ( $tab_checkboxes[ $current_tab ] as $field ) {
				if ( ! isset( $input[ $field ] ) ) {
					$input[ $field ] = 'no';
				}
			}
		}
		
		// Remove hidden field marker
		if ( isset( $input['_advanced_tab'] ) ) {
			unset( $input['_advanced_tab'] );
		}
		// Marker used by PRO to force sending OSS tax classes even if nothing is checked.
		$oss_marker_present = false;
		if ( isset( $input['tax_class_oss_oss_tab'] ) ) {
			$oss_marker_present = true;
			unset( $input['tax_class_oss_oss_tab'] );
		}
		// If OSS marker is present and nothing selected, clear the saved value.
		if ( $oss_marker_present && ! isset( $input['tax_class_oss'] ) ) {
			$input['tax_class_oss'] = [];
		}

		// Start with old settings and only override sanitized keys from current submission.
		$sanitized = is_array( $old_settings ) ? $old_settings : [];
		
		$fields_to_process = [];
		if ( $current_tab !== null && isset( $tab_indicators[ $current_tab ] ) ) {
			$fields_to_process = $tab_indicators[ $current_tab ];
			if ( isset( $tab_checkboxes[ $current_tab ] ) ) {
				$fields_to_process = array_merge( $fields_to_process, $tab_checkboxes[ $current_tab ] );
			}
		} else {
			// Fallback: sanitize only keys present in the request.
			$fields_to_process = array_keys( $input );
		}
		
		// OSS tax classes can be rendered inside checkout tab by PRO but stored in FREE settings.
		// Always process them when marker is present.
		if ( $oss_marker_present && ! in_array( 'tax_class_oss', $fields_to_process, true ) ) {
			$fields_to_process[] = 'tax_class_oss';
		}

		foreach ( $fields_to_process as $field ) {
			if ( ! array_key_exists( $field, $input ) ) {
				continue;
			}

			$value = $input[ $field ];

			switch ( $field ) {
				// Connection.
				case 'api_token':
					// Do not over-sanitize secrets (avoid mangling tokens).
					$token = is_scalar( $value ) ? (string) $value : '';
					$token = trim( $token );
					// Remove control characters (keep the actual token intact).
					$token = preg_replace( '/[\x00-\x1F\x7F]/u', '', $token );
					$sanitized['api_token'] = $token;
					break;

				case 'subdomain':
					$subdomain = is_scalar( $value ) ? (string) $value : '';
					$subdomain = trim( $subdomain );
					// Subdomain should be a safe slug-like string.
					$subdomain = sanitize_key( $subdomain );
					$sanitized['subdomain'] = $subdomain;
					break;

				case 'department_id':
					$department_id = is_scalar( $value ) ? absint( $value ) : 0;
					$sanitized['department_id'] = $department_id > 0 ? (string) $department_id : '';
					break;

				// Documents.
				case 'tax_class_zero':
				case 'tax_class_zw':
				case 'tax_class_np':
					$tax_class = is_scalar( $value ) ? sanitize_title( (string) $value ) : '_none_';
					$sanitized[ $field ] = in_array( $tax_class, $allowed_tax_classes, true ) ? $tax_class : '_none_';
					break;

				case 'tax_class_oss':
					// Multi-select (checkboxes) – store as array of allowed tax class slugs.
					$selected = [];
					if ( is_array( $value ) ) {
						foreach ( $value as $slug ) {
							if ( ! is_scalar( $slug ) ) {
								continue;
							}
							$slug = sanitize_title( (string) $slug );
							if ( in_array( $slug, $allowed_tax_classes, true ) ) {
								$selected[] = $slug;
							}
						}
					} elseif ( is_scalar( $value ) ) {
						// Backward-compat: allow single value stored as string.
						$slug = sanitize_title( (string) $value );
						if ( in_array( $slug, $allowed_tax_classes, true ) && $slug !== '_none_' ) {
							$selected[] = $slug;
						}
					}
					$sanitized['tax_class_oss'] = array_values( array_unique( $selected ) );
					break;

				case 'vat_exemption_basis':
					$sanitized['vat_exemption_basis'] = is_scalar( $value ) ? sanitize_text_field( (string) $value ) : '';
					break;

				case 'no_vat_document_type':
					$doc_type = is_scalar( $value ) ? sanitize_key( (string) $value ) : $defaults['no_vat_document_type'];
					$sanitized['no_vat_document_type'] = in_array( $doc_type, $allowed_no_vat_document_types, true ) ? $doc_type : $defaults['no_vat_document_type'];
					break;

				case 'payment_deadline_days':
					$days = is_scalar( $value ) ? absint( $value ) : absint( $defaults['payment_deadline_days'] );
					if ( $days > 60 ) {
						$days = 60;
					}
					$sanitized['payment_deadline_days'] = (string) $days;
					break;

				case 'invoice_issue_place':
					$sanitized['invoice_issue_place'] = is_scalar( $value ) ? sanitize_text_field( (string) $value ) : '';
					break;

				case 'invoice_language_mode':
					$mode = is_scalar( $value ) ? sanitize_key( (string) $value ) : $defaults['invoice_language_mode'];
					$sanitized['invoice_language_mode'] = in_array( $mode, $allowed_invoice_language_mode, true ) ? $mode : $defaults['invoice_language_mode'];
					break;

				case 'invoice_language_selected':
					$lang = is_scalar( $value ) ? (string) $value : $defaults['invoice_language_selected'];
					$lang = trim( $lang );
					$sanitized['invoice_language_selected'] = in_array( $lang, $allowed_invoice_language_codes, true ) ? $lang : $defaults['invoice_language_selected'];
					break;

				case 'invoice_notes':
					$sanitized['invoice_notes'] = is_scalar( $value ) ? sanitize_textarea_field( (string) $value ) : '';
					break;

				case 'lump_sum_enable':
					$val = is_scalar( $value ) ? sanitize_key( (string) $value ) : 'no';
					$sanitized['lump_sum_enable'] = in_array( $val, $allowed_yes_no, true ) ? $val : 'no';
					break;

				case 'lump_sum_default_value':
					$rate = is_scalar( $value ) ? (string) $value : $defaults['lump_sum_default_value'];
					$rate = trim( $rate );
					$sanitized['lump_sum_default_value'] = in_array( $rate, $allowed_lump_sum_default_values, true ) ? $rate : $defaults['lump_sum_default_value'];
					break;

				// Checkout/store.
				case 'nip_display_mode':
					$mode = is_scalar( $value ) ? sanitize_key( (string) $value ) : $defaults['nip_display_mode'];
					$sanitized['nip_display_mode'] = in_array( $mode, $allowed_nip_display_mode, true ) ? $mode : $defaults['nip_display_mode'];
					break;

				case 'invoice_checkbox_label':
					$sanitized['invoice_checkbox_label'] = is_scalar( $value ) ? sanitize_text_field( (string) $value ) : '';
					break;

				case 'nip_required':
				case 'validate_nip_format':
					$val = is_scalar( $value ) ? sanitize_key( (string) $value ) : 'no';
					$sanitized[ $field ] = in_array( $val, $allowed_yes_no, true ) ? $val : 'no';
					break;

				// Advanced.
				case 'enable_debug_logging':
					$val = is_scalar( $value ) ? sanitize_key( (string) $value ) : 'no';
					$sanitized['enable_debug_logging'] = in_array( $val, $allowed_yes_no, true ) ? $val : 'no';
					break;

				default:
					// Ignore unknown keys.
					break;
			}
		}
		
		// Clear API cache if credentials changed
		$old_token = isset( $old_settings['api_token'] ) ? $old_settings['api_token'] : '';
		$new_token = isset( $sanitized['api_token'] ) ? $sanitized['api_token'] : '';
		$old_subdomain = isset( $old_settings['subdomain'] ) ? $old_settings['subdomain'] : '';
		$new_subdomain = isset( $sanitized['subdomain'] ) ? $sanitized['subdomain'] : '';
		
		if ( $old_token !== $new_token || $old_subdomain !== $new_subdomain ) {
			Client::clear_cache();
		}
		
		return $sanitized;
	}

	/**
	 * Get option value
	 * 
	 * @param string $key Option key
	 * @param mixed $default Default value
	 * @return mixed
	 */
	public function get_option( $key, $default = '' ) {
		$defaults = $this->get_defaults();
		$default_value = isset( $defaults[ $key ] ) ? $defaults[ $key ] : $default;
		return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : $default_value;
	}

	/**
	 * Enqueue admin styles
	 */
	public function enqueue_admin_styles( $hook_suffix ) {
		if ( strpos( $hook_suffix, 'devikit-fakturownia' ) === false ) {
			return;
		}
		
		wp_add_inline_style( 'admin-bar', '
			.fakturownia-wc-tabs {
				display: flex;
				margin-bottom: 20px;
				padding-bottom: 0;
				margin-top: 20px;
			}
			.fakturownia-wc-tabs .nav-tab {
				margin-left: 0;
				margin-right: 5px;
				background: #fff;
				border: 1px solid #0073aa;
				border-bottom: 3px solid transparent;
				padding: 10px 20px;
				font-size: 14px;
				line-height: 1.71428571;
				font-weight: 600;
				text-decoration: none;
				white-space: nowrap;
				color: #0073aa;
				border-radius: 3px 3px 0 0;
			}
			.fakturownia-wc-tabs .nav-tab:hover {
				background: #f8f8f8;
				color: #00a0d2;
			}
			.fakturownia-wc-tabs .nav-tab-active {
				background: #0073aa;
				color: #fff;
				border-bottom: 3px solid #004b6d;
			}
			.fakturownia-wc-tabs .nav-tab-active:hover {
				background: #0073aa;
				color: #fff;
			}
			.fakturownia-wc-tab-content {
				background: #fff;
				padding: 25px;
				border: 1px solid #ddd;
				box-shadow: 0 1px 3px rgba(0,0,0,0.1);
				border-radius: 3px;
			}
			.fakturownia-wc-settings-section {
				margin-bottom: 20px;
			}
			.fakturownia-wc-settings-section h2 {
				font-size: 18px;
				padding: 0;
				margin: 0 0 15px 0;
				color: #0073aa;
				border-bottom: 1px solid #eee;
				padding-bottom: 10px;
			}
			.fakturownia-wc-pro-notice {
				background: #f8f8f8;
				border-left: 4px solid #0073aa;
				padding: 15px;
				margin: 20px 0;
			}
		' );
	}
	
	/**
	 * Enqueue admin scripts
	 */
	public function enqueue_admin_scripts( $hook_suffix ) {
		if ( strpos( $hook_suffix, 'devikit-fakturownia' ) === false ) {
			return;
		}
		
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Tab navigation in admin context
		$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'settings';
		
		wp_enqueue_script( 'jquery' );
		
		// Test connection button script
		if ( $active_tab === 'settings' ) {
			$test_connection_js = "
				jQuery(document).ready(function($) {
					$('#devikit-fakturownia-test-connection').on('click', function() {
						var \$btn = $(this);
						var \$result = $('#devikit-fakturownia-connection-result');
						
						\$btn.prop('disabled', true);
						\$result.text('" . esc_js( __( 'Testing...', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ) . "').css('color', '#666');

						$.post(ajaxurl, {
							action: 'devikit_fakturownia_test_connection',
							api_token: $('input[name=\"" . esc_js( self::OPTION_NAME ) . "[api_token]\"]').val(),
							subdomain: $('input[name=\"" . esc_js( self::OPTION_NAME ) . "[subdomain]\"]').val(),
							nonce: '" . esc_js( wp_create_nonce( 'fakturownia_test_connection' ) ) . "'
						}, function(response) {
							\$btn.prop('disabled', false);
							if (response.success) {
								\$result.text(response.data.message).css('color', 'green');
							} else {
								\$result.text(response.data.message).css('color', 'red');
							}
						});
					});
				});
			";
			wp_add_inline_script( 'jquery', $test_connection_js );
		}

		// Documents tab scripts
		if ( $active_tab === 'documents' ) {
			$selector = esc_js( self::OPTION_NAME . '[invoice_language_mode]' );
			$documents_js = "jQuery(function($){
				$('input[name=\"{$selector}\"]').on('change', function(){
					if ($(this).val() === 'select') {
						$('#invoice-language-select-wrapper').show();
					} else {
						$('#invoice-language-select-wrapper').hide();
					}
				}).trigger('change');
			});";
			wp_add_inline_script( 'jquery', $documents_js );
		}
	}

	/**
	 * Render settings page
	 */
	public function render_page() {
		// Auto-refresh API cache on settings page load
		static $cache_refreshed = false;
		if ( ! $cache_refreshed ) {
			Client::clear_cache();
			$cache_refreshed = true;
		}
		
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Tab navigation in admin context
		$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'settings';
		
		$tabs = apply_filters( 'devikit_fakturownia_settings_tabs', [
			'settings'   => __( 'Fakturownia Connection', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'documents'  => __( 'Invoicing', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'checkout'   => __( 'Store', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'automation' => __( 'Automation', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'receipts'   => __( 'Receipts', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'warehouse'  => __( 'Warehouse', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'advanced'   => __( 'Advanced', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
		] );

		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Fakturownia Integration', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h1>
			
			<?php $this->display_tax_rounding_notice(); ?>
			
			<div class="fakturownia-wc-tabs">
				<?php foreach ( $tabs as $tab_key => $tab_name ) : ?>
					<a href="?page=devikit-fakturownia&amp;tab=<?php echo esc_attr( $tab_key ); ?>" 
					   class="nav-tab <?php echo esc_attr( $active_tab === $tab_key ? 'nav-tab-active' : '' ); ?>">
						<?php echo esc_html( $tab_name ); ?>
					</a>
				<?php endforeach; ?>
			</div>

			<div class="fakturownia-wc-tab-content">
				<?php
				if ( $active_tab === 'settings' ) {
					$this->render_settings_tab();
				} elseif ( $active_tab === 'documents' ) {
					$this->render_documents_tab();
				} elseif ( $active_tab === 'checkout' ) {
					$this->render_checkout_tab();
				} elseif ( $active_tab === 'automation' ) {
					$this->render_automation_tab();
				} elseif ( $active_tab === 'receipts' ) {
					$this->render_receipts_tab();
				} elseif ( $active_tab === 'warehouse' ) {
					$this->render_warehouse_tab();
				} elseif ( $active_tab === 'advanced' ) {
					$this->render_advanced_tab();
				} else {
					do_action( 'devikit_fakturownia_render_tab_' . $active_tab );
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render settings tab
	 */
	private function render_settings_tab() {
		?>
		<div class="fakturownia-wc-settings-section">
			<div style="margin-bottom: 20px;">
				<a href="https://devikit.pl/dokumentacja/fakturownia-woocommerce-pro/" target="_blank" style="text-decoration: none; display: inline-flex; align-items: center; padding: 8px 16px; background: #2271b1; color: #fff; border-radius: 4px; font-weight: 500;">
					<span style="margin-right: 8px;">📚</span>
					<?php echo esc_html__( 'View Documentation', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
				</a>
			</div>
			<h2><?php echo esc_html__( 'General Settings', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'devikit_fakturownia_group' ); ?>
				<input type="hidden" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[_tab]" value="settings" />
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'API Token', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<input type="password" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[api_token]" value="<?php echo esc_attr( $this->get_option( 'api_token' ) ); ?>" class="regular-text" />
							<p class="description"><?php echo esc_html__( 'Enter your Fakturownia API Token (from Fakturownia Settings → Account Settings → Integration → API Authorization Code).', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'Subdomain', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<input type="text" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[subdomain]" value="<?php echo esc_attr( $this->get_option( 'subdomain' ) ); ?>" class="regular-text" />
							<p class="description"><?php echo esc_html__( 'Enter your Fakturownia subdomain (e.g., if your URL is https://example.fakturownia.pl/, enter "example").', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'Company/Department ID', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<input type="text" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[department_id]" value="<?php echo esc_attr( $this->get_option( 'department_id' ) ); ?>" class="regular-text" />
							<p class="description"><?php echo esc_html__( 'Optional. Enter company or department ID if you have multiple companies in one Fakturownia account. You can find it in the URL when editing company/department in Settings → Company Data.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'Connection', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<button type="button" id="devikit-fakturownia-test-connection" class="button"><?php echo esc_html__( 'Test Connection', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></button>
							<span id="devikit-fakturownia-connection-result"></span>
						</td>
					</tr>
				</table>
				
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render documents tab
	 */
	private function render_documents_tab() {
		// Get WooCommerce tax classes
		$tax_classes = wc_get_product_tax_class_options();
		
		?>
		<div class="fakturownia-wc-settings-section">
			<h2><?php echo esc_html__( 'VAT Rates Mapping', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h2>
			<?php if ( ! wc_tax_enabled() ) : ?>
			<div class="notice notice-info inline" style="margin: 15px 0; padding: 10px;">
				<p><strong><?php echo esc_html__( 'Important:', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong></p>
				<p><?php echo esc_html__( 'You currently have VAT taxes disabled in WooCommerce (meaning you are not a VAT payer). All documents will be issued with ZW (VAT exempt) rate.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
				<p><?php echo esc_html__( 'If you are a VAT payer, please enable taxes by going to', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?> 
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=tax' ) ); ?>" target="_blank">
						<?php echo esc_html__( 'WooCommerce → Settings → Tax', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
					</a>
					<?php echo esc_html__( 'and enable "Enable tax rates and calculations".', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
				</p>
			</div>
			<?php else : ?>
			<div class="notice notice-info inline" style="margin: 15px 0; padding: 10px;">
				<p><strong><?php echo esc_html__( 'Important:', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong></p>
				<p><?php echo esc_html__( 'If you are not a VAT payer and want to issue invoices with ZW (VAT exempt) rate, you should disable taxes in WooCommerce settings.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
				<p><?php echo esc_html__( 'To disable taxes, go to', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?> 
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=tax' ) ); ?>" target="_blank">
						<?php echo esc_html__( 'WooCommerce → Settings → Tax', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
					</a>
					<?php echo esc_html__( 'and disable "Enable tax rates and calculations".', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
				</p>
			</div>
			<p class="description" style="margin-bottom:15px;">
				<?php echo esc_html__( 'Map WooCommerce tax classes to Fakturownia VAT rates:', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?><br>
				<strong>0</strong> - <?php echo esc_html__( '0% rate (books, export goods)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?><br>
				<strong>zw</strong> - <?php echo esc_html__( 'VAT exempt (zwolnione od podatku)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?><br>
				<strong>np</strong> - <?php echo esc_html__( 'not subject to VAT (services outside EU, VAT margin)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
			</p>
			<?php endif; ?>
			<form method="post" action="options.php">
				<?php settings_fields( 'devikit_fakturownia_group' ); ?>
				<input type="hidden" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[_tab]" value="documents" />
				
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( '0% Tax Rate', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<select name="<?php echo esc_attr( self::OPTION_NAME ); ?>[tax_class_zero]">
								<option value="_none_" <?php selected( $this->get_option( 'tax_class_zero' ), '_none_' ); ?>><?php echo esc_html__( 'None', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></option>
								<?php foreach ( $tax_classes as $slug => $name ) : ?>
									<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $this->get_option( 'tax_class_zero' ), $slug ); ?>>
										<?php echo esc_html( $name ); ?>
									</option>
								<?php endforeach; ?>
							</select>
							<p class="description"><?php echo esc_html__( 'Select tax class for 0% VAT products.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'ZW Tax Rate (VAT exempt)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<select name="<?php echo esc_attr( self::OPTION_NAME ); ?>[tax_class_zw]">
								<option value="_none_" <?php selected( $this->get_option( 'tax_class_zw' ), '_none_' ); ?>><?php echo esc_html__( 'None', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></option>
								<?php foreach ( $tax_classes as $slug => $name ) : ?>
									<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $this->get_option( 'tax_class_zw' ), $slug ); ?>>
										<?php echo esc_html( $name ); ?>
									</option>
								<?php endforeach; ?>
							</select>
							<p class="description"><?php echo esc_html__( 'Select the tax class for positions issued as ZW (exempt) in Fakturownia. This is separate from the 0% rate (zero rate) and NP.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'NP Tax Rate', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<select name="<?php echo esc_attr( self::OPTION_NAME ); ?>[tax_class_np]">
								<option value="_none_" <?php selected( $this->get_option( 'tax_class_np' ), '_none_' ); ?>><?php echo esc_html__( 'None', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></option>
								<?php foreach ( $tax_classes as $slug => $name ) : ?>
									<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $this->get_option( 'tax_class_np' ), $slug ); ?>>
										<?php echo esc_html( $name ); ?>
									</option>
								<?php endforeach; ?>
							</select>
							<p class="description"><?php echo esc_html__( 'Select tax class for non-VAT products (np).', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
						</td>
					</tr>
					<?php 
					/**
					 * Hook for PRO to add OSS/MOSS tax classes selection after VAT mapping
					 */
					if ( class_exists( 'Devikit\FakturowniaPro\Plugin' ) ) {
						do_action( 'devikit_fakturownia_settings_after_vat_mapping' );
					}
					?>
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'VAT Exemption Legal Basis', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<input type="text" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[vat_exemption_basis]" 
								   value="<?php echo esc_attr( $this->get_option( 'vat_exemption_basis' ) ); ?>" 
								   class="regular-text" />
							<p class="description"><?php echo esc_html__( 'Legal basis for VAT exemption (e.g., "Art. 43 ust. 1 pkt 29 ustawy o VAT"). This will be included in invoices with ZW rate.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
						</td>
					</tr>
					<?php if ( ! wc_tax_enabled() ) : ?>
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'Document Type (No VAT)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<?php if ( $this->is_pro_available() ) : ?>
								<!-- PRO plugin installed - show select -->
								<select name="<?php echo esc_attr( self::OPTION_NAME ); ?>[no_vat_document_type]">
									<option value="bill" <?php selected( $this->get_option( 'no_vat_document_type', 'invoice_without_vat' ), 'bill' ); ?>>
										<?php echo esc_html__( 'Rachunek', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
									</option>
									<option value="invoice_without_vat" <?php selected( $this->get_option( 'no_vat_document_type', 'invoice_without_vat' ), 'invoice_without_vat' ); ?>>
										<?php echo esc_html__( 'Invoice Without VAT (Faktura bez VAT)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
									</option>
								</select>
								<p class="description"><?php echo esc_html__( 'Select document type to use when WooCommerce taxes are disabled.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
							<?php else : ?>
								<!-- FREE version - show fixed value with upgrade notice -->
								<input type="text" value="<?php echo esc_attr__( 'Invoice Without VAT (Faktura bez VAT)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>" class="regular-text" readonly style="background-color: #f0f0f0;" />
								<p class="description">
									<?php echo esc_html__( 'In PRO version you can choose between "Rachunek" and "Invoice Without VAT (Faktura bez VAT)".', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</p>
								<div class="fakturownia-wc-pro-notice" style="margin-top: 10px;">
									<p>
										<a href="https://devikit.pl/produkt/fakturownia-woocommerce-pro/" target="_blank" class="button button-primary">
											<?php esc_html_e( 'Buy Pro Version', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
										</a>
									</p>
								</div>
							<?php endif; ?>
						</td>
					</tr>
					<?php endif; ?>
				</table>

				<h3 style="margin-top:30px;"><?php echo esc_html__( 'Invoice Details', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h3>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'Payment Deadline', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<input type="number" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[payment_deadline_days]" 
								   value="<?php echo esc_attr( $this->get_option( 'payment_deadline_days', '7' ) ); ?>" 
								   min="0" max="60" style="width:60px;" />
							<?php echo esc_html__( 'days', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
							<p class="description"><?php echo esc_html__( 'Set default number of days for invoice payment.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'Issue Place', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<input type="text" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[invoice_issue_place]" 
								   value="<?php echo esc_attr( $this->get_option( 'invoice_issue_place' ) ); ?>" 
								   class="regular-text" />
							<p class="description"><?php echo esc_html__( 'Place where invoice is issued (e.g., "Warsaw", "Kraków").', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'Invoice Language', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<?php
							$invoice_language_mode = $this->get_option( 'invoice_language_mode', 'customer_country' );
							$invoice_language_selected = $this->get_option( 'invoice_language_selected', 'pl' );
							
							$language_options = [
								'pl'    => __( 'Polish', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'en'    => __( 'English', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'en-GB' => __( 'English (GB)', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'ar'    => __( 'Arabic', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'cn'    => __( 'Chinese', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'cz'    => __( 'Czech', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'de'    => __( 'German', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'es'    => __( 'Spanish', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'et'    => __( 'Estonian', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'fa'    => __( 'Persian', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'fr'    => __( 'French', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'hu'    => __( 'Hungarian', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'hr'    => __( 'Croatian', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'it'    => __( 'Italian', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'nl'    => __( 'Dutch', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'ru'    => __( 'Russian', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'sk'    => __( 'Slovak', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'sl'    => __( 'Slovenian', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
								'tr'    => __( 'Turkish', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
							];
							?>
							<label style="display:block; margin-bottom:10px;">
								<input type="radio" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[invoice_language_mode]" value="customer_country" <?php checked( $invoice_language_mode, 'customer_country' ); ?> />
								<?php echo esc_html__( 'Customer country language', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
							</label>
							<label style="display:block; margin-bottom:10px;">
								<input type="radio" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[invoice_language_mode]" value="select" <?php checked( $invoice_language_mode, 'select' ); ?> />
								<?php echo esc_html__( 'Select other language', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
							</label>
							<div id="invoice-language-select-wrapper" style="margin-top: 10px; margin-left: 25px; <?php echo $invoice_language_mode !== 'select' ? 'display:none;' : ''; ?>">
								<select name="<?php echo esc_attr( self::OPTION_NAME ); ?>[invoice_language_selected]" id="invoice-language-select">
									<?php foreach ( $language_options as $code => $name ) : ?>
										<option value="<?php echo esc_attr( $code ); ?>" <?php selected( $invoice_language_selected, $code ); ?>>
											<?php echo esc_html( $name ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<p class="description" style="margin-top:10px;">
								<?php echo esc_html__( 'Choose how invoice language should be determined. Customer country language will use the language based on customer billing country.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'Invoice Notes', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<textarea name="<?php echo esc_attr( self::OPTION_NAME ); ?>[invoice_notes]" rows="5" class="large-text"><?php echo esc_textarea( $this->get_option( 'invoice_notes' ) ); ?></textarea>
							<p class="description">
								<?php echo esc_html__( 'Default notes that will be added to all invoices. You can override this per order.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								<br>
								<strong><?php echo esc_html__( 'Available variables:', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong>
								{order_id}, {order_number}, {order_date}, {order_date_raw}, {customer_name}, {customer_first_name}, {customer_last_name}, {customer_email}, {customer_phone}, {billing_company}, {billing_nip}, {billing_address}, {shipping_address}, {order_total}, {order_total_formatted}, {payment_method}, {shipping_method}
							</p>
						</td>
					</tr>
				</table>

				<h3 style="margin-top:30px;"><?php echo esc_html__( 'Lump Sum (Ryczałt)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h3>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'Enable Lump Sum', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[lump_sum_enable]" value="yes" <?php checked( $this->get_option( 'lump_sum_enable' ), 'yes' ); ?> />
								<?php echo esc_html__( 'I am a lump sum taxpayer', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
							</label>
							<p class="description"><?php echo esc_html__( 'Enable lump sum tax calculation for invoices.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'Default Lump Sum Rate', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<select name="<?php echo esc_attr( self::OPTION_NAME ); ?>[lump_sum_default_value]">
								<option value="empty" <?php selected( $this->get_option( 'lump_sum_default_value', 'empty' ), 'empty' ); ?>><?php echo esc_html__( 'Select...', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></option>
								<option value="3" <?php selected( $this->get_option( 'lump_sum_default_value', 'empty' ), '3' ); ?>>3%</option>
								<option value="5.5" <?php selected( $this->get_option( 'lump_sum_default_value', 'empty' ), '5.5' ); ?>>5,5%</option>
								<option value="8.5" <?php selected( $this->get_option( 'lump_sum_default_value', 'empty' ), '8.5' ); ?>>8,5%</option>
								<option value="10" <?php selected( $this->get_option( 'lump_sum_default_value', 'empty' ), '10' ); ?>>10%</option>
								<option value="12" <?php selected( $this->get_option( 'lump_sum_default_value', 'empty' ), '12' ); ?>>12%</option>
								<option value="12.5" <?php selected( $this->get_option( 'lump_sum_default_value', 'empty' ), '12.5' ); ?>>12,5%</option>
								<option value="14" <?php selected( $this->get_option( 'lump_sum_default_value', 'empty' ), '14' ); ?>>14%</option>
								<option value="15" <?php selected( $this->get_option( 'lump_sum_default_value', 'empty' ), '15' ); ?>>15%</option>
								<option value="17" <?php selected( $this->get_option( 'lump_sum_default_value', 'empty' ), '17' ); ?>>17%</option>
							</select>
							<p class="description"><?php echo esc_html__( 'Default lump sum rate. This can be overridden per product.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
						</td>
					</tr>
				</table>

				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render checkout tab
	 */
	private function render_checkout_tab() {
		?>
		<div class="fakturownia-wc-settings-section">
			<h2><?php echo esc_html__( 'Store Settings (Checkout)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'devikit_fakturownia_group' ); ?>
				<input type="hidden" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[_tab]" value="checkout" />
				
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'VAT Field Visibility', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<label style="display:block; margin-bottom:10px;">
								<input type="radio" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[nip_display_mode]" value="always" <?php checked( $this->get_option( 'nip_display_mode', 'always' ), 'always' ); ?> />
								<?php echo esc_html__( 'Always visible', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								<p class="description" style="margin-top:2px;"><?php echo esc_html__( 'The VAT field is always visible at checkout.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
							</label>
							<label style="display:block;">
								<input type="radio" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[nip_display_mode]" value="checkbox" <?php checked( $this->get_option( 'nip_display_mode' ), 'checkbox' ); ?> />
								<?php echo esc_html__( 'Show "I want an invoice" checkbox', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								<p class="description" style="margin-top:2px;"><?php echo esc_html__( 'The VAT field is hidden until the user checks the option.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
								<p class="description" style="margin-top:5px; color:#856404; background:#fff3cd; padding:8px; border-left:3px solid #ffc107;">
									<strong><?php echo esc_html__( 'Note:', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong> 
									<?php echo esc_html__( 'This option works only on classic checkout. In WooCommerce Gutenberg Blocks, the VAT field is always visible.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</p>
							</label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'Checkbox Label', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<?php
							$checkbox_label_value = $this->get_option( 'invoice_checkbox_label', '' );
							$placeholder = __( 'I want an invoice', 'invoicing-integration-for-fakturownia-and-woocommerce' );
							?>
							<input type="text" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[invoice_checkbox_label]" 
								   value="<?php echo esc_attr( $checkbox_label_value ); ?>" 
								   placeholder="<?php echo esc_attr( $placeholder ); ?>"
								   class="regular-text" />
							<p class="description">
								<?php 
								printf(
									/* translators: %s: Default placeholder value */
									esc_html__( 'Leave empty to use default: "%s"', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
									esc_html( $placeholder )
								);
								?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'VAT Field Required', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[nip_required]" value="yes" <?php checked( $this->get_option( 'nip_required' ), 'yes' ); ?> />
								<?php echo esc_html__( 'VAT field is required in the order form', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
							</label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo esc_html__( 'VAT Format Validation', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[validate_nip_format]" value="yes" <?php checked( $this->get_option( 'validate_nip_format', 'yes' ), 'yes' ); ?> />
								<?php echo esc_html__( 'Check if VAT has exactly 10 digits', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
							</label>
							<p class="description"><?php echo esc_html__( 'If this option is enabled, the plugin will check if the entered NIP has exactly 10 digits.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
						</td>
					</tr>
				</table>

				<?php 
				/**
				 * Hook for PRO to add OSS/MOSS settings and VIES validation
				 */
				if ( class_exists( 'Devikit\FakturowniaPro\Plugin' ) ) {
					do_action( 'devikit_fakturownia_settings_before_vies' );
					do_action( 'devikit_fakturownia_settings_after_vies' );
				} else {
					// PRO not installed - show disabled PRO features
					?>
					<h3 style="margin-top:30px;"><?php esc_html_e( 'VIES VAT Validation (EU)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
						<span style="background: #ffc107; color: #856404; font-size: 11px; padding: 2px 8px; border-radius: 3px; margin-left: 10px; font-weight: normal;"><?php esc_html_e( 'PRO', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></span>
					</h3>
					<p class="description" style="margin-bottom:15px;">
						<?php esc_html_e( 'Validate VAT numbers in EU VIES database.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
					</p>
					<table class="form-table" style="opacity: 0.5;">
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Enable VIES Validation', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label>
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Validate VAT numbers using EU VIES database', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
					</table>
					
					<h3 style="margin-top:30px;"><?php esc_html_e( 'OSS/MOSS Support', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
						<span style="background: #ffc107; color: #856404; font-size: 11px; padding: 2px 8px; border-radius: 3px; margin-left: 10px; font-weight: normal;"><?php esc_html_e( 'PRO', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></span>
					</h3>
					<p class="description" style="margin-bottom:15px;">
						<?php esc_html_e( 'OSS (One Stop Shop) procedure for EU VAT handling.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
					</p>
					<table class="form-table" style="opacity: 0.5;">
						<tr>
							<th><?php esc_html_e( 'Enable OSS/MOSS', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label>
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Enable OSS/MOSS support', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
					</table>
					
					<h3 style="margin-top:30px;"><?php esc_html_e( 'Reverse Charge (B2B EU)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
						<span style="background: #ffc107; color: #856404; font-size: 11px; padding: 2px 8px; border-radius: 3px; margin-left: 10px; font-weight: normal;"><?php esc_html_e( 'PRO', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></span>
					</h3>
					<table class="form-table" style="opacity: 0.5;">
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Enable Reverse Charge', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label>
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Enable automatic VAT removal for B2B EU transactions', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
					</table>
					
					<div class="fakturownia-wc-pro-notice" style="margin-top: 20px;">
						<p>
							<?php esc_html_e( 'VIES VAT validation, OSS/MOSS support, and Reverse Charge are available in PRO version.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
						</p>
						<p>
							<a href="https://devikit.pl/produkt/fakturownia-woocommerce-pro/" target="_blank" class="button button-primary">
								<?php esc_html_e( 'Buy Pro Version', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
							</a>
						</p>
					</div>
					<?php
				}
				?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Check if Pro version is installed (regardless of license status)
	 * License validation is handled by the PRO plugin itself (grayed out UI)
	 *
	 * @return bool
	 */
	private function is_pro_available() {
		return class_exists( 'Devikit\FakturowniaPro\Plugin' );
	}

	/**
	 * Render automation tab content
	 */
	private function render_automation_tab() {
		if ( $this->is_pro_available() ) {
			do_action( 'devikit_fakturownia_render_tab_automation' );
			return;
		}
		
		$statuses = wc_get_order_statuses();
		unset( $statuses['wc-checkout-draft'] );
		
		?>
		<div class="fakturownia-wc-settings-section">
			<h2><?php esc_html_e( 'Automation Settings', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h2>
			
			<div class="fakturownia-wc-pro-notice">
				<p><?php esc_html_e( 'Automation features are available in Pro version.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
				<p>
					<a href="https://devikit.pl/produkt/fakturownia-woocommerce-pro/" target="_blank" class="button button-primary">
						<?php esc_html_e( 'Buy Pro Version', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
					</a>
				</p>
			</div>
			
			<div class="fakturownia-wc-pro-features">
				<h3><?php esc_html_e( 'Automatic Invoice Issuing', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h3>
				<table class="form-table" style="opacity: 0.5;">
					<tbody>
						<tr>
							<th><?php esc_html_e( 'Enable Automation', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label>
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Automatically issue invoices', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Issuing Condition', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label style="display:block; margin-bottom:10px;">
									<input type="radio" disabled checked />
									<?php esc_html_e( 'For all orders', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
								<label style="display:block; margin-bottom:10px;">
									<input type="radio" disabled />
									<?php esc_html_e( 'Only when NIP is provided', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
								<label style="display:block;">
									<input type="radio" disabled />
									<?php esc_html_e( 'Only when NIP is NOT provided', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Order Status', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<?php
								$count = 0;
								foreach ( $statuses as $status => $label ) :
									$checked = ( $count === 0 );
									$count++;
								?>
									<label style="display: block; margin-bottom: 5px;">
										<input type="checkbox" disabled <?php echo $checked ? 'checked' : ''; ?> />
										<?php echo esc_html( $label ); ?>
									</label>
								<?php endforeach; ?>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Send Email', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label>
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Automatically send invoice by email to customer', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
					</tbody>
				</table>

				<h3 style="margin-top: 30px;"><?php esc_html_e( 'Automatic Proforma Issuing', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h3>
				<table class="form-table" style="opacity: 0.5;">
					<tbody>
						<tr>
							<th><?php esc_html_e( 'Enable Automation', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label>
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Automatically issue proforma invoices', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Issuing Condition', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label style="display:block; margin-bottom:10px;">
									<input type="radio" disabled checked />
									<?php esc_html_e( 'For all orders', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
								<label style="display:block; margin-bottom:10px;">
									<input type="radio" disabled />
									<?php esc_html_e( 'Only when NIP is provided', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
								<label style="display:block;">
									<input type="radio" disabled />
									<?php esc_html_e( 'Only when NIP is NOT provided', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Order Status', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<?php
								$count = 0;
								foreach ( $statuses as $status => $label ) :
									$checked = ( $count === 0 );
									$count++;
								?>
									<label style="display: block; margin-bottom: 5px;">
										<input type="checkbox" disabled <?php echo $checked ? 'checked' : ''; ?> />
										<?php echo esc_html( $label ); ?>
									</label>
								<?php endforeach; ?>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Send Email', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label>
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Automatically send proforma by email to customer', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
					</tbody>
				</table>
				
				<h3 style="margin-top: 30px;"><?php esc_html_e( 'Automatic Receipt Issuing', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h3>
				<table class="form-table" style="opacity: 0.5;">
					<tbody>
						<tr>
							<th><?php esc_html_e( 'Enable Automation', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label>
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Automatically issue receipts', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Issuing Condition', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label style="display:block; margin-bottom:10px;">
									<input type="radio" disabled checked />
									<?php esc_html_e( 'For all orders', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
								<label style="display:block; margin-bottom:10px;">
									<input type="radio" disabled />
									<?php esc_html_e( 'Only when NIP is provided', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
								<label style="display:block;">
									<input type="radio" disabled />
									<?php esc_html_e( 'Only when NIP is NOT provided', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Order Status', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<?php
								$count = 0;
								foreach ( $statuses as $status => $label ) :
									$checked = ( $count === 0 );
									$count++;
								?>
									<label style="display: block; margin-bottom: 5px;">
										<input type="checkbox" disabled <?php echo $checked ? 'checked' : ''; ?> />
										<?php echo esc_html( $label ); ?>
									</label>
								<?php endforeach; ?>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Send Email', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label>
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Automatically send receipt by email to customer', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
					</tbody>
				</table>
				
				<h3 style="margin-top: 30px;"><?php esc_html_e( 'Automatic Correction Issuing', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h3>
				<table class="form-table" style="opacity: 0.5;">
					<tbody>
						<tr>
							<th><?php esc_html_e( 'Enable Automation', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label>
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Automatically issue corrections on refund', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Order Status', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label style="display: block; margin-bottom: 5px;">
									<input type="checkbox" disabled checked />
									<?php esc_html_e( 'Refunded', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
								<label style="display: block; margin-bottom: 5px;">
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Cancelled', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Send Email', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label>
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Automatically send correction by email to customer', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
					</tbody>
				</table>
				
				<h3 style="margin-top: 30px;"><?php esc_html_e( 'Automatic Bill Issuing (without VAT)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h3>
				<table class="form-table" style="opacity: 0.5;">
					<tbody>
						<tr>
							<th><?php esc_html_e( 'Enable Automation', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label>
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Automatically issue bills', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Issuing Condition', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label style="display:block; margin-bottom:10px;">
									<input type="radio" disabled checked />
									<?php esc_html_e( 'For all orders', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
								<label style="display:block; margin-bottom:10px;">
									<input type="radio" disabled />
									<?php esc_html_e( 'Only when NIP is provided', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
								<label style="display:block;">
									<input type="radio" disabled />
									<?php esc_html_e( 'Only when NIP is NOT provided', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Order Status', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<?php
								$count = 0;
								foreach ( $statuses as $status => $label ) :
									$checked = ( $count === 0 );
									$count++;
								?>
									<label style="display: block; margin-bottom: 5px;">
										<input type="checkbox" disabled <?php echo $checked ? 'checked' : ''; ?> />
										<?php echo esc_html( $label ); ?>
									</label>
								<?php endforeach; ?>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Send Email', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label>
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Automatically send bill by email to customer', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}

	/**
	 * Render receipts tab content
	 */
	private function render_receipts_tab() {
		if ( $this->is_pro_available() ) {
			do_action( 'devikit_fakturownia_render_tab_receipts' );
			return;
		}
		
		?>
		<div class="fakturownia-wc-settings-section">
			<h2><?php esc_html_e( 'Receipt (Paragon) Settings', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h2>
			
			<div class="fakturownia-wc-pro-notice">
				<p><?php esc_html_e( 'Receipt features are available in Pro version.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
				<p>
					<a href="https://devikit.pl/produkt/fakturownia-woocommerce-pro/" target="_blank" class="button button-primary">
						<?php esc_html_e( 'Buy Pro Version', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
					</a>
				</p>
			</div>
			
			<div class="fakturownia-wc-pro-features">
				<table class="form-table" style="opacity: 0.5;">
					<tbody>
						<tr>
							<th><?php esc_html_e( 'Default Payment Deadline (days)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<input type="number" disabled value="7" class="small-text" />
								<p class="description"><?php esc_html_e( 'Default payment deadline in days for receipts.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Receipt Language', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label style="display:block; margin-bottom:10px;">
									<input type="radio" disabled checked />
									<?php esc_html_e( 'Customer country language', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
								<label style="display:block; margin-bottom:10px;">
									<input type="radio" disabled />
									<?php esc_html_e( 'Select other language', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
								<p class="description" style="margin-top:10px;">
									<?php esc_html_e( 'Choose how receipt language should be determined.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Default Notes', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<textarea disabled rows="3" class="large-text"></textarea>
								<p class="description">
									<?php esc_html_e( 'Default notes to include on receipts.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}

	/**
	 * Render warehouse tab content
	 */
	private function render_warehouse_tab() {
		if ( $this->is_pro_available() ) {
			do_action( 'devikit_fakturownia_render_tab_warehouse' );
			return;
		}
		
		?>
		<div class="fakturownia-wc-settings-section">
			<h2><?php esc_html_e( 'Warehouse Integration (Stock Sync)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h2>
			
			<div class="fakturownia-wc-pro-notice">
				<p><?php esc_html_e( 'Warehouse synchronization features are available in Pro version.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></p>
				<p>
					<a href="https://devikit.pl/produkt/fakturownia-woocommerce-pro/" target="_blank" class="button button-primary">
						<?php esc_html_e( 'Buy Pro Version', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
					</a>
				</p>
			</div>
			
			<div class="fakturownia-wc-pro-features">
				<p class="description">
					<?php esc_html_e( 'Synchronize product stock levels between WooCommerce and Fakturownia warehouse. Changes in Fakturownia will be reflected in WooCommerce in real-time via webhook.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
				</p>
				
				<table class="form-table" style="opacity: 0.5;">
					<tbody>
						<tr>
							<th><?php esc_html_e( 'Stock Synchronization', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label>
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Enable real-time stock synchronization via webhook', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
								<p class="description">
									<?php esc_html_e( 'When enabled, stock changes in Fakturownia will be automatically synchronized to WooCommerce via webhook (real-time).', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Warehouse', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<select disabled style="width: 300px;">
									<option><?php esc_html_e( '-- Select warehouse --', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></option>
								</select>
								<p class="description">
									<?php esc_html_e( 'Select the warehouse in Fakturownia to synchronize stock levels from. This warehouse will also be used when creating invoices.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Sync Prices', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<label>
									<input type="checkbox" disabled />
									<?php esc_html_e( 'Also sync product prices', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</label>
								<p class="description">
									<?php esc_html_e( 'When enabled, price changes will be synchronized in both directions: from Fakturownia to WooCommerce (via webhook) and from WooCommerce to Fakturownia (when product is saved).', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>

				<h3 style="margin-top: 30px;"><?php esc_html_e( 'Webhook Configuration', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h3>
				<p class="description" style="margin-bottom: 20px;">
					<?php esc_html_e( 'To configure webhook in Fakturownia, go to Ustawienia konta → Integracja → Webhooki and create a new webhook. Set the type (rodzaj) to "product:update" and add the values from the "Adres" and "Api token" fields below.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
				</p>
				<table class="form-table" style="opacity: 0.5;">
					<tbody>
						<tr>
							<th><?php esc_html_e( 'Adres', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<input type="text" class="regular-text" disabled value="<?php echo esc_attr( rest_url( 'devikit-fakturownia/v1/warehouse/sync' ) ); ?>" />
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Api token', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
							<td>
								<div style="display: flex; gap: 10px; align-items: center;">
									<input type="text" class="regular-text" disabled value="••••••••••••••••" />
									<button type="button" class="button" disabled><?php esc_html_e( 'Generate New Token', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></button>
								</div>
								<p class="description">
									<?php esc_html_e( 'Security token to protect access to the stock update function. Change this token only if there was a data breach or security incident and you need to set a new token for security purposes.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}

	/**
	 * Render advanced tab content
	 */
	private function render_advanced_tab() {
		?>
		<div class="fakturownia-wc-settings-section">
			<form method="post" action="options.php">
				<?php settings_fields( 'devikit_fakturownia_group' ); ?>
				<input type="hidden" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[_tab]" value="advanced" />
				<input type="hidden" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[_advanced_tab]" value="1" />
				
				<h2><?php esc_html_e( 'Logs and Debugging', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></h2>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Detailed Logs', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[enable_debug_logging]" value="yes" <?php checked( $this->get_option( 'enable_debug_logging' ), 'yes' ); ?> />
								<?php esc_html_e( 'Enable detailed logging to debug file', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
							</label>
							<p class="description">
								<?php 
								printf(
									/* translators: %s: log file name */
									esc_html__( 'Logs will be saved in: WooCommerce → Status → Logs → %s', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
									'<code>devikit_fakturownia_debug</code>'
								);
								?>
							</p>
						</td>
					</tr>
				</table>
				
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * AJAX Test Connection
	 */
	public function ajax_test_connection() {
		check_ajax_referer( 'fakturownia_test_connection', 'nonce' );
		
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Unauthorized', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		}

		$api_token = isset( $_POST['api_token'] ) ? sanitize_text_field( wp_unslash( $_POST['api_token'] ) ) : '';
		$subdomain = isset( $_POST['subdomain'] ) ? sanitize_text_field( wp_unslash( $_POST['subdomain'] ) ) : '';
		
		if ( empty( $api_token ) || empty( $subdomain ) ) {
			wp_send_json_error( [ 'message' => __( 'API Token and Subdomain are required.', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		}

		$client = new Client( $api_token, $subdomain );
		if ( $client->test_connection() ) {
			wp_send_json_success( [ 'message' => __( 'Connection successful!', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		} else {
			wp_send_json_error( [ 'message' => __( 'Connection failed. Check your credentials.', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		}
	}

	/**
	 * Display tax rounding notice
	 */
	private function display_tax_rounding_notice() {
		$tax_settings_url = admin_url( 'admin.php?page=wc-settings&tab=tax' );
		
		// Only show this notice if taxes are enabled
		if ( ! wc_tax_enabled() ) {
			return;
		}
		
		// Check if prices include tax
		$prices_include_tax = get_option( 'woocommerce_prices_include_tax', 'no' );
		if ( $prices_include_tax === 'yes' ) {
			?>
			<div class="notice notice-error" style="margin: 20px 0;">
				<p>
					<strong><?php esc_html_e( 'Important:', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong>
				<?php 
				printf(
					/* translators: %s: Link to WooCommerce Tax settings */
					esc_html__( 'WooCommerce is configured to enter prices with tax included. For proper invoice generation, it is necessary to set "Enter prices with tax" to "No, I will enter prices excluding tax". Please change this setting in %s.', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
					'<a href="' . esc_url( $tax_settings_url ) . '">' . esc_html__( 'WooCommerce → Settings → Tax', 'invoicing-integration-for-fakturownia-and-woocommerce' ) . '</a>'
				);
					?>
				</p>
			</div>
			<?php
		}
		
		// Check if price decimals is set to 2
		$price_decimals = intval( get_option( 'woocommerce_price_num_decimals', 2 ) );
		if ( $price_decimals != 2 ) {
			$general_settings_url = admin_url( 'admin.php?page=wc-settings' );
			?>
			<div class="notice notice-error" style="margin: 20px 0;">
				<p>
					<strong><?php esc_html_e( 'Important:', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong>
				<?php 
				printf(
					/* translators: 1: Number of decimal places, 2: Link to WooCommerce Settings */
					esc_html__( 'The number of decimal places is set to %1$d. For proper invoice generation, it is necessary to set this value to 2. Please change it in %2$s.', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
					esc_html( $price_decimals ),
					'<a href="' . esc_url( $general_settings_url ) . '">' . esc_html__( 'WooCommerce → Settings', 'invoicing-integration-for-fakturownia-and-woocommerce' ) . '</a>'
				);
					?>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * Display Pro upgrade banner
	 */
	public function display_pro_upgrade_banner() {
		if ( $this->is_pro_available() ) {
			return;
		}
		
		$dismissed = get_option( 'devikit_fakturownia_banner_dismissed', false );
		$remind_later = get_option( 'devikit_fakturownia_banner_remind_later', 0 );
		
		if ( $dismissed === 'permanent' ) {
			return;
		}
		
		if ( $remind_later > time() ) {
			return;
		}
		
		// Enqueue banner scripts
		wp_enqueue_script( 'devikit-fakturownia-banner', DEVIKIT_FAKTUROWNIA_URL . 'assets/js/admin-banner.js', array( 'jquery' ), '1.0.0', true );
		wp_localize_script( 'devikit-fakturownia-banner', 'devikitFakturowniaWcBanner', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'devikit_fakturownia_banner_nonce' )
		) );
		
		?>
		<div class="notice notice-info is-dismissible devikit-fakturownia-pro-banner" style="position: relative; padding: 15px 40px 15px 15px;">
			<div style="display: flex; align-items: center; gap: 20px;">
				<div style="flex: 1;">
					<h3 style="margin: 0 0 10px 0; font-size: 16px;">
						<?php esc_html_e( 'Automate your invoicing with Fakturownia WooCommerce PRO!', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
					</h3>
					<p style="margin: 0 0 10px 0; font-size: 14px;">
						<?php esc_html_e( 'Save time and streamline your accounting process - let invoices be created automatically.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
					</p>
					<div style="margin: 15px 0;">
						<strong><?php esc_html_e( 'Features available in Pro version:', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></strong>
						<ul style="margin: 10px 0 0 20px; list-style-type: disc;">
							<li><?php esc_html_e( 'Automatic invoice generation after status change', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></li>
							<li><?php esc_html_e( 'Proforma invoice support', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></li>
							<li><?php esc_html_e( 'Receipts (paragony)', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></li>
							<li><?php esc_html_e( 'Warehouse synchronization with Fakturownia', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></li>
							<li><?php esc_html_e( 'Bulk document generation and email sending', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></li>
							<li><?php esc_html_e( 'Email notifications with PDF attachments', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></li>
							<li><?php esc_html_e( 'Corrections (korygujące) support', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></li>
							<li><?php esc_html_e( 'OSS/MOSS support for digital services', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></li>
							<li><?php esc_html_e( 'GTU codes and PKWiU support', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></li>
						</ul>
					</div>
					<div style="margin-top: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
						<a href="https://devikit.pl/produkt/fakturownia-woocommerce-pro/" target="_blank" class="button button-primary" style="margin-right: 10px;">
							<?php esc_html_e( 'Buy Pro Version', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
						</a>
						<button type="button" class="button button-secondary devikit-fakturownia-banner-remind-later">
							<?php esc_html_e( 'Remind me later', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
						</button>
						<button type="button" class="button button-link devikit-fakturownia-banner-dismiss-permanent" style="color: #a00;">
							<?php esc_html_e( 'Don\'t remind me anymore', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?>
						</button>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	
	/**
	 * AJAX handler for dismissing banner
	 */
	public function ajax_dismiss_banner() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'devikit_fakturownia_banner_nonce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
			return;
		}
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'Insufficient permissions', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
			return;
		}
		
		$action = isset( $_POST['action_type'] ) ? sanitize_text_field( wp_unslash( $_POST['action_type'] ) ) : '';
		
		if ( $action === 'remind_later' ) {
			update_option( 'devikit_fakturownia_banner_remind_later', time() + ( 30 * DAY_IN_SECONDS ) );
			delete_option( 'devikit_fakturownia_banner_dismissed' );
			wp_send_json_success( [ 'message' => __( 'Banner will be shown again in 30 days', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		} elseif ( $action === 'dismiss_permanent' ) {
			update_option( 'devikit_fakturownia_banner_dismissed', 'permanent' );
			delete_option( 'devikit_fakturownia_banner_remind_later' );
			wp_send_json_success( [ 'message' => __( 'Banner permanently dismissed', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		} else {
			wp_send_json_error( [ 'message' => __( 'Invalid action', 'invoicing-integration-for-fakturownia-and-woocommerce' ) ] );
		}
	}
}

