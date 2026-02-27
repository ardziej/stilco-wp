<?php
/**
 * Skrypt dodający utworzone strony do menu Primary i Footer
 */

require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';

echo "Konfiguracja menu...\n";

// Funkcja pomocnicza do pobierania ID strony po slugu
function get_page_id_by_slug($slug) {
    $page = get_page_by_path($slug);
    if ($page) {
        return $page->ID;
    }
    return false;
}

// Konfigurujemy Primary Menu
$primary_menu_name = 'Menu Główne';
$primary_menu_location = 'primary';
$primary_menu_exists = wp_get_nav_menu_object($primary_menu_name);

if (!$primary_menu_exists) {
    $primary_menu_id = wp_create_nav_menu($primary_menu_name);
} else {
    $primary_menu_id = $primary_menu_exists->term_id;
    // Wyczyśćmy to menu przed ponownym dodaniem
    $menu_items = wp_get_nav_menu_items($primary_menu_id);
    foreach ($menu_items as $item) {
        wp_delete_post($item->ID, true);
    }
}

// Zapisz lokalizacje
$locations = get_theme_mod('nav_menu_locations');
$locations[$primary_menu_location] = $primary_menu_id;
set_theme_mod('nav_menu_locations', $locations);

// Elementy dla Primary Menu
wp_update_nav_menu_item($primary_menu_id, 0, array(
    'menu-item-title'   => 'Strona Główna',
    'menu-item-classes' => 'home',
    'menu-item-url'     => home_url( '/' ), 
    'menu-item-status'  => 'publish'
));

$product_slug = 'materac-stilco';
$product_page_url = home_url( '/produkt/' . $product_slug . '/' );

wp_update_nav_menu_item($primary_menu_id, 0, array(
    'menu-item-title'   => 'Materac',
    'menu-item-url'     => $product_page_url,
    'menu-item-status'  => 'publish',
    'menu-item-classes' => 'stilco-main-product'
));

$primary_pages = [
    'O nas' => 'o-nas',
    'FAQ' => 'faq',
    'Kontakt' => 'kontakt'
];

foreach ($primary_pages as $title => $slug) {
    $page_id = get_page_id_by_slug($slug);
    if ($page_id) {
        wp_update_nav_menu_item($primary_menu_id, 0, array(
            'menu-item-title'   => $title,
            'menu-item-object-id' => $page_id,
            'menu-item-object'  => 'page',
            'menu-item-type'    => 'post_type',
            'menu-item-status'  => 'publish'
        ));
    }
}
echo "Primary Menu gotowe.\n";

// Konfigurujemy Footer Menu
$footer_menu_name = 'Menu Stopka';
$footer_menu_location = 'footer';
$footer_menu_exists = wp_get_nav_menu_object($footer_menu_name);

if (!$footer_menu_exists) {
    $footer_menu_id = wp_create_nav_menu($footer_menu_name);
} else {
    $footer_menu_id = $footer_menu_exists->term_id;
    // Wyczyśćmy to menu przed ponownym dodaniem
    $menu_items = wp_get_nav_menu_items($footer_menu_id);
    foreach ($menu_items as $item) {
        wp_delete_post($item->ID, true);
    }
}

$locations[$footer_menu_location] = $footer_menu_id;
set_theme_mod('nav_menu_locations', $locations);

$footer_pages = [
    'Regulamin' => 'regulamin',
    'Polityka Prywatności' => 'polityka-prywatnosci',
    'Zwroty i Reklamacje' => 'zwroty-i-reklamacje'
];

foreach ($footer_pages as $title => $slug) {
    $page_id = get_page_id_by_slug($slug);
    if ($page_id) {
        wp_update_nav_menu_item($footer_menu_id, 0, array(
            'menu-item-title'   => $title,
            'menu-item-object-id' => $page_id,
            'menu-item-object'  => 'page',
            'menu-item-type'    => 'post_type',
            'menu-item-status'  => 'publish'
        ));
    }
}
echo "Footer Menu gotowe.\n";
echo "Menu zaktualizowane pomyślnie!\n";
