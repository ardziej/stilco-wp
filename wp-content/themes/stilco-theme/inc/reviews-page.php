<?php
/**
 * Reviews page: listing of every published review and the submission form.
 *
 * Added on review comments J18 (a landing page with a review form that lands
 * in the service mailbox, publishing stays manual) and J19 (the homepage
 * button leads to a page collecting all reviews).
 *
 * Submissions are e-mailed, never auto-published. Uploaded files are attached
 * to that e-mail and removed from disk afterwards, so nothing a visitor sends
 * becomes publicly reachable.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const STILCO_REVIEWS_PER_PAGE      = 12;
const STILCO_REVIEW_MAX_FILES      = 5;
const STILCO_REVIEW_MAX_FILE_BYTES = 10485760; // 10 MB.

/**
 * Allowed upload types for the review form.
 *
 * @return array<string, string> Extension pattern => MIME type.
 */
function stilco_review_allowed_mime_types() {
	return array(
		'jpg|jpeg' => 'image/jpeg',
		'png'      => 'image/png',
		'webp'     => 'image/webp',
		'heic'     => 'image/heic',
		'mp4'      => 'video/mp4',
		'mov'      => 'video/quicktime',
	);
}

/**
 * Check whether the current request renders the reviews page.
 *
 * @return bool
 */
function stilco_is_reviews_page() {
	return ( function_exists( 'is_page_template' ) && is_page_template( 'page-opinie.php' ) )
		|| ( function_exists( 'is_page' ) && is_page( 'opinie' ) );
}

/**
 * Get the permalink of the reviews page.
 *
 * @return string
 */
function stilco_get_reviews_page_url() {
	$page = get_page_by_path( 'opinie' );

	return $page ? get_permalink( $page ) : home_url( '/opinie/' );
}

/**
 * Fetch published reviews for the listing.
 *
 * @param int $paged    Page number, 1-based.
 * @param int $per_page Reviews per page.
 * @return array{reviews: array<int, WP_Comment>, total: int, pages: int}
 */
function stilco_get_all_reviews( $paged = 1, $per_page = STILCO_REVIEWS_PER_PAGE ) {
	$paged    = max( 1, (int) $paged );
	$per_page = max( 1, (int) $per_page );

	$base_args = array(
		'status' => 'approve',
		'type'   => 'review',
	);

	$total = (int) get_comments( array_merge( $base_args, array( 'count' => true ) ) );

	$reviews = get_comments(
		array_merge(
			$base_args,
			array(
				'number'  => $per_page,
				'offset'  => ( $paged - 1 ) * $per_page,
				'orderby' => 'comment_date_gmt',
				'order'   => 'DESC',
			)
		)
	);

	return array(
		'reviews' => is_array( $reviews ) ? $reviews : array(),
		'total'   => $total,
		'pages'   => (int) ceil( $total / $per_page ),
	);
}

/**
 * Average rating across all published reviews.
 *
 * @return array{average: float, count: int}
 */
function stilco_get_reviews_summary() {
	$reviews = get_comments(
		array(
			'status' => 'approve',
			'type'   => 'review',
			'number' => 0,
		)
	);

	$sum   = 0;
	$count = 0;

	foreach ( (array) $reviews as $review ) {
		$rating = (int) get_comment_meta( $review->comment_ID, 'rating', true );

		if ( $rating >= 1 && $rating <= 5 ) {
			$sum += $rating;
			$count++;
		}
	}

	return array(
		'average' => $count ? round( $sum / $count, 1 ) : 0.0,
		'count'   => $count,
	);
}

/**
 * Where review submissions are sent.
 *
 * @return array<int, string>
 */
function stilco_get_review_form_recipients() {
	$configured = (string) stilco_get_setting( 'reviews_form_recipient', '' );

	if ( '' !== trim( $configured ) ) {
		$emails = array_map( 'trim', explode( ',', $configured ) );
	} else {
		$emails = array(
			(string) stilco_get_setting( 'contact_person_1_email', 'daniel@stilco.pl' ),
			(string) stilco_get_setting( 'contact_person_2_email', 'edyta@stilco.pl' ),
		);
	}

	return array_values( array_filter( array_unique( $emails ), 'is_email' ) );
}

