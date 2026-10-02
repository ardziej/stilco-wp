<?php

namespace Devikit\Fakturownia;

use Devikit\Fakturownia\Admin\Settings;
use Devikit\Fakturownia\Admin\OrderColumns;
use Devikit\Fakturownia\Api\Client;
use Devikit\Fakturownia\Frontend\Checkout;
use Devikit\Fakturownia\Frontend\CheckoutBlocks;
use Devikit\Fakturownia\Frontend\MyAccount;
use Devikit\Fakturownia\NipField;
use Devikit\Fakturownia\LumpSumProductFields;
use Devikit\Fakturownia\ProProductFields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {

	/**
	 * Singleton instance
	 *
	 * @var Plugin
	 */
	private static $instance;

	/**
	 * @var Settings
	 */
	private $settings;

	/**
	 * @var Client
	 */
	private $api_client;

	/**
	 * @var InvoiceManager
	 */
	private $invoice_manager;

	/**
	 * @var Checkout
	 */
	private $checkout;

	/**
	 * @var CheckoutBlocks
	 */
	private $checkout_blocks;

	/**
	 * @var OrderColumns
	 */
	private $order_columns;

	/**
	 * @var NipField
	 */
	private $nip_field;

	/**
	 * @var MyAccount
	 */
	private $my_account;

	/**
	 * Get singleton instance
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->init_hooks();
		$this->init_components();
	}

	/**
	 * Initialize hooks
	 */
	private function init_hooks() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	/**
	 * Initialize components
	 */
	private function init_components() {
		$this->settings = new Settings();
		
		$api_token = $this->settings->get_option( 'api_token' );
		$subdomain = $this->settings->get_option( 'subdomain' );
		$this->api_client = new Client( $api_token, $subdomain );

		$this->invoice_manager = new InvoiceManager( $this->api_client, $this->settings );
		$this->checkout = new Checkout();
		$this->checkout_blocks = new CheckoutBlocks();
		$this->my_account = new MyAccount();
		$this->order_columns = new OrderColumns();
		$this->nip_field = new NipField();
		
		// Initialize lump sum product fields
		new LumpSumProductFields();
		
		// Initialize PRO product fields (grayed out in FREE)
		// Class will avoid duplicates when PRO is installed (no license checks in FREE).
		new ProProductFields();
	}

	/**
	 * Enqueue assets
	 */
	public function enqueue_assets() {
		// Enqueue styles/scripts if needed
	}

	/**
	 * Get Settings instance
	 * 
	 * @return Settings
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * Get API Client instance
	 * 
	 * @return Client
	 */
	public function get_api_client() {
		return $this->api_client;
	}

	/**
	 * Get Invoice Manager instance
	 * 
	 * @return InvoiceManager
	 */
	public function get_invoice_manager() {
		return $this->invoice_manager;
	}
}

