# Pods Content Mapping

Dokument opisuje sekcje przeniesione z hardcode do WordPressa z wykorzystaniem Pods, wraz z fallbackami do obecnych treści.

## 1. Home / Hero
- **Current hardcoded content found:** eyebrow, H1 accent line, lead, oba CTA, obraz tła, 4 trust labels, label scrolla.
- **Recommended WP admin source:** `Page` fields na stronie głównej + `the_title()` dla pierwszej linii H1.
- **Implementation code:** [template-parts/front-page/hero.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/front-page/hero.php), [inc/pods-content.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/inc/pods-content.php)
- **Required Pods fields:** `home_hero_*`, `home_trust_1_label` ... `home_trust_4_label`
- **Fallback logic:** gdy pole jest puste, renderuje się dotychczasowy tekst/URL/obraz.
- **Notes:** teksty przez `esc_html`, URL przez `esc_url`, alt przez `esc_attr`; obraz pobiera alt z Media Library, a `*_alt` nadpisuje go tylko jeśli jest ustawiony.

## 2. Home / Dual Comfort
- **Current hardcoded content found:** eyebrow, nagłówek, lead, 2 opisy stron materaca, badge, obraz.
- **Recommended WP admin source:** `Page` fields.
- **Implementation code:** [template-parts/front-page/dual-comfort.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/front-page/dual-comfort.php)
- **Required Pods fields:** `home_dual_*`
- **Fallback logic:** pełny fallback do istniejących treści i assetu.
- **Notes:** DOM i klasy pozostały bez zmian; wszystkie wartości są escapowane zgodnie z kontekstem.

## 3. Home / Layers
- **Current hardcoded content found:** tytuł sekcji, lead, 3 karty warstw z obrazami.
- **Recommended WP admin source:** `Page` fields.
- **Implementation code:** [template-parts/front-page/layers.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/front-page/layers.php)
- **Required Pods fields:** `home_layers_*`, `home_layer_1_*` ... `home_layer_3_*`
- **Fallback logic:** jeśli którykolwiek field jest pusty, wraca obecna treść i lokalny obraz.
- **Notes:** zachowana kolejność nagłówków `h2 > h3`; alt fallback z biblioteki mediów lub hardcoded.

## 4. Home / Categories
- **Current hardcoded content found:** title, lead, 3 karty kategorii, CTA labels, 2 obrazy.
- **Recommended WP admin source:** `Page` fields.
- **Implementation code:** [template-parts/front-page/categories.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/front-page/categories.php)
- **Required Pods fields:** `home_categories_*`, `home_category_1_*` ... `home_category_3_*`
- **Fallback logic:** obecne etykiety, opisy, emoji i obrazy są fallbackiem.
- **Notes:** URL CTA są zmapowane do osobnych pól; zachowany layout i klasy.

## 5. Home / Reviews
- **Current hardcoded content found:** eyebrow, title, lead, CTA, empty state.
- **Recommended WP admin source:** `Page` fields; same opinie nadal pochodzą z istniejących review comments WooCommerce.
- **Implementation code:** [template-parts/front-page/highlighted-reviews.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/front-page/highlighted-reviews.php)
- **Required Pods fields:** `home_reviews_*`
- **Fallback logic:** jeśli pole jest puste, wraca stara kopia; jeśli brak opinii, używa się fallback empty state.
- **Notes:** bez zmian w query i strukturze kart opinii.

## 6. Home / B2B
- **Current hardcoded content found:** eyebrow, title, lead, CTA, 2 stat cards.
- **Recommended WP admin source:** `Page` fields.
- **Implementation code:** [template-parts/front-page/b2b.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/front-page/b2b.php)
- **Required Pods fields:** `home_b2b_*`, `home_b2b_stat_1_*`, `home_b2b_stat_2_*`
- **Fallback logic:** pełny fallback do obecnych wartości.
- **Notes:** CTA label i URL są rozdzielone dla prostszej edycji.

