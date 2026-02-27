<?php
/**
 * Skrypt ładujący obrazy materacy do Media Library i podpinający do produktu.
 */

// Wczytywanie kontekstu WP z kontenera dockera
require_once( dirname(__FILE__) . '/../../../wp-load.php' );
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );

$product_id = 96; // ID wg ps wyjścia wcześniej
$source_dir_base = '/var/www/html/docs/mattresses/';

// Tymczasowo skopiujemy pliki z hosta do obrazu kontenera, stąd posłużymy się inną ścieżką w terminalu. 
// Najpierw musimy być pewni lokalizacji. Nasz kod działa wewnątrz WP, a WP zmapowany jest na /var/www/html
// Będziemy uploadować pliki z /var/www/html/docs/mattresses bo zmapujemy folder /var/www/html = ./ w docker-compose
// Jednak docs jest PRZED WP, ah. Nie spatchujemy tego tak prosto. Folder "docs" znajduje się obok "wp-content".
// W docker-compose:
// ./wp-content:/var/www/html/wp-content
// wp_core:/var/www/html
// Więc WP kontener NIE WIDZI bezpośrednio folderu docs. Skopiujemy je ręcznie z dockera później. Na potrzeby skryptu założymy, że przerzucimy je do wp-content/uploads/tmp_m/.

$tmp_dir = WP_CONTENT_DIR . '/tmp_m/';
$images = [ '1.jpg', '2.jpg', '3.jpg', '4.jpg' ];
$uploaded_ids = [];

echo "Rozpoczynam import zdjęć z: " . $tmp_dir . "\n";

foreach ( $images as $image ) {
    $file = $tmp_dir . $image;
    
    if ( ! file_exists( $file ) ) {
        echo "Plik nie istnieje: $file \n";
        continue;
    }
    
    // Upload do media library z side-load w locie
    $file_array = [
        'name'     => basename( $file ),
        'tmp_name' => $file,
    ];
    
    // Potrzebujemy "sideload", ale on usuwa pliki. Nie ma to znaczenia, bo to wideo tymczasowe.
    $attachment_id = media_handle_sideload( $file_array, $product_id, "Zdjęcie materaca " . $image );
        
    if ( is_wp_error( $attachment_id ) ) {
        echo "Błąd wgrywania $image : " . $attachment_id->get_error_message() . "\n";
    } else {
        echo "Pomyślnie dodano: $image (ID: $attachment_id)\n";
        $uploaded_ids[] = $attachment_id;
    }
}

if ( !empty($uploaded_ids) ) {
    // Pierwsze zdjęcie jako okładka
    set_post_thumbnail( $product_id, $uploaded_ids[0] );
    echo "Ustawiono miniaturę główną (ID: {$uploaded_ids[0]})\n";
    
    // Pozostałe do galerii WooCommerce
    if ( count($uploaded_ids) > 1 ) {
        $gallery_ids = array_slice($uploaded_ids, 1);
        update_post_meta( $product_id, '_product_image_gallery', implode(',', $gallery_ids) );
        echo "Ustawiono galerię zdjęć z id: " . implode(',', $gallery_ids) . "\n";
    }
} else {
    echo "Brak zdjęć do powiązania.\n";
}

echo "Proces zakończony.\n";
