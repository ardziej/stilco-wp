<?php
/**
 * Pods-backed content helpers and schema sync.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check whether Pods is available.
 *
 * @return bool
 */
function stilco_has_pods() {
	return function_exists( 'pods' ) && function_exists( 'pods_api' );
}

/**
 * Get the global settings pod name.
 *
 * @return string
 */
function stilco_get_settings_pod_name() {
	return 'stilco_settings';
}

/**
 * Get a Pods object for the current page.
 *
 * @param int|null $post_id Optional post ID.
 * @return object|null
 */
function stilco_get_page_pod( $post_id = null ) {
	if ( ! stilco_has_pods() ) {
		return null;
	}

	$post_id = $post_id ? absint( $post_id ) : get_queried_object_id();

	if ( ! $post_id ) {
		return null;
	}

	try {
		return pods( 'page', $post_id );
	} catch ( Throwable $exception ) {
		return null;
	}
}

/**
 * Get the global settings pod object.
 *
 * @return object|null
 */
function stilco_get_settings_pod() {
	if ( ! stilco_has_pods() ) {
		return null;
	}

	try {
		return pods( stilco_get_settings_pod_name() );
	} catch ( Throwable $exception ) {
		return null;
	}
}

/**
 * Read a field from a pod object with a fallback.
 *
 * @param object|null $pod      Pod object.
 * @param string      $field    Field name.
 * @param mixed       $fallback Fallback value.
 * @return mixed
 */
function stilco_get_pod_field( $pod, $field, $fallback = '' ) {
	if ( empty( $pod ) || ! is_object( $pod ) || ! method_exists( $pod, 'field' ) ) {
		return $fallback;
	}

	try {
		$value = $pod->field( $field );
	} catch ( Throwable $exception ) {
		return $fallback;
	}

	if ( null === $value || '' === $value || array() === $value ) {
		return $fallback;
	}

	return $value;
}

/**
 * Read a page field with a fallback.
 *
 * @param string   $field    Field name.
 * @param mixed    $fallback Fallback value.
 * @param int|null $post_id  Optional post ID.
 * @return mixed
 */
function stilco_get_page_field( $field, $fallback = '', $post_id = null ) {
	return stilco_get_pod_field( stilco_get_page_pod( $post_id ), $field, $fallback );
}

/**
 * Read a settings field with a fallback.
 *
 * @param string $field    Field name.
 * @param mixed  $fallback Fallback value.
 * @return mixed
 */
function stilco_get_setting( $field, $fallback = '' ) {
	return stilco_get_pod_field( stilco_get_settings_pod(), $field, $fallback );
}

/**
 * Normalize a Pods media field to attachment ID / URL / alt data.
 *
 * @param mixed  $value         Raw field value.
 * @param string $fallback_url  Fallback URL.
 * @param string $fallback_alt  Fallback alt.
 * @param string $size          Image size.
 * @return array{url:string,alt:string,id:int}
 */
function stilco_get_media_image_data( $value, $fallback_url = '', $fallback_alt = '', $size = 'full' ) {
	$image = array(
		'url' => $fallback_url,
		'alt' => $fallback_alt,
		'id'  => 0,
	);

	if ( empty( $value ) ) {
		return $image;
	}

	if ( is_numeric( $value ) ) {
		$image['id'] = absint( $value );
	} elseif ( is_array( $value ) ) {
		if ( ! empty( $value['ID'] ) ) {
			$image['id'] = absint( $value['ID'] );
		} elseif ( ! empty( $value['id'] ) ) {
			$image['id'] = absint( $value['id'] );
		}

		if ( empty( $image['url'] ) && ! empty( $value['guid'] ) ) {
			$image['url'] = (string) $value['guid'];
		}

		if ( ! empty( $value['alt'] ) ) {
			$image['alt'] = (string) $value['alt'];
		}
	} elseif ( is_string( $value ) ) {
		$image['url'] = $value;
	}

	if ( $image['id'] ) {
		$image_url = wp_get_attachment_image_url( $image['id'], $size );
		$image_alt = get_post_meta( $image['id'], '_wp_attachment_image_alt', true );

		if ( $image_url ) {
			$image['url'] = $image_url;
		}

		if ( '' !== trim( (string) $image_alt ) ) {
			$image['alt'] = (string) $image_alt;
		}
	}

	return $image;
}

/**
 * Override media alt text via a text field when provided.
 *
 * @param array  $image     Image data.
 * @param string $override  Alt override.
 * @return array{url:string,alt:string,id:int}
 */
