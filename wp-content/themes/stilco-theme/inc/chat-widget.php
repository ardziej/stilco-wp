<?php
/**
 * Chat widget UI helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue chat widget assets.
 *
 * @return void
 */
function stilco_enqueue_chat_widget_assets() {
	if ( is_admin() ) {
		return;
	}

	wp_enqueue_style(
		'stilco-chat-widget',
		stilco_get_theme_asset_uri( 'assets/css/chat-widget.css' ),
		array( 'stilco-style' ),
		stilco_get_theme_asset_version( 'assets/css/chat-widget.css' )
	);

	wp_enqueue_style(
		'stilco-chat-widget-effects',
		stilco_get_theme_asset_uri( 'assets/css/chat-widget-effects.css' ),
		array( 'stilco-chat-widget' ),
		stilco_get_theme_asset_version( 'assets/css/chat-widget-effects.css' )
	);

	wp_enqueue_script(
		'stilco-chat-widget',
		stilco_get_theme_asset_uri( 'assets/js/chat-widget.js' ),
		array(),
		stilco_get_theme_asset_version( 'assets/js/chat-widget.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_chat_widget_assets', 130 );
