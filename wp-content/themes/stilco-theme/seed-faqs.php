<?php
/**
 * Skrypt seedujący dane FAQ z faq.md do bazy WordPress.
 * Przeznaczony do jednorazowego uruchomienia z wiersza poleceń.
 */

// Wczytywanie kontekstu WP z kontenera dockera
require_once( dirname(__FILE__) . '/../../../wp-load.php' );

$faqs = [
    [
        'question' => 'Jak dużą przewagę ma pianka Visco nad zwykłą gąbką tapicerską?',
        'answer' => 'Pianka Visco (termoelastyczna, tzw. memory foam) dostosowuje się idealnie do Twojego ciała pod wpływem temperatury. Konwencjonalna gąbka nie posiada takich właściwości wsparcia kręgosłupa i o wiele szybciej ulega odkształceniom.',
        'category' => 'Produkt: Pianki, Twardość, Rozmiary'
    ],
    [
        'question' => 'Jaką twardość ma Materac Stilco?',
        'answer' => 'Konstrukcja (HR40 + Visco45) klasyfikuje materac pod optymalne H3 (średnio twardy). Jest zaprojektowany tak, aby zadowalać >80% europejskich śpiochów ważących od 55 kg do 105 kg.',
        'category' => 'Produkt: Pianki, Twardość, Rozmiary'
    ],
    [
        'question' => 'Po jakim czasie od rozpakowania materac zyska wymiary robocze (22 cm grubość)?',
        'answer' => 'Rozpakuj materac jak najszybciej. Gąbki poliuretanowe powracają do swojej objętości w ok. **72 godziny**. Przez pierwszy dzień nie powinieneś na nim spać, aby pozwolić komórkom we wnętrzu zaczerpnąć powietrza.',
        'category' => 'Produkt: Pianki, Twardość, Rozmiary'
    ],
    [
        'question' => 'Jakie są wymiary paczki? (Rolowanie materacy)',
        'answer' => 'Nasze materace rolujemy i pakujemy próżniowo za pomocą bardzo nowoczesnej maszyny. Dzięki temu w paczce 40x40x(Długość rolki w zależności od rozmiaru np. 160) cm i wadze koło 20-30kg paczkę łatwo odbierzesz od kuriera lub wniesiesz samemu na czwarte piętro.',
        'category' => 'Logistyka i Utrzymanie'
    ],
    [
        'question' => 'Jak czyścić pokrowiec?',
        'answer' => 'Dzięki zamkowi błyskawicznemu, zdejmiesz pokrowiec w 1 ułamku i wypierzesz w temperaturze do 60°C. Do wnętrza wkładu się nie dostaniesz i nie musisz prać samej pianki - dbaj o to, by używać specjalistycznego ochraniacza lub bardzo dobrych prześcieradeł.',
        'category' => 'Logistyka i Utrzymanie'
    ]
];

echo "Rozpoczynam seedowanie...\n";

foreach ( $faqs as $faq ) {
    // Krok 1: Kategoria
    $term = term_exists( $faq['category'], 'faq_category' );
    if ( ! $term && ! is_wp_error( $term ) ) {
        $term = wp_insert_term( $faq['category'], 'faq_category' );
        echo "Dodano kategorię: " . $faq['category'] . "\n";
    }

    if ( is_wp_error( $term ) ) {
        echo "Błąd taksonomii: " . $term->get_error_message() . "\n";
        continue;
    }

    // Krok 2: Sprawdź czy FAQ już istnieje (np. po tytule)
    $args = [
        'post_type'  => 'faq',
        'title'      => $faq['question'],
        'post_status'=> 'any',
        'numberposts'=> 1
    ];
    $existing = get_posts($args);

    if ( empty($existing) ) {
        // Dodaj nowy
        $post_id = wp_insert_post([
            'post_title'   => $faq['question'],
            'post_content' => $faq['answer'],
            'post_status'  => 'publish',
            'post_type'    => 'faq'
        ]);

        if ( ! is_wp_error( $post_id ) ) {
            // Przypisz kategorię
            wp_set_object_terms( $post_id, (int) $term['term_id'], 'faq_category' );
            echo "Dodano FAQ: " . $faq['question'] . "\n";
        } else {
            echo "Błąd dodawania FAQ: " . $faq['question'] . "\n";
        }
    } else {
        echo "Pominięto istniejące FAQ: " . $faq['question'] . "\n";
    }
}

echo "Gotowe!\n";
