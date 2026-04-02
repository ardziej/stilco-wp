<?php
/**
 * Product review image helpers.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check whether the current request renders a product review form.
 *
 * @return bool
 */
function stilco_is_product_review_form_context() {
	return function_exists( 'is_product' ) && is_product();
}

/**
 * Render the review image upload field.
 *
 * @return void
 */
function stilco_add_review_image_field() {
	if ( ! stilco_is_product_review_form_context() ) {
		return;
	}
	?>
	<p class="comment-form-image mb-4 mt-4">
		<label for="review_image" class="block text-sm font-medium text-gray-700 mb-1">
			<?php esc_html_e( 'Dodaj zdjęcie produktu (opcjonalnie)', 'stilco' ); ?>
		</label>
		<input type="file" name="review_image" id="review_image" accept="image/jpeg,image/png,image/webp" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-stilco-accent/10 file:text-stilco-accent hover:file:bg-stilco-accent/20" />
	</p>
	<?php
}
add_action( 'comment_form_logged_in_after', 'stilco_add_review_image_field' );
add_action( 'comment_form_after_fields', 'stilco_add_review_image_field' );

/**
 * Register review image comment meta for REST access.
 *
 * @return void
 */
function stilco_register_review_image_meta() {
	register_meta(
		'comment',
		'_review_image_id',
		array(
			'type'         => 'integer',
			'description'  => 'Review Image Attachment ID',
			'single'       => true,
			'show_in_rest' => true,
		)
	);
}
add_action( 'rest_api_init', 'stilco_register_review_image_meta' );

/**
 * Persist an uploaded review image after comment submission.
 *
 * @param int   $comment_id       Comment ID.
 * @param int   $comment_approved Comment approval state.
 * @param array $commentdata      Raw comment payload.
 * @return void
 */
function stilco_save_review_image( $comment_id, $comment_approved = 0, $commentdata = array() ) {
	if ( empty( $_FILES['review_image']['name'] ) ) {
		return;
	}

	$post_id = isset( $commentdata['comment_post_ID'] ) ? (int) $commentdata['comment_post_ID'] : 0;

	if ( $post_id <= 0 || 'product' !== get_post_type( $post_id ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$attachment_id = media_handle_upload( 'review_image', 0 );

	if ( ! is_wp_error( $attachment_id ) ) {
		add_comment_meta( $comment_id, '_review_image_id', $attachment_id );
	}
}
add_action( 'comment_post', 'stilco_save_review_image', 10, 3 );

/**
 * Render the uploaded review image.
 *
 * @param WP_Comment $comment Review comment object.
 * @return void
 */
function stilco_display_review_image( $comment ) {
	$image_id = (int) get_comment_meta( $comment->comment_ID, '_review_image_id', true );

	if ( ! $image_id ) {
		return;
	}

	echo '<div class="review-image mt-4 mb-4">';
	echo wp_get_attachment_image( $image_id, 'medium', false, array( 'class' => 'rounded-xl shadow-sm max-w-full h-auto max-h-48 object-cover' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</div>';
}
add_action( 'woocommerce_review_after_comment_text', 'stilco_display_review_image', 10, 1 );
