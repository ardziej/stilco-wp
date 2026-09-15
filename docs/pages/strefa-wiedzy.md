<!--
slug: strefa-wiedzy
template: page-strefa-wiedzy.php
menu_order: 2
-->

# Strefa wiedzy

> **Global Layout:** Ta strona dziedziczy Header i Footer opisane w pliku `global_layout.md`.
> Decyzja z przeglądu FigJam (#1, 2026-09-15): Blog i FAQ łączymy w jedną stronę „Strefa wiedzy”. Osobna pozycja „FAQ” i „Blog” znika z menu głównego. Stara strona `/faq` zostaje jako alias (ten sam szablon FAQ), ale nie jest linkowana z menu.

## 1. Hero
Ten sam hero co FAQ (`template-parts/page-faq/hero.php`): tytuł strony jako H1, lead z pola Pods `faq_hero_lead`.

## 2. FAQ (akordeony)
Grupy pytań z CPT `faq` pogrupowane taksonomią `faq_category` (`template-parts/page-faq/faq-groups.php`). Treść pytań: `docs/pages/faq.md`, seed: `seed-faqs.php`.

## 3. Artykuły (blog)
Siatka 3 kolumn (`template-parts/knowledge/posts.php` → `template-parts/blog/card.php`), maks. 9 najnowszych wpisów. Karty o równej wysokości (FigJam #13): obraz 4:3, tytuł ucięty do 2 linii, zajawka do 3, data przy dolnej krawędzi.

Pola Pods (strona): `knowledge_posts_eyebrow`, `knowledge_posts_title`, `knowledge_posts_lead`, `knowledge_posts_empty`.

## 4. Contact Ribbon
Jak na FAQ (`template-parts/page-faq/contact-ribbon.php`).

## Pojedynczy wpis
`single.php`: nagłówek z linkiem „Strefa wiedzy”, obraz wyróżniający, treść, a pod spodem sekcja „Powiązane artykuły” z 3 kartami (FigJam #19) — najpierw wpisy z tej samej kategorii, dopełniane najnowszymi (`stilco_get_related_posts()` w `inc/blog.php`).

## Wdrożenie
1. Utworzyć stronę „Strefa wiedzy” (slug `strefa-wiedzy`) z szablonem „Strefa wiedzy” — `scripts/add_wp_pages.py` czyta ten plik.
2. Uruchomić `setup-menus.php` (menu: Materac, Strefa wiedzy, O nas, Kontakt).
3. Uruchomić `seed-faqs.php` dla nowych pytań.