function stilco_override_media_alt( array $image, $override = '' ) {
	if ( '' !== trim( (string) $override ) ) {
		$image['alt'] = (string) $override;
	}

	return $image;
}

/**
 * Build a link array from fields with fallback values.
 *
 * @param string   $label_field     Label field name.
 * @param string   $url_field       URL field name.
 * @param string   $fallback_label  Fallback label.
 * @param string   $fallback_url    Fallback URL.
 * @param int|null $post_id         Optional post ID.
 * @param bool     $settings_scope  Whether to read from settings.
 * @return array{label:string,url:string}
 */
function stilco_get_link_data( $label_field, $url_field, $fallback_label, $fallback_url, $post_id = null, $settings_scope = false ) {
	$label = $settings_scope
		? stilco_get_setting( $label_field, $fallback_label )
		: stilco_get_page_field( $label_field, $fallback_label, $post_id );
	$url   = $settings_scope
		? stilco_get_setting( $url_field, $fallback_url )
		: stilco_get_page_field( $url_field, $fallback_url, $post_id );

	return array(
		'label' => (string) $label,
		'url'   => (string) $url,
	);
}

/**
 * Get footer link groups from settings.
 *
 * @return array<string, array<int, array{label:string,url:string}>>
 */
function stilco_get_footer_link_groups() {
	$groups = array(
		'shop'    => array(
			array( 'Materace', '/sklep' ),
			array( 'Akcesoria', '/kategoria/akcesoria' ),
			array( 'Karty Podarunkowe', '/karty-podarunkowe' ),
		),
		'company' => array(
			array( 'O nas', '/o-nas' ),
			array( 'Kontakt', '/kontakt' ),
			array( 'Opinie klientów', '/opinie' ),
		),
		'support' => array(
			array( 'FAQ & Pytania', '/faq' ),
			array( 'Dostawa i 30 Dni Testu', '/zwroty-i-reklamacje' ),
			array( 'Realizacja Gwarancji', '/gwarancja' ),
		),
		'legal'   => array(
			array( 'Regulamin', '/regulamin' ),
			array( 'Polityka prywatności', '/polityka-prywatnosci' ),
		),
	);

	$output = array();

	foreach ( $groups as $group_key => $items ) {
		$output[ $group_key ] = array();

		foreach ( $items as $index => $item ) {
			$item_index                = $index + 1;
			$output[ $group_key ][] = array(
				'label' => (string) stilco_get_setting( "footer_{$group_key}_link_{$item_index}_label", $item[0] ),
				'url'   => (string) stilco_get_setting( "footer_{$group_key}_link_{$item_index}_url", $item[1] ),
			);
		}
	}

	return $output;
}

/**
 * Get FAQ items for the homepage.
 *
 * @return array<int, array{question:string,answer:string}>
 */
function stilco_get_front_page_faq_items() {
	$fallback = array(
		array(
			'question' => 'Jak długo trwa dostawa?',
			'answer'   => 'Standardowy czas realizacji to od 2 do 5 dni roboczych. Materace wysyłamy na płasko lub starannie zrolowane w grubym kartonie, korzystając z zaufanych firm kurierskich.',
		),
		array(
			'question' => 'Jak działa 100 nocy na próbę?',
			'answer'   => 'Od momentu doręczenia masz 100 dni na testowanie materaca w domowych warunkach. Ciało potrzebuje około 3-4 tygodni, by przyzwyczaić się do nowego podparcia. Jeśli po tym czasie materac nadal Ci nie odpowiada, skontaktuj się z nami – zorganizujemy darmowy zwrot.',
		),
		array(
			'question' => 'Jak prać pokrowiec?',
			'answer'   => 'Pokrowiec posiada zamek 360°, co pozwala na odpięcie górnej lub dolnej warstwy niezależnie. Możesz prać go w pralce w temperaturze do 40°C używając delikatnych detergentów. Susz tradycyjnie, nie wolno suszyć w suszarce bębnowej.',
		),
		array(
			'question' => 'Czy materac jest dwustronny?',
			'answer'   => 'Tak! Nasz model posiada dwie różne strony twardości. Strona z pianką Visco to odczucie "otulenia" (H2), a druga strona (H3) z pianką HR zapewnia stabilniejsze, nieco twardsze podparcie. Ty decydujesz, jak wolisz spać.',
		),
	);

	if ( ! post_type_exists( 'faq' ) ) {
		return $fallback;
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'faq',
			'posts_per_page' => 4,
			'post_status'    => 'publish',
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
		)
	);

	if ( ! $query->have_posts() ) {
		return $fallback;
	}

	$items = array();

	while ( $query->have_posts() ) {
		$query->the_post();

		$items[] = array(
			'question' => get_the_title(),
			'answer'   => get_the_content(),
		);
	}

	wp_reset_postdata();

	return ! empty( $items ) ? $items : $fallback;
}