## 7. Home / FAQ Preview
- **Current hardcoded content found:** title, lead, 4 pytania i odpowiedzi.
- **Recommended WP admin source:** `Page` fields dla title/lead, istniejący CPT `faq` dla pytań/odpowiedzi.
- **Implementation code:** [template-parts/front-page/faq.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/front-page/faq.php), [inc/pods-content.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/inc/pods-content.php)
- **Required Pods fields:** `home_faq_title`, `home_faq_lead`
- **Fallback logic:** najpierw pobierane są 4 wpisy `faq`; gdy ich brak, wyświetla się obecny hardcoded zestaw.
- **Notes:** zachowana a11y akordeonu i atrybuty `aria-expanded`.

## 8. About / Hero
- **Current hardcoded content found:** obraz hero, H1, lead.
- **Recommended WP admin source:** `the_title()` + `Page` fields.
- **Implementation code:** [template-parts/page-about/hero.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-about/hero.php)
- **Required Pods fields:** `about_hero_image`, `about_hero_image_alt`, `about_hero_lead`
- **Fallback logic:** gdy brak pola, wraca obecny asset i kopia.
- **Notes:** semantyka `h1` zachowana.

## 9. About / Founders
- **Current hardcoded content found:** obraz, headline z `<br>`, 2 akapity.
- **Recommended WP admin source:** `Page` fields.
- **Implementation code:** [template-parts/page-about/founders.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-about/founders.php)
- **Required Pods fields:** `about_founders_title`, `about_founders_body`, `about_founders_image`, `about_founders_image_alt`
- **Fallback logic:** renderuje oryginalny tekst i obraz jeśli pola są puste.
- **Notes:** tylko `<br>` jest dopuszczony w tytule; body wychodzi przez `wp_kses_post`.

## 10. About / Timeline + Values + CTA
- **Current hardcoded content found:** timeline title + 4 milestones, values title + 3 cards + image, CTA title/button.
- **Recommended WP admin source:** `Page` fields.
- **Implementation code:** [template-parts/page-about/timeline.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-about/timeline.php), [template-parts/page-about/values.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-about/values.php), [template-parts/page-about/cta.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-about/cta.php)
- **Required Pods fields:** `about_timeline_*`, `about_value_*`, `about_values_*`, `about_cta_*`
- **Fallback logic:** dotychczasowe lata, teksty i obraz pozostają domyślne.
- **Notes:** układ kart i animacje pozostały bez zmian.

## 11. Contact / Hero + Contact Blocks + Map + FAQ Ribbon
- **Current hardcoded content found:** hero image + H1, sekcja intro, osoby kontaktowe, adres, mapa, overlay copy, FAQ ribbon.
- **Recommended WP admin source:** `the_title()` + `Page` fields dla sekcji strony + `Pods Settings Page` dla globalnych danych kontaktowych i mapy.
- **Implementation code:** [template-parts/page-contact/hero.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-contact/hero.php), [template-parts/page-contact/contact-columns.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-contact/contact-columns.php), [template-parts/page-contact/map.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-contact/map.php), [template-parts/page-contact/faq-ribbon.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-contact/faq-ribbon.php)
- **Required Pods fields:** `contact_*` na `Page`, `contact_person_*`, `company_*`, `contact_map_*` na Settings Page
- **Fallback logic:** istniejące dane telefonów, maili, adresu i mapy pozostają jako fallback.
- **Notes:** telefony i maile są bezpiecznie budowane do `tel:` / `mailto:`; consent idzie przez `wp_kses_post`.

## 12. FAQ / Hero + Contact Ribbon
- **Current hardcoded content found:** hero image, H1, lead, contact ribbon image/title/lead/CTA.
- **Recommended WP admin source:** `the_title()` + `Page` fields.
- **Implementation code:** [template-parts/page-faq/hero.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-faq/hero.php), [template-parts/page-faq/contact-ribbon.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-faq/contact-ribbon.php)
- **Required Pods fields:** `faq_hero_*`, `faq_contact_*`
- **Fallback logic:** wracają obecne teksty, URL i obrazy zewnętrzne.
- **Notes:** główna lista FAQ pozostaje na istniejącym CPT `faq`.

