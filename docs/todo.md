# TODO — Stilco

Jedno miejsce na to, co zostało do zrobienia i do rozstrzygnięcia. Źródła uwag: `docs/figma-comments.md` (FigJam „Stilco - strona”, Filip) i `docs/figma-comments-sklep.md` (Figma „Stilco — sklep”, Jakub).

Stan na 2026-09-21. Po zamknięciu pozycji przenieść ją do sekcji „Zrobione” z numerem commita.

## 1. Do rozstrzygnięcia (blokuje pracę)

- [ ] **Menu — 3 czy 4 pozycje.** Jakub (J4) chce trzech: Materac, O marce, Kontakt, a blog i FAQ jako sekcje wewnątrz „O marce”. Filip (#1) chciał czterech ze „Strefą wiedzy”. Wersja Jakuba oznacza skasowanie `page-strefa-wiedzy.php` i przeniesienie FAQ oraz wpisów na stronę „O marce”. Do czasu decyzji zostaje wersja Filipa.
- [ ] **CTA w headerze.** „Zamów materac” (Jakub, J2) czy „Skonfiguruj” (Filip, #1)?
- [ ] **Sekcja Kategorie.** Jakub (J17) pisze „ta sekcja out”. Filip (#5) chciał ją przebudować i tak jest teraz: dwa kafelki, Materac i Dlaczego Stilco. Usuwamy całą czy zostawiamy?
- [ ] **Własne pianki** (Filip #11). Czy pianki są nasze? Jeśli tak, dopisać „taki materac znajdziesz tylko u nas” w sekcji wartości na stronie O nas.
- [ ] **Klikalne kafelki korzyści** (Filip #9). Pasek pod „Dodaj do koszyka”: 100 nocy, gwarancja, dostawa, polska produkcja. Otwierać opisy po kliknięciu? Jeśli tak, potrzebne teksty do czterech kafelków.
- [ ] **Źródło opinii ze zdjęciami** (Filip #6). Skąd biorą się zdjęcia przy opiniach i kto je dodaje. Formularz na `/opinie` już zbiera pliki, ale publikacja jest ręczna.

## 2. Uwagi Jakuba jeszcze niewdrożone

- [ ] **J8 — render pokrowca z rozwarstwieniem.** Jakub pyta, czy da się pokazać rozwarstwienie warstw na naszym pokrowcu, tak jak na zdjęciu obok. Nie mam z czego tego złożyć. Potrzebna grafika albo zgoda na render AI plus materiał źródłowy.
- [ ] **J20 — przycisk w sekcji Blog.** W projekcie sekcja „08 Blog” ma przycisk „z czapy”, ma trafić pod sekcję, wyśrodkowany, w kolorystyce strony. Na stronie głównej w kodzie nie ma sekcji z blogiem, więc nie ma czego poprawiać. Wrócić, jeśli sekcja blogowa ma się pojawić na głównej.

## 3. Synchronizacja projektu z Figmą

Prace idą na **nowej stronie „Po uwagach Jakuba”** w pliku `d0WzwsfTTDJDUHUfsxpErW`. Strony „Stan obecny” i „Propozycje” zostają nietknięte — druga wbrew nazwie nie była pusta, ma już sześć ramek od projektanta.

Zrobione na makiecie „Home — Desktop 1440”:
- hero skrócony z 1000 do 880 px, treść wyśrodkowana, nadrzędna linia „MANUFAKTURA DOBREGO SNU”, CTA „Zamów materac”, biała zasłona i gradient pod tekstem (J2);
- belka zaufania pod hero z trzema pozycjami (J11);
- ciemna sekcja: „Wiele potrzeb. Jeden materac.”, nowy lead, przycisk „Zamów teraz”, tło rozjaśnione do `#2B3035` (J13–J16).

Zrobione na makiecie „Materac — Desktop 1440”:
- kafelek „Twój rozmiar” w siatce rozmiarów oraz panel formularza wyceny z prefillem 200 / 160 / 22 (J1).

Decyzja Michała z 2026-09-21: makiety mają odzwierciedlać to, co strona ma dzisiaj, czyli uwagi Jakuba **i** wdrożone wcześniej uwagi Filipa.

Zrobione dodatkowo na wszystkich sześciu ramkach:
- nawigacja Materac / Strefa wiedzy / O nas / Kontakt, pozycja „Blog” usunięta, przycisk „Skonfiguruj”;
- teksty Dual Comfort, warstw, kategorii, opinii i sekcji B2B przepisane z aktualnej strony, w tym „Dostawa w całej Polsce” zamiast Europy;
- kategorie: kafelek „Akcesoria” usunięty, drugi kafelek przerobiony na „Dlaczego Stilco?” w tym samym stylu co pierwszy;
- sekcja „08 Blog” usunięta, bo na stronie głównej jej nie ma; przycisk pod FAQ usunięty z tego samego powodu;
- pytania w podglądzie FAQ zrównane z tymi, które renderuje strona;
- stopka: Materac, Dlaczego Stilco?, Strefa wiedzy;
- „2 lata gwarancji” na stronie produktu poprawione na „5 lat gwarancji”;
- O nas: „Globalny zasięg” zastąpiony, CTA „Przejdź do konfiguratora”;
- hero mobilny dostał tę samą nadrzędną linię, wyśrodkowanie i belkę zaufania co desktop;
- podmienione zdjęcia: materac od boku w Dual Comfort, zdejmowany pokrowiec i śpiąca modelka w sekcji warstw, desktop i mobile.

Zostało:
- [ ] **Makiety stron `/opinie` i `/strefa-wiedzy`** — w projekcie ich nie ma, a na stronie już są.
- [ ] **J8** — render pokrowca z rozwarstwieniem, wciąż brak materiału.
- [ ] **Dociągnąć stronę do makiet.** Sekcja po sekcji zbliżyć stronę do projektu. Michał zwrócił uwagę, że na stronie produktu przycisk jest w innym miejscu i inaczej wygląda, hero się różni, pozostałe sekcje podobnie. Teraz, gdy treści są zgodne, zostaje sama warstwa wizualna: układ, odstępy, typografia, pozycje przycisków.
- [ ] **Pozostałe zdjęcia** poza hero, Dual Comfort i warstwami nadal pochodzą ze starego zestawu.

## 4. Wdrożenie i konfiguracja

- [ ] **Pola Pods w bazie nadpisują fallbacki z PHP.** Po deployu przejrzeć w wp-admin: `home_hero_*`, `home_trust_1..3_label`, `home_mid_cta_*`, `home_category_*`, `home_b2b_*`, `home_reviews_cta_*`, `about_timeline_4_*`, `about_cta_*`, `mattress_badge_2_*`, `contact_faq_cta_*`, `contact_map_overlay_text`, linki w stopce.
- [ ] **Uruchomić skrypty:** `scripts/add_wp_pages.py` (strony `strefa-wiedzy` i `opinie`), `setup-menus.php`, `seed-faqs.php`.
- [ ] **Poczta wychodząca.** `/opinie` i „Twój rozmiar” wysyłają przez `wp_mail`. Bez działającego SMTP zgłoszenia przepadną. Ustawić adresatów w Pods: `reviews_form_recipient`, `custom_size_recipient`.
- [ ] **Checkbox newslettera w CF7** (Filip #22). Snippet w `docs/pages/contact.md`, do wklejenia w wp-admin. Brak integracji z systemem newslettera.
- [ ] **Zweryfikować odpowiedzi FAQ** (Filip #14). Sześć nowych pytań ma odpowiedzi napisane przez agenta, oznaczone `TODO` w `docs/pages/faq.md`. Zwłaszcza: czas przechowywania w rolce, możliwość obejrzenia materaca w Malborku, opcje dostawy.
- [ ] **Koszyk i checkout** (Filip #20). Czekają na komentarze po udostępnieniu widoków.

## 5. Środowisko lokalne

- [ ] **Port 8080 zajęty** przez proces `yerdd serve`, a kontener `stilco-wp` nie jest w sieci `ai-stilco_default` i nie widzi bazy. Podgląd chodzi na osobnym kontenerze `stilco-wp-preview` (port 8083) zmontowanym na worktree. Docelowo naprawić `docker-compose`.

## Zrobione

- Uwagi Filipa z FigJam — commity `a8ed8d6`, `5f334f2`. Status per komentarz w `docs/figma-comments.md`.
- Niesprzeczne uwagi Jakuba — commit `3ff3f82`. Status per komentarz w `docs/figma-comments-sklep.md`.
