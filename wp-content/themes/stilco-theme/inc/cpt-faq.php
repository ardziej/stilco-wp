<?php
/**
 * Rejestracja struktury dla sekcji FAQ (Custom Post Type & Taxonomy)
 * Dodatkowo dodajemy dedykowaną zakładkę "Stilco" w menu panelu administracyjnym.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Rejestracja Taksonomii dla FAQ - np. Kategorie (Logistyka, Produkt, etc.)
 */
function stilco_register_faq_taxonomy() {
    $labels = array(
        'name'              => _x( 'Kategorie FAQ', 'taxonomy general name', 'stilco' ),
        'singular_name'     => _x( 'Kategoria', 'taxonomy singular name', 'stilco' ),
        'search_items'      => __( 'Szukaj Kategorii', 'stilco' ),
        'all_items'         => __( 'Wszystkie Kategorie', 'stilco' ),
        'parent_item'       => __( 'Kategoria Nadrzędna', 'stilco' ),
        'parent_item_colon' => __( 'Kategoria Nadrzędna:', 'stilco' ),
        'edit_item'         => __( 'Edytuj Kategorię', 'stilco' ),
        'update_item'       => __( 'Aktualizuj Kategorię', 'stilco' ),
        'add_new_item'      => __( 'Dodaj Nową Kategorię', 'stilco' ),
        'new_item_name'     => __( 'Nazwa Nowej Kategorii', 'stilco' ),
        'menu_name'         => __( 'Kategorie FAQ', 'stilco' ),
    );

    $args = array(
        'hierarchical'      => true, // Tak jak kategorie w WP
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'faq-category' ),
        'show_in_rest'      => true, // Dla edytora Gutenberg i WP API
    );

    register_taxonomy( 'faq_category', array( 'faq' ), $args );
}
add_action( 'init', 'stilco_register_faq_taxonomy', 0 );

/**
 * Rejestracja Custom Post Type dla FAQ
 */
function stilco_register_faq_cpt() {
    $labels = array(
        'name'                  => _x( 'FAQ (Pytania i Odpowiedzi)', 'Post type general name', 'stilco' ),
        'singular_name'         => _x( 'Pytanie', 'Post type singular name', 'stilco' ),
        'menu_name'             => _x( 'FAQ', 'Admin Menu text', 'stilco' ),
        'name_admin_bar'        => _x( 'Pytanie FAQ', 'Add New on Toolbar', 'stilco' ),
        'add_new'               => __( 'Dodaj nowe', 'stilco' ),
        'add_new_item'          => __( 'Dodaj nowe Pytanie', 'stilco' ),
        'new_item'              => __( 'Nowe Pytanie', 'stilco' ),
        'edit_item'             => __( 'Edytuj Pytanie', 'stilco' ),
        'view_item'             => __( 'Zobacz Pytanie', 'stilco' ),
        'all_items'             => __( 'Wszystkie Pytania (FAQ)', 'stilco' ),
        'search_items'          => __( 'Szukaj Pytań', 'stilco' ),
        'parent_item_colon'     => __( 'Nadrzędne Pytanie:', 'stilco' ),
        'not_found'             => __( 'Nie znaleziono żadnych pytań.', 'stilco' ),
        'not_found_in_trash'    => __( 'Brak pytań w koszu.', 'stilco' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => 'stilco_settings', // Istotne: Przypisanie jako podmenu do głównej zakładki "Stilco"
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'faq' ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor' ), // Tytuł to Pytanie, edytor to Odpowiedź
        'show_in_rest'       => true, // Ważne: dla nowego edytora Gutenberg
    );

    register_post_type( 'faq', $args );
}
// Uruchamiamy po menu z priorytetem 10
add_action( 'init', 'stilco_register_faq_cpt', 10 );

/**
 * Dodanie głównej zakładki (Menu najwyższego poziomu) - "Stilco"
 */
function stilco_register_main_admin_menu() {
    add_menu_page(
        __( 'Zarządzanie marką Stilco', 'stilco' ), // Title strony
        'Stilco', // Nazwa zakładki w wp-admin
        'manage_options', // Wymagane uprawnienie
        'stilco_settings', // Szzgólny / unikalny śllug (odniesienie)
        'stilco_settings_page_callback', // Funkcja budująca domyślny widok (może być pusta jeżeli chcemy tylko przekierować)
        'dashicons-star-filled', // Ikona
        4 // Pozycja zaraz pod Kokpitem
    );
}

// Callback tylko jeśli użytkownik wejdzie bezpośrednio na /wp-admin/admin.php?page=stilco_settings
function stilco_settings_page_callback() {
    // Wypisze prostą planszę informacyjną do rozbudowywania o ustawienia API itp
    echo '<div class="wrap">';
    echo '<h1>' . esc_html__( 'Ustawienia marki Stilco', 'stilco' ) . '</h1>';
    echo '<p>' . esc_html__( 'Wybierz podmenu z panelu po lewej stronie, aby zarządzać poszczególnymi elementami strony (np. FAQ).', 'stilco' ) . '</p>';
    echo '</div>';
}
add_action( 'admin_menu', 'stilco_register_main_admin_menu', 9 );

// Upewniamy się, że podkategorie są przypisanie pod nowe menu
function stilco_adjust_faq_tax_menu() {
    add_submenu_page(
        'stilco_settings',
        __( 'Kategorie FAQ', 'stilco' ),
        __( 'Kategorie FAQ', 'stilco' ),
        'manage_categories',
        'edit-tags.php?taxonomy=faq_category&post_type=faq'
    );
}
add_action( 'admin_menu', 'stilco_adjust_faq_tax_menu', 11 );
