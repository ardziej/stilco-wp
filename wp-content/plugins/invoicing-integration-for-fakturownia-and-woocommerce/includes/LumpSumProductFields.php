<?php

namespace Devikit\Fakturownia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lump Sum Product Fields
 * Adds lump sum (ryczałt) field to products
 */
class LumpSumProductFields {

	const LUMP_SUM_META_KEY = '_fakturownia_lump_sum';

	/**
	 * Verify WooCommerce product save nonce and capability.
	 *
	 * @param int  $post_id      Post ID.
	 * @param bool $is_variation Whether this is a variation save (AJAX uses a different nonce).
	 * @return bool
	 */
	private function is_valid_product_save_request( $post_id, $is_variation = false ) {
		if ( ! is_admin() ) {
			return false;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}

		if ( $is_variation && isset( $_POST['security'] ) ) {
			return (bool) wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['security'] ) ),
				'save-variations'
			);
		}

		if ( ! isset( $_POST['woocommerce_meta_nonce'] ) ) {
			return false;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['woocommerce_meta_nonce'] ) );
		return (bool) wp_verify_nonce( $nonce, 'woocommerce_save_data' );
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		// Only add fields if PRO plugin is not active (PRO handles it in ProductCodesManager)
		if ( ! class_exists( 'Devikit\FakturowniaPro\Admin\ProductCodesManager' ) ) {
			// Add lump sum field to product edit page (General tab)
			add_action( 'woocommerce_product_options_general_product_data', [ $this, 'add_lump_sum_field' ] );
			add_action( 'woocommerce_process_product_meta', [ $this, 'save_lump_sum_field' ] );
			
			// Add field to product variations
			add_action( 'woocommerce_product_after_variable_attributes', [ $this, 'add_lump_sum_field_variation' ], 10, 3 );
			add_action( 'woocommerce_save_product_variation', [ $this, 'save_lump_sum_field_variation' ], 10, 2 );
		}
	}

	/**
	 * Get available lump sum options
	 * 
	 * @return array
	 */
	private function get_lump_sum_options() {
		return [
			'empty' => __( 'Select...', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'3'     => '3%',
			'5.5'   => '5,5%',
			'8.5'   => '8,5%',
			'10'    => '10%',
			'12'    => '12%',
			'12.5'  => '12,5%',
			'14'    => '14%',
			'15'    => '15%',
			'17'    => '17%',
		];
	}

	/**
	 * Add lump sum field to simple/variable product
	 */
	public function add_lump_sum_field() {
		global $post;
		
		// Check if lump sum is enabled in settings
		$settings = get_option( 'devikit_fakturownia_settings', [] );
		$lump_sum_enable = isset( $settings['lump_sum_enable'] ) && $settings['lump_sum_enable'] === 'yes';
		
		if ( ! $lump_sum_enable ) {
			return;
		}
		
		$lump_sum_value = get_post_meta( $post->ID, self::LUMP_SUM_META_KEY, true );
		
		echo '<div class="options_group">';
		
		woocommerce_wp_select( [
			'id'          => self::LUMP_SUM_META_KEY,
			'label'       => __( 'Lump Sum', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'desc_tip'    => true,
			'description' => __( 'Select lump sum rate for this product. If not set, the default rate from settings will be used.', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
			'value'       => $lump_sum_value,
			'options'     => $this->get_lump_sum_options(),
		] );
		
		echo '</div>';
	}

	/**
	 * Save lump sum field for simple/variable product
	 */
	public function save_lump_sum_field( $post_id ) {
		if ( ! $this->is_valid_product_save_request( $post_id ) ) {
			return;
		}

		if ( isset( $_POST[ self::LUMP_SUM_META_KEY ] ) ) {
			$lump_sum_value = sanitize_text_field( wp_unslash( $_POST[ self::LUMP_SUM_META_KEY ] ) );
			update_post_meta( $post_id, self::LUMP_SUM_META_KEY, $lump_sum_value );
		}
	}

	/**
	 * Add lump sum field to product variation
	 */
	public function add_lump_sum_field_variation( $loop, $variation_data, $variation ) {
		// Check if lump sum is enabled in settings
		$settings = get_option( 'devikit_fakturownia_settings', [] );
		$lump_sum_enable = isset( $settings['lump_sum_enable'] ) && $settings['lump_sum_enable'] === 'yes';
		
		if ( ! $lump_sum_enable ) {
			return;
		}
		
		$lump_sum_value = get_post_meta( $variation->ID, self::LUMP_SUM_META_KEY, true );
		$options = $this->get_lump_sum_options();
		?>
		<p class="form-row form-row-full">
			<label for="<?php echo esc_attr( self::LUMP_SUM_META_KEY . '_' . $loop ); ?>"><?php esc_html_e( 'Lump Sum', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></label>
			<select id="<?php echo esc_attr( self::LUMP_SUM_META_KEY . '_' . $loop ); ?>" name="<?php echo esc_attr( self::LUMP_SUM_META_KEY ); ?>[<?php echo esc_attr( $loop ); ?>]">
				<?php foreach ( $options as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $lump_sum_value, $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
			<span class="description"><?php esc_html_e( 'Select lump sum rate for this variation. If not set, the default rate from settings will be used.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></span>
		</p>
		<?php
	}

	/**
	 * Save lump sum field for variation
	 */
	public function save_lump_sum_field_variation( $variation_id, $loop ) {
		if ( ! $this->is_valid_product_save_request( $variation_id, true ) ) {
			return;
		}

		if ( isset( $_POST[ self::LUMP_SUM_META_KEY ][ $loop ] ) ) {
			$lump_sum_value = sanitize_text_field( wp_unslash( $_POST[ self::LUMP_SUM_META_KEY ][ $loop ] ) );
			update_post_meta( $variation_id, self::LUMP_SUM_META_KEY, $lump_sum_value );
		}
	}

	/**
	 * Get lump sum for product
	 * 
	 * @param \WC_Product $product
	 * @param int|null    $variation_id Variation ID if applicable
	 * @param string      $default_value Default lump sum value from settings
	 * @return string
	 */
	public static function get_product_lump_sum( $product, $variation_id = null, $default_value = 'empty' ) {
		if ( ! $product ) {
			return '';
		}
		
		// Check variation first
		if ( $variation_id ) {
			$variation_lump_sum = get_post_meta( $variation_id, self::LUMP_SUM_META_KEY, true );
			if ( ! empty( $variation_lump_sum ) && $variation_lump_sum !== 'none' && $variation_lump_sum !== 'empty' ) {
				return $variation_lump_sum;
			}
		}
		
		// Check product
		$product_lump_sum = get_post_meta( $product->get_id(), self::LUMP_SUM_META_KEY, true );
		if ( ! empty( $product_lump_sum ) && $product_lump_sum !== 'none' && $product_lump_sum !== 'empty' ) {
			return $product_lump_sum;
		}
		
		// Use default
		if ( ! empty( $default_value ) && $default_value !== 'none' && $default_value !== 'empty' ) {
			return $default_value;
		}
		
		return '';
	}
}