/**
 * Upsert a pod field.
 *
 * @param string $pod_name Pod name.
 * @param array  $field    Field config.
 * @return void
 */
function stilco_upsert_pods_field( $pod_name, array $field ) {
	$api = pods_api();
	$pod = $api->load_pod(
		array(
			'name' => $pod_name,
		),
		false
	);

	if ( empty( $pod ) ) {
		return;
	}

	$pod_id   = isset( $pod['id'] ) ? (int) $pod['id'] : 0;
	$group_id = 0;

	if ( is_object( $pod ) && method_exists( $pod, 'get_groups' ) ) {
		$groups = $pod->get_groups();

		if ( ! empty( $groups[0] ) && isset( $groups[0]['id'] ) ) {
			$group_id = (int) $groups[0]['id'];
		}
	}

	$existing = $api->load_field(
		array(
			'name' => $field['name'],
			'pod'  => $pod_name,
		),
		false
	);

	if ( ! empty( $existing ) && isset( $existing['id'] ) ) {
		$field['id'] = (int) $existing['id'];
	}

	$field['pod']    = $pod_name;
	$field['pod_id'] = $pod_id;

	if ( $group_id ) {
		$field['group_id'] = $group_id;
	}

	$api->save_field( $field );
}

/**
 * Sync the theme Pods schema.
 *
 * @return void
 */
