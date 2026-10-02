# TODO i przekazanie pracy — Stilco

Dokument jest pisany tak, żeby ktoś bez historii rozmowy mógł usiąść i kontynuować. Stan na **2026-10-02** (druga sesja tego dnia).

- Worktree: `/Users/ardziej/orca/workspaces/stilco/nereid`
- Wszystko jest na `main` (`git@github.com:ardziej/stilco-wp.git`). Branch `ardziej/figma-comments-read` został zmergowany i usunięty.
- Drugi checkout `~/dev/stilco/stilco` stoi na czystym `main`. Jego niezacommitowane prace z kwietnia zostały rozdzielone: pakiet prawny, wtyczki płatności, `AGENTS.md` → `main`; blog → branch `ardziej/blog-kwiecien` (patrz decyzja 13).

## 1. Co się wydarzyło

Projekt to motyw WordPress/WooCommerce `wp-content/themes/stilco-theme` dla producenta materacy ze szwalnią w Malborku. Sprzedawany jest **jeden** produkt: materac dwustronny, strona White i strona Blue.

Wpłynęły dwa niezależne zestawy uwag z Figmy i oba zostały wdrożone w części:

| Zestaw | Autor | Plik | Spis |
| --- | --- | --- | --- |
| FigJam „Stilco - strona” | Filip Tobis, 22 komentarze | `uQIPjHyVC5lDahjtyoUxiQ` | `docs/figma-comments.md` |
| Figma „Stilco — sklep” | Jakub, 20 komentarzy | `d0WzwsfTTDJDUHUfsxpErW` | `docs/figma-comments-sklep.md` |

Oba pliki mają tabele ze statusem każdej uwagi. **Zacznij od nich**, zanim cokolwiek zmienisz.

Commity po kolei:
- `a8ed8d6`, `5f334f2` — uwagi Filipa
- `0595004` — odczyt uwag Jakuba
- `3ff3f82` — niesprzeczna część uwag Jakuba, plus strona `/opinie` i „Twój rozmiar”
- `e5436ad`, `4274b95`, `00bd367`, `29da4a2` — dokumentacja, makiety, zrzuty
- `354d73c`…`aada6f5` — **dociągnięcie strony do makiet** (opis niżej)

### Dociągnięcie do makiet (2026-10-02)

Referencja: strona „Po uwagach Jakuba” w pliku `d0WzwsfTTDJDUHUfsxpErW` (id strony `168:2333`; ramki: Home `168:2334` / `168:2875`, O nas `168:3384` / `168:3622`, Materac `168:3822` / `168:4265`).

**Ważne odkrycie:** ramka „Materac” to nie strona produktu WooCommerce, tylko szablon landingu `page-mattress.php` (sekcje `template-parts/page-mattress/*`). Żadna strona w bazie nie używa tego szablonu, a menu „Materac” prowadzi do `/produkt/materac-stilco/`. Dlatego strona produktu została dociągnięta do tej ramki, a sekcje, które landing już miał, są teraz współdzielone.

Strona produktu:
- BuyBox według makiety: galeria 4:3 (1:1 na telefonie) z plakietką Bestseller w kadrze, ocena nad tytułem (z prawdziwej średniej opinii, nie stałe 4.9), tytuł 78px, blok „Cena z dostawą” (cena podąża za wybranym rozmiarem), kafelki rozmiarów z rysunkiem w skali, „Twój rozmiar” jako ostatni kafelek, przycisk 68px `#a84a34`, cztery kafelki korzyści.
- Otwarcie „Twój rozmiar” zeruje wybrany rozmiar i chowa tylko datę i przycisk koszyka; kafelki zostają.
- Ceny bez „,00” (`woocommerce_price_trim_zeros`).
- Sekcje „Zajrzyj do środka materaca” i „100 dni na podjęcie decyzji.” pochodzą z `page-mattress/composition.php` i `final-cta.php`. Stare `single-product/technology.php` i `final-cta.php` skasowane. Kotwica `#technologia` zachowana.
- Kolejność: BuyBox, struktura, opinie, CTA 100 nocy, powiązane produkty.

