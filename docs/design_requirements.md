# Wymagania Projektowe (Design Requirements)

## 1. Wytyczne Wizualne (Visual Guidelines)

### Kolorystyka
- **Kolor Główny i Tło (Background & Primary):** Jasne, pastelowe i radosne. Kremowa biel `#FAFAFA` jako absolutna baza, przełamywana ciepłymi beżami (Soft Beige) np. `#F4EFEA` w dużych sekcjach tła, co buduje naturalny, rodzinny klimat.
- **Kolor Dodatkowy (Secondary):** Delikatna szałwiowa zieleń (Pale Sage Green) `#C5D1C5` lub podobne pastelowe ziemiste barwy - symbolizuje zdrowie, relaks i ekologię.
- **Akcent (Accent):** Wyrazista Terakota / Rdzawy Pomarańcz (Terracotta) `#C85A41` - używane punktowo do wezwań do akcji (CTA - Add to Cart) i przycisków.
- **Tekst:** Ciemnoszary / Grafitowy `#212529` lub naturalny, wyblakły brąz do tekstów dla lepszej czytelności (unikamy czystego czarnego!).

### Typografia (Typography)
- **Nagłówki (Headings):** Kroj szeryfowy (np. *Playfair Display* lub *Merriweather*) - dodaje elegancji, ale w jasnych kolorach staje się bardziej przytulny i ciepły.
- **Tekst Główny (Body Text):** Kroj bezszeryfowy, wysoce czytelny (np. *Inter*, *Roboto*, lub *Outfit*) - dla czystości i łatwości czytania opisów.

### Ogólny Klimat (Mood & Vibe)
- **Rodzinny i Autentyczny (Family-First & Lifestyle):** Odejście od abstrakcyjnych renderów i sterylności na rzecz prawdziwych ludzi, dzieci i zwierząt korzystających z materaca w jasnych, słonecznych sypialniach.
- **Czystość i Miękkość (Clean & Soft):** Duża ilość przestrzeni ujemnej (whitespace), budujące odczucie bezpieczeństwa, miękkości i wygody.
- **Premium, ale przystępnie (Premium but Accessible):** Jesteśmy producentem wysokiej jakości materacy, ale mówimy do klienta ludzkim językiem i pokazujemy produkt blisko klienta.
- **Zaufanie (Trust):** Szerokie wykorzystanie certyfikatów i weryfikacji społecznej opartych na faktycznym komforcie i zdrowiu bliskich.

## 2. Elementy Konwersji (Conversion Elements)

- **Przyciski CTA (Call to Action):** Zawsze kontrastujące (Akcent), zaokrąglone krawędzie, pełne komunikaty np. "Dodaj do koszyka" lub "Wybierz rozmiar".
- **Sticky Add to Cart:** Na karcie produktu na mobile i desktop po przescrollowaniu pierwszej sekcji - pasek przypięty na dole, z ceną i szybkim przyciskiem zakupu.
- **Mikrointerakcje:** Subtelne powiększanie zdjęć na hover, animowane strzałki rozwijania FAQ, rozmywanie tła przy pop-upach (glassmorphism).

## 3. Elementy Budujące Zaufanie (Trust Signals)
Muszą być obecne globalnie, szczególne widoczne w koszyku oraz na kartach produktu:
- Bezpieczne Płatności (ikony BLIK, Przelewy24, PayU, Visa, Mastercard).
- "Wyprodukowano w Polsce" (Made in Poland).
- Szybka dostawa (np. ikonka ciężarówki + "Wysyłka w 24h").
- 30 dni na testowanie – mniejsze ryzyko dla klienta.
- Certyfikaty: Oeko-Tex, CertiPUR.

## 4. Dodatkowe Wymagania SEO i Dostępności (Accessibility)

### Wymagania SEO
- Każda strona powinna mieć unikalny `<h1>`.
- Dobra hierarchia nagłówków (H2, H3).
- Optymalizacja ładowania obrazów (WebP, lazy loading).

## 5. Animacje i Doświadczenie Użytkownika (UX & Animations - Soft & Organic)

