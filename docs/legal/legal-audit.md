# Legal Audit - sklep internetowy B2C z materacami

> Wersja robocza do weryfikacji przez prawnika. Audyt nie stanowi opinii prawnej. Wnioski bazują na stanie repozytorium, istniejących treściach i oficjalnych źródłach publicznych dostępnych w dniu `2026-04-14`.

## 1. Zakres audytu

Przejrzane materiały i punkty styku:

- `docs/pages/terms.md`
- `docs/pages/privacy.md`
- `docs/pages/returns.md`
- `docs/pages/dostawa.md`
- `docs/pages/faq.md`
- `docs/pages/contact.md`
- `docs/products.md`
- `wp-content/themes/stilco-theme/create-pages.php`
- `wp-content/themes/stilco-theme/page-legal.php`
- `wp-content/themes/stilco-theme/footer.php`
- `wp-content/themes/stilco-theme/inc/pods-content.php`
- `wp-content/themes/stilco-theme/woocommerce/cart/cart.php`
- `wp-content/themes/stilco-theme/woocommerce/cart/mini-cart.php`
- `wp-content/themes/stilco-theme/woocommerce/checkout/form-checkout.php`
- `wp-content/themes/stilco-theme/template-parts/woocommerce/single-product/hero.php`
- `wp-content/themes/stilco-theme/template-parts/page-contact/contact-columns.php`

## 2. Co już istnieje

W repozytorium są zalążki stron i punktów informacyjnych:

- strony `Regulamin`, `Prywatność`, `Zwroty`, `Dostawa`, `FAQ`, `Kontakt` jako pliki Markdown w `docs/pages`
- importer `create-pages.php`, który importuje `docs/pages/*.md` do WordPressa i dla slugów prawnych przypisuje `page-legal.php`
- szablon `page-legal.php` z neutralnym, czytelnym layoutem dla długich treści
- linki prawne w footerze i sekcji support
- komunikaty zakupowe na karcie produktu, w koszyku i checkoutcie

To jest dobra baza techniczna, ale nie spełnia jeszcze poziomu kompletności wymaganego dla sklepu B2C z dużymi produktami gabarytowymi.

## 3. Główne braki, ryzyka i niespójności

### Krytyczne

1. Obecne dokumenty prawne w `docs/pages` są zbyt krótkie i miejscami zawierają dane przykładowe lub marketingowe, które nie powinny trafić na produkcję.
2. Brakuje jasnego rozdzielenia między ustawowym prawem odstąpienia w 14 dni a dobrowolnym programem `100 dni testowania`.
3. Brakuje jednoznacznej informacji, kto ponosi koszt zwrotu materaca jako produktu gabarytowego i jak taki zwrot ma być organizowany.
4. Sklep komunikuje `sprawdzone opinie` bez widocznego w repo procesu weryfikacji opinii.

### Wysokie

1. [template-parts/woocommerce/single-product/hero.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/woocommerce/single-product/hero.php:81) używa tekstu `4.9/5 (128 sprawdzonych opinii)`, co tworzy ryzyko wprowadzenia w błąd, jeśli brak realnej weryfikacji.
2. [template-parts/woocommerce/single-product/hero.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/woocommerce/single-product/hero.php:116) komunikuje `Darmowa dostawa i zwrot`, choć repo nie zawiera jeszcze kompletnej i spójnej polityki kosztów zwrotu.
3. [woocommerce/cart/cart.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/woocommerce/cart/cart.php:178) sugeruje, że klient `może zwrócić materac, jeśli nie spełni oczekiwań`, bez rozróżnienia 14 dni ustawowych od 100 dni programu i bez warunków.
4. [woocommerce/checkout/form-checkout.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/woocommerce/checkout/form-checkout.php:103) odsyła wyłącznie do regulaminu, bez dodatkowych komunikatów o zwrocie gabarytu, prywatności, 100 dniach i kosztach transportu zwrotnego.
5. [footer.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/footer.php:73) i [inc/pods-content.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/inc/pods-content.php:221) pokazują zbyt wąski zestaw linków prawnych - brak cookies, reklamacji, formularzy, polityki opinii i polityki promocji.

### Średnie

1. `docs/pages/privacy.md` miesza prywatność i cookies, ale nie zawiera pełnej matrycy celów, podstaw prawnych, odbiorców i transferów.
2. Nie ma widocznej w repo kompletnej polityki cookies ani konfiguracji banera zgód.
3. `docs/pages/returns.md` i fallback FAQ sugerują `darmowy zwrot`, co może być sprzeczne z finalnym modelem logistycznym.
4. [template-parts/page-contact/contact-columns.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-contact/contact-columns.php:12) zawiera konkretne osoby, numery telefonów i adresy jako ustawienia domyślne - trzeba potwierdzić zgodę na publikację i poprawność danych.
5. Nie ma odrębnej, jasnej informacji o zasadach promocji i najniższej cenie z 30 dni.

