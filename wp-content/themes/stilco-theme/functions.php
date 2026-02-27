<?php
/**
 * Stilco Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Dołączanie własnych CPT i zakładek Panelu
require_once get_template_directory() . '/inc/cpt-faq.php';

/**
 * Konfiguracja wsparcia motywu
 */
function stilco_setup() {
    // Wsparcie podstawowe
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    add_theme_support( 'custom-logo' );

    // Rejestracja menu
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'stilco' ),
        'footer'  => __( 'Footer Menu', 'stilco' ),
    ) );

    // Wsparcie WooCommerce
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 600,
        'single_image_width'    => 800,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ),
    ) );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'stilco_setup' );

function stilco_enqueue_scripts() {
/**
 * Ładowanie zasobów kompilowanych przez Vite
 */
    // Dynamiczne wczytywanie pakietów z manifest.json generowanego przez Vite
    $dist_path = get_template_directory() . '/dist/';
    $dist_url  = get_template_directory_uri() . '/dist/';
    $manifest_path = $dist_path . '.vite/manifest.json';
    
    if ( file_exists( $manifest_path ) ) {
        $manifest = json_decode( file_get_contents( $manifest_path ), true );
        
        if ( is_array( $manifest ) ) {
            // JS 
            if ( isset( $manifest['assets/js/app.js']['file'] ) ) {
                wp_enqueue_script( 'stilco-app', $dist_url . $manifest['assets/js/app.js']['file'], array('jquery'), null, true );
            }
            // CSS
            if ( isset( $manifest['assets/css/app.css']['file'] ) ) {
                wp_enqueue_style( 'stilco-style', $dist_url . $manifest['assets/css/app.css']['file'], array(), null );
            }
        }
    }
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_scripts', 100 );

// Wyłączenie domyślnych styli WooCommerce aby wdrożyć własne z Tailwinda
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Inline CSS overrides for WooCommerce Checkout Block
 * Injected after the compiled stylesheet for highest specificity.
 */
function stilco_checkout_block_css() {
    if ( ! function_exists('is_checkout') || ! is_checkout() || is_wc_endpoint_url('order-received') ) {
        return;
    }

    $css = '
        /* Hide "Kasa" page title */
        .woocommerce-checkout .entry-title,
        .woocommerce-checkout h1,
        body.woocommerce-checkout h1 { display: none !important; }

        /* Widen block container */
        .wp-block-woocommerce-checkout {
            max-width: 1280px !important;
            width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding: 3rem 2rem !important;
            box-sizing: border-box !important;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        /* Force 50/50 side-by-side layout on the sidebar-layout container */
        .wc-block-components-sidebar-layout {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            gap: 0 !important;
            align-items: flex-start !important;
            position: relative !important;
        }

        /* Left column: form fields */
        .wc-block-components-sidebar-layout .wc-block-components-main {
            width: 50% !important;
            flex: 0 0 50% !important;
            min-width: 0 !important;
            padding-right: 3rem !important;
            max-width: 50% !important;
        }

        /* Right column: sidebar (order summary) */
        .wc-block-components-sidebar-layout > .wc-block-checkout__sidebar {
            width: 50% !important;
            flex: 0 0 50% !important;
            min-width: 0 !important;
            padding-left: 3rem !important;
            max-width: 50% !important;
        }

        /* Vertical divider between columns */
        .wc-block-components-sidebar-layout::after {
            content: "";
            position: absolute;
            top: 0; bottom: 0; left: 50%;
            width: 1px;
            background-color: #e5e7eb;
            pointer-events: none;
        }

        /* Remove card/frame from order summary block */
        .wc-block-checkout__sidebar,
        .wc-block-checkout__sidebar > div,
        .wc-block-checkout__sidebar .wc-block-components-panel {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }
    ';

    wp_add_inline_style( 'stilco-style', $css );

    // JS: hide the heading that React renders as h1
    add_action( 'wp_footer', function() {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var h1s = document.querySelectorAll("h1");
                h1s.forEach(function(h) {
                    if (h.textContent.trim() === "Kasa" || h.textContent.trim() === "Checkout") {
                        h.style.display = "none";
                    }
                });
            });
            // Also try immediately
            var h1s = document.querySelectorAll("h1");
            h1s.forEach(function(h) {
                if (h.textContent.trim() === "Kasa" || h.textContent.trim() === "Checkout") {
                    h.style.display = "none";
                }
            });
        </script>';
    }, 5 );
}
add_action( 'wp_enqueue_scripts', 'stilco_checkout_block_css', 200 );

