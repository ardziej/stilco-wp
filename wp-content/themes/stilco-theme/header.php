<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class('font-sans bg-stilco-light text-stilco-dark'); ?>>
    <?php wp_body_open(); ?>

	    <?php if ( stilco_is_live_checkout_page() ) : ?>
        <header id="checkout-header" class="fixed top-0 z-50 w-full flex items-center justify-between px-6 py-4 md:px-12 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
            <div class="w-1/3"></div>
            <div class="w-1/3 flex justify-center">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="block focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm">
                    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logo.svg" alt="Stilco Logo" class="h-8 md:h-10 w-auto">
                </a>
            </div>
            <div class="w-1/3 flex justify-end">
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="flex items-center space-x-2 text-gray-500 hover:text-stilco-dark transition-colors duration-300">
                    <span class="text-sm font-medium hidden md:inline">Wróć do koszyka</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </a>
            </div>
        </header>
        <div id="main-content" class="min-h-screen flex flex-col w-full pt-[80px] md:pt-[96px] bg-stilco-sand">
    <?php else : ?>
        <?php 
        $is_transparent_header = stilco_is_transparent_header_context();
        // Light hero (new home design): header stays translucent but keeps dark text and logo.
        $is_light_hero         = $is_transparent_header && stilco_is_light_hero_context();

        if ( $is_light_hero ) {
            $header_classes = 'transition-all duration-500 bg-white/60 backdrop-blur-md border-b border-white/40 text-stilco-dark';
            $logo_classes   = 'transition-all duration-300';
        } elseif ( $is_transparent_header ) {
            $header_classes = 'transition-all duration-500 bg-white/5 backdrop-blur-md border-b border-white/10 text-white';
            $logo_classes   = 'transition-all duration-300 invert brightness-0';
        } else {
            $header_classes = 'bg-white/95 backdrop-blur-md text-stilco-dark border-b border-gray-100 shadow-sm';
            $logo_classes   = '';
        }

        $main_padding = $is_transparent_header ? '' : 'pt-[88px] md:pt-[104px]';
        $header_cta   = stilco_get_header_cta();
        ?>
        <header id="main-header"
            data-hero-tone="<?php echo esc_attr( $is_light_hero ? 'light' : 'dark' ); ?>"
            class="fixed top-0 z-50 w-full px-6 py-4 md:px-12 shadow-[0_4px_30px_rgba(0,0,0,0.1)] <?php echo esc_attr( $header_classes ); ?>">
            <div class="flex items-center justify-between gap-6">
            <div class="flex items-center gap-10">
                <div class="header-logo">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="block focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm">
                        <img id="logo-img" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logo.svg" alt="Stilco Logo" class="h-10 w-auto <?php echo esc_attr( $logo_classes ); ?>">
                    </a>
                </div>

                <nav class="header-nav hidden md:flex items-center" aria-label="Menu główne">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'primary',
                            'container'      => false,
                            'menu_class'     => 'flex space-x-6 text-sm font-medium tracking-wide',
                            'fallback_cb'    => false,
                        )
                    );
                    ?>
                </nav>
            </div>

            <a href="<?php echo esc_url( $header_cta['url'] ); ?>" class="header-cta hidden md:inline-flex items-center justify-center rounded-full bg-stilco-dark px-7 py-3 text-xs font-bold uppercase tracking-[0.12em] text-white shadow-lg shadow-black/20 transition-colors duration-300 hover:bg-stilco-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-accent">
                <?php echo esc_html( $header_cta['label'] ); ?>
            </a>

            <div class="header-actions flex items-center space-x-4">
                <!-- Account Icon -->
                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="text-current hover:text-stilco-accent transition-colors duration-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 drop-shadow-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </a>

                <!-- Koszyk Icon Toggle -->
                <button id="cart-toggle-btn" class="relative group focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm p-1 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-current group-hover:text-stilco-accent transition-colors duration-300 drop-shadow-sm"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <?php if ( class_exists( 'WooCommerce' ) && ! is_null( WC()->cart ) ) : 
                        $cart_count = WC()->cart->get_cart_contents_count();
                    ?>
                    <span
                        class="cart-contents-count absolute -top-2 -right-2 bg-stilco-accent text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full shadow-md mt-0 <?php echo $cart_count == 0 ? 'hidden' : ''; ?>">
                        <?php echo esc_html( $cart_count ); ?>
                    </span>
                    <?php endif; ?>
                </button>

                <!-- Mobile Menu Toggle -->
                <button id="mobile-menu-toggle" type="button" class="md:hidden text-current focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm p-1" aria-controls="mobile-menu" aria-expanded="false" aria-label="Otwórz menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-current" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
            </div>

            <div id="mobile-menu" class="md:hidden hidden absolute left-0 top-full w-full border-t border-gray-100 bg-white px-6 py-6 text-stilco-dark shadow-xl">
                <nav aria-label="Menu mobilne">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'primary',
                            'container'      => false,
                            'menu_class'     => 'flex flex-col gap-4 text-base font-medium tracking-wide',
                            'fallback_cb'    => false,
                        )
                    );
                    ?>
                </nav>
                <a href="<?php echo esc_url( $header_cta['url'] ); ?>" class="mt-6 flex items-center justify-center rounded-full bg-stilco-dark px-7 py-3 text-xs font-bold uppercase tracking-[0.12em] text-white shadow-lg shadow-black/20 transition-colors duration-300 hover:bg-stilco-accent">
                    <?php echo esc_html( $header_cta['label'] ); ?>
                </a>
            </div>
        </header>

        <div id="main-content" class="min-h-screen flex flex-col w-full <?php echo esc_attr( $main_padding ); ?>">
    <?php endif; ?>
