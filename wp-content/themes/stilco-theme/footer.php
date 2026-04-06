</div> <!-- Zamykanie main-content z header.php -->

<?php if ( ! stilco_is_live_checkout_page() ) : ?>
<?php
$footer_links = stilco_get_footer_link_groups();
?>
<footer class="bg-stilco-sand text-stilco-dark pt-24 pb-12 px-6 md:px-12 mt-20 border-t border-stilco-secondary/20 rounded-t-[3rem]">
    <!-- Newsletter Warstwa Górna -->
    <div class="max-w-4xl mx-auto text-center mb-24">
        <h2 class="text-4xl md:text-6xl font-serif font-bold mb-6 text-stilco-dark tracking-tight leading-tight"><?php echo esc_html( stilco_get_setting( 'footer_newsletter_title', 'Obudź się z pomysłem na lepszy sen.' ) ); ?></h2>
        <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto"><?php echo esc_html( stilco_get_setting( 'footer_newsletter_lead', 'Odbierz 10% rabatu na pierwsze zamówienie. Zapisz się do naszego biuletynu pełnego wskazówek jak podnieść jakość wypoczynku.' ) ); ?></p>
        <form class="flex flex-col sm:flex-row max-w-lg mx-auto gap-3">
            <input type="email" placeholder="<?php echo esc_attr( stilco_get_setting( 'footer_newsletter_placeholder', 'Twój adres e-mail' ) ); ?>" aria-label="Adres e-mail"
                class="w-full px-6 py-4 bg-white border border-gray-200 rounded-full text-stilco-dark focus:ring-2 focus:ring-stilco-accent focus:border-transparent outline-none shadow-sm transition-all focus:shadow-md">
            <button type="submit"
                class="bg-stilco-accent px-8 py-4 rounded-full font-medium text-white hover:bg-[#A84A34] transition-colors whitespace-nowrap shadow-lg shadow-stilco-accent/30 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-dark">
                <?php echo esc_html( stilco_get_setting( 'footer_newsletter_button_text', 'Zapisz się' ) ); ?>
            </button>
        </form>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 border-t border-stilco-dark/10 pt-16">
        <!-- Kolumna 1: Brand -->
        <div class="footer-brand space-y-4">
            <h3 class="text-3xl font-display font-bold tracking-tighter"><?php echo esc_html( stilco_get_setting( 'footer_brand_title', 'STILCO' ) ); ?></h3>
            <p class="text-sm text-gray-600 leading-relaxed pr-6"><?php echo esc_html( stilco_get_setting( 'footer_brand_text', 'Twoje królestwo snu. Projektujemy organiczne materace dla idealnego, nocnego wypoczynku we dwoje. Polska produkcja.' ) ); ?></p>
        </div>

        <!-- Kolumna 2: Sklep -->
        <div class="footer-links">
            <h4 class="font-display font-semibold text-lg mb-6 text-stilco-dark"><?php echo esc_html( stilco_get_setting( 'footer_shop_title', 'Sklep' ) ); ?></h4>
            <ul class="space-y-3 text-sm text-gray-600 font-medium">
                <?php foreach ( $footer_links['shop'] as $item ) : ?>
                <li><a href="<?php echo esc_url( $item['url'] ); ?>" class="hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm px-1 -mx-1"><?php echo esc_html( $item['label'] ); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Kolumna 3: Firma -->
        <div class="footer-company">
            <h4 class="font-display font-semibold text-lg mb-6 text-stilco-dark"><?php echo esc_html( stilco_get_setting( 'footer_company_title', 'Firma' ) ); ?></h4>
            <ul class="space-y-3 text-sm text-gray-600 font-medium">
                <?php foreach ( $footer_links['company'] as $item ) : ?>
                <li><a href="<?php echo esc_url( $item['url'] ); ?>" class="hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm px-1 -mx-1"><?php echo esc_html( $item['label'] ); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Kolumna 4: Pomoc -->
        <div class="footer-contact">
            <h4 class="font-display font-semibold text-lg mb-6 text-stilco-dark"><?php echo esc_html( stilco_get_setting( 'footer_support_title', 'Wsparcie' ) ); ?></h4>
            <ul class="space-y-3 text-sm text-gray-600 font-medium">
                <?php foreach ( $footer_links['support'] as $item ) : ?>
                <li><a href="<?php echo esc_url( $item['url'] ); ?>" class="hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm px-1 -mx-1"><?php echo esc_html( $item['label'] ); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Warstwa Dolna (Prawa autorskie i płatności) -->
    <div
        class="max-w-7xl mx-auto border-t border-stilco-dark/10 mt-16 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 gap-6">
        <p class="font-medium text-gray-400">&copy; <?php echo esc_html( date('Y') ); ?> <?php echo esc_html( stilco_get_setting( 'footer_copyright_text', 'Stilco. Wszelkie prawa zastrzeżone.' ) ); ?><br/><span class="text-stilco-dark/60 mt-1 block"><?php echo esc_html( stilco_get_setting( 'footer_made_in_poland_text', 'Wyprodukowano z ❤️ w Polsce.' ) ); ?></span></p>
        
        <div class="flex items-center space-x-4 opacity-50 grayscale hover:grayscale-0 transition-all">
            <!-- Miejsce na SVG logotypów płatności (BLIK, Visa, itp.) -->
            <span><?php echo esc_html( stilco_get_setting( 'footer_security_text', '🛡️ Bezpieczne Płatności SSL' ) ); ?></span>
            <span><?php echo esc_html( stilco_get_setting( 'footer_payment_method_1', 'BLIK' ) ); ?></span>
            <span><?php echo esc_html( stilco_get_setting( 'footer_payment_method_2', 'Przelewy24' ) ); ?></span>
            <span><?php echo esc_html( stilco_get_setting( 'footer_payment_method_3', 'Visa/Mastercard' ) ); ?></span>
        </div>

        <div class="flex space-x-6">
            <?php foreach ( $footer_links['legal'] as $item ) : ?>
            <a href="<?php echo esc_url( $item['url'] ); ?>" class="hover:text-stilco-dark transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm"><?php echo esc_html( $item['label'] ); ?></a>
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