Home: nagłówki sekcji Outfit Regular zamiast Bold (jak w makiecie), proporcje Dual Comfort, warstw i ciemnej sekcji, hero 64.8px w trzech liniach (także na telefonie). Nagłówek „Wiele potrzeb. Jeden materac.” był grafitowy na ciemnym tle — poprawiony.

Stopka (wszystkie strony): logo zamiast napisu STILCO, etykiety kolumn w terakocie, podkreślone linki, dolny pasek jak w makiecie.

Poprawki znalezione po drodze:
- Fonty: Inter i Playfair Display ładowane teraz także w wadze 700, więc `font-bold` wygląda jak Bold z makiet, a nie 600.
- `@tailwindcss/typography` nie był zainstalowany, a regulamin, polityka, zwykłe strony, wpisy bloga i tekst założycieli na O nas używały `prose`. Wszystko renderowało się bez stylów. Plugin dodany z motywem `prose-stilco`.
- Formularz opinii na stronie produktu nie miał wrappera `#review_form_wrapper`, na który celuje cały jego CSS, i rozpychał stronę na telefonie. Naprawione.
- `min-h-screen` na `#main-content` usunięte, więc krótki wpis bloga nie zostawia pustego ekranu.

Zrzuty po zmianach: `docs/screenshots/2026-10-02-po-makietach/`.

### Co powstało wcześniej w motywie

- `page-strefa-wiedzy.php` — FAQ u góry, wpisy bloga pod spodem, zastępuje osobne Blog i FAQ w menu
- `page-opinie.php` + `inc/reviews-page.php` + `template-parts/page-opinie/*` — lista wszystkich opinii i formularz zgłoszenia nowej
- `inc/custom-size.php` + `template-parts/woocommerce/single-product/custom-size.php` — „Twój rozmiar” na stronie produktu
- `single.php` + `template-parts/blog/*` + `inc/blog.php` — wpis bloga z sekcją powiązanych artykułów
- `inc/lightbox.php` + `assets/js/lightbox.js` — globalny lightbox na zdjęciach sekcji
- `inc/header.php`, `assets/js/modules/mobile-menu.js` — CTA w headerze i działające menu mobilne
- `template-parts/front-page/trust-bar.php` — belka pod hero
- `assets/js/contact-form-validation.js` — walidacja inline pól CF7

## 2. Uruchomienie środowiska

Docker bywa wyłączony po restarcie. Kolejność:

```sh
open -a Docker                 # poczekaj aż `docker info` przestanie zwracać błąd
docker start stilco-db stilco-wp-preview
curl -s -o /dev/null -w '%{http_code}\n' http://localhost:8083/
```

Podgląd stoi pod **http://localhost:8083** w kontenerze `stilco-wp-preview`, zmontowanym na `wp-content` tego worktree. Strony do sprawdzenia: `/`, `/strefa-wiedzy/`, `/opinie/`, `/o-nas/`, `/produkt/materac-stilco/`, `/kontakt/`.

**Nie używaj portu 8080 ani kontenera `stilco-wp`.** Port zajmuje proces `yerdd serve`, a `stilco-wp` nie jest w żadnej sieci (pochodzi z checkoutu `~/dev/stilco/stilco`, baza z `~/dev/stilco/ai`), więc zwraca błąd bazy. `docker-compose.yml` w repo jest poprawny — problem to stan kontenerów z innych katalogów, nie plik.

Kontener podglądu został odtworzony 2026-10-02 (ten sam wolumen WordPressa, ta sama baza). `uploads` z `~/dev/stilco/stilco/wp-content/uploads` jest teraz montowane **do zapisu** — trzeba było wgrać zdjęcia produktu. Pusty mount `mu-plugins` z `/tmp` usunięty. Polecenie `docker run` jest w historii commita `feat(theme): real product photos…`; w skrócie: `--network ai-stilco_default -p 8083:80`, wolumen `/var/www/html`, `wp-content` z worktree, uploads rw, dwie wtyczki płatności ro, `WORDPRESS_CONFIG_EXTRA` z `WP_HOME`/`WP_SITEURL` na 8083.