## 4. Wymagane strony i podstrony WordPress

Docelowy minimalny zestaw stron WP:

- `/regulamin` -> `docs/legal/regulamin.md`
- `/polityka-prywatnosci` -> `docs/legal/polityka-prywatnosci.md`
- `/polityka-cookies` -> `docs/legal/polityka-cookies.md`
- `/zwroty-i-odstapienie` -> `docs/legal/zwroty-i-odstapienie.md`
- `/100-dni-testowania` -> `docs/legal/100-dni-testowania.md`
- `/reklamacje` -> `docs/legal/reklamacje.md`
- `/dostawa-i-platnosci` -> `docs/legal/dostawa-i-platnosci.md`
- `/formularz-odstapienia` -> `docs/legal/formularz-odstapienia.md`
- `/formularz-reklamacyjny` -> `docs/legal/formularz-reklamacyjny.md`
- `/faq-prawno-zakupowe` -> `docs/legal/faq-prawno-zakupowe.md`
- `/promocje-i-najnizsza-cena` -> `docs/legal/promocje-i-najnizsza-cena.md`
- `/opinie-klientow` -> `docs/legal/opinie-klientow.md`
- `/kontakt` albo `/kontakt-i-dane-sprzedawcy` -> `docs/legal/kontakt-i-dane-sprzedawcy.md`

Rekomendacja dla istniejącego slugu `/zwroty-i-reklamacje`:

- wariant bezpieczny: zostawić jako stronę pośrednią z dwoma dużymi CTA:
  - `Ustawowe 14 dni odstąpienia`
  - `100 dni testowania`
- wariant lepszy UX po review: rozdzielić na dwie osobne strony i zaktualizować linki w footerze oraz mikrocopy.

## 5. Mikrocopy i miejsca wdrożenia w WooCommerce/WP

### Karta produktu

Miejsce:

- [template-parts/woocommerce/single-product/hero.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/woocommerce/single-product/hero.php:109)

Co dodać:

- komunikat o produkcie gabarytowym
- link do `Dostawa i płatności`
- link do `Zwroty i odstąpienie`
- osobny komunikat: `100 dni testowania to dodatkowy program sklepu`

Rekomendowana treść:

- `Materac jest produktem gabarytowym. Zwrot może wymagać odbioru kurierskiego lub transportu gabarytowego. Szczegóły i koszty znajdziesz w zakładce Zwroty i odstąpienie.`
- `100 dni testowania to dobrowolny program sklepu dla wybranych materacy i nie ogranicza ustawowego prawa odstąpienia w 14 dni.`

### Koszyk

Miejsca:

- [woocommerce/cart/cart.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/woocommerce/cart/cart.php:174)
- [woocommerce/cart/mini-cart.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/woocommerce/cart/mini-cart.php:100)

Co dodać:

- krótki box z kosztami i zasadami zwrotu gabarytu
- link do `100 dni testowania`
- link do `Zwroty i odstąpienie`

Rekomendowana treść:

- `Zwrot materaca jako produktu gabarytowego może wymagać specjalnego transportu. Przed zakupem sprawdź, kto ponosi koszt zwrotu i jak organizowany jest odbiór.`

### Checkout

Miejsce:

- [woocommerce/checkout/form-checkout.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/woocommerce/checkout/form-checkout.php:95)

Co dodać:

- obok checkboxa regulaminu dodać link do polityki prywatności
- dodać komunikat o kosztach zwrotu gabarytu
- doprecyzować `100 dni` jako odrębną politykę

Rekomendowana treść:

- `Kupuję i płacę` powinno być poprzedzone informacją: `Przed złożeniem zamówienia zapoznaj się z Regulaminem, Polityką prywatności, zasadami Zwrotów i odstąpienia oraz polityką 100 dni testowania.`
- `Jeśli zwrot materaca wymaga odpłatnego odbioru, koszt ponosi: [SKLEP/KLIENT].`

### Footer

Miejsca:

- [footer.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/footer.php:73)
- [inc/pods-content.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/inc/pods-content.php:221)

Co dodać:

- `Polityka cookies`
- `Zwroty i odstąpienie`
- `100 dni testowania`
- `Reklamacje`
- `Formularz odstąpienia`
- `Formularz reklamacyjny`
- `Opinie klientów`
- `Promocje i najniższa cena`

### FAQ

Miejsca:

