<?php

namespace Devikit\Fakturownia\Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Client {

	/**
	 * API Token
	 * 
	 * @var string
	 */
	private $api_token;

	/**
	 * Subdomain
	 * 
	 * @var string
	 */
	private $subdomain;

	/**
	 * Base API URL
	 * 
	 * @var string
	 */
	private $api_url;

	/**
	 * Constructor
	 * 
	 * @param string $api_token Fakturownia API Token
	 * @param string $subdomain Fakturownia subdomain
	 */
	public function __construct( $api_token, $subdomain ) {
		$this->api_token = $api_token;
		$this->subdomain = $subdomain;
		$this->api_url = 'https://' . $subdomain . '.fakturownia.pl/';
	}

	/**
	 * Test connection to Fakturownia API
	 * 
	 * @return bool
	 */
	public function test_connection() {
		try {
			$response = $this->get( 'invoices.json', [ 'page' => 1, 'per_page' => 1 ] );
			return ! is_wp_error( $response );
		} catch ( \Exception $e ) {
			return false;
		}
	}

	/**
	 * Get client by tax ID (NIP)
	 * 
	 * @param string $tax_no Tax ID (NIP)
	 * @return array|null
	 */
	public function get_client_by_tax_no( $tax_no ) {
		$response = $this->get( 'clients.json', [
			'tax_no' => $tax_no,
			'per_page' => 1,
		] );

		if ( is_wp_error( $response ) ) {
			$this->log( 'get_client_by_tax_no ERROR: ' . $response->get_error_message(), 'error' );
			return null;
		}

		if ( ! empty( $response ) && is_array( $response ) && isset( $response[0] ) ) {
			return $response[0];
		}

		return null;
	}

	/**
	 * Find client by multiple fields
	 * 
	 * @param array $client_data Client data
	 * @return array|null Client data or null if not found
	 */
	public function find_client( $client_data ) {
		$params = [
			'per_page' => 1,
		];

		if ( ! empty( $client_data['name'] ) ) {
			$params['name'] = $client_data['name'];
		}
		if ( ! empty( $client_data['tax_no'] ) ) {
			$params['tax_no'] = $client_data['tax_no'];
		}

		$response = $this->get( 'clients.json', $params );

		if ( is_wp_error( $response ) ) {
			$this->log( 'find_client ERROR: ' . $response->get_error_message(), 'error' );
			return null;
		}

		if ( ! empty( $response ) && is_array( $response ) ) {
			// Try to match by name and tax_no if provided
			foreach ( $response as $client ) {
				$match = true;
				if ( ! empty( $client_data['name'] ) && isset( $client['name'] ) && $client['name'] !== $client_data['name'] ) {
					$match = false;
				}
				if ( ! empty( $client_data['tax_no'] ) && isset( $client['tax_no'] ) && $client['tax_no'] !== $client_data['tax_no'] ) {
					$match = false;
				}
				if ( $match ) {
					return $client;
				}
			}
		}

		return null;
	}

	/**
	 * Create client
	 * 
	 * @param array $data Client data
	 * @return array|WP_Error
	 */
	public function create_client( $data ) {
		$params = [
			'api_token' => $this->api_token,
			'client' => $data,
		];

		return $this->post( 'clients.json', $params );
	}

	/**
	 * Update client
	 * 
	 * @param int $client_id Client ID
	 * @param array $data Client data
	 * @return array|WP_Error
	 */
	public function update_client( $client_id, $data ) {
		$this->log( 'Updating client ID: ' . $client_id . ' with data: ' . wp_json_encode( $data, JSON_UNESCAPED_UNICODE ), 'debug' );
		
		$params = [
			'api_token' => $this->api_token,
			'client' => array_merge( [ 'id' => $client_id ], $data ),
		];

		$result = $this->put( 'clients/' . $client_id . '.json', $params );
		
		if ( is_wp_error( $result ) ) {
			$this->log( 'Client update ERROR: ' . $result->get_error_message(), 'error' );
		} else {
			$this->log( 'Client update SUCCESS', 'debug' );
		}
		
		return $result;
	}

