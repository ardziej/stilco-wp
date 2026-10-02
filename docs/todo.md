# TODO i przekazanie pracy — Stilco

Dokument jest pisany tak, żeby ktoś bez historii rozmowy mógł usiąść i kontynuować. Stan na **2026-10-02**.

- Worktree: `/Users/ardziej/orca/workspaces/stilco/nereid`
- Branch: `ardziej/figma-comments-read`, ostatni commit `00bd367`, drzewo czyste, wszystko wypchnięte
- Repozytorium: `git@github.com:ardziej/stilco-wp.git`, gałąź główna `main` (brak PR, branch nie był jeszcze mergowany)

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
- `e5436ad`, `4274b95`, `00bd367` — dokumentacja, makiety, zrzuty

### Co powstało nowego w motywie

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

**Nie używaj portu 8080 ani kontenera `stilco-wp`.** Port zajmuje proces `yerdd serve`, a `stilco-wp` nie jest w sieci `ai-stilco_default` i zwraca błąd bazy. Kontener podglądu powstał osobno właśnie z tego powodu i dokłada mu-plugin przepisujący adresy mediów na bieżący host.

Po zmianach w CSS lub JS:

```sh
cd wp-content/themes/stilco-theme && npm run build
```

Zrzuty ekranu robi skrypt oparty o `agent-browser` (viewport, `screenshot --full`, konwersja przez `magick`). Nie ma go w repo, odtwarza się w kilka linijek. Przed zrzutami Strefy wiedzy warto dodać na chwilę dwa wpisy demo z meta `_stilco_demo_post`, żeby było widać siatkę kart, i skasować je zaraz po.

Aktualne zrzuty: `docs/screenshots/2026-10-02-stan-biezacy/`, poprzednie `docs/screenshots/2026-09-15-figma-review/`.

## 3. Pułapki, na których już się przejechaliśmy

- **Pola Pods nadpisują PHP.** Każdy tekst leci przez `stilco_get_page_field()` lub `stilco_get_setting()` z fallbackiem w kodzie. Jeśli pole jest wypełnione w bazie, zmiana w PHP **nie będzie widoczna**. Lokalny podgląd ma pola puste, produkcja niekoniecznie.
- **Formularz kontaktowy to Contact Form 7**, żyje w bazie, nie w repo. Lokalnie wtyczki nie ma, więc widać placeholder. To nie jest błąd.
- **Leniwe ładowanie zdjęć** psuje zrzuty pełnej strony. Puste kadry w „Odkryj wnętrze materaca” są artefaktem, nie defektem.
- **`wp_mail` bez SMTP nic nie wyśle.** Oba nowe formularze tylko mailują, nic nie publikują.
- **Figma: `minHeight`.** Ramki z importu HTML mają ustawione `minHeight`, przez co `resize()` cicho nie działa. Trzeba najpierw `node.minHeight = null`.
- **Figma: pozycja w siatce.** To metoda na dziecku: `child.setGridChildPosition(row, col)`, nie na rodzicu.
- **Figma: fonty.** Każdą zmianę tekstu poprzedź `getStyledTextSegments(['fontName'])` i `loadFontAsync` dla każdego segmentu, inaczej skrypt wywala się w połowie i wycofuje całość.
- **Figma: strony ładują się leniwie.** `figma.root.children` pokaże `children: 0` dla niezaładowanej strony. Strona „Propozycje” wyglądała na pustą, a miała sześć ramek projektanta.

## 4. Decyzje, na które czekamy (blokują dalszą pracę)

