<?php
/**
 * Asset loading and head markup.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue theme assets from Vite dev server or production manifest.
 *
 * @return void
 */
function stilco_enqueue_scripts() {
	if ( stilco_is_vite_dev_server_available() ) {
		$dev_server_origin = stilco_get_vite_dev_server_origin();

		wp_enqueue_style( 'stilco-style', $dev_server_origin . '/assets/css/app.css', array(), null );
		wp_enqueue_script( 'stilco-vite-client', $dev_server_origin . '/@vite/client', array(), null, true );
		wp_enqueue_script( 'stilco-app', $dev_server_origin . '/assets/js/app.js', array( 'jquery' ), null, true );

		return;
	}

	$style_url  = stilco_get_vite_asset_url( 'assets/css/app.css' );
	$script_url = stilco_get_vite_asset_url( 'assets/js/app.js' );

	if ( $style_url ) {
		wp_enqueue_style( 'stilco-style', $style_url, array(), null );
	} else {
		wp_register_style( 'stilco-style', false, array(), STILCO_THEME_VERSION );
		wp_enqueue_style( 'stilco-style' );
	}

	if ( $script_url ) {
		wp_enqueue_script( 'stilco-app', $script_url, array( 'jquery' ), null, true );
	}
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_scripts', 100 );

/**
 * Mark Vite dev server scripts as ES modules.
 *
 * @param string $tag    Script tag HTML.
 * @param string $handle Registered script handle.
 * @param string $src    Script source URL.
 * @return string
 */
function stilco_filter_vite_script_loader_tag( $tag, $handle, $src ) {
	if ( ! stilco_is_vite_dev_server_available() ) {
		return $tag;
	}

	if ( ! in_array( $handle, array( 'stilco-vite-client', 'stilco-app' ), true ) ) {
		return $tag;
	}

	return sprintf(
		'<script type="module" src="%1$s"></script>',
		esc_url( $src )
	);
}
add_filter( 'script_loader_tag', 'stilco_filter_vite_script_loader_tag', 10, 3 );

/**
 * Output favicon links in the document head.
 *
 * @return void
 */
function stilco_output_favicon_links() {
	$favicon_dir = STILCO_THEME_URI . '/assets/favicon';
	?>
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( $favicon_dir . '/apple-touch-icon.png' ); ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( $favicon_dir . '/favicon-32x32.png' ); ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( $favicon_dir . '/favicon-16x16.png' ); ?>">
	<link rel="manifest" href="<?php echo esc_url( $favicon_dir . '/site.webmanifest' ); ?>">
	<link rel="shortcut icon" href="<?php echo esc_url( $favicon_dir . '/favicon.ico' ); ?>">
	<meta name="theme-color" content="#c85a41">
	<?php
}
add_action( 'wp_head', 'stilco_output_favicon_links', 1 );