Po zmianach w CSS lub JS:

```sh
cd wp-content/themes/stilco-theme && npm run build
```

Zrzuty ekranu: `scripts/screenshots.sh docs/screenshots/<data>-<opis>` (agent-browser + magick). Przed zrzutami Strefy wiedzy warto dodać na chwilę dwa wpisy demo z meta `_stilco_demo_post`, żeby było widać siatkę kart, i skasować je zaraz po.

## 3. Pułapki, na których już się przejechaliśmy

- **Pola Pods nadpisują PHP.** Każdy tekst leci przez `stilco_get_page_field()` lub `stilco_get_setting()` z fallbackiem w kodzie. Jeśli pole jest wypełnione w bazie, zmiana w PHP **nie będzie widoczna**. Lokalny podgląd ma pola puste, produkcja niekoniecznie. Dotyczy też nowych fallbacków w stopce (`footer_brand_text`, `footer_copyright_text`, `footer_made_in_poland_text`) i w sekcji struktury (`mattress_composition_*`).
- **Ramka „Materac” = `page-mattress.php`**, nie WooCommerce. Strona produktu współdzieli z landingiem `composition.php` i `final-cta.php`; zmiana tam zmienia obie strony.
- **`node_modules` motywu jest w gicie** (od pierwszego commita). Po `npm install` nowe pliki trzeba dodać do commita albo świadomie to posprzątać.
- **Klasy `prose-*` wymagają `@tailwindcss/typography`** — jest już w `package.json`, nie usuwaj.
- **Formularz kontaktowy to Contact Form 7**, żyje w bazie, nie w repo. Lokalnie wtyczki nie ma, więc widać placeholder. To nie jest błąd.
- **Leniwe ładowanie zdjęć** psuje zrzuty pełnej strony. Skrypt przełącza je na eager, ale część i tak nie zdąży.
- **`wp_mail` bez SMTP nic nie wyśle.** Oba nowe formularze tylko mailują, nic nie publikują.
- **Dodawanie do koszyka na stronie produktu to zwykły POST z przeładowaniem**, nie AJAX. Panel koszyka sam się nie otwiera.
- **Bezwzględne adresy w bazie.** Pozycja menu „Materac” miała zapisany `http://localhost:8081/...` z dawnego uruchomienia `setup-menus.php` (na 8081 stoi teraz inna aplikacja). Lokalnie poprawione na ścieżkę względną, skrypt też zapisuje już ścieżkę względną. Po zmianie portu przeszukaj bazę: `select ... where meta_value like '%localhost:808%'`.
- **Linki w stopce `/dostawa`, `/gwarancja`, `/karty-podarunkowe` dają lokalnie 404** — strony nie zostały utworzone (`add_wp_pages.py` domyślnie celuje w niedziałający port 8080; dla `/gwarancja` nie ma nawet pliku w `docs/pages/`).
- **agent-browser zapisuje względne ścieżki zrzutów względem swojego katalogu**, nie bieżącego. Podawaj ścieżki bezwzględne.
- **Figma: lista stron przez `get_metadata` pokazuje tylko „Stan obecny”.** Pełną listę daje `use_figma` z `figma.root.children`.
- **Figma: `minHeight`.** Ramki z importu HTML mają ustawione `minHeight`, przez co `resize()` cicho nie działa. Trzeba najpierw `node.minHeight = null`.
- **Figma: pozycja w siatce.** To metoda na dziecku: `child.setGridChildPosition(row, col)`, nie na rodzicu.
- **Figma: fonty.** Każdą zmianę tekstu poprzedź `getStyledTextSegments(['fontName'])` i `loadFontAsync` dla każdego segmentu, inaczej skrypt wywala się w połowie i wycofuje całość.
- **Figma: strony ładują się leniwie.** `figma.root.children` pokaże `children: 0` dla niezaładowanej strony.
- **Figma: artefakty importu.** Ciemne hero na O nas to kontener zdjęcia z jednolitym wypełnieniem `#212529`, nie projekt. Kafelki kategorii na Home są w makiecie przesunięte w lewo, bo po usunięciu trzeciego nikt ich nie wyśrodkował. Panel „Twój rozmiar” nachodzi w makiecie na kafelki korzyści — to adnotacja stanu.