1. **Menu — trzy pozycje czy cztery.** Jakub (J4) chce: Materac, O marce, Kontakt, a blog i FAQ jako sekcje wewnątrz „O marce”. Filip (#1) chciał: Materac, Strefa wiedzy, O nas, Kontakt. **Obecnie wdrożona jest wersja Filipa.** Wersja Jakuba oznacza skasowanie `page-strefa-wiedzy.php` i przeniesienie treści.
2. **Etykieta CTA w headerze.** „Zamów materac” (Jakub) czy „Skonfiguruj” (Filip)? Obecnie „Skonfiguruj”.
3. **Sekcja kategorii na stronie głównej.** Jakub (J17): „ta sekcja out”. Filip (#5): przebudować. Obecnie są dwa kafelki, Materac Stilco i Dlaczego Stilco. Michał nie odpowiedział na to pytanie.
4. **Czy pianki są własne** (Filip #11). Jeśli tak, dopisać „taki materac znajdziesz tylko u nas” w sekcji wartości na O nas.
5. **Klikalne kafelki korzyści** pod „Dodaj do koszyka” (Filip #9). Jeśli mają się rozwijać, potrzebne teksty do czterech kafelków.
6. **Źródło opinii ze zdjęciami** (Filip #6). Kto dodaje zdjęcia do opinii i skąd.

## 5. Następne zadanie w kolejce

**Dociągnąć stronę do makiet.** Michał poprosił o to wprost i zaznaczył, że na stronie produktu przycisk jest w innym miejscu i inaczej wygląda, hero się różni, pozostałe sekcje podobnie.

Treści są już po obu stronach identyczne, więc zostaje sama warstwa wizualna: układ, odstępy, typografia, pozycje przycisków. Punkt wyjścia to **strona produktu**, tam rozjazdy są największe.

Referencją jest strona **„Po uwagach Jakuba”** w pliku Figma `d0WzwsfTTDJDUHUfsxpErW`. Sześć ramek: Home, O nas i Materac, każda desktop 1440 i mobile 390. Strony „Stan obecny” i „Propozycje” zostawiamy w spokoju, to materiał projektanta.

Konto `m@dedicapps.com` ma prawo zapisu do tego pliku. Przed `use_figma` trzeba wczytać przewodnik z zasobu `skill://figma/figma-use/SKILL.md`.

## 6. Pozostałe zadania

### Makiety
- [ ] Makiety stron `/opinie` i `/strefa-wiedzy` — w projekcie ich nie ma, na stronie już są.
- [ ] Zdjęcia poza hero, Dual Comfort i sekcją warstw nadal pochodzą ze starego zestawu. Nowe są w `docs/photos/new`, wgrywa się je przez `upload_assets` z `nodeIds`.
- [ ] **J8** — Jakub pyta o render pokrowca z widocznym rozwarstwieniem. Brak materiału źródłowego, do ustalenia z marką.

### Wdrożenie
- [ ] Przejrzeć pola Pods w wp-admin, bo nadpisują nowe teksty: `home_hero_*`, `home_trust_1..3_label`, `home_mid_cta_*`, `home_category_*`, `home_b2b_*`, `home_reviews_cta_*`, `about_timeline_4_*`, `about_cta_*`, `mattress_badge_2_*`, `contact_faq_cta_*`, `contact_map_overlay_text`, linki w stopce.
- [ ] Uruchomić `scripts/add_wp_pages.py` (strony `strefa-wiedzy` i `opinie`), `setup-menus.php`, `seed-faqs.php`.
- [ ] Skonfigurować SMTP i adresatów w Pods: `reviews_form_recipient`, `custom_size_recipient`.
- [ ] Dodać checkbox newslettera do formularza CF7 (Filip #22). Gotowy snippet w `docs/pages/contact.md`. Zapis do systemu newslettera nie jest podpięty.
- [ ] Zweryfikować z marką sześć odpowiedzi FAQ oznaczonych `TODO` w `docs/pages/faq.md`, zwłaszcza czas przechowywania materaca w rolce, możliwość obejrzenia go w Malborku i opcje dostawy.

### Czeka na materiały
- [ ] Koszyk i checkout (Filip #20). Filip nie miał dostępu do tych widoków, komentarze mają dojść później.

### Dług techniczny
- [ ] Naprawić `docker-compose`, żeby `stilco-wp` wstawał w sieci `ai-stilco_default` i nie trzeba było osobnego kontenera podglądu.
- [ ] Na stronie wpisu bloga przy krótkiej treści zostaje duża pusta przestrzeń. Powód to `min-h-screen` na `#main-content` w `header.php`, zachowanie sprzed tych zmian.