- **Organiczne Animacje Wejścia (Fade-In & Slide):** Zdjęcia i teksty pojawiają się bardzo płynnie i powoli podczas przewijania (fade-in z delikatnym przesunięciem z dołu). Unikamy agresywnych czy przesadnie "technicznych" animacji, skupiamy się na uspokajającym odczuciu relaksu.
- **Lifestylowe Moduły (Lifestyle Integration):** Wszelkie komponenty i moduły, nawet te czysto informacyjne, staramy się wspierać zdjęciami, na których widać materace jako centralny punkt życia rodziny (dzieci, rodzice, psy), a nie izlowane warstwy pianek w ciemności (z wyjątkiem ewentualnie jednej małej technicznej sekcji gdzie pokazujemy przekrój ekspercki).
- **Karty Cech z Fotografiami (Photo-led Bento Box):** Zamiast abstrakcyjnych ikon renderów komputerowych (np. wykres termoregulacji), w zgrabnym układzie siatki ("Bento Box") prezentujemy jasne, ciepłe fotografie - np. dłoń opierająca się o oddychający pokrowiec czy śpiące spokojnie dziecko dla sekcji "Dla Dzieci i Dorosłych". Koniecznie z dbałością o spójne, zaokrąglenia wszystkich rogów kafelków (`border-radius`), co buduje spójną, organicznie miękką całość.
- **Mikrointerakcje Budujące Ciepło:** Zmiany na najechaniu myszką (hover) przycisków czy kafelków - np. delikatne unoszenie się kafelka i podświetlanie go za sprawą rzucania dużego, lecz bardzo miękkiego i jasnego cienia roztartego po krawędzi. Szklane panele (Glassmorphism) mogą pojawiać się przy nałożeniu białej, silnie doświetlającej warstwy nad zdjęcia, a nie zaciemniającej.
- **Czystość Kodu i Wydajność (Perfekcyjna Płynność):** Interakcje bez zacięć. Do animacji używa się funkcji z łagodnym, przyjaznym wyhamowaniem na końcu krzywej Beziera, by spowolnienie przy kończeniu animacji nadawało im miękkości i leniwości, adekwatnej do zasypiania.
- **Nienaruszalność Typowych Struktur Poszczególnych Z podstron:** Cała nawigacja oraz konkretne poszczególne sekcje zaplanowane wcześniej na docelowych stronach (Home, About, Koszyk, Produkty) pozostają tam gdzie były – zmiana kierunku projektowego objawia się zastosowaniem nowej palety, większymi okrągłościami frontendu i wypełnieniem tych istniejących już bloków nową bazą bardzo jasnych zdjęć i pasteli.

### Wymagania WCAG 2.2 (Poziom AA)
Aby sklep był dostępny dla jak najszerszej grupy odbiorców, design oraz kod muszą spełniać kryteria WCAG 2.2:

**1. Wytyczne Designu (Visual & UI):**
- **Kontrast (Kryterium 1.4.3 & 1.4.11):** Teksty podstawowe muszą posiadać kontrast minimum 4.5:1 względem tła (duże teksty min. 3:1). Akcenty graficzne i przyciski CTA (np. złote/miedziane) na białym/granatowym tle również muszą być czytelne (min. kontrast 3:1 dla elementów niestandardowych).
- **Wygląd Fokusu (Kryterium 2.4.11 & 2.4.12 - Focus Appearance):** Elementy aktywne (przyciski, linki, pola formularzy) muszą mieć wyraźny i kontrastowy obrys (outline) po najechaniu klawiaturą (np. `focus-visible`), o grubości co najmniej 2px.
- **Rozmiar Celu (Kryterium 2.5.8 - Target Size Minimum):** Wszystkie interaktywne elementy (przyciski, linki w menu, ikony koszyka itp.) muszą mieć rozmiar klikalny co najmniej 24x24 piksele CSS (idealnie 44x44 na mobile). Odstępy między małymi celami ułatwiające kliknięcie.
- **Brak ukrytych kontrolek (Kryterium 3.2.6 - Consistent Help):** Elementy pomocy, takie jak kontakt czy polityka zwrotów, muszą znajdować się w tym samym miejscu na każdej podstronie (np. zawsze stały blok w stopce).

**2. Wytyczne Kodowania (Development):**
- **Semantyczny HTML:** Poprawne użycie tagów `<header>`, `<main>`, `<nav>`, `<footer>`, `<section>`, `<article>`. Hierarchia nagłówków (`h1`-`h6`) nie może pomijać poziomów.
- **Atrybuty ARIA i Teksty Alternatywne:** 
  - Główne zdjęcia produktowe, infografiki czy certyfikaty muszą posiadać atrybut `alt` opisujący zdjęcie (np. `alt="Materac Stilco w przekroju ukazujący warstwy pianki HR i Visco"`).
  - Puste atrybuty `alt=""` stosowane tylko dla ikon dekoracyjnych.
  - Wykorzystanie `aria-expanded` dla rozwijanych menu, akordeonów FAQ oraz `aria-label` lub `aria-labelledby` dla przycisków ikonkowych bez tekstu.
- **Obsługa Klawiatury (Kryteria 2.1.1 & 2.1.2):** Cały proces nawigacji i koszyka zakupowego musi być w 100% możliwy do obsłużenia klawiaturą (klawisze `Tab`, `Enter`, `Spacja`, strzałki). Brak tzw. "pułapek klawiaturowych".
- **Redundantne wprowadzanie danych (Kryterium 3.3.7 - Redundant Entry):** Jeśli użytkownik wprowadził już adres dostawy, formularz (np. w checkoutcie / koszyku) poprosi o dane rozliczeniowe poprzez zaoferowanie opcji autouzupełnienia (opcja "skopiuj dane dostawy" lub "ten sam adres").