function stilco_sync_pods_schema() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) || ! function_exists( 'pods_api' ) ) {
		return;
	}

	$schema_version = '2026-04-03-1';

	if ( get_option( 'stilco_pods_schema_version' ) === $schema_version ) {
		return;
	}

	$api = pods_api();

	$settings_pod = $api->load_pod(
		array(
			'name' => stilco_get_settings_pod_name(),
		),
		false
	);

	if ( empty( $settings_pod ) ) {
		$api->add_pod(
			array(
				'create_extend'        => 'create',
				'create_pod_type'      => 'settings',
				'create_setting_name'  => stilco_get_settings_pod_name(),
				'create_label_title'   => 'Ustawienia globalne Stilco',
				'create_label_menu'    => 'Stilco Global',
				'create_menu_location' => 'settings',
			)
		);
	}

	$page_pod = $api->load_pod(
		array(
			'name' => 'page',
		),
		false
	);

	if ( empty( $page_pod ) ) {
		$api->add_pod(
			array(
				'create_extend'   => 'extend',
				'extend_pod_type' => 'post_type',
				'extend_post_type'=> 'page',
				'extend_storage'  => 'meta',
			)
		);
	}

	$page_fields = array(
		array( 'name' => 'home_hero_eyebrow', 'label' => 'Home: Hero eyebrow', 'type' => 'text' ),
		array( 'name' => 'home_hero_title_accent', 'label' => 'Home: Hero accent line', 'type' => 'text' ),
		array( 'name' => 'home_hero_lead', 'label' => 'Home: Hero lead', 'type' => 'paragraph' ),
		array( 'name' => 'home_hero_primary_cta_text', 'label' => 'Home: Hero primary CTA text', 'type' => 'text' ),
		array( 'name' => 'home_hero_primary_cta_url', 'label' => 'Home: Hero primary CTA URL', 'type' => 'website' ),
		array( 'name' => 'home_hero_secondary_cta_text', 'label' => 'Home: Hero secondary CTA text', 'type' => 'text' ),
		array( 'name' => 'home_hero_secondary_cta_url', 'label' => 'Home: Hero secondary CTA URL', 'type' => 'website' ),
		array( 'name' => 'home_hero_scroll_label', 'label' => 'Home: Hero scroll label', 'type' => 'text' ),
		array( 'name' => 'home_hero_image', 'label' => 'Home: Hero image', 'type' => 'file', 'options' => array( 'file_format_type' => 'single', 'file_uploader' => 'attachment', 'file_type' => 'images' ) ),
		array( 'name' => 'home_hero_image_alt', 'label' => 'Home: Hero image alt override', 'type' => 'text' ),
		array( 'name' => 'home_dual_eyebrow', 'label' => 'Home: Dual comfort eyebrow', 'type' => 'text' ),
		array( 'name' => 'home_dual_title', 'label' => 'Home: Dual comfort title', 'type' => 'text' ),
		array( 'name' => 'home_dual_lead', 'label' => 'Home: Dual comfort lead', 'type' => 'paragraph' ),
		array( 'name' => 'home_dual_item_1_title', 'label' => 'Home: Dual comfort item 1 title', 'type' => 'text' ),
		array( 'name' => 'home_dual_item_1_text', 'label' => 'Home: Dual comfort item 1 text', 'type' => 'paragraph' ),
		array( 'name' => 'home_dual_item_2_title', 'label' => 'Home: Dual comfort item 2 title', 'type' => 'text' ),
		array( 'name' => 'home_dual_item_2_text', 'label' => 'Home: Dual comfort item 2 text', 'type' => 'paragraph' ),
		array( 'name' => 'home_dual_image', 'label' => 'Home: Dual comfort image', 'type' => 'file', 'options' => array( 'file_format_type' => 'single', 'file_uploader' => 'attachment', 'file_type' => 'images' ) ),
		array( 'name' => 'home_dual_image_alt', 'label' => 'Home: Dual comfort image alt override', 'type' => 'text' ),
		array( 'name' => 'home_dual_badge', 'label' => 'Home: Dual comfort badge', 'type' => 'text' ),
		array( 'name' => 'home_layers_title', 'label' => 'Home: Layers title', 'type' => 'text' ),
		array( 'name' => 'home_layers_lead', 'label' => 'Home: Layers lead', 'type' => 'paragraph' ),
		array( 'name' => 'home_categories_title', 'label' => 'Home: Categories title', 'type' => 'text' ),
		array( 'name' => 'home_categories_lead', 'label' => 'Home: Categories lead', 'type' => 'paragraph' ),
		array( 'name' => 'home_reviews_eyebrow', 'label' => 'Home: Reviews eyebrow', 'type' => 'text' ),
		array( 'name' => 'home_reviews_title', 'label' => 'Home: Reviews title', 'type' => 'text' ),
		array( 'name' => 'home_reviews_lead', 'label' => 'Home: Reviews lead', 'type' => 'paragraph' ),
		array( 'name' => 'home_reviews_cta_text', 'label' => 'Home: Reviews CTA text', 'type' => 'text' ),
		array( 'name' => 'home_reviews_cta_url', 'label' => 'Home: Reviews CTA URL', 'type' => 'website' ),
		array( 'name' => 'home_reviews_empty_text', 'label' => 'Home: Reviews empty state', 'type' => 'text' ),
		array( 'name' => 'home_b2b_eyebrow', 'label' => 'Home: B2B eyebrow', 'type' => 'text' ),
		array( 'name' => 'home_b2b_title', 'label' => 'Home: B2B title', 'type' => 'text' ),
		array( 'name' => 'home_b2b_lead', 'label' => 'Home: B2B lead', 'type' => 'paragraph' ),
		array( 'name' => 'home_b2b_cta_text', 'label' => 'Home: B2B CTA text', 'type' => 'text' ),
		array( 'name' => 'home_b2b_cta_url', 'label' => 'Home: B2B CTA URL', 'type' => 'website' ),
		array( 'name' => 'home_faq_title', 'label' => 'Home: FAQ title', 'type' => 'text' ),
		array( 'name' => 'home_faq_lead', 'label' => 'Home: FAQ lead', 'type' => 'paragraph' ),
		array( 'name' => 'about_hero_lead', 'label' => 'About: Hero lead', 'type' => 'paragraph' ),
		array( 'name' => 'about_hero_image', 'label' => 'About: Hero image', 'type' => 'file', 'options' => array( 'file_format_type' => 'single', 'file_uploader' => 'attachment', 'file_type' => 'images' ) ),
		array( 'name' => 'about_hero_image_alt', 'label' => 'About: Hero image alt override', 'type' => 'text' ),
		array( 'name' => 'about_founders_title', 'label' => 'About: Founders title', 'type' => 'text' ),
		array( 'name' => 'about_founders_body', 'label' => 'About: Founders body', 'type' => 'wysiwyg' ),
		array( 'name' => 'about_founders_image', 'label' => 'About: Founders image', 'type' => 'file', 'options' => array( 'file_format_type' => 'single', 'file_uploader' => 'attachment', 'file_type' => 'images' ) ),
		array( 'name' => 'about_founders_image_alt', 'label' => 'About: Founders image alt override', 'type' => 'text' ),
		array( 'name' => 'about_timeline_title', 'label' => 'About: Timeline title', 'type' => 'text' ),
		array( 'name' => 'about_values_title', 'label' => 'About: Values title', 'type' => 'text' ),
		array( 'name' => 'about_cta_title', 'label' => 'About: CTA title', 'type' => 'text' ),
		array( 'name' => 'about_cta_text', 'label' => 'About: CTA text', 'type' => 'text' ),
		array( 'name' => 'about_cta_url', 'label' => 'About: CTA URL', 'type' => 'website' ),
		array( 'name' => 'contact_hero_image', 'label' => 'Contact: Hero image', 'type' => 'file', 'options' => array( 'file_format_type' => 'single', 'file_uploader' => 'attachment', 'file_type' => 'images' ) ),
		array( 'name' => 'contact_hero_image_alt', 'label' => 'Contact: Hero image alt override', 'type' => 'text' ),
		array( 'name' => 'contact_intro_title', 'label' => 'Contact: Intro title', 'type' => 'text' ),
		array( 'name' => 'contact_intro_lead', 'label' => 'Contact: Intro lead', 'type' => 'paragraph' ),
		array( 'name' => 'contact_form_title', 'label' => 'Contact: Form title', 'type' => 'text' ),
		array( 'name' => 'contact_form_consent', 'label' => 'Contact: Form consent text', 'type' => 'wysiwyg' ),
		array( 'name' => 'contact_form_button_text', 'label' => 'Contact: Form button text', 'type' => 'text' ),
		array( 'name' => 'contact_faq_title', 'label' => 'Contact: FAQ ribbon title', 'type' => 'text' ),
		array( 'name' => 'contact_faq_lead', 'label' => 'Contact: FAQ ribbon lead', 'type' => 'paragraph' ),
		array( 'name' => 'contact_faq_cta_text', 'label' => 'Contact: FAQ ribbon CTA text', 'type' => 'text' ),
		array( 'name' => 'contact_faq_cta_url', 'label' => 'Contact: FAQ ribbon CTA URL', 'type' => 'website' ),
		array( 'name' => 'faq_hero_lead', 'label' => 'FAQ: Hero lead', 'type' => 'paragraph' ),
		array( 'name' => 'faq_hero_image', 'label' => 'FAQ: Hero image', 'type' => 'file', 'options' => array( 'file_format_type' => 'single', 'file_uploader' => 'attachment', 'file_type' => 'images' ) ),
		array( 'name' => 'faq_hero_image_alt', 'label' => 'FAQ: Hero image alt override', 'type' => 'text' ),
		array( 'name' => 'faq_contact_title', 'label' => 'FAQ: Contact ribbon title', 'type' => 'text' ),
		array( 'name' => 'faq_contact_lead', 'label' => 'FAQ: Contact ribbon lead', 'type' => 'paragraph' ),
		array( 'name' => 'faq_contact_cta_text', 'label' => 'FAQ: Contact ribbon CTA text', 'type' => 'text' ),
		array( 'name' => 'faq_contact_cta_url', 'label' => 'FAQ: Contact ribbon CTA URL', 'type' => 'website' ),
		array( 'name' => 'faq_contact_image', 'label' => 'FAQ: Contact ribbon image', 'type' => 'file', 'options' => array( 'file_format_type' => 'single', 'file_uploader' => 'attachment', 'file_type' => 'images' ) ),
		array( 'name' => 'faq_contact_image_alt', 'label' => 'FAQ: Contact ribbon image alt override', 'type' => 'text' ),
		array( 'name' => 'mattress_lead', 'label' => 'Mattress: Lead', 'type' => 'paragraph' ),
		array( 'name' => 'mattress_rating_text', 'label' => 'Mattress: Rating text', 'type' => 'text' ),
		array( 'name' => 'mattress_price_label', 'label' => 'Mattress: Price label', 'type' => 'text' ),
		array( 'name' => 'mattress_measure_link_text', 'label' => 'Mattress: Measure link text', 'type' => 'text' ),
		array( 'name' => 'mattress_measure_link_url', 'label' => 'Mattress: Measure link URL', 'type' => 'website' ),
		array( 'name' => 'mattress_add_to_cart_text', 'label' => 'Mattress: Add to cart text', 'type' => 'text' ),
		array( 'name' => 'mattress_composition_eyebrow', 'label' => 'Mattress: Composition eyebrow', 'type' => 'text' ),
		array( 'name' => 'mattress_composition_title', 'label' => 'Mattress: Composition title', 'type' => 'text' ),
		array( 'name' => 'mattress_stories_title', 'label' => 'Mattress: Stories title', 'type' => 'text' ),
		array( 'name' => 'mattress_final_cta_title', 'label' => 'Mattress: Final CTA title', 'type' => 'text' ),
		array( 'name' => 'mattress_final_cta_lead', 'label' => 'Mattress: Final CTA lead', 'type' => 'paragraph' ),
		array( 'name' => 'mattress_final_cta_text', 'label' => 'Mattress: Final CTA button text', 'type' => 'text' ),
		array( 'name' => 'mattress_final_cta_url', 'label' => 'Mattress: Final CTA button URL', 'type' => 'website' ),
	);

	foreach ( range( 1, 4 ) as $index ) {
		$page_fields[] = array( 'name' => "home_trust_{$index}_label", 'label' => "Home: Trust label {$index}", 'type' => 'text' );
	}

	foreach ( range( 1, 3 ) as $index ) {
		$page_fields[] = array( 'name' => "home_layer_{$index}_title", 'label' => "Home: Layer {$index} title", 'type' => 'text' );
		$page_fields[] = array( 'name' => "home_layer_{$index}_text", 'label' => "Home: Layer {$index} text", 'type' => 'paragraph' );
		$page_fields[] = array( 'name' => "home_layer_{$index}_image", 'label' => "Home: Layer {$index} image", 'type' => 'file', 'options' => array( 'file_format_type' => 'single', 'file_uploader' => 'attachment', 'file_type' => 'images' ) );
		$page_fields[] = array( 'name' => "home_layer_{$index}_image_alt", 'label' => "Home: Layer {$index} image alt", 'type' => 'text' );
		$page_fields[] = array( 'name' => "home_category_{$index}_title", 'label' => "Home: Category {$index} title", 'type' => 'text' );
		$page_fields[] = array( 'name' => "home_category_{$index}_text", 'label' => "Home: Category {$index} text", 'type' => 'paragraph' );
		$page_fields[] = array( 'name' => "home_category_{$index}_cta_text", 'label' => "Home: Category {$index} CTA text", 'type' => 'text' );
		$page_fields[] = array( 'name' => "home_category_{$index}_cta_url", 'label' => "Home: Category {$index} CTA URL", 'type' => 'website' );
		$page_fields[] = array( 'name' => "home_category_{$index}_image", 'label' => "Home: Category {$index} image", 'type' => 'file', 'options' => array( 'file_format_type' => 'single', 'file_uploader' => 'attachment', 'file_type' => 'images' ) );
		$page_fields[] = array( 'name' => "home_category_{$index}_image_alt", 'label' => "Home: Category {$index} image alt", 'type' => 'text' );
	}

	$page_fields[] = array( 'name' => 'home_category_3_icon', 'label' => 'Home: Category 3 icon text', 'type' => 'text' );
	$page_fields[] = array( 'name' => 'home_b2b_stat_1_value', 'label' => 'Home: B2B stat 1 value', 'type' => 'text' );
	$page_fields[] = array( 'name' => 'home_b2b_stat_1_label', 'label' => 'Home: B2B stat 1 label', 'type' => 'text' );
	$page_fields[] = array( 'name' => 'home_b2b_stat_2_value', 'label' => 'Home: B2B stat 2 value', 'type' => 'text' );
	$page_fields[] = array( 'name' => 'home_b2b_stat_2_label', 'label' => 'Home: B2B stat 2 label', 'type' => 'text' );

	foreach ( range( 1, 4 ) as $index ) {
		$page_fields[] = array( 'name' => "about_timeline_{$index}_year", 'label' => "About: Timeline {$index} year", 'type' => 'text' );
		$page_fields[] = array( 'name' => "about_timeline_{$index}_title", 'label' => "About: Timeline {$index} title", 'type' => 'text' );
		$page_fields[] = array( 'name' => "about_timeline_{$index}_text", 'label' => "About: Timeline {$index} text", 'type' => 'paragraph' );
	}

	foreach ( range( 1, 3 ) as $index ) {
		$page_fields[] = array( 'name' => "about_value_{$index}_title", 'label' => "About: Value {$index} title", 'type' => 'text' );
		$page_fields[] = array( 'name' => "about_value_{$index}_text", 'label' => "About: Value {$index} text", 'type' => 'paragraph' );
	}

	$page_fields[] = array( 'name' => 'about_values_image', 'label' => 'About: Values image', 'type' => 'file', 'options' => array( 'file_format_type' => 'single', 'file_uploader' => 'attachment', 'file_type' => 'images' ) );
	$page_fields[] = array( 'name' => 'about_values_image_alt', 'label' => 'About: Values image alt override', 'type' => 'text' );

	foreach ( range( 1, 8 ) as $index ) {
		$page_fields[] = array( 'name' => "mattress_gallery_{$index}_image", 'label' => "Mattress: Gallery image {$index}", 'type' => 'file', 'options' => array( 'file_format_type' => 'single', 'file_uploader' => 'attachment', 'file_type' => 'images' ) );
		$page_fields[] = array( 'name' => "mattress_gallery_{$index}_image_alt", 'label' => "Mattress: Gallery image {$index} alt", 'type' => 'text' );
	}

	foreach ( range( 1, 6 ) as $index ) {
		$page_fields[] = array( 'name' => "mattress_size_{$index}_label", 'label' => "Mattress: Size {$index} label", 'type' => 'text' );
		$page_fields[] = array( 'name' => "mattress_size_{$index}_price", 'label' => "Mattress: Size {$index} price", 'type' => 'number' );
		$page_fields[] = array( 'name' => "mattress_size_{$index}_active", 'label' => "Mattress: Size {$index} active", 'type' => 'boolean' );
	}

	foreach ( range( 1, 4 ) as $index ) {
		$page_fields[] = array( 'name' => "mattress_badge_{$index}_title", 'label' => "Mattress: Badge {$index} title", 'type' => 'text' );
		$page_fields[] = array( 'name' => "mattress_badge_{$index}_note", 'label' => "Mattress: Badge {$index} note", 'type' => 'text' );
	}

	foreach ( range( 1, 2 ) as $index ) {
		$page_fields[] = array( 'name' => "mattress_composition_block_{$index}_title", 'label' => "Mattress: Composition block {$index} title", 'type' => 'text' );
		$page_fields[] = array( 'name' => "mattress_composition_block_{$index}_text", 'label' => "Mattress: Composition block {$index} text", 'type' => 'paragraph' );
		$page_fields[] = array( 'name' => "mattress_composition_block_{$index}_image", 'label' => "Mattress: Composition block {$index} image", 'type' => 'file', 'options' => array( 'file_format_type' => 'single', 'file_uploader' => 'attachment', 'file_type' => 'images' ) );
		$page_fields[] = array( 'name' => "mattress_composition_block_{$index}_image_alt", 'label' => "Mattress: Composition block {$index} image alt", 'type' => 'text' );

		foreach ( range( 1, 3 ) as $bullet_index ) {
			$page_fields[] = array( 'name' => "mattress_composition_block_{$index}_bullet_{$bullet_index}", 'label' => "Mattress: Composition block {$index} bullet {$bullet_index}", 'type' => 'text' );
		}
	}

	foreach ( range( 1, 3 ) as $index ) {
		$page_fields[] = array( 'name' => "mattress_story_{$index}_title", 'label' => "Mattress: Story {$index} title", 'type' => 'text' );
		$page_fields[] = array( 'name' => "mattress_story_{$index}_meta", 'label' => "Mattress: Story {$index} meta", 'type' => 'text' );
		$page_fields[] = array( 'name' => "mattress_story_{$index}_image", 'label' => "Mattress: Story {$index} image", 'type' => 'file', 'options' => array( 'file_format_type' => 'single', 'file_uploader' => 'attachment', 'file_type' => 'images' ) );
		$page_fields[] = array( 'name' => "mattress_story_{$index}_image_alt", 'label' => "Mattress: Story {$index} image alt", 'type' => 'text' );
	}

	$page_fields[] = array( 'name' => 'mattress_final_cta_image', 'label' => 'Mattress: Final CTA background image', 'type' => 'file', 'options' => array( 'file_format_type' => 'single', 'file_uploader' => 'attachment', 'file_type' => 'images' ) );
	$page_fields[] = array( 'name' => 'mattress_final_cta_image_alt', 'label' => 'Mattress: Final CTA image alt override', 'type' => 'text' );
	$page_fields[] = array( 'name' => 'mattress_sticky_title', 'label' => 'Mattress: Sticky bar title', 'type' => 'text' );
	$page_fields[] = array( 'name' => 'mattress_sticky_price_from', 'label' => 'Mattress: Sticky bar price from', 'type' => 'text' );
	$page_fields[] = array( 'name' => 'mattress_sticky_button_text', 'label' => 'Mattress: Sticky bar button text', 'type' => 'text' );
	$page_fields[] = array( 'name' => 'mattress_sticky_image', 'label' => 'Mattress: Sticky bar image', 'type' => 'file', 'options' => array( 'file_format_type' => 'single', 'file_uploader' => 'attachment', 'file_type' => 'images' ) );
	$page_fields[] = array( 'name' => 'mattress_sticky_image_alt', 'label' => 'Mattress: Sticky bar image alt override', 'type' => 'text' );

	$settings_fields = array(
		array( 'name' => 'contact_person_1_name', 'label' => 'Global: Contact person 1 name', 'type' => 'text' ),
		array( 'name' => 'contact_person_1_role', 'label' => 'Global: Contact person 1 role', 'type' => 'text' ),
		array( 'name' => 'contact_person_1_phone', 'label' => 'Global: Contact person 1 phone', 'type' => 'text' ),
		array( 'name' => 'contact_person_1_email', 'label' => 'Global: Contact person 1 email', 'type' => 'email' ),
		array( 'name' => 'contact_person_2_name', 'label' => 'Global: Contact person 2 name', 'type' => 'text' ),
		array( 'name' => 'contact_person_2_role', 'label' => 'Global: Contact person 2 role', 'type' => 'text' ),
		array( 'name' => 'contact_person_2_phone', 'label' => 'Global: Contact person 2 phone', 'type' => 'text' ),
		array( 'name' => 'contact_person_2_email', 'label' => 'Global: Contact person 2 email', 'type' => 'email' ),
		array( 'name' => 'company_name', 'label' => 'Global: Company name', 'type' => 'text' ),
		array( 'name' => 'company_address', 'label' => 'Global: Company address', 'type' => 'wysiwyg' ),
		array( 'name' => 'contact_map_embed_url', 'label' => 'Global: Contact map embed URL', 'type' => 'website' ),
		array( 'name' => 'contact_map_overlay_title', 'label' => 'Global: Contact map overlay title', 'type' => 'text' ),
		array( 'name' => 'contact_map_overlay_text', 'label' => 'Global: Contact map overlay text', 'type' => 'text' ),
		array( 'name' => 'footer_newsletter_title', 'label' => 'Global: Footer newsletter title', 'type' => 'text' ),
		array( 'name' => 'footer_newsletter_lead', 'label' => 'Global: Footer newsletter lead', 'type' => 'paragraph' ),
		array( 'name' => 'footer_newsletter_placeholder', 'label' => 'Global: Footer newsletter placeholder', 'type' => 'text' ),
		array( 'name' => 'footer_newsletter_button_text', 'label' => 'Global: Footer newsletter button text', 'type' => 'text' ),
		array( 'name' => 'footer_brand_title', 'label' => 'Global: Footer brand title', 'type' => 'text' ),
		array( 'name' => 'footer_brand_text', 'label' => 'Global: Footer brand text', 'type' => 'paragraph' ),
		array( 'name' => 'footer_shop_title', 'label' => 'Global: Footer shop title', 'type' => 'text' ),
		array( 'name' => 'footer_company_title', 'label' => 'Global: Footer company title', 'type' => 'text' ),
		array( 'name' => 'footer_support_title', 'label' => 'Global: Footer support title', 'type' => 'text' ),
		array( 'name' => 'footer_copyright_text', 'label' => 'Global: Footer copyright text', 'type' => 'text' ),
		array( 'name' => 'footer_made_in_poland_text', 'label' => 'Global: Footer made in Poland text', 'type' => 'text' ),
		array( 'name' => 'footer_security_text', 'label' => 'Global: Footer security text', 'type' => 'text' ),
		array( 'name' => 'footer_payment_method_1', 'label' => 'Global: Footer payment method 1', 'type' => 'text' ),
		array( 'name' => 'footer_payment_method_2', 'label' => 'Global: Footer payment method 2', 'type' => 'text' ),
		array( 'name' => 'footer_payment_method_3', 'label' => 'Global: Footer payment method 3', 'type' => 'text' ),
	);

	foreach ( array( 'shop', 'company', 'support' ) as $group ) {
		foreach ( range( 1, 3 ) as $index ) {
			$settings_fields[] = array( 'name' => "footer_{$group}_link_{$index}_label", 'label' => "Global: Footer {$group} link {$index} label", 'type' => 'text' );
			$settings_fields[] = array( 'name' => "footer_{$group}_link_{$index}_url", 'label' => "Global: Footer {$group} link {$index} URL", 'type' => 'website' );
		}
	}

	foreach ( range( 1, 2 ) as $index ) {
		$settings_fields[] = array( 'name' => "footer_legal_link_{$index}_label", 'label' => "Global: Footer legal link {$index} label", 'type' => 'text' );
		$settings_fields[] = array( 'name' => "footer_legal_link_{$index}_url", 'label' => "Global: Footer legal link {$index} URL", 'type' => 'website' );
	}

	foreach ( $page_fields as $field ) {
		stilco_upsert_pods_field( 'page', $field );
	}

	foreach ( $settings_fields as $field ) {
		stilco_upsert_pods_field( stilco_get_settings_pod_name(), $field );
	}

	update_option( 'stilco_pods_schema_version', $schema_version, false );
}
add_action( 'admin_init', 'stilco_sync_pods_schema' );
