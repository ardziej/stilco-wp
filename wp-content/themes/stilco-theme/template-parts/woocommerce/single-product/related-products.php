<?php
/**
 * Single product related and upsell section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="mt-24">
	<?php woocommerce_upsell_display(); ?>
	<?php woocommerce_related_products( array( 'posts_per_page' => 4, 'columns' => 4 ) ); ?>
</div>
