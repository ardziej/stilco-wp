# Stilco E-commerce - Agent Context & Plan

Ten plik służy jako główne źródło wiedzy (Single Source of Truth) dla obecnych i przyszłych działań deweloperskich nad motywem e-commerce premium dla marki Stilco produkującej materace.

## 1. Aktualny Status (Zrobione)
Zdefiniowano podwaliny dedykowanego motywu WordPress/WooCommerce (`stilco-theme`) bez ociężałych page-builderów, stawiając na szybkość, nowoczesny "Premium Feel" oraz konwersję (wzorując się na markach takich jak Tempur, Hilding, a UX z darmowymi dostawami i 100-dniowymi testami na Snoovio).

### Ukończone Funkcjonalności:
- **Konfiguracja Środowiska Front-end:** Inicjalizacja pakietów `npm`, konfiguracja `Vite.js` z `TailwindCSS` do szybkiego kompilowania assetów.
- **Szkielet Motywu WP (Core):** Utworzono pliki deklaracyjne – `style.css`, `functions.php` (rejestracja menu, wsparcie dla woo, wpięcie customowych fragmentów AJAX dla koszyka).
- **Globalne Szablony Tła:**
  - `header.php`: Glassmorphism header, responsywne menu robocze, interaktywna ikona koszyka podpięta pod zliczenia AJAX.
  - `footer.php`: Podział kolumnowy, Trust Signals (Newsletter, Linki do polityk, Kontakt).
- **Prototypy UI Stron:**
  - `front-page.php`: Lifestylowa struktura w układzie "Hero ze zdjęciem w tle -> Pasek zaufania (100 Nocy / Darmowa dostawa) -> Trzy główne kafelki kategorii".
  - `index.php`: Prosty zapasowy układ loopa do bloga z wpisami po 3 w rzędzie (Grid na Tailwind).
- **Modyfikacje WooCommerce:**
  - `archive-product.php`: Czysty widok listingu (Karta produktu, zdjęcie przeskakujące na animacji, odznaka promocji, przycisk do szybkiego ewentualnego dodania).
  - `cart/mini-cart.php` (Off-Canvas): Modalne "Drawer" wysuwane z prawej strony (Tailwind), połączone z Vanilla JS (z nasłuchiwaniem na eventy `added_to_cart` z woo).
  - `single-product.php` (Konfigurator): Wykreowany zarys podziału ekranu 50/50, gdzie prawa strona przewiduje docelowy, interaktywny "Konfigurator" wielkości/twardości dla wariantów Woo.

## 2. Tech Stack
- **Backend:** PHP 8+, WordPress 6+, WooCommerce.
- **Frontend Budowanie:** Node.js, npm, Vite.
- **Style CSS:** Tailwind CSS (Konfiguracja kolorów zdefiniowana w `tailwind.config.js` oparta o granaty, biel i złato/miedź `stilco-accent`). Autoprefixer + PostCSS.
- **JavaScript:** ES6 Vanilla JavaScript (bez nakładek typu React/Vue by zminimalizować ciężar sklepu, skupienie na obsłudze DOM API do animacji `IntersectionObserver` i off-canvas UI).

## 3. Road-map / Plan Dalszego Działania
Co pozostało do wdrożenia / Należy podnieść w kolejnych iteracjach:

- [ ] **Dynamiczny Routing Assetów Vite:** W pliku `functions.php` przygotowanie inteligentnej ładowarki sprawdzającej czy Vite serwer działa (`localhost:5173`), by ładować z niego, lub wczytywać manifest.json gdy zbudowane przez `npm run build` na produkcji.
- [ ] **Pełna Integracja Produktu Zmiennego (Konfigurator):** Przejęcie procesu generowania pól wyboru i przycisków dodawania wariantu, aby kafelki rozmiarów (80x200, 160x200 itd.) i twardości wyglądały jak przyciski radiowe z pięknym CSSem. Należy do tego nadpisać pliki z `woocommerce/single-product/add-to-cart/variable.php`.
- [ ] **Kasa (Checkout):** Przeprojektowanie `checkout/form-checkout.php` dla 1-kolumnowego (lub podzielonego np. 60/40) gładkiego przebiegu z zamknięciem zbędnych pól, ukierunkowanego pod szybką sprzedaż opartą o płatności mobilne/Blik.
- [ ] **Zaawansowane Animacje:** Wpięcie mikroskryptów poprawiających gładkość UX (np. Swiper.js dla galerii produktowej w locie lub lepsze lifestylowe przejazdy).

Ten plan aktualizuj po każdym zakończonym sprincie deweloperskim.

## 4. Dane Produktów (Do utworzenia w WP/WooCommerce)

**Produkt: Materac piankowy Stilco**

**Specyfikacja:**
- **Grubość:** 22 cm.
- **Rozmiary standardowe:** 80x200, 90x200, 120x200, 140x200, 160x200 oraz 180x200.
- **Niestandardowe rozmiary:** Istnieje możliwość zamówienia materaca w niestandardowym rozmiarze. 
- **Budowa:** Wykonany z połączenia dwóch wysokogatunkowych pianek. Rdzeń stanowi pianka HR (wysokoelastyczna) o gęstości 40 kg/m3. Górna warstwa to pianka Visco (termoelastyczna) o gęstości 45 kg/m3, która dopasowuje się do ciała.
- **Spoiwo:** Do klejenia używany jest klej wodny.
- **Pokrowiec (obszycie):** Włoskie tkaniny połączone zamkiem rozdzielczym, co ułatwia pranie pokrowca.
- **Dodatki w cenie:** Materac zapakowany w folię oraz karton, nożyk do rozcinania folii oraz przesyłka kurierska wliczona w poniższe ceny.

**Warianty (Rozmiar i Cennik):**

| Rozmiar | Koszt produkcji | Sugerowana cena detaliczna netto | Cena brutto do sklepu WP | Marża |
| :--- | :--- | :--- | :--- | :--- |
| **80x200** | 1 060 zł | 2 109 zł | **2 595 zł** | 1 049 zł |
| **90x200** | 1 147 zł | 2 283 zł | **2 808 zł** | 1 136 zł |
| **120x200** | 1 180 zł | 2 348 zł | **2 888 zł** | 1 168 zł |
| **140x200** | 1 545 zł | 3 075 zł | **3 782 zł** | 1 530 zł |
| **160x200** | 1 820 zł | 3 622 zł | **4 455 zł** | 1 802 zł |
| **180x200** | 2 080 zł | 4 139 zł | **5 091 zł** | 2 059 zł |

## 5. Architektura Kontekstowa poza WordPressem
Aby rozwój e-commerce i dokumentacja stały w uporządkowanym miejscu, repozytorium wykracza poza standardowy folder WP. Znajdują się w nim:
- `scripts/`: Zespół skryptów automatyzujących rutynowe podpinanie/aktualizacje treści. (np. `wp_api.py`, `add_wp_pages.py`).
- `docs/`: Przestrzeń dyskowa w formaci Markdown z której `scripts` czerpie content (`docs/pages/*.md`, `docs/products.md`).
- `assets/mocks/`: Wizualizacje projektu, UI, zarysy z Figmy w png.
- `utils/plugins/`: Konieczne paczki pre-instalacyjne (np. dla rest API auth).
- Pliki środowiskowe jak `.env` (ukryte w `.gitignore` wraz z venv) trzymają niezbędne zmienne konfiguracyjne do zintegrowania skryptów pythona ze sklepem.
