</div> <!-- Zamykanie main-content z header.php -->

<?php if ( ! stilco_is_live_checkout_page() ) : ?>
<?php
$footer_links = stilco_get_footer_link_groups();
?>
<footer class="bg-stilco-sand text-stilco-dark pt-24 pb-12 px-6 md:px-12 mt-20 border-t border-stilco-secondary/20 rounded-t-[3rem]">
    <!-- Newsletter Warstwa Górna -->
    <div class="max-w-4xl mx-auto text-center mb-24">
        <h2 class="text-4xl md:text-6xl font-serif font-bold mb-6 text-stilco-dark tracking-tight leading-tight"><?php echo esc_html( stilco_get_setting( 'footer_newsletter_title', 'Obudź się z pomysłem na lepszy sen.' ) ); ?></h2>
        <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto"><?php echo esc_html( stilco_get_setting( 'footer_newsletter_lead', 'Zapisz się, aby otrzymywać wskazówki dotyczące regeneracji, najnowsze badania oraz nowości produktowe od Stilco.' ) ); ?></p>
        <form class="flex flex-col sm:flex-row max-w-lg mx-auto gap-3">
            <input type="email" placeholder="<?php echo esc_attr( stilco_get_setting( 'footer_newsletter_placeholder', 'Twój adres e-mail' ) ); ?>" aria-label="Adres e-mail"
                class="w-full px-6 py-4 bg-white border border-gray-200 rounded-full text-stilco-dark focus:ring-2 focus:ring-stilco-accent focus:border-transparent outline-none shadow-sm transition-all focus:shadow-md">
            <button type="submit"
                class="bg-stilco-accent px-8 py-4 rounded-full font-medium text-white hover:bg-[#A84A34] transition-colors whitespace-nowrap shadow-lg shadow-stilco-accent/30 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-dark">
                <?php echo esc_html( stilco_get_setting( 'footer_newsletter_button_text', 'Zapisz się' ) ); ?>
            </button>
        </form>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-12 border-t border-stilco-dark/10 pt-16">
        <div>
            <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logo.svg" alt="Stilco" class="h-9 w-auto">
            <p class="mt-4 pr-6 text-sm leading-relaxed text-stilco-dark/80"><?php echo esc_html( stilco_get_setting( 'footer_brand_text', 'Materace piankowe szyte z myślą o dobrym śnie — dwie warstwy pianki HR i Visco, pokrowiec z włoskich tkanin. Projektujemy i produkujemy je w Polsce, sprzedając bezpośrednio, bez pośredników.' ) ); ?></p>
        </div>

        <div>
            <h4 class="text-[11px] font-semibold uppercase tracking-[0.12em] text-[#a84a34]"><?php echo esc_html( stilco_get_setting( 'footer_shop_title', 'Sklep' ) ); ?></h4>
            <ul class="mt-4 space-y-2 text-sm leading-6">
                <?php foreach ( $footer_links['shop'] as $item ) : ?>
                <li><a href="<?php echo esc_url( $item['url'] ); ?>" class="text-stilco-dark/80 underline decoration-stilco-dark/40 underline-offset-[6px] hover:text-stilco-accent hover:decoration-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm"><?php echo esc_html( $item['label'] ); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div>
            <h4 class="text-[11px] font-semibold uppercase tracking-[0.12em] text-[#a84a34]"><?php echo esc_html( stilco_get_setting( 'footer_company_title', 'Firma' ) ); ?></h4>
            <ul class="mt-4 space-y-2 text-sm leading-6">
                <?php foreach ( $footer_links['company'] as $item ) : ?>
                <li><a href="<?php echo esc_url( $item['url'] ); ?>" class="text-stilco-dark/80 underline decoration-stilco-dark/40 underline-offset-[6px] hover:text-stilco-accent hover:decoration-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm"><?php echo esc_html( $item['label'] ); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div>
            <h4 class="text-[11px] font-semibold uppercase tracking-[0.12em] text-[#a84a34]"><?php echo esc_html( stilco_get_setting( 'footer_support_title', 'Wsparcie' ) ); ?></h4>
            <ul class="mt-4 space-y-2 text-sm leading-6">
                <?php foreach ( $footer_links['support'] as $item ) : ?>
                <li><a href="<?php echo esc_url( $item['url'] ); ?>" class="text-stilco-dark/80 underline decoration-stilco-dark/40 underline-offset-[6px] hover:text-stilco-accent hover:decoration-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm"><?php echo esc_html( $item['label'] ); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="max-w-7xl mx-auto border-t border-stilco-dark/10 mt-16 pt-8 flex flex-col md:flex-row justify-between items-center gap-6 text-center md:text-left">
        <p class="text-xs uppercase tracking-[0.08em] leading-5 text-stilco-dark/70">&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( stilco_get_setting( 'footer_copyright_text', 'Stilco' ) ); ?><br><?php echo esc_html( stilco_get_setting( 'footer_made_in_poland_text', 'Made in Poland' ) ); ?></p>

        <div class="flex flex-wrap justify-center items-center gap-4 text-[11px] uppercase tracking-[0.12em] text-stilco-dark/70">
            <span><?php echo esc_html( stilco_get_setting( 'footer_security_text', 'Bezpieczne płatności SSL' ) ); ?></span>
            <span><?php echo esc_html( stilco_get_setting( 'footer_payment_method_1', 'BLIK' ) ); ?></span>
            <span><?php echo esc_html( stilco_get_setting( 'footer_payment_method_2', 'Przelewy24' ) ); ?></span>
            <span><?php echo esc_html( stilco_get_setting( 'footer_payment_method_3', 'Visa / Mastercard' ) ); ?></span>
        </div>

        <div class="flex gap-6 text-sm">
            <?php foreach ( $footer_links['legal'] as $item ) : ?>
            <a href="<?php echo esc_url( $item['url'] ); ?>" class="text-stilco-dark/80 underline decoration-stilco-dark/40 underline-offset-[6px] hover:text-stilco-accent hover:decoration-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm"><?php echo esc_html( $item['label'] ); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</footer>
<?php endif; ?>

<?php get_template_part( 'template-parts/footer/cart-drawer' ); ?>
<?php get_template_part( 'template-parts/footer/chat-widget' ); ?>

<?php wp_footer(); ?>
</body>

</html>
