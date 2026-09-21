<?php
/**
 * "Twój rozmiar" custom-size enquiry on the product page.
 *
 * Added on review comment J1: picking a custom size swaps the cart for a
 * form, and the enquiry lands in the service mailbox as a request for an
 * individual quote. Nothing is added to the cart and no order is created.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default dimensions prefilled in the form, in centimetres.
 *
 * @return array{length:int, width:int, height:int}
 */
function stilco_custom_size_defaults() {
	return array(
		'length' => (int) stilco_get_setting( 'custom_size_default_length', 200 ),
		'width'  => (int) stilco_get_setting( 'custom_size_default_width', 160 ),
		'height' => (int) stilco_get_setting( 'custom_size_default_height', 22 ),
	);
}

/**
 * Where custom-size enquiries are sent.
 *
 * Reuses the review form recipients so both forms land in one mailbox
 * unless a dedicated address is configured.
 *
 * @return array<int, string>
 */
function stilco_get_custom_size_recipients() {
	$configured = (string) stilco_get_setting( 'custom_size_recipient', '' );

	if ( '' !== trim( $configured ) ) {
		$emails = array_map( 'trim', explode( ',', $configured ) );
		$emails = array_values( array_filter( array_unique( $emails ), 'is_email' ) );

		if ( $emails ) {
			return $emails;
		}
	}

	return stilco_get_review_form_recipients();
}

/**
 * Validate one dimension value.
 *
 * @param mixed $value Raw value in centimetres.
 * @param int   $min   Lower bound.
 * @param int   $max   Upper bound.
 * @return int|null Null when out of range.
 */
function stilco_validate_dimension( $value, $min, $max ) {
	$number = (int) $value;

	return ( $number >= $min && $number <= $max ) ? $number : null;
}

/**
 * Handle a custom-size enquiry submission.
 *
 * @return void
 */
function stilco_handle_custom_size_submission() {
	if ( ! isset( $_POST['stilco_custom_size_form'] ) ) {
		return;
	}

	$redirect = isset( $_POST['stilco_custom_size_redirect'] )
		? esc_url_raw( wp_unslash( $_POST['stilco_custom_size_redirect'] ) )
		: home_url( '/' );

	if ( ! isset( $_POST['stilco_custom_size_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stilco_custom_size_nonce'] ) ), 'stilco_custom_size_form' ) ) {
		wp_safe_redirect( add_query_arg( 'wycena', 'blad', $redirect ) . '#twoj-rozmiar' );
		exit;
	}

	// Honeypot.
	if ( ! empty( $_POST['stilco_custom_size_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'wycena', 'wyslana', $redirect ) . '#twoj-rozmiar' );
		exit;
	}

	$name    = isset( $_POST['custom_size_name'] ) ? sanitize_text_field( wp_unslash( $_POST['custom_size_name'] ) ) : '';
	$email   = isset( $_POST['custom_size_email'] ) ? sanitize_email( wp_unslash( $_POST['custom_size_email'] ) ) : '';
	$phone   = isset( $_POST['custom_size_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['custom_size_phone'] ) ) : '';
	$comment = isset( $_POST['custom_size_comment'] ) ? sanitize_textarea_field( wp_unslash( $_POST['custom_size_comment'] ) ) : '';
	$consent = ! empty( $_POST['custom_size_consent'] );

	$length = stilco_validate_dimension( $_POST['custom_size_length'] ?? 0, 60, 260 );
	$width  = stilco_validate_dimension( $_POST['custom_size_width'] ?? 0, 60, 260 );
	$height = stilco_validate_dimension( $_POST['custom_size_height'] ?? 0, 10, 40 );

	$errors = array();

	if ( '' === $name ) {
		$errors[] = 'Podaj imię i nazwisko.';
	}

	if ( ! is_email( $email ) ) {
		$errors[] = 'Podaj poprawny adres e-mail.';
	}

	if ( '' === $phone ) {
		$errors[] = 'Podaj numer telefonu.';
	}

	if ( null === $length || null === $width ) {
		$errors[] = 'Długość i szerokość podaj w centymetrach, w zakresie 60–260.';
	}

	if ( null === $height ) {
		$errors[] = 'Wysokość podaj w centymetrach, w zakresie 10–40.';
	}

	if ( ! $consent ) {
		$errors[] = 'Potrzebujemy zgody na przetwarzanie danych.';
	}

	if ( $errors ) {
		set_transient( 'stilco_custom_size_errors_' . stilco_review_client_key(), $errors, 5 * MINUTE_IN_SECONDS );
		wp_safe_redirect( add_query_arg( 'wycena', 'blad', $redirect ) . '#twoj-rozmiar' );
		exit;
	}

	$body = implode(
		"\n",
		array(
			'Zapytanie o materac w niestandardowym rozmiarze.',
			'',
			'Imię i nazwisko: ' . $name,
			'E-mail: ' . $email,
			'Telefon: ' . $phone,
			'',
			sprintf( 'Wymiary: %d x %d x %d cm (długość x szerokość x wysokość)', $length, $width, $height ),
			'',
			'Komentarz:',
			'' !== $comment ? $comment : '(brak)',
			'',
			'Strona: ' . $redirect,
			'Wysłano: ' . current_time( 'Y-m-d H:i' ),
		)
	);

	wp_mail(
		stilco_get_custom_size_recipients(),
		sprintf( 'Wycena indywidualna %dx%dx%d cm — %s', $length, $width, $height, $name ),
		$body,
		array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $name . ' <' . $email . '>' )
	);

	wp_safe_redirect( add_query_arg( 'wycena', 'wyslana', $redirect ) . '#twoj-rozmiar' );
	exit;
}
add_action( 'template_redirect', 'stilco_handle_custom_size_submission' );

/**
 * Read and clear stashed custom-size validation errors.
 *
 * @return array<int, string>
 */
function stilco_take_custom_size_errors() {
	$key    = 'stilco_custom_size_errors_' . stilco_review_client_key();
	$errors = get_transient( $key );

	if ( ! is_array( $errors ) ) {
		return array();
	}

	delete_transient( $key );

	return $errors;
}

/**
 * Enqueue the panel toggle script on the product page.
 *
 * @return void
 */
function stilco_enqueue_custom_size_assets() {
	if ( is_admin() || ! stilco_is_single_product_page() ) {
		return;
	}

	wp_enqueue_style(
		'stilco-custom-size',
		stilco_get_theme_asset_uri( 'assets/css/custom-size.css' ),
		array( 'stilco-style' ),
		stilco_get_theme_asset_version( 'assets/css/custom-size.css' )
	);

	wp_enqueue_script(
		'stilco-custom-size',
		stilco_get_theme_asset_uri( 'assets/js/custom-size.js' ),
		array(),
		stilco_get_theme_asset_version( 'assets/js/custom-size.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_custom_size_assets', 130 );
