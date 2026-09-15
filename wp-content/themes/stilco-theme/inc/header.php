<?php
/**
 * Header helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the main header CTA ("Skonfiguruj") label and URL.
 *
 * Values come from the global settings pod with a hardcoded fallback
 * pointing at the single mattress product (the configurator).
 *
 * @return array{label:string,url:string}
 */
function stilco_get_header_cta() {
	return stilco_get_link_data(
		'header_cta_text',
		'header_cta_url',
		'Skonfiguruj',
		'/produkt/materac-stilco/',
		null,
		true
	);
}
