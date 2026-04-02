<?php
/**
 * Production dashboard page helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check whether the current request is the production dashboard template.
 *
 * @return bool
 */
function stilco_is_production_dashboard_page() {
	return function_exists( 'is_page_template' ) && is_page_template( 'page-production-dashboard.php' );
}

/**
 * Get the production dashboard document title.
 *
 * @return string
 */
function stilco_get_production_dashboard_page_title() {
	return __( 'Stilco - Panel Produkcyjny', 'stilco' );
}
