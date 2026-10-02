<?php
/**
 * Uninstall script for Invoicing Integration for Fakturownia and WooCommerce
 *
 * @package Devikit\Fakturownia
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Get option to check if user wants to keep data
$devikit_fakturownia_keep_data = get_option( 'devikit_fakturownia_keep_data_on_uninstall', 'no' );

if ( $devikit_fakturownia_keep_data === 'yes' ) {
	// User wants to keep data, do nothing
	return;
}

// Delete all plugin options
delete_option( 'devikit_fakturownia_settings' );
delete_option( 'devikit_fakturownia_banner_dismissed' );
delete_option( 'devikit_fakturownia_banner_remind_later' );
delete_option( 'devikit_fakturownia_keep_data_on_uninstall' );

// Delete all transients
global $wpdb;
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

// Note: We do NOT delete order meta (_fakturownia_invoice_id, etc.) as these are part of order history
// and should be preserved even if plugin is uninstalled



