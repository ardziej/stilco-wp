<!--
slug: opinie
template: page-opinie.php
menu_order: 5
-->

# Głos wyspanych klientów

> **Global Layout:** Ta strona dziedziczy Header i Footer opisane w pliku `global_layout.md`.
> Powstała na uwagi z przeglądu Figma „Stilco — sklep”: J18 (landing z formularzem opinii) i J19 (podstrona ze zbiorem wszystkich opinii, link w stopce).

## 1. Hero
Nagłówek strony, lead oraz podsumowanie ocen: średnia z opublikowanych opinii, gwiazdki i liczba opinii. Pod spodem przycisk prowadzący do formularza. Pola Pods: `reviews_hero_eyebrow`, `reviews_hero_lead`, `reviews_hero_cta`.

## 2. Wszystkie opinie
Siatka trzech kolumn ze wszystkimi zatwierdzonymi opiniami WooCommerce (`type = review`, `status = approve`), po 12 na stronę, z paginacją. Karta pokazuje ocenę, zdjęcie z `_review_image_id` (klikalne, otwiera lightbox), treść i autora.

## 3. Formularz opinii
Pola: ocena 1–5 gwiazdek (wymagana), imię i nazwisko, e-mail, telefon (opcjonalny), treść opinii, pliki (zdjęcia i wideo), zgoda na przetwarzanie danych (wymagana) i zgoda na publikację (opcjonalna).

Obsługa: `inc/reviews-page.php`. Zgłoszenie **nie jest publikowane automatycznie** — leci e-mailem na skrzynkę obsługową, a publikacja zostaje po stronie zespołu. Pliki są dołączane do wiadomości i kasowane z dysku zaraz po wysyłce, więc nic przesłanego przez formularz nie jest publicznie dostępne.

Limity: do 5 plików, każdy do 10 MB, formaty JPG, PNG, WEBP, HEIC, MP4, MOV. Formularz ma nonce i honeypot.

Adresaci: pole Pods `reviews_form_recipient` (kilka adresów po przecinku). Gdy puste, wiadomość idzie na adresy z `contact_person_1_email` i `contact_person_2_email`.

## Wdrożenie
1. Utworzyć stronę „Opinie” (slug `opinie`) z szablonem „Opinie” — `scripts/add_wp_pages.py` czyta ten plik.
2. Sprawdzić, czy WordPress wysyła pocztę (na produkcji SMTP). Bez tego formularz nic nie dostarczy.
3. Ustawić `reviews_form_recipient` w ustawieniach globalnych Pods.
