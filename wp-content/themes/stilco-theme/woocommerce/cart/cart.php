<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 * Custom theme implementation for Stilco.
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="max-w-7xl mx-auto px-6 py-12 md:py-24 animate-on-scroll bg-stilco-light min-h-[70vh]">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-stilco-dark mb-4 drop-shadow-sm">Twój koszyk</h1>
        <p class="text-gray-500 font-sans">Dzieli Cię zaledwie krok od najlepszego snu w życiu.</p>
    </div>

    <form class="woocommerce-cart-form grid grid-cols-1 lg:grid-cols-12 gap-12" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
        
        <!-- Lewa Kolumna: Tabela Produktów -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents w-full" cellspacing="0">
                    <thead class="bg-stilco-sand/50 text-left border-b border-gray-100">
                        <tr>
                            <th class="product-remove py-4 px-6 text-xs uppercase tracking-wider text-gray-400 font-semibold w-10">&nbsp;</th>
                            <th class="product-thumbnail py-4 px-4 text-xs uppercase tracking-wider text-gray-400 font-semibold w-24">&nbsp;</th>
                            <th class="product-name py-4 px-4 text-xs uppercase tracking-wider text-gray-400 font-semibold">Produkt</th>
                            <th class="product-price py-4 px-4 text-xs uppercase tracking-wider text-gray-400 font-semibold hidden sm:table-cell">Cena</th>
                            <th class="product-quantity py-4 px-4 text-xs uppercase tracking-wider text-gray-400 font-semibold">Ilość</th>
                            <th class="product-subtotal py-4 px-6 text-xs uppercase tracking-wider text-gray-400 font-semibold text-right">Razem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                        <?php
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                ?>
                                <tr class="woocommerce-cart-form__cart-item bg-white hover:bg-gray-50/50 transition-colors <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                                    <td class="product-remove px-6 align-middle">
                                        <?php
                                            echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                'woocommerce_cart_item_remove_link',
                                                sprintf(
                                                    '<a href="%s" class="remove text-gray-300 hover:text-red-500 transition-colors" aria-label="%s" data-product_id="%s" data-product_sku="%s"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></a>',
                                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                    esc_html__( 'Remove this item', 'woocommerce' ),
                                                    esc_attr( $product_id ),
                                                    esc_attr( $_product->get_sku() )
                                                ),
                                                $cart_item_key
                                            );
                                        ?>
                                    </td>

                                    <td class="product-thumbnail px-4 py-4 align-middle">
                                    <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail', array('class' => 'w-16 h-16 object-cover rounded-xl border border-gray-100 shadow-sm')), $cart_item, $cart_item_key );

                                    if ( ! $product_permalink ) {
                                        echo $thumbnail; // PHPCS: XSS ok.
                                    } else {
                                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
                                    }
                                    ?>
                                    </td>

                                    <td class="product-name px-4 py-4 align-middle font-display font-medium text-stilco-dark" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                                    <?php
                                    if ( ! $product_permalink ) {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                    } else {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="hover:text-stilco-accent transition-colors">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                    }

                                    do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                    // Warianty np. rozmiar
                                    echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

                                    // Adnotacja np. Dostawa
                                    echo '<p class="text-xs text-stilco-accent mt-2 flex items-center font-sans tracking-tight"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>Gotowe do wysyłki w 48h</p>';
                                    ?>
                                    </td>

                                    <td class="product-price px-4 py-4 align-middle font-sans text-gray-500 hidden sm:table-cell" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
                                        <?php
                                            echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                        ?>
                                    </td>

                                    <td class="product-quantity px-4 py-4 align-middle" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
                                    <?php
                                    if ( $_product->is_sold_individually() ) {
                                        $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                    } else {
                                        // Nadpisanie styli dla inputa
                                        $product_quantity = woocommerce_quantity_input(
                                            array(
                                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                                'input_value'  => $cart_item['quantity'],
                                                'max_value'    => $_product->get_max_purchase_quantity(),
                                                'min_value'    => '0',
                                                'product_name' => $_product->get_name(),
                                                'classes'      => array('input-text', 'qty', 'text', 'w-16', 'text-center', 'border', 'border-gray-200', 'rounded-lg', 'py-2', 'focus:ring-2', 'focus:ring-stilco-accent', 'outline-none')
                                            ),
                                            $_product,
                                            false
                                        );
                                    }

                                    echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                    ?>
                                    </td>

                                    <td class="product-subtotal px-6 py-4 align-middle font-sans font-semibold text-stilco-dark text-right" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
                                        <?php
                                            echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                        <?php do_action( 'woocommerce_cart_contents' ); ?>
                        <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                    </tbody>
                </table>
                
                <!-- Akcje koszyka (Kupon / Update) -->
                <div class="bg-gray-50 border-t border-gray-100 p-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <?php if ( wc_coupons_enabled() ) { ?>
                        <div class="coupon flex w-full sm:w-auto">
                            <input type="text" name="coupon_code" class="input-text w-full sm:w-48 border border-gray-200 rounded-l-full px-4 py-3 text-sm focus:outline-none focus:border-stilco-accent focus:ring-1 focus:ring-stilco-accent" id="coupon_code" value="" placeholder="Kod rabatowy..." /> <button type="submit" class="button btn-outline bg-stilco-dark text-white hover:bg-stilco-accent border-0 rounded-none rounded-r-full px-6 py-3 text-sm font-medium transition-colors" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
                            <?php do_action( 'woocommerce_cart_coupon' ); ?>
                        </div>
                    <?php } ?>

                    <button type="submit" class="button btn-outline text-gray-500 hover:text-stilco-dark hover:bg-gray-100 border-gray-200 py-3 px-6 text-sm font-medium w-full sm:w-auto transition-colors" name="update_cart" value="Zaktualizuj koszyk">Zaktualizuj koszyk</button>

                    <?php do_action( 'woocommerce_cart_actions' ); ?>
                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                </div>
            </div>
        </div>

        <!-- Prawa Kolumna: Podsumowanie Koszyka -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 sticky top-32">
                <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

                <div class="cart-collaterals">
                    <?php
                        /**
                         * Cart collaterals hook.
                         *
                         * @hooked woocommerce_cross_sell_display
                         * @hooked woocommerce_cart_totals - 10
                         */
                        do_action( 'woocommerce_cart_collaterals' );
                    ?>
                </div>

                <!-- Trust Signals USP umieszczone pod podsumowaniem -->
                <div class="mt-8 pt-6 border-t border-gray-100 space-y-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-stilco-accent mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <p class="text-xs text-gray-500 font-sans leading-relaxed">Pamiętaj, Twoje zamówienie objęte jest gwarancją 10 lat oraz 100 nocy testowych. Możesz zwrócić materac, jeśli nie spełni Twoich oczekiwań.</p>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-stilco-accent mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <p class="text-xs text-gray-500 font-sans leading-relaxed">Szyfrowana, bezpieczna transakcja (SSL 256-bit).</p>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