/**
 * Normalise the $_FILES entry for the multi-file review upload field.
 *
 * @return array<int, array<string, mixed>>
 */
function stilco_collect_review_upload_entries() {
	if ( empty( $_FILES['review_media'] ) || ! is_array( $_FILES['review_media']['name'] ) ) {
		return array();
	}

	$field   = $_FILES['review_media']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$entries = array();

	foreach ( array_keys( $field['name'] ) as $index ) {
		if ( UPLOAD_ERR_NO_FILE === (int) $field['error'][ $index ] ) {
			continue;
		}

		$entries[] = array(
			'name'     => sanitize_file_name( (string) $field['name'][ $index ] ),
			'type'     => (string) $field['type'][ $index ],
			'tmp_name' => (string) $field['tmp_name'][ $index ],
			'error'    => (int) $field['error'][ $index ],
			'size'     => (int) $field['size'][ $index ],
		);
	}

	return $entries;
}

/**
 * Move validated uploads out of the temp dir so they can be attached.
 *
 * @param array<int, array<string, mixed>> $entries Normalised upload entries.
 * @return array{paths: array<int, string>, errors: array<int, string>}
 */
function stilco_handle_review_uploads( array $entries ) {
	$paths  = array();
	$errors = array();

	if ( empty( $entries ) ) {
		return compact( 'paths', 'errors' );
	}

	if ( count( $entries ) > STILCO_REVIEW_MAX_FILES ) {
		$errors[] = sprintf( 'Możesz dodać najwyżej %d plików.', STILCO_REVIEW_MAX_FILES );
		return compact( 'paths', 'errors' );
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';

	foreach ( $entries as $entry ) {
		if ( UPLOAD_ERR_OK !== $entry['error'] ) {
			$errors[] = sprintf( 'Nie udało się wgrać pliku „%s”.', $entry['name'] );
			continue;
		}

		if ( $entry['size'] > STILCO_REVIEW_MAX_FILE_BYTES ) {
			$errors[] = sprintf( 'Plik „%s” jest większy niż 10 MB.', $entry['name'] );
			continue;
		}

		$checked = wp_check_filetype_and_ext( $entry['tmp_name'], $entry['name'], stilco_review_allowed_mime_types() );

		if ( empty( $checked['type'] ) ) {
			$errors[] = sprintf( 'Format pliku „%s” nie jest obsługiwany.', $entry['name'] );
			continue;
		}

		$moved = wp_handle_upload(
			$entry,
			array(
				'test_form' => false,
				'mimes'     => stilco_review_allowed_mime_types(),
			)
		);

		if ( ! empty( $moved['error'] ) || empty( $moved['file'] ) ) {
			$errors[] = sprintf( 'Nie udało się zapisać pliku „%s”.', $entry['name'] );
			continue;
		}

		$paths[] = $moved['file'];
	}

	return compact( 'paths', 'errors' );
}

/**
 * Build the notification e-mail body.
 *
 * @param array<string, mixed> $data Sanitised form values.
 * @return string
 */
function stilco_build_review_email_body( array $data ) {
	$lines = array(
		'Nowa opinia z formularza na stronie /opinie.',
		'',
		'Ocena: ' . $data['rating'] . '/5',
		'Imię i nazwisko: ' . $data['name'],
		'E-mail: ' . $data['email'],
		'Telefon: ' . ( '' !== $data['phone'] ? $data['phone'] : '(nie podano)' ),
		'',
		'Treść opinii:',
		$data['content'],
		'',
		'Zgoda na publikację: ' . ( $data['consent_publish'] ? 'tak' : 'nie' ),
		'Załączniki: ' . ( $data['attachments'] ? $data['attachments'] : 'brak' ),
		'',
		'Wysłano: ' . current_time( 'Y-m-d H:i' ),
	);

	return implode( "\n", $lines );
}

/**
 * Handle a review form submission.
 *
 * @return void
 */
function stilco_handle_review_form_submission() {
	if ( ! isset( $_POST['stilco_review_form'] ) ) {
		return;
	}

	$redirect = stilco_get_reviews_page_url();

	if ( ! isset( $_POST['stilco_review_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stilco_review_nonce'] ) ), 'stilco_review_form' ) ) {
		wp_safe_redirect( add_query_arg( 'opinia', 'blad', $redirect ) . '#formularz-opinii' );
		exit;
	}

	// Honeypot: real visitors leave this hidden field empty.
	if ( ! empty( $_POST['stilco_review_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'opinia', 'wyslana', $redirect ) . '#formularz-opinii' );
		exit;
	}

	$rating          = isset( $_POST['review_rating'] ) ? (int) $_POST['review_rating'] : 0;
	$name            = isset( $_POST['review_name'] ) ? sanitize_text_field( wp_unslash( $_POST['review_name'] ) ) : '';
	$email           = isset( $_POST['review_email'] ) ? sanitize_email( wp_unslash( $_POST['review_email'] ) ) : '';
	$phone           = isset( $_POST['review_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['review_phone'] ) ) : '';
	$content         = isset( $_POST['review_content'] ) ? sanitize_textarea_field( wp_unslash( $_POST['review_content'] ) ) : '';
	$consent_privacy = ! empty( $_POST['review_consent_privacy'] );
	$consent_publish = ! empty( $_POST['review_consent_publish'] );

	$errors = array();

	if ( $rating < 1 || $rating > 5 ) {
		$errors[] = 'Wybierz ocenę od 1 do 5 gwiazdek.';
	}

	if ( '' === $name ) {
		$errors[] = 'Podaj imię i nazwisko.';
	}

	if ( ! is_email( $email ) ) {
		$errors[] = 'Podaj poprawny adres e-mail.';
	}

	if ( '' === $content ) {
		$errors[] = 'Napisz kilka słów o materacu.';
	}

	if ( ! $consent_privacy ) {
		$errors[] = 'Potrzebujemy zgody na przetwarzanie danych.';
	}

	$uploads = stilco_handle_review_uploads( stilco_collect_review_upload_entries() );
	$errors  = array_merge( $errors, $uploads['errors'] );

	if ( $errors ) {
		foreach ( $uploads['paths'] as $path ) {
			wp_delete_file( $path );
		}

		set_transient( 'stilco_review_errors_' . stilco_review_client_key(), $errors, 5 * MINUTE_IN_SECONDS );
		wp_safe_redirect( add_query_arg( 'opinia', 'blad', $redirect ) . '#formularz-opinii' );
		exit;
	}

	$attachment_names = array_map( 'wp_basename', $uploads['paths'] );

	$body = stilco_build_review_email_body(
		array(
			'rating'          => $rating,
			'name'            => $name,
			'email'           => $email,
			'phone'           => $phone,
			'content'         => $content,
			'consent_publish' => $consent_publish,
			'attachments'     => implode( ', ', $attachment_names ),
		)
	);

	wp_mail(
		stilco_get_review_form_recipients(),
		sprintf( 'Nowa opinia (%d/5) od %s', $rating, $name ),
		$body,
		array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $name . ' <' . $email . '>' ),
		$uploads['paths']
	);

	foreach ( $uploads['paths'] as $path ) {
		wp_delete_file( $path );
	}

	wp_safe_redirect( add_query_arg( 'opinia', 'wyslana', $redirect ) . '#formularz-opinii' );
	exit;
}
add_action( 'template_redirect', 'stilco_handle_review_form_submission' );

/**
 * Per-visitor key used to stash validation errors between redirects.
 *
 * @return string
 */
function stilco_review_client_key() {
	$ip    = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';

	return md5( $ip . '|' . $agent );
}

/**
 * Read and clear stashed validation errors.
 *
 * @return array<int, string>
 */
function stilco_take_review_form_errors() {
	$key    = 'stilco_review_errors_' . stilco_review_client_key();
	$errors = get_transient( $key );

	if ( ! is_array( $errors ) ) {
		return array();
	}

	delete_transient( $key );

	return $errors;
}

/**
 * Enqueue reviews page assets.
 *
 * @return void
 */
function stilco_enqueue_reviews_page_assets() {
	if ( is_admin() || ! stilco_is_reviews_page() ) {
		return;
	}

	wp_enqueue_style(
		'stilco-reviews-page',
		stilco_get_theme_asset_uri( 'assets/css/reviews-page.css' ),
		array( 'stilco-style' ),
		stilco_get_theme_asset_version( 'assets/css/reviews-page.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'stilco_enqueue_reviews_page_assets', 130 );
