<?php
/**
 * Mini-cart
 * Nadpisany plik WooCommerce dla kompatybilości z Tailwind
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_mini_cart'); ?>

<?php if (!WC()->cart->is_empty()): ?>

<ul class="woocommerce-mini-cart cart_list product_list_widget divide-y divide-gray-200">
    <?php
    do_action('woocommerce_before_mini_cart_contents');

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
            $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
            $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
?>
    <li
        class="woocommerce-mini-cart-item flex py-6 <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>">

        <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
            <?php if (empty($product_permalink)): ?>
            <?php echo $_product->get_image('thumbnail', array('class' => 'h-full w-full object-cover object-center')); ?>
            <?php
            else: ?>
            <a href="<?php echo esc_url($product_permalink); ?>">
                <?php echo str_replace(array('width="', 'height="'), array('class="h-full w-full object-cover object-center" data-width="', 'data-height="'), $_product->get_image('thumbnail')); ?>
            </a>
            <?php
            endif; ?>
        </div>

        <div class="ml-4 flex flex-1 flex-col">
            <div>
                <div class="flex justify-between text-base font-medium text-stilco-dark font-display font-medium">
                    <h3>
                        <?php if (empty($product_permalink)): ?>
                        <?php echo wp_kses_post($product_name); ?>
                        <?php
            else: ?>
                        <a href="<?php echo esc_url($product_permalink); ?>">
                            <?php echo wp_kses_post($product_name); ?>
                        </a>
                        <?php
            endif; ?>
                    </h3>
                    <p class="ml-4">
                        <?php echo $product_price; ?>
                    </p>
                </div>
                <?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>
                        <div class="flex flex-1 items-end justify-between font-sans text-sm mt-2">
                            <p class="text-gray-500">Ilość: <?php echo $cart_item['quantity']; ?>
                </p>

                <div class="flex">
                    <?php
            echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                'woocommerce_cart_item_remove_link',
                sprintf(
                '<a href="%s" class="remove remove_from_cart_button font-medium text-red-600 hover:text-red-500" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">Usuń</a>',
                esc_url(wc_get_cart_remove_url($cart_item_key)),
                /* translators: %s is the product name */
                esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
                esc_attr($product_id),
                esc_attr($cart_item_key),
                esc_attr($_product->get_sku())
            ),
                $cart_item_key
            );
?>
                </div>
            </div>
        </div>
    </li>
    <?php
        }
    }

    do_action('woocommerce_mini_cart_contents');
?>
</ul>

<div class="border-t border-gray-200 py-6 mt-6">
    <div class="flex justify-between text-lg font-display font-bold text-stilco-dark">
        <p class="woocommerce-mini-cart__total total">
            <?php
    /**
     * Hook: woocommerce_widget_shopping_cart_total.
     *
     * @hooked woocommerce_widget_shopping_cart_subtotal - 10
     */
    do_action('woocommerce_widget_shopping_cart_total');
?>
        </p>
    </div>
    <p class="mt-0.5 text-sm text-gray-500 font-sans">Darmowa dostawa do wszystkich zamówień na materace.</p>

    <div class="mt-6 flex flex-col gap-3">
        <?php do_action('woocommerce_widget_shopping_cart_buttons'); ?>
    </div>
</div>

<?php
else: ?>

<div class="woocommerce-mini-cart__empty-message blocktext-center py-12 flex flex-col items-center justify-center">
    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
    </svg>
    <p class="text-gray-500 text-lg">Twój koszyk jest pusty.</p>
    <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>"
        class="mt-6 btn-outline inline-block">
        Zacznij zakupy
    </a>
</div>

<?php
endif; ?>

<?php do_action('woocommerce_after_mini_cart'); ?>