- `docs/pages/faq.md`
- `wp-content/themes/stilco-theme/page-faq.php`
- fallback FAQ w [inc/pods-content.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/inc/pods-content.php:267)

Co dodać:

- osobną kategorię `Zwroty, reklamacje i płatności`
- pytania o 14 dni, 100 dni, koszt zwrotu, niestandardowe rozmiary, reklamacje, opinie i promocje

### Mail potwierdzający zamówienie

W repo nie ma własnego override dla maili WooCommerce.

Rekomendacja:

- dodać do maila potwierdzającego zamówienie sekcję z linkami do:
  - Regulaminu
  - Polityki prywatności
  - Zwrotów i odstąpienia
  - 100 dni testowania
  - Dostawy i płatności

### Strona kontaktu

Miejsce:

- [template-parts/page-contact/contact-columns.php](/Users/ardziej/dev/stilco/stilco/wp-content/themes/stilco-theme/template-parts/page-contact/contact-columns.php:12)

Co dodać:

- wyraźne dane sprzedawcy
- adres do reklamacji
- adres do zwrotów
- e-mail RODO

## 6. Bezpieczna struktura plików

Zaproponowana struktura:

- `docs/legal/regulamin.md`
- `docs/legal/polityka-prywatnosci.md`
- `docs/legal/polityka-cookies.md`
- `docs/legal/zwroty-i-odstapienie.md`
- `docs/legal/100-dni-testowania.md`
- `docs/legal/reklamacje.md`
- `docs/legal/dostawa-i-platnosci.md`
- `docs/legal/formularz-odstapienia.md`
- `docs/legal/formularz-reklamacyjny.md`
- `docs/legal/faq-prawno-zakupowe.md`
- `docs/legal/promocje-i-najnizsza-cena.md`
- `docs/legal/opinie-klientow.md`
- `docs/legal/kontakt-i-dane-sprzedawcy.md`
- `docs/legal/legal-audit.md`

## 7. Bezpieczny sposób wdrożenia do WordPressa

Rekomendacja bez ruszania layoutu:

1. Traktować `docs/legal` jako źródło prawdy do review prawnego.
2. Po akceptacji prawnika przenieść zatwierdzone treści do stron WordPress korzystających z `page-legal.php`.
3. Najmniej ryzykowne opcje publikacji:
- ręczne wklejenie zatwierdzonych treści do stron w panelu WP,
- albo rozszerzenie importera `create-pages.php`, aby czytał również `docs/legal`, ale dopiero po review i z mapą slugów.
4. Nie nadpisywać automatycznie obecnych `docs/pages/terms.md`, `privacy.md`, `returns.md` przed akceptacją prawnika, bo są powiązane z istniejącym mechanizmem seedowania.

## 8. Lista brakujących danych do uzupełnienia przez właściciela sklepu

- pełna nazwa firmy
- forma prawna
- adres siedziby
- adres korespondencyjny
- adres zwrotów
- adres reklamacyjny
- NIP, REGON, KRS/CEIDG
- dane kontaktowe obsługi klienta
- adres e-mail RODO
- operator płatności i faktycznie aktywne metody płatności
- przewoźnicy i obsługiwane kraje
- rzeczywisty koszt zwrotu materaca w 14 dniach
- rzeczywisty koszt i model logistyczny programu `100 dni`
- lista produktów objętych programem `100 dni`
- zasady gratisów i promocji przy zwrotach
- dane gwaranta i dokument gwarancyjny
- używane narzędzia cookies, analityki, remarketingu, newslettera
- model weryfikacji opinii klientów

## 9. Lista pytań do prawnika

- Czy finalny model komunikowania kosztu zwrotu materaca jako gabarytu jest wystarczająco precyzyjny?
- Jak najlepiej opisać odpowiedzialność konsumenta za zmniejszenie wartości materaca po rozpakowaniu i testowaniu?
- Czy i jak sformułować wyłączenie dla materacy w niestandardowych rozmiarach?
- Czy w danym modelu biznesowym można utrzymać warunek higieniczny dla programu `100 dni`, a nie mieszać go z ustawowym 14-dniowym odstąpieniem?
- Jak sformułować zasady rozliczenia gratisów, pakietów i rabatów przy zwrotach?
- Czy planowany baner cookies i zakres narzędzi marketingowych spełnia wymagania zgody?
- Czy opis opinii klientów odpowiada faktycznemu procesowi i nie narusza obowiązków informacyjnych?
- Czy obecny model reklamacji i odpowiedzi w 14 dni jest prawidłowo opisany dla wszystkich żądań konsumenta?
- Czy status platformy ODR powinien być jeszcze komunikowany w tej wersji regulaminu `[DO WERYFIKACJI PRAWNEJ]`?

