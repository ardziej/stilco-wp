<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 * Custom theme implementation for Stilco.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
    return;
}

?>

<div class="w-full bg-stilco-sand min-h-[80vh] py-12 md:py-20 px-6">
    <div class="max-w-screen-xl mx-auto">

        <!-- Subheader – security note only, no "Kasa" heading -->
        <p class="text-center text-sm text-gray-400 font-sans mb-10 tracking-wide">🔒 Bezpieczna transakcja chroniona szyfrowaniem 256-bit SSL.</p>

        <form name="checkout" method="post"
              class="checkout woocommerce-checkout flex flex-col lg:flex-row gap-0"
              action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
              enctype="multipart/form-data">

            <!-- Lewa Kolumna: Formularz Danych (50%) -->
            <div class="w-full lg:w-1/2 lg:pr-12 space-y-8" id="customer_details">
                <?php if ( $checkout->get_checkout_fields() ) : ?>
                    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <h2 class="text-2xl font-display font-semibold text-stilco-dark mb-6">Dane Bilingowe</h2>
                        <?php do_action( 'woocommerce_checkout_billing' ); ?>
                    </div>

                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <h2 class="text-2xl font-display font-semibold text-stilco-dark mb-6">Dostawa &amp; Uwagi</h2>
                        <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                    </div>

                    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
                <?php endif; ?>
            </div>

            <!-- Pionowy Separaor -->
            <div class="hidden lg:block w-px bg-gray-200 self-stretch mx-0 flex-shrink-0"></div>

            <!-- Prawa Kolumna: Podsumowanie zamówienia (50%) -->
            <div class="w-full lg:w-1/2 lg:pl-12 mt-8 lg:mt-0">
                <div class="lg:sticky lg:top-8">

                    <h3 id="order_review_heading" class="text-2xl font-display font-bold text-stilco-dark mb-6">
                        <?php esc_html_e( 'Twoje zamówienie', 'woocommerce' ); ?>
                    </h3>

                    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                    <div id="order_review" class="woocommerce-checkout-review-order">
                        <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                    </div>

                    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

                    <!-- Trust Signals -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="grid grid-cols-3 gap-4 mb-8">
                            <div class="flex flex-col items-center text-center space-y-2">
                                <div class="w-10 h-10 rounded-full bg-stilco-accent/10 text-stilco-accent flex items-center justify-center mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"></path><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path><path d="M12 7v5l4 2"></path></svg>
                                </div>
                                <span class="text-[11px] font-bold text-gray-800 uppercase tracking-wider">100 nocy<br>na próbę</span>
                            </div>
                            <div class="flex flex-col items-center text-center space-y-2">
                                <div class="w-10 h-10 rounded-full bg-stilco-accent/10 text-stilco-accent flex items-center justify-center mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                </div>
                                <span class="text-[11px] font-bold text-gray-800 uppercase tracking-wider">10 lat<br>gwarancji</span>
                            </div>
                            <div class="flex flex-col items-center text-center space-y-2">
                                <div class="w-10 h-10 rounded-full bg-stilco-accent/10 text-stilco-accent flex items-center justify-center mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                </div>
                                <span class="text-[11px] font-bold text-gray-800 uppercase tracking-wider">Darmowa<br>Dostawa</span>
                            </div>
                        </div>

                        <div class="flex flex-col items-center justify-center space-y-4">
                            <div class="flex space-x-3 mb-2">
                                <div class="px-3 py-1 bg-gray-50 border border-gray-200 rounded-md text-[10px] font-bold text-gray-500 shadow-sm flex items-center justify-center">BLIK</div>
                                <div class="px-3 py-1 bg-gray-50 border border-gray-200 rounded-md text-[10px] font-bold text-gray-500 shadow-sm flex items-center justify-center">P24</div>
                                <div class="px-3 py-1 bg-gray-50 border border-gray-200 rounded-md text-[10px] font-bold text-gray-500 shadow-sm flex items-center justify-center">VISA</div>
                                <div class="px-3 py-1 bg-gray-50 border border-gray-200 rounded-md text-[10px] font-bold text-gray-500 shadow-sm flex items-center justify-center">MC</div>
                            </div>
                            <span class="text-[11px] text-gray-500 text-center font-sans tracking-tight leading-relaxed">
                                Klikając przycisk "Kupuję i płacę" zgadzasz się z <a href="/regulamin" class="underline hover:text-stilco-accent transition-colors">regulaminem</a> sklepu.<br>
                                Twoje dane są w 100% bezpieczne (szyfrowanie SSL).
                            </span>
                        </div>
                    </div>

                </div>
            </div>

        </form>
    </div>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
