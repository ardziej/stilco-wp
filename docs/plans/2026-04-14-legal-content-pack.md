# Legal Content Pack Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Przygotować roboczy pakiet dokumentów prawnych i informacyjnych dla sklepu B2C WooCommerce sprzedającego materace online w Polsce.

**Architecture:** Źródłem prawdy są pliki Markdown w `docs/legal`, a raport wdrożeniowy wskazuje, jak bezpiecznie przepisać lub zaimportować te treści do WordPressa bez przebudowy layoutu. Zakres tej iteracji obejmuje audyt, treści, mikrocopy i checklisty; bez agresywnych zmian w kodzie motywu.

**Tech Stack:** Markdown, WordPress, WooCommerce, istniejący importer `create-pages.php`, szablon `page-legal.php`

---

### Task 1: Audyt istniejących treści i punktów styku

**Files:**
- Review: `docs/pages/terms.md`
- Review: `docs/pages/privacy.md`
- Review: `docs/pages/returns.md`
- Review: `docs/pages/dostawa.md`
- Review: `docs/pages/faq.md`
- Review: `docs/pages/contact.md`
- Review: `docs/products.md`
- Review: `wp-content/themes/stilco-theme/footer.php`
- Review: `wp-content/themes/stilco-theme/inc/pods-content.php`
- Review: `wp-content/themes/stilco-theme/woocommerce/cart/cart.php`
- Review: `wp-content/themes/stilco-theme/woocommerce/cart/mini-cart.php`
- Review: `wp-content/themes/stilco-theme/woocommerce/checkout/form-checkout.php`
- Review: `wp-content/themes/stilco-theme/template-parts/woocommerce/single-product/hero.php`

**Step 1: Zebrać istniejące strony i komunikaty prawne**

Run: `rg -n "regulamin|prywat|zwrot|100|opini|cookies|promoc|dostaw|płatn" docs wp-content/themes/stilco-theme -g '!**/node_modules/**'`
Expected: lista istniejących treści, fallbacków i miejsc do poprawy

**Step 2: Zanotować luki i ryzyka**

Expected:
- brak pełnych dokumentów B2C
- brak jasnej informacji o koszcie zwrotu gabarytu
- brak polityki opinii, cookies i promocji
- niezweryfikowane claims w UI

**Step 3: Udokumentować wyniki**

Output: materiał wejściowy do `docs/legal/legal-audit.md`

### Task 2: Zbudować pakiet źródłowych dokumentów w `docs/legal`

**Files:**
- Create: `docs/legal/regulamin.md`
- Create: `docs/legal/polityka-prywatnosci.md`
- Create: `docs/legal/polityka-cookies.md`
- Create: `docs/legal/zwroty-i-odstapienie.md`
- Create: `docs/legal/100-dni-testowania.md`
- Create: `docs/legal/reklamacje.md`
- Create: `docs/legal/dostawa-i-platnosci.md`
- Create: `docs/legal/formularz-odstapienia.md`
- Create: `docs/legal/formularz-reklamacyjny.md`
- Create: `docs/legal/faq-prawno-zakupowe.md`
- Create: `docs/legal/promocje-i-najnizsza-cena.md`
- Create: `docs/legal/opinie-klientow.md`
- Create: `docs/legal/kontakt-i-dane-sprzedawcy.md`

**Step 1: Przyjąć bezpieczne założenia redakcyjne**

Expected:
- wszystkie brakujące dane jako placeholdery
- każda niepewność oznaczona `[DO WERYFIKACJI PRAWNEJ]`
- rozdzielenie `14 dni ustawowych` od `100 dni testowania`

**Step 2: Spisać treści do publikacji**

Expected: komplet dokumentów roboczych gotowych do review prawnika i biznesu

**Step 3: Zachować spójność pojęć**

Expected:
- sprzedawca
- konsument
- produkt gabarytowy
- produkt wykonywany według specyfikacji
- brak zgodności towaru z umową

### Task 3: Przygotować raport wdrożeniowy

**Files:**
- Create: `docs/legal/legal-audit.md`

**Step 1: Opisać stan obecny repo**

Expected: lista istniejących stron, punktów styku i treści ryzykownych

**Step 2: Opisać wymagane strony WP**

Expected: mapa docelowych podstron i slugów

**Step 3: Wskazać miejsca wdrożenia w motywie**

Expected:
- karta produktu
- koszyk
- checkout
- footer
- mail po zamówieniu
- FAQ

**Step 4: Dodać checklisty**

Expected:
- brakujące dane od właściciela
- pytania do prawnika
- QA przed publikacją

### Task 4: Zweryfikować pakiet końcowy

**Files:**
- Review: `docs/legal/*.md`
- Review: `docs/plans/2026-04-14-legal-content-pack.md`

**Step 1: Sprawdzić kompletność nazw i placeholderów**

Run: `rg -n "\\[DO WERYFIKACJI PRAWNEJ\\]|\\[NAZWA FIRMY\\]|\\[EMAIL\\]|\\[OPERATOR PŁATNOŚCI\\]" docs/legal`
Expected: świadoma lista miejsc do uzupełnienia

**Step 2: Sprawdzić spójność nazewnictwa plików**

Run: `find docs/legal -maxdepth 1 -type f | sort`
Expected: pełen zestaw plików z audytem i dokumentami

**Step 3: Podsumować wdrożenie**

Expected: krótki opis co powstało, jakie są ryzyka i co trzeba zrobić dalej przed publikacją