## 10. Lista ryzyk prawnych

- Ryzyko zarzutu wprowadzania w błąd przez claim `sprawdzone opinie`.
- Ryzyko zarzutu wprowadzania w błąd przez claim `darmowy zwrot`, jeśli koszt lub warunki są inne.
- Ryzyko obciążenia sklepu kosztem zwrotu, jeśli klient nie został poinformowany o nim przed zakupem.
- Ryzyko niezgodności z Omnibus, jeśli ceny promocyjne nie pokazują najniższej ceny z 30 dni.
- Ryzyko nieprawidłowych zgód cookies/remarketingu.
- Ryzyko publikacji niepełnych lub fikcyjnych danych sprzedawcy.
- Ryzyko zbyt szerokiego lub nieprawidłowego wyłączenia prawa odstąpienia dla materacy.

## 11. Checklista wdrożenia w WordPress/WooCommerce

- Utworzyć wszystkie brakujące strony prawne z docelowymi slugami.
- Zaktualizować linki stopki i menu support/legal.
- Uzupełnić checkboxy i komunikaty w checkoutcie.
- Dodać mikrocopy o gabarycie, kosztach zwrotu i 100 dniach na karcie produktu.
- Dodać linki do polityk w koszyku, checkoutcie i mailu potwierdzającym zamówienie.
- Wdrożyć lub potwierdzić baner cookies i panel zgód.
- Potwierdzić i opisać model weryfikacji opinii.
- Dodać mechanizm najniższej ceny z 30 dni w miejscach z promocjami.
- Zweryfikować komunikaty o gwarancji.

## 12. Checklista QA przed publikacją

- Wszystkie placeholdery `[NAZWA FIRMY]`, `[EMAIL]`, `[OPERATOR PŁATNOŚCI]` zostały zastąpione danymi realnymi.
- Każdy dokument przeszedł review prawne.
- Linki w footerze, checkoutcie i FAQ prowadzą do właściwych stron.
- Karta produktu pokazuje jasną informację o kosztach zwrotu gabarytu.
- Program `100 dni` jest opisany jako dodatkowy i odrębny od `14 dni`.
- Claimy `sprawdzone opinie`, `darmowy zwrot`, `10 lat gwarancji`, `15 lat gwarancji` mają pokrycie w danych i dokumentach.
- Polityka cookies odpowiada faktycznej konfiguracji sklepu.
- Promocje pokazują najniższą cenę z 30 dni.
- Formularze odstąpienia i reklamacyjny są dostępne z poziomu strony oraz maili.

## 13. Utworzone pliki

- `docs/plans/2026-04-14-legal-content-pack.md`
- `docs/legal/regulamin.md`
- `docs/legal/polityka-prywatnosci.md`
- `docs/legal/polityka-cookies.md`
- `docs/legal/zwroty-i-odstapienie.md`
- `docs/legal/100-dni-testowania.md`
- `docs/legal/reklamacje.md`
- `docs/legal/dostawa-i-platnosci.md`
- `docs/legal/formularz-odstapienia.md`
- `docs/legal/formularz-reklamacyjny.md`
- `docs/legal/faq-prawno-zakupowe.md`
- `docs/legal/promocje-i-najnizsza-cena.md`
- `docs/legal/opinie-klientow.md`
- `docs/legal/kontakt-i-dane-sprzedawcy.md`
- `docs/legal/legal-audit.md`

## 14. Źródła publiczne wykorzystane do założeń roboczych

- UOKiK - forma odstąpienia od umowy: [prawakonsumenta.uokik.gov.pl/prawo-odstapienia-od-umowy/forma/](https://prawakonsumenta.uokik.gov.pl/prawo-odstapienia-od-umowy/forma/)
- UOKiK - informacje o obniżkach cen: [prawakonsumenta.uokik.gov.pl/prawo-do-informacji/informacje-o-obnizkach-cen/](https://prawakonsumenta.uokik.gov.pl/prawo-do-informacji/informacje-o-obnizkach-cen/)
- UOKiK - fałszywe opinie: [uokik.gov.pl/falszywe-opinie-stop](https://uokik.gov.pl/falszywe-opinie-stop)
- UODO / EROD o ważnej zgodzie: [uodo.gov.pl/pl/138/3070](https://uodo.gov.pl/pl/138/3070)
- ISAP - ustawa z 30 maja 2014 r. o prawach konsumenta: [isap.sejm.gov.pl/.../D20232759L.pdf](https://isap.sejm.gov.pl/isap.nsf/download.xsp/WDU20230002759/T/D20232759L.pdf)
