<?php
/**
 * Nadpisany Szablon Archiwum Produktów WooCommerce (Sklep)
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-6">
        <header class="mb-10 text-center animate-on-scroll">
            <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                <h1 class="text-4xl md:text-5xl font-display font-bold text-stilco-dark mb-4"><?php woocommerce_page_title(); ?></h1>
            <?php endif; ?>
            
            <?php
            /**
             * Opis kategorii
             */
            do_action( 'woocommerce_archive_description' );
            ?>
        </header>

        <?php
        if ( woocommerce_product_loop() ) {
            ?>
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 pb-4 border-b border-gray-200">
                <div class="text-sm text-gray-500 mb-4 md:mb-0">
                    <?php do_action( 'woocommerce_before_shop_loop' ); // Wyniki, sortowanie ?>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php
                if ( wc_get_loop_prop( 'total' ) ) {
                    while ( have_posts() ) {
                        the_post();
                        /**
                         * Pętla produktu. Szablon content-product.php
                         * (Dla uproszczenia wdrażamy custom markup bezpośrednio tutaj w celach poglądowych, 
                         * w pełnym motywie nadpisalibyśmy content-product.php)
                         */
                        global $product;
                        
                        if ( empty( $product ) || ! $product->is_visible() ) {
                            continue;
                        }
                        ?>
                        <div class="group bg-white rounded-3xl p-6 shadow-sm hover:shadow-xl transition-all duration-500 border border-transparent hover:border-stilco-accent/20 relative flex flex-col items-center text-center">
                            <?php if ( $product->is_on_sale() ) : ?>
                                <span class="absolute top-6 left-6 bg-stilco-accent text-white text-[10px] font-bold px-3 py-1.5 rounded-full z-10 tracking-widest uppercase shadow-sm">
                                    Promocja
                                </span>
                            <?php endif; ?>
                            
                            <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="block w-full mb-6 overflow-hidden rounded-2xl relative focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent">
                                <?php 
                                // Customowy rozmiar lub domyślny thumbnail
                                echo $product->get_image('woocommerce_thumbnail', array('class' => 'w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out')); 
                                ?>
                            </a>
                            
                            <h3 class="font-serif font-semibold text-xl mb-3 text-stilco-dark">
                                <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm"><?php echo $product->get_title(); ?></a>
                            </h3>
                            
                            <div class="text-stilco-dark font-medium mb-6 flex items-center justify-center space-x-2">
                                <?php echo $product->get_price_html(); ?>
                            </div>
                            
                            <div class="mt-auto w-full">
                                <a href="?add-to-cart=<?php echo esc_attr( $product->get_id() ); ?>" data-quantity="1" class="btn-outline w-full block py-3 text-sm rounded-full border border-stilco-accent text-stilco-accent hover:bg-stilco-accent hover:text-white transition-all duration-300 font-medium focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-dark" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" aria-label="Dodaj do koszyka">
                                    Dodaj do koszyka
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            
            <div class="mt-12">
                <?php do_action( 'woocommerce_after_shop_loop' ); // Paginacja ?>
            </div>
            <?php
        } else {
            do_action( 'woocommerce_no_products_found' );
        }
        ?>
    </div>
</div>

<?php
get_footer( 'shop' );
