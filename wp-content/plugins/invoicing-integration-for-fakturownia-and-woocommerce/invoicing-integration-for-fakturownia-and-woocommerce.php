<?php
/**
 * Plugin Name: Invoicing Integration for Fakturownia and WooCommerce
 * Plugin URI: https://wordpress.org/plugins/invoicing-integration-for-fakturownia-and-woocommerce/
 * Description: WooCommerce integration with Fakturownia accounting system for Polish businesses.
 * Version: 1.0.9
 * Author: Devikit
 * Author URI: https://devikit.pl/
 * Text Domain: invoicing-integration-for-fakturownia-and-woocommerce
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 * WC requires at least: 5.0
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'DEVIKIT_FAKTUROWNIA_VERSION', '1.0.9' );
define( 'DEVIKIT_FAKTUROWNIA_FILE', __FILE__ );
define( 'DEVIKIT_FAKTUROWNIA_PATH', plugin_dir_path( __FILE__ ) );
define( 'DEVIKIT_FAKTUROWNIA_URL', plugin_dir_url( __FILE__ ) );
define( 'DEVIKIT_FAKTUROWNIA_BASENAME', plugin_basename( __FILE__ ) );

// Native Autoloader (No Composer needed)
spl_autoload_register( function ( $class ) {
	$prefix = 'Devikit\\Fakturownia\\';
	$base_dir = DEVIKIT_FAKTUROWNIA_PATH;

	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}

	$relative_class = substr( $class, $len );
	
	// Map namespaces to directories
	$map = [
		'Admin\\' => 'admin/',
		'Api\\'   => 'api/',
	];

	$file_path = '';
	$found = false;

	foreach ( $map as $ns_prefix => $dir ) {
		if ( strncmp( $ns_prefix, $relative_class, strlen( $ns_prefix ) ) === 0 ) {
			$class_file = str_replace( $ns_prefix, '', $relative_class );
			$file_path = $base_dir . $dir . str_replace( '\\', '/', $class_file ) . '.php';
			$found = true;
			break;
		}
	}

	if ( ! $found ) {
		// Default to includes
		$file_path = $base_dir . 'includes/' . str_replace( '\\', '/', $relative_class ) . '.php';
	}

	if ( file_exists( $file_path ) ) {
		require $file_path;
	}
} );

// Initialize the plugin
function devikit_fakturownia_init() {
	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', function() {
			echo '<div class="error"><p>' . esc_html__( 'Fakturownia WooCommerce requires WooCommerce to be installed and active.', 'invoicing-integration-for-fakturownia-and-woocommerce' ) . '</p></div>';
		} );
		return;
	}

	// Note: load_plugin_textdomain() is not needed for plugins hosted on WordPress.org
	// WordPress automatically loads translations from /wp-content/languages/plugins/ since version 4.6
	// Our Text Domain header matches the plugin slug, so automatic loading works correctly

	// Initialize main plugin class
	\Devikit\Fakturownia\Plugin::instance();
}
add_action( 'plugins_loaded', 'devikit_fakturownia_init' );

// Declare compatibility with HPOS
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', DEVIKIT_FAKTUROWNIA_FILE, true );
	}
} );

