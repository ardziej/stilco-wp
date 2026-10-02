<?php
/**
 * Import product photos into the media library and set them as the
 * product's main image and gallery, in file-name order.
 *
 *   docker cp docs/mattresses stilco-wp-preview:/tmp/mattresses
 *   docker cp scripts/set_product_gallery.php stilco-wp-preview:/tmp/
 *   docker exec -u www-data stilco-wp-preview php /tmp/set_product_gallery.php /tmp/mattresses [product-slug]
 *
 * On the server: same, with wp-load.php's path adjusted below.
 *
 * Safe to re-run: a file already imported (matched by its original name)
 * is reused, not uploaded twice. Alt texts come from ALT below.
 */

require_once '/var/www/html/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

const ALT = array(
	'01-materac-stilco-w-sypialni.jpg'            => 'Materac Stilco na łóżku w jasnej sypialni',
	'02-materac-stilco-front.jpg'                 => 'Materac Stilco od frontu, widać biały wierzch i granatowy bok',
	'03-materac-stilco-bok-white-blue.jpg'        => 'Bok materaca Stilco: strona White u góry, strona Blue u dołu, metka Stilco',
	'04-materac-stilco-naroznik.jpg'              => 'Narożnik materaca Stilco z metką',
	'05-materac-stilco-zdejmowany-pokrowiec.jpg'  => 'Zdejmowanie pokrowca materaca Stilco po rozpięciu zamka',
	'06-materac-stilco-tkanina-pokrowca.jpg'      => 'Tkanina pokrowca materaca Stilco z bliska',
	'07-materac-stilco-odpoczynek.jpg'            => 'Kobieta odpoczywająca na materacu Stilco',
);

$dir  = rtrim( $argv[1] ?? '/tmp/mattresses', '/' );
$slug = $argv[2] ?? 'materac-stilco';

$product = get_page_by_path( $slug, OBJECT, 'product' );
if ( ! $product ) {
	fwrite( STDERR, "Product '$slug' not found\n" );
	exit( 1 );
}

$files = glob( $dir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE );
sort( $files );
if ( ! $files ) {
	fwrite( STDERR, "No images in $dir\n" );
	exit( 1 );
}

$ids = array();
foreach ( $files as $file ) {
	$name     = basename( $file );
	$existing = get_posts(
		array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_key'    => '_stilco_source_file',
			'meta_value'  => $name,
		)
	);

	if ( $existing ) {
		$id = (int) $existing[0];
		echo "reuse  $id  $name\n";
	} else {
		// media_handle_sideload() moves the file, so hand it a copy.
		$tmp = wp_tempnam( $name );
		copy( $file, $tmp );
		$id = media_handle_sideload( array( 'name' => $name, 'tmp_name' => $tmp ), $product->ID );
		if ( is_wp_error( $id ) ) {
			fwrite( STDERR, "fail   $name: " . $id->get_error_message() . "\n" );
			exit( 1 );
		}
		update_post_meta( $id, '_stilco_source_file', $name );
		echo "import $id  $name\n";
	}

	if ( isset( ALT[ $name ] ) ) {
		update_post_meta( $id, '_wp_attachment_image_alt', ALT[ $name ] );
	}
	$ids[] = $id;
}

$wc_product = wc_get_product( $product->ID );
$wc_product->set_image_id( array_shift( $ids ) );
$wc_product->set_gallery_image_ids( $ids );
$wc_product->save();

echo 'main ' . $wc_product->get_image_id() . ', gallery ' . implode( ',', $wc_product->get_gallery_image_ids() ) . "\n";
