<?php
/**
 * Frontend template UI helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check whether the current request uses the transparent header variant.
 *
 * @return bool
 */
function stilco_is_transparent_header_context() {
	return is_front_page() || is_home() || is_page_template( array( 'page-about.php', 'page-contact.php', 'page-faq.php' ) );
}

/**
 * Check whether the current request is the mattress landing template.
 *
 * @return bool
 */
function stilco_is_mattress_page() {
	return is_page_template( 'page-mattress.php' );
}

/**
 * Check whether the current request is the contact page template.
 *
 * @return bool
 */
function stilco_is_contact_page() {
	return is_page_template( 'page-contact.php' );
}

/**
 * Check whether the current request renders the dedicated front page.
 *
 * @return bool
 */
function stilco_is_marketing_front_page() {
	return is_front_page();
}

/**
 * Shared frontend helpers are intentionally limited to request context checks.
 */
