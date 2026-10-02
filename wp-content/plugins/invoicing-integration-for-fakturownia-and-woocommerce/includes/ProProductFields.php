<?php

namespace Devikit\Fakturownia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PRO Product Fields (Grayed out in FREE)
 * Shows GTU, PKWiU, and Fakturownia Product ID fields as disabled when PRO is not installed
 */
class ProProductFields {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Always add hooks - they will check internally if PRO is installed
		// This ensures fields are shown only when PRO is not installed (no license checks in FREE).
		
		// Add GTU and PKWiU fields to General tab (priority 25 to run after PRO if it exists)
		add_action( 'woocommerce_product_options_general_product_data', [ $this, 'add_pro_fields_general' ], 25 );
		
		// Add fields to product variations (priority 25 to run after PRO if it exists)
		add_action( 'woocommerce_product_after_variable_attributes', [ $this, 'add_pro_fields_variation' ], 25, 3 );
	}

	/**
	 * Check if PRO plugin is installed
	 * 
	 * @return bool
	 */
	private function is_pro_installed() {
		return class_exists( 'Devikit\FakturowniaPro\Plugin' );
	}

	/**
	 * Get GTU codes list (same as PRO)
	 * Simplified - no translations to avoid memory issues
	 * 
	 * @return array
	 */
	private function get_gtu_codes() {
		// Use static cache to avoid multiple translations
		static $gtu_codes = null;
		
		if ( $gtu_codes === null ) {
			$gtu_codes = [
				''       => __( 'Select...', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
				'GTU_01' => 'GTU_01',
				'GTU_02' => 'GTU_02',
				'GTU_03' => 'GTU_03',
				'GTU_04' => 'GTU_04',
				'GTU_05' => 'GTU_05',
				'GTU_06' => 'GTU_06',
				'GTU_07' => 'GTU_07',
				'GTU_08' => 'GTU_08',
				'GTU_09' => 'GTU_09',
				'GTU_10' => 'GTU_10',
				'GTU_11' => 'GTU_11',
				'GTU_12' => 'GTU_12',
				'GTU_13' => 'GTU_13',
			];
		}
		
		return $gtu_codes;
	}

	/**
	 * Add PRO fields to General tab (GTU and PKWiU)
	 */
	public function add_pro_fields_general() {
		// Only show when PRO is not installed (avoid duplicates; no license checks in FREE).
		if ( $this->is_pro_installed() ) {
			return;
		}
		
		global $post;
		
		if ( ! $post || ! isset( $post->ID ) ) {
			return;
		}
		
		// Prevent multiple calls
		static $called = false;
		if ( $called ) {
			return;
		}
		$called = true;
		
		// Get existing values if PRO was active before
		$gtu_code = get_post_meta( $post->ID, '_fakturownia_gtu_code', true );
		$pkwiu_code = get_post_meta( $post->ID, '_fakturownia_pkwiu_code', true );
		$gtu_codes = $this->get_gtu_codes();
		
		?>
		<div class="options_group" style="opacity: 0.5; pointer-events: none;">
			<p class="form-field _fakturownia_gtu_code_field">
				<label for="_fakturownia_gtu_code"><?php esc_html_e( 'Fakturownia - GTU Code', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></label>
				<select id="_fakturownia_gtu_code" name="_fakturownia_gtu_code" disabled>
					<?php foreach ( $gtu_codes as $code => $label ) : ?>
						<option value="<?php echo esc_attr( $code ); ?>" <?php selected( $gtu_code, $code ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
				<span class="description"><?php esc_html_e( 'GTU (Grupa Towarów i Usług) code for VAT purposes. This code will be included in invoices.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></span>
			</p>
			<p class="form-field _fakturownia_pkwiu_code_field">
				<label for="_fakturownia_pkwiu_code"><?php esc_html_e( 'Fakturownia - PKWiU Code', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></label>
				<input type="text" id="_fakturownia_pkwiu_code" name="_fakturownia_pkwiu_code" value="<?php echo esc_attr( $pkwiu_code ); ?>" disabled />
				<span class="description"><?php esc_html_e( 'PKWiU (Polska Klasyfikacja Wyrobów i Usług) code for VAT exempt products (ZW rate).', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></span>
			</p>
		</div>
		<p style="margin-top: 10px;">
			<?php
			printf(
				/* translators: %s: Link to PRO version */
				esc_html__( 'GTU and PKWiU are available in %s', 'invoicing-integration-for-fakturownia-and-woocommerce' ),
				'<a href="https://devikit.pl/produkt/fakturownia-woocommerce-pro/" target="_blank" style="color:#2271b1;">' . esc_html__( 'PRO version', 'invoicing-integration-for-fakturownia-and-woocommerce' ) . '</a>'
			);
			?>
		</p>
		<?php
	}

	/**
	 * Add PRO fields to product variation
	 */
	public function add_pro_fields_variation( $loop, $variation_data, $variation ) {
		// Only show when PRO is not installed (avoid duplicates; no license checks in FREE).
		if ( $this->is_pro_installed() ) {
			return;
		}
		
		if ( ! isset( $variation->ID ) ) {
			return;
		}
		
		$gtu_code = get_post_meta( $variation->ID, '_fakturownia_gtu_code', true );
		$pkwiu_code = get_post_meta( $variation->ID, '_fakturownia_pkwiu_code', true );
		$gtu_codes = $this->get_gtu_codes();
		?>
		<div style="opacity: 0.5; pointer-events: none;">
			<p class="form-row form-row-full">
				<label for="_fakturownia_gtu_code_<?php echo esc_attr( $loop ); ?>"><?php esc_html_e( 'Fakturownia - GTU Code', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></label>
				<select id="_fakturownia_gtu_code_<?php echo esc_attr( $loop ); ?>" name="_fakturownia_gtu_code[<?php echo esc_attr( $loop ); ?>]" disabled>
					<?php foreach ( $gtu_codes as $code => $label ) : ?>
						<option value="<?php echo esc_attr( $code ); ?>" <?php selected( $gtu_code, $code ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
				<span class="description"><?php esc_html_e( 'GTU code for VAT purposes.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></span>
			</p>
			<p class="form-row form-row-full">
				<label for="_fakturownia_pkwiu_code_<?php echo esc_attr( $loop ); ?>"><?php esc_html_e( 'Fakturownia - PKWiU Code', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></label>
				<input type="text" id="_fakturownia_pkwiu_code_<?php echo esc_attr( $loop ); ?>" name="_fakturownia_pkwiu_code[<?php echo esc_attr( $loop ); ?>]" value="<?php echo esc_attr( $pkwiu_code ); ?>" disabled />
				<span class="description"><?php esc_html_e( 'PKWiU code for VAT exempt products.', 'invoicing-integration-for-fakturownia-and-woocommerce' ); ?></span>
			</p>
		</div>
		<?php
	}
}