## 13. Mattress Landing / Buy Box
- **Current hardcoded content found:** H1, lead, rating copy, price label, gallery images, size labels/prices, CTA, benefit badges.
- **Recommended WP admin source:** `the_title()` + `Page` fields.
- **Implementation code:** [template-parts/page-mattress/buy-box.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-mattress/buy-box.php), [inc/mattress.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/inc/mattress.php)
- **Required Pods fields:** `mattress_*`, `mattress_gallery_*`, `mattress_size_*`, `mattress_badge_*`
- **Fallback logic:** stare obrazy, ceny i etykiety pozostają domyślne.
- **Notes:** JS rozmiarów pozostaje kompatybilny, bo nadal dostaje `data-price` na tych samych przyciskach.

## 14. Mattress Landing / Composition + Stories + Final CTA + Sticky Bar
- **Current hardcoded content found:** section headings, 2 bloki composition, 3 customer stories, final CTA, sticky bar copy i obraz.
- **Recommended WP admin source:** `Page` fields.
- **Implementation code:** [template-parts/page-mattress/composition.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-mattress/composition.php), [template-parts/page-mattress/customer-stories.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-mattress/customer-stories.php), [template-parts/page-mattress/final-cta.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-mattress/final-cta.php), [template-parts/page-mattress/sticky-cart-bar.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-mattress/sticky-cart-bar.php)
- **Required Pods fields:** `mattress_composition_*`, `mattress_story_*`, `mattress_final_cta_*`, `mattress_sticky_*`
- **Fallback logic:** pełny fallback do obecnego sales copy i assetów.
- **Notes:** przy final CTA URL jest edytowalny, ale layout i klasy pozostają identyczne.

## 15. Footer / Global Settings
- **Current hardcoded content found:** newsletter title/lead/input/button, brand copy, 3 kolumny linków, legal links, copyright, payment labels.
- **Recommended WP admin source:** `Pods Settings Page`
- **Implementation code:** [footer.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/footer.php), [inc/pods-content.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/inc/pods-content.php)
- **Required Pods fields:** `footer_*`
- **Fallback logic:** cała stopka wraca do obecnych wartości, jeśli konkretne pola są puste.
- **Notes:** linki są escapowane przez `esc_url`, teksty przez `esc_html`.

## 16. Pods Schema Bootstrap
- **Current hardcoded content found:** brak panelu dla powyższych sekcji.
- **Recommended WP admin source:** Pods extension of `Page` + Pods Settings Page `stilco_settings`.
- **Implementation code:** [inc/pods-content.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/inc/pods-content.php), [functions.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/functions.php)
- **Required Pods fields:** tworzone programowo przez `stilco_sync_pods_schema()`
- **Fallback logic:** jeśli Pods nie jest aktywny, frontend dalej działa na obecnych hardcoded fallbackach.
- **Notes:** sync działa tylko w `wp-admin` i tylko dla `manage_options`, więc nie dokłada zapisu po stronie frontu.

## 17. Plugin Installation
- **Current hardcoded content found:** repo nie miało Pods.
- **Recommended WP admin source:** plugin `pods`
- **Implementation code:** plugin files znajdują się w [wp-content/plugins/pods](/Users/ardziej/dev/stilco/stilco/wp-content/plugins/pods)
- **Required Pods fields:** brak dodatkowych, to tylko runtime dependency.
- **Fallback logic:** motyw działa bez aktywnego Pods dzięki helperom fallbackowym.
- **Notes:** w tym workspace `wp-cli` nie był dostępny; plugin został pobrany do repo, ale aktywacja musi nastąpić w docelowej instalacji WordPress używającej tego `wp-content`.
