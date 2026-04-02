<?php
/**
 * WooCommerce archive header.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<header class="mb-10 text-center animate-on-scroll">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="text-4xl md:text-5xl font-display font-bold text-stilco-dark mb-4"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php do_action( 'woocommerce_archive_description' ); ?>
</header>
