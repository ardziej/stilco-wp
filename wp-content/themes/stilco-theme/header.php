<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>

    <!-- Favicon -->
    <?php $favicon_dir = get_template_directory_uri() . '/assets/favicon'; ?>
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url($favicon_dir); ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url($favicon_dir); ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url($favicon_dir); ?>/favicon-16x16.png">
    <link rel="manifest" href="<?php echo esc_url($favicon_dir); ?>/site.webmanifest">
    <link rel="shortcut icon" href="<?php echo esc_url($favicon_dir); ?>/favicon.ico">
    <meta name="theme-color" content="#c85a41">
    <!-- DEV MODE VITE, na produkcji załadujemy skompilowany plik app.css -->
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <link rel="stylesheet" href="http://localhost:5173/assets/css/app.css">
    <script type="module" src="http://localhost:5173/assets/js/app.js"></script>
</head>

<body <?php body_class('font-sans bg-stilco-light text-stilco-dark'); ?>>
    <?php wp_body_open(); ?>

    <?php if ( class_exists( 'WooCommerce' ) && is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) : ?>
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
        $is_transparent_header = is_front_page() || is_home() || is_page_template(array('page-about.php', 'page-contact.php', 'page-faq.php'));
        
        $header_classes = $is_transparent_header 
            ? 'transition-all duration-500 bg-white/5 backdrop-blur-md border-b border-white/10 text-white' 
            : 'bg-white/95 backdrop-blur-md text-stilco-dark border-b border-gray-100 shadow-sm';
        $logo_classes = $is_transparent_header ? 'transition-all duration-300 invert brightness-0' : '';
        $main_padding = $is_transparent_header ? '' : 'pt-[88px] md:pt-[104px]'; 
        ?>
        <header id="main-header"
            class="fixed top-0 z-50 w-full flex items-center justify-between px-6 py-4 md:px-12 shadow-[0_4px_30px_rgba(0,0,0,0.1)] <?php echo esc_attr( $header_classes ); ?>">
            <div class="header-logo">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="block focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm">
                    <img id="logo-img" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logo.svg" alt="Stilco Logo" class="h-10 w-auto <?php echo esc_attr( $logo_classes ); ?>">
                </a>
            </div>

            <nav class="header-nav hidden md:flex items-center space-x-8">
                <?php
    wp_nav_menu(array(
        'theme_location' => 'primary',
        'container' => false,
        'menu_class' => 'flex space-x-6 text-sm font-medium tracking-wide',
        'fallback_cb' => false,
    ));
    ?>
            </nav>

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
                <button class="md:hidden text-current focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-sm p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-current" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </header>

        <style>
            /* Nadpisanie linków nav menu w celu optymalizacji i zachowania pięknego efektu hover z Terakotą */
            .header-nav ul li a {
                position: relative;
                transition: color 0.3s ease;
                outline: none;
            }
            .header-nav ul li a:focus-visible {
                outline: 2px solid #C85A41;
                border-radius: 4px;
                padding: 2px;
            }
            .header-nav ul li a::after {
                content: '';
                position: absolute;
                width: 0;
                height: 1px;
                bottom: -4px;
                left: 0;
                background-color: #C85A41;
                transition: width 0.3s ease;
            }
            .header-nav ul li a:hover::after,
            .header-nav ul li.current-menu-item a::after {
                width: 100%;
            }
            .header-nav ul li:hover a {
                color: #C85A41;
            }
        </style>

        <?php if ( $is_transparent_header ) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const header = document.getElementById('main-header');
                const logo = document.getElementById('logo-img');
                
                const onScroll = () => {
                    if (window.scrollY > 50) {
                        header.classList.remove('bg-white/5', 'text-white', 'border-white/10');
                        header.classList.add('bg-white/90', 'text-stilco-dark', 'border-transparent');
                        logo.classList.remove('invert', 'brightness-0');
                    } else {
                        header.classList.add('bg-white/5', 'text-white', 'border-white/10');
                        header.classList.remove('bg-white/90', 'text-stilco-dark', 'border-transparent');
                        logo.classList.add('invert', 'brightness-0');
                    }
                };
                
                window.addEventListener('scroll', onScroll, { passive: true });
                onScroll(); // trigger immediately on load
            });
        </script>
        <?php endif; ?>

        <div id="main-content" class="min-h-screen flex flex-col w-full <?php echo esc_attr( $main_padding ); ?>">
    <?php endif; ?>