/**
 * WooCommerce AJAX Cart Fragments Refresh
 * Dodaje dynamiczną aktualizację ikony koszyka w nagłówku.
 */
function stilco_cart_fragments( $fragments ) {
    ob_start();
    $count = WC()->cart->get_cart_contents_count();
    ?>
    <span class="cart-contents-count absolute -top-2 -right-2 bg-stilco-accent text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full mt-0 <?php echo $count == 0 ? 'hidden' : ''; ?>">
        <?php echo esc_html( $count ); ?>
    </span>
    <?php
    $fragments['span.cart-contents-count'] = ob_get_clean();
    
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'stilco_cart_fragments' );

// Custom button classes dla mini cart
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );

function stilco_custom_mini_cart_buttons() {
    echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="btn-outline w-full text-center py-3">Zobacz Koszyk</a>';
    echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="btn-primary w-full text-center py-3">Przejdź do Kasy</a>';
}
add_action( 'woocommerce_widget_shopping_cart_buttons', 'stilco_custom_mini_cart_buttons', 20 );

/**
 * Reviews: Add photo upload support
 */
add_action( 'wp_footer', 'stilco_add_enctype_to_comment_form' );
function stilco_add_enctype_to_comment_form() {
    if ( is_product() ) {
        echo '<script>
            var f = document.getElementById("commentform");
            if (f) { f.setAttribute("enctype", "multipart/form-data"); }
        </script>';
    }
}

add_action( 'comment_form_logged_in_after', 'stilco_add_review_image_field' );
add_action( 'comment_form_after_fields', 'stilco_add_review_image_field' );
function stilco_add_review_image_field() {
    echo '<p class="comment-form-image mb-4 mt-4">
            <label for="review_image" class="block text-sm font-medium text-gray-700 mb-1">Dodaj zdjęcie produktu (opcjonalnie)</label>
            <input type="file" name="review_image" id="review_image" accept="image/jpeg,image/png,image/webp" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-stilco-accent/10 file:text-stilco-accent hover:file:bg-stilco-accent/20" />
          </p>';
}

add_action( 'rest_api_init', 'stilco_register_review_image_meta' );
function stilco_register_review_image_meta() {
    register_meta('comment', '_review_image_id', array(
        'type' => 'integer',
        'description' => 'Review Image Attachment ID',
        'single' => true,
        'show_in_rest' => true
    ));
}

add_action( 'comment_post', 'stilco_save_review_image' );
function stilco_save_review_image( $comment_id ) {
    if ( isset($_FILES['review_image']) && !empty($_FILES['review_image']['name']) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        $attachment_id = media_handle_upload( 'review_image', 0 );
        if ( !is_wp_error( $attachment_id ) ) {
            add_comment_meta( $comment_id, '_review_image_id', $attachment_id );
        }
    }
}

add_action( 'woocommerce_review_after_comment_text', 'stilco_display_review_image', 10, 1 );
function stilco_display_review_image( $comment ) {
    $image_id = get_comment_meta( $comment->comment_ID, '_review_image_id', true );
    if ( $image_id ) {
        echo '<div class="review-image mt-4 mb-4">';
        echo wp_get_attachment_image( $image_id, 'medium', false, array('class' => 'rounded-xl shadow-sm max-w-full h-auto max-h-48 object-cover') );
        echo '</div>';
    }
}

/**
 * Get highlighted reviews for Home Page
 */
function stilco_get_highlighted_reviews( $limit = 4 ) {
    $args = array(
        'status' => 'approve',
        'type' => 'review',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'rating',
                'value' => '5',
                'compare' => '='
            ),
            array(
                'key' => '_review_image_id',
                'compare' => 'EXISTS'
            )
        ),
        'number' => $limit,
    );
    return get_comments( $args );
}

/**
 * Minimalize Checkout Fields
 */
add_filter( 'woocommerce_checkout_fields' , 'stilco_minimal_checkout_fields' );
function stilco_minimal_checkout_fields( $fields ) {
    // Usunięcie zbędnych pól
    unset($fields['billing']['billing_address_2']); // "Apartment, suite, unit etc."
    unset($fields['shipping']['shipping_address_2']);
    
    // Można usunąć nazwę firmy jeśli sklep jest w 100% B2C, ale na razie zostawiamy jako opcjonalne.
    // unset($fields['billing']['billing_company']);
    
    // Uproszczenie labeli (opcjonalnie)
    if(isset($fields['order']['order_comments'])) {
        $fields['order']['order_comments']['placeholder'] = 'Dodatkowe uwagi do zamówienia (opcjonalnie)';
        $fields['order']['order_comments']['label'] = 'Uwagi';
    }

    return $fields;
}