## 4. Decyzje, na które czekamy

Blokujące dalszą pracę:

1. **Menu — trzy pozycje czy cztery.** Jakub (J4): Materac, O marce, Kontakt, blog i FAQ wewnątrz „O marce”. Filip (#1): Materac, Strefa wiedzy, O nas, Kontakt. **Wdrożona jest wersja Filipa.**
2. **Etykieta i miejsce CTA w headerze.** „Zamów materac” (Jakub) czy „Skonfiguruj” (Filip)? Obecnie „Skonfiguruj”, wyśrodkowany. Makieta ma przycisk po prawej, obok ikon.
3. **Sekcja kategorii na stronie głównej.** Jakub (J17): „ta sekcja out”. Filip (#5): przebudować. Obecnie dwa kafelki.
4. **Czy pianki są własne** (Filip #11). Jeśli tak, dopisać „taki materac znajdziesz tylko u nas” na O nas.
5. **Klikalne kafelki korzyści** pod „Dodaj do koszyka” (Filip #9). Jeśli mają się rozwijać, potrzebne teksty do czterech kafelków.
6. **Źródło opinii ze zdjęciami** (Filip #6).

Nowe, z dociągania do makiet:

7. **Opinie na stronie produktu.** Makieta ma w tym miejscu „Historie naszych klientów” (trzy karty wideo, gotowe w `page-mattress/customer-stories.php`), strona ma pełną listę opinii z formularzem. Zamienić i odesłać do `/opinie`, czy zostawić listę?
8. **Powiązane produkty** pod CTA — w makiecie ich nie ma. Przy jednym produkcie sekcja pokazuje ten sam materac. Usunąć?
9. **Zdjęcie hero na Home.** Makieta ma inne ujęcie (z poduszką i metkami, plik 1536×1024, wygląda na generowane). Nie ma go w repo; strona używa zdjęcia z sesji `hero-lifestyle.jpg`. Które zostaje?
10. **Ceny.** W bazie: 2 590–4 990 zł (120×200 = 3 290 zł). W `CLAUDE.md`: 2 595–5 091 zł (120×200 = 2 888 zł). Makieta: 3 190 zł. Która tabela jest aktualna?
11. **Domyślny rozmiar.** W makiecie rozmiar jest już wybrany, na stronie nie — do czasu kliknięcia widać zakres cen i nieaktywny przycisk. Ustawić domyślny wariant w WooCommerce (np. 160×200)?
12. **„Tabela rozmiarów”** — link z makiety nie jest wdrożony, bo nie ma do czego linkować.
13. **Który blog zostaje.** Branch `ardziej/blog-kwiecien` (z kwietnia, oparty o stary `main`) ma pełny blog: `home.php`, archiwum, kategorie, wyszukiwarkę, sekcję „najnowsze artykuły” na stronie głównej, 10 artykułów w `docs/blog/` i importer `seed-blog-posts.php`. `main` ma prostszy blog z września: `single.php` z powiązanymi wpisami i listę w Strefie wiedzy. Pliki `single.php` i `inc/blog.php` kolidują, sekcja na stronie głównej jest sprzeczna z makietą (blok bloga usunięty). Najtańsza opcja: wziąć z kwietnia artykuły i importer, zostawić wrześniowe szablony.

## 5. Następne zadanie w kolejce

Po decyzjach z sekcji 4 (zwłaszcza 7 i 8) dokończyć stronę produktu. Pozostałe ramki są dociągnięte.

Do sprawdzenia przy okazji:
- **Treść regulaminu jest zastępcza** — gotowy zamiennik czeka w `docs/legal/` (pakiet z kwietnia: regulamin, polityki, formularze, `legal-audit.md`; do weryfikacji przez prawnika): adres „ul. Przykładowej 12, 00-001 Miasto”, PayU zamiast samego Przelewy24, „30 Dni na Testowanie” zamiast 100 nocy. Teraz, gdy strona ma style, widać to wyraźnie.
- Link „Karty podarunkowe” w stopce prowadzi lokalnie do 404. Treść czeka w `docs/pages/karty-podarunkowe.md`; makieta tego linku nie ma.

## 6. Pozostałe zadania

### Makiety
- [ ] Makiety stron `/opinie` i `/strefa-wiedzy` — w projekcie ich nie ma, na stronie już są.
- [ ] Ramki mobilne mają jeszcze stare teksty w miejscach, gdzie desktop jest aktualny (np. lead ciemnej sekcji na Home sprzed J16).
- [ ] Zdjęcia poza hero, Dual Comfort i sekcją warstw nadal pochodzą ze starego zestawu. Nowe są w `docs/photos/new`, wgrywa się je przez `upload_assets` z `nodeIds`.
- [ ] **J8** — Jakub pyta o render pokrowca z widocznym rozwarstwieniem. Brak materiału źródłowego, do ustalenia z marką.

### Wdrożenie
- [ ] **Zdjęcia produktu na serwerze.** Lokalnie produkt ma 7 prawdziwych zdjęć z `docs/mattresses/` (zastąpiły rendery `1–4.jpg`). Na serwerze: skopiować `docs/mattresses` i `scripts/set_product_gallery.php`, poprawić ścieżkę `wp-load.php` i uruchomić jako użytkownik serwera WWW. Skrypt jest idempotentny.
- [ ] Przejrzeć pola Pods w wp-admin, bo nadpisują nowe teksty: `home_hero_*`, `home_trust_1..3_label`, `home_mid_cta_*`, `home_category_*`, `home_b2b_*`, `home_reviews_cta_*`, `about_timeline_4_*`, `about_cta_*`, `mattress_badge_2_*`, `mattress_composition_*`, `mattress_final_cta_*`, `contact_faq_cta_*`, `contact_map_overlay_text`, `footer_brand_text`, `footer_copyright_text`, `footer_made_in_poland_text`, linki w stopce.
- [ ] Uruchomić `scripts/add_wp_pages.py` (strony `strefa-wiedzy` i `opinie`), `setup-menus.php`, `seed-faqs.php`.
- [ ] Skonfigurować SMTP i adresatów w Pods: `reviews_form_recipient`, `custom_size_recipient`.
- [ ] Dodać checkbox newslettera do formularza CF7 (Filip #22). Gotowy snippet w `docs/pages/contact.md`. Zapis do systemu newslettera nie jest podpięty.
- [ ] Zweryfikować z marką sześć odpowiedzi FAQ oznaczonych `TODO` w `docs/pages/faq.md`.

### Czeka na materiały
- [ ] Koszyk i checkout (Filip #20). Filip nie miał dostępu do tych widoków, komentarze mają dojść później.

### Dług techniczny
- [ ] W repo są trzy pliki instrukcji dla agentów: `AGENT.md`, `AGENTS.md` i `CLAUDE.md` (symlink do `AGENT.md`). Treści się rozjechały — scalić w jeden.
- [ ] Lokalnie wtyczka Pods jest nieaktywna (`active_plugins` jej nie zawiera), więc podgląd pokazuje wyłącznie fallbacki z PHP.
- [ ] Przelewy24 i Fakturownia: uzupełnić dane merchant i token API, zrobić testowy checkout (lista w `AGENTS.md`).
- [ ] Odtworzyć podgląd tak, żeby nie zależał od katalogów spoza repo (`~/dev/stilco/stilco` dla uploads i wtyczek płatności, `/private/tmp` dla mu-plugins). Albo uruchomić `docker compose up -d` z `~/dev/stilco/stilco` na innym porcie niż 8080.
- [ ] Rozważyć wyjęcie `wp-content/themes/stilco-theme/node_modules` z gita (dodać do `.gitignore`, `git rm -r --cached`). `dist/` jest commitowany, więc wdrożenie go nie potrzebuje.