	/**
	 * Create invoice
	 * 
	 * @param array $data Invoice data
	 * @return array|WP_Error
	 */
	public function create_invoice( $data ) {
		$params = [
			'api_token' => $this->api_token,
			'invoice' => $data,
		];

		$this->log( 'Creating invoice with data: ' . wp_json_encode( $params, JSON_UNESCAPED_UNICODE ), 'debug' );

		return $this->post( 'invoices.json', $params );
	}

	/**
	 * Get invoice
	 * 
	 * @param int $invoice_id Invoice ID
	 * @return array|WP_Error
	 */
	public function get_invoice( $invoice_id ) {
		return $this->get( 'invoices/' . $invoice_id . '.json' );
	}

	/**
	 * Download invoice PDF
	 * 
	 * @param int $invoice_id Invoice ID
	 * @return string|WP_Error PDF content or error
	 */
	public function download_invoice_pdf( $invoice_id ) {
		if ( empty( $this->api_token ) || empty( $this->subdomain ) ) {
			return new \WP_Error( 'no_credentials', __( 'API token and subdomain are required', 'invoicing-integration-for-fakturownia-and-woocommerce' ) );
		}

		$url = $this->api_url . 'invoices/' . $invoice_id . '.pdf?api_token=' . urlencode( $this->api_token );
		
		$args = [
			'timeout'   => 30,
			'sslverify' => true,
		];

		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) ) {
			$this->log( 'PDF Download Error: ' . $response->get_error_message(), 'error' );
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$content_type = wp_remote_retrieve_header( $response, 'content-type' );
		
		if ( $code !== 200 ) {
			$body = wp_remote_retrieve_body( $response );
			$this->log( 'PDF Download Error. Status: ' . $code . ', Body: ' . substr( $body, 0, 500 ), 'error' );
			/* translators: %d: HTTP status code */
			return new \WP_Error( 'pdf_download_failed', sprintf( __( 'Failed to download PDF. Status: %d', 'invoicing-integration-for-fakturownia-and-woocommerce' ), $code ) );
		}

		// Check if response is actually PDF
		if ( strpos( $content_type, 'application/pdf' ) !== false ) {
			return wp_remote_retrieve_body( $response );
		}
		
		// Might be JSON error
		$body = wp_remote_retrieve_body( $response );
		$error_data = json_decode( $body, true );
		if ( isset( $error_data['message'] ) ) {
			$error_message = $error_data['message'];
			$this->log( 'PDF Download API Error: ' . $error_message, 'error' );
			/* translators: %s: Error message */
			return new \WP_Error( 'pdf_download_failed', sprintf( __( 'PDF download error: %s', 'invoicing-integration-for-fakturownia-and-woocommerce' ), $error_message ) );
		}
		
		// If we get here, response is not PDF and not a known error
		$this->log( 'PDF Download Error: Unexpected response type: ' . $content_type, 'error' );
		/* translators: %s: Content type */
		return new \WP_Error( 'pdf_download_failed', sprintf( __( 'Unexpected response type: %s', 'invoicing-integration-for-fakturownia-and-woocommerce' ), $content_type ) );
	}

	/**
	 * Get invoice templates
	 * 
	 * Note: Fakturownia API may not support fetching templates via API.
	 * This endpoint may require web interface authentication or may not exist.
	 * 
	 * @return array|WP_Error
	 */
	public function get_templates() {
		// Try different possible endpoints
		$endpoints = [
			'invoice_templates.json',
			'invoices/templates.json',
			'templates.json',
		];
		
		foreach ( $endpoints as $endpoint ) {
			$response = $this->get( $endpoint );
			
			if ( is_wp_error( $response ) ) {
				$error_code = $response->get_error_code();
				$error_message = $response->get_error_message();
				
				// If 404 or 422, try next endpoint
				if ( strpos( $error_message, '404' ) !== false || strpos( $error_message, '422' ) !== false || strpos( $error_message, '403' ) !== false ) {
					continue;
				}
				
				// For other errors, log and return
				$this->log( 'get_templates ERROR (' . $endpoint . '): ' . $error_message, 'error' );
				return $response;
			}
			
			// Success - parse response
			if ( is_array( $response ) ) {
				// If response is already an array, check if it's a list or an object
				if ( isset( $response[0] ) && is_array( $response[0] ) ) {
					// It's already a list of templates
					return $response;
				} elseif ( isset( $response['invoice_templates'] ) && is_array( $response['invoice_templates'] ) ) {
					// Templates are nested under 'invoice_templates' key
					return $response['invoice_templates'];
				} elseif ( isset( $response['templates'] ) && is_array( $response['templates'] ) ) {
					// Templates are nested under 'templates' key
					return $response['templates'];
				} elseif ( ! empty( $response ) ) {
					// Try to use response as-is if it's not empty
					return $response;
				}
			}
		}
		
		// If all endpoints failed, templates may not be available via API
		$this->log( 'get_templates: All endpoints failed. Templates may not be available via API.', 'warning' );
		return [];
	}

	/**
	 * Get products
	 * 
	 * @param array $params Query parameters
	 * @return array|WP_Error
	 */
	public function get_products( $params = [] ) {
		return $this->get( 'products.json', $params );
	}

	/**
	 * Get warehouses
	 * 
	 * @return array|WP_Error
	 */
	public function get_warehouses() {
		return $this->get( 'warehouses.json' );
	}

	/**
	 * Query products by search term
	 * 
	 * @param string $query Search query (product name or code)
	 * @param int $page Page number
	 * @param int $warehouse_id Optional warehouse ID
	 * @return array|WP_Error
	 */
	public function query_products( $query, $page = 1, $warehouse_id = null ) {
		$params = [
			'api_token' => $this->api_token,
			'query' => $query,
			'page' => $page,
		];
		
		if ( $warehouse_id ) {
			$params['warehouse_id'] = $warehouse_id;
		}
		
		return $this->get( 'products.json', $params );
	}

	/**
	 * Get single product
	 * 
	 * @param int $product_id Product ID
	 * @return array|WP_Error
	 */
	public function get_product( $product_id ) {
		return $this->get( 'products/' . $product_id . '.json' );
	}

	/**
	 * Update product
	 * 
	 * @param int $product_id Product ID
	 * @param array $data Product data
	 * @return array|WP_Error
	 */
	public function update_product( $product_id, $data ) {
		$params = [
			'api_token' => $this->api_token,
			'product' => $data,
		];

		return $this->put( 'products/' . $product_id . '.json', $params );
	}

	/**
	 * Clear API cache
	 */
	public static function clear_cache() {
		global $wpdb;
		
		// Delete all transients starting with our prefix
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
				$wpdb->esc_like( '_transient_devikit_fakturownia_' ) . '%'
			)
		);
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
				$wpdb->esc_like( '_transient_timeout_devikit_fakturownia_' ) . '%'
			)
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	}

	/**
	 * Send GET request
	 * 
	 * @param string $endpoint API endpoint (without base URL)
	 * @param array $params Query parameters
	 * @return array|WP_Error
	 */
	private function get( $endpoint, $params = [] ) {
		$url = $this->api_url . $endpoint;
		
		// Add API token to params
		$params['api_token'] = $this->api_token;
		
		$url = add_query_arg( $params, $url );

		$args = [
			'timeout'   => 30,
			'sslverify' => true,
		];

		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) ) {
			$this->log( 'GET Error: ' . $response->get_error_message(), 'error' );
			return $response;
		}

		return $this->parse_response( $response );
	}

	/**
	 * Send POST request
	 * 
	 * @param string $endpoint API endpoint (without base URL)
	 * @param array $params Request parameters
	 * @return array|WP_Error
	 */
	private function post( $endpoint, $params = [] ) {
		$url = $this->api_url . $endpoint;

		$args = [
			'headers'   => [
				'Content-Type' => 'application/json',
			],
			'body'      => wp_json_encode( $params ),
			'timeout'   => 30,
			'sslverify' => true,
		];

		$response = wp_remote_post( $url, $args );

		if ( is_wp_error( $response ) ) {
			$this->log( 'POST Error: ' . $response->get_error_message(), 'error' );
			return $response;
		}

		return $this->parse_response( $response );
	}

	/**
	 * Send PUT request
	 * 
	 * @param string $endpoint API endpoint (without base URL)
	 * @param array $params Request parameters
	 * @return array|WP_Error
	 */
	private function put( $endpoint, $params = [] ) {
		$url = $this->api_url . $endpoint;

		$args = [
			'method'    => 'PUT',
			'headers'   => [
				'Content-Type' => 'application/json',
			],
			'body'      => wp_json_encode( $params ),
			'timeout'   => 30,
			'sslverify' => true,
		];

		$response = wp_remote_request( $url, $args );

		if ( is_wp_error( $response ) ) {
			$this->log( 'PUT Error: ' . $response->get_error_message(), 'error' );
			return $response;
		}

		return $this->parse_response( $response );
	}

	/**
	 * Parse API response
	 * 
	 * @param array $response WordPress HTTP response
	 * @return array|WP_Error
	 */
	private function parse_response( $response ) {
		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );

		if ( $code !== 200 && $code !== 201 ) {
			$this->log( 'API Response Error. Status: ' . $code . ', Body: ' . $body, 'error' );
			
			// Try to parse error message from JSON
			$error_data = json_decode( $body, true );
			if ( isset( $error_data['message'] ) ) {
				$error_message = $this->format_error_message( $error_data['message'] );
				return new \WP_Error( 'fakturownia_api_error', $error_message, $error_data );
			}
			
			/* translators: %d: HTTP status code */
			return new \WP_Error( 'fakturownia_api_error', sprintf( __( 'API returned status %d', 'invoicing-integration-for-fakturownia-and-woocommerce' ), $code ) );
		}

		// Parse JSON response
		$data = json_decode( $body, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			$this->log( 'JSON Parse Error: ' . json_last_error_msg(), 'error' );
			return new \WP_Error( 'fakturownia_json_error', __( 'Failed to parse JSON response', 'invoicing-integration-for-fakturownia-and-woocommerce' ) );
		}

		return $data;
	}

	/**
	 * Format error message from API response
	 * 
	 * @param mixed $message Error message (can be string, array, or nested structure)
	 * @return string Formatted error message
	 */
	private function format_error_message( $message ) {
		if ( is_string( $message ) ) {
			return $message;
		}
		
		if ( is_array( $message ) ) {
			$errors = [];
			foreach ( $message as $key => $value ) {
				if ( is_numeric( $key ) && is_array( $value ) ) {
					// Array of position errors - format as "Position X: errors"
					$position_errors = [];
					foreach ( $value as $field => $field_errors ) {
						if ( is_array( $field_errors ) ) {
							$position_errors[] = $field . ': ' . implode( ', ', $field_errors );
						} else {
							$position_errors[] = $field . ': ' . $field_errors;
						}
					}
					if ( ! empty( $position_errors ) ) {
						/* translators: %d: Position number in invoice items list */
						$errors[] = sprintf( __( 'Position %d', 'invoicing-integration-for-fakturownia-and-woocommerce' ), $key + 1 ) . ': ' . implode( '; ', $position_errors );
					}
				} elseif ( is_array( $value ) ) {
					if ( isset( $value[0] ) && is_string( $value[0] ) ) {
						// Simple array of strings
						$errors[] = $key . ': ' . implode( ', ', $value );
					} else {
						// Nested array - recurse
						$nested = $this->format_error_message( $value );
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
		
		return (string) $message;
	}

	/**
	 * Log message
	 * 
	 * @param string $message Log message
	 * @param string $level Log level: 'error', 'warning', 'info', 'debug'
	 */
	public function log( $message, $level = 'error' ) {
		if ( ! class_exists( 'WC_Logger' ) ) {
			return;
		}

		$logger = wc_get_logger();
		
		// Redact secrets in logs
		$message = $this->redact_secrets( $message );
		
		// Check if debug logging is enabled
		$settings = get_option( 'devikit_fakturownia_settings', [] );
		$debug_enabled = isset( $settings['enable_debug_logging'] ) && $settings['enable_debug_logging'] === 'yes';
		
		if ( $debug_enabled ) {
			// Log to debug file when enabled
			$logger->log( $level, $message, [ 'source' => 'devikit_fakturownia_debug' ] );
		} elseif ( $level === 'error' ) {
			// Always log errors to main file
			$logger->error( $message, [ 'source' => 'invoicing-integration-for-fakturownia-and-woocommerce' ] );
		}
	}

	/**
	 * Redact secrets from log messages
	 * 
	 * @param string $message Log message
	 * @return string Message with secrets redacted
	 */
	private function redact_secrets( $message ) {
		if ( ! empty( $this->api_token ) ) {
			$message = str_replace( $this->api_token, '[REDACTED]', $message );
		}
		return $message;
	}
}

