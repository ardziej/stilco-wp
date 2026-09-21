# Komentarze z pliku Figma „Stilco — sklep”

Źródło: https://www.figma.com/design/d0WzwsfTTDJDUHUfsxpErW/Stilco-%E2%80%94-sklep
Autor wszystkich: **Jakub**. Daty: 2026-09-11 (1 komentarz) i 2026-09-15 (19 komentarzy). Odczytane 2026-09-21, 20 wątków, żaden nierozwiązany, jedna odpowiedź w wątku.

Uwaga: to inny zestaw uwag niż FigJam „Stilco - strona” od Filipa (`docs/figma-comments.md`). Część uwag Jakuba **jest sprzeczna** z tym, co już wdrożono na podstawie uwag Filipa — lista sprzeczności na końcu.

Numeracja poniżej jest chronologiczna. `y` to pozycja komentarza w ramce projektu.

Granice sekcji w ramce „Home — Desktop 1440 — nowy header” (`36:2`), do mapowania `y` na sekcję:

| Sekcja | y |
| --- | --- |
| Header | 0–77 |
| 01 Hero | 0–1000 |
| 02 Dual Comfort | 1000–1776 |
| 03 Warstwy materaca | 1776–2555 |
| 04 Wybierz materac | 2555–2857 |
| 05 Kategorie | 2857–3755 |
| 06 Opinie klientów | 3755–4830 |
| 07 Dla biznesu | 4830–5455 |
| 08 Blog | 5455–6632 |
| 09 FAQ | 6632–7441 |

W ramce „Materac — Desktop 1440” (`55:2`, sekcje liczone od y=77): BuyBox 0–1435, 02 Budowa materaca 1435–2858, 03 Historie klientów 2858–3921, 04 Test 100 nocy 3921–4493.

## Materac — Desktop 1440 (`55:2`)

### J1 — Opcja „Twój rozmiar” (y=912)
> Dodajmy opcję "Twój rozmiar" - logika powinna być taka, że po kliknięciu nie masz opcji koszyka a formularz, header w stylu "Dopasuj materac Stilco do Twoich indywidualnych potrzeb. Wpisz wymiary jakich potrzebujesz, a my wrócimy do Ciebie z indywidualną wyceną". Formularz powinien mieśc imie i nazwisko, telefon, mail a w kontekście materaca dlugosc, szerokowsc i wysokosc - wypelnione juz bazowymi danymi (np 200x160x22) + miejsce na komentarz indywidualny. To powinno spadac na maila osblugowego do Edyty i Daniela.

Nowa funkcjonalność w konfiguratorze: dodatkowy kafelek rozmiaru „Twój rozmiar” przełącza prawą kolumnę z koszyka na formularz zapytania o wycenę. Pola: imię i nazwisko, telefon, e-mail, długość / szerokość / wysokość (prefill 200 / 160 / 22), komentarz. Wysyłka na skrzynkę obsługową.

## Home — Desktop 1440 — nowy header (`36:2`)

### Header

**J3 — Ulubione do usunięcia** (y=26). Zgodne z uwagą Filipa #8, już zrobione.

**J4 — Trzy pozycje w menu** (y=37)
> zostawiamy 3 buttony, Materac, O marce, Kontakt - Blog, FAQ - wrzucamy w sekcji o marce, niżej na głównej mogą zostać sekcje tak jak są + na stopce strony, tak jak jest.

Menu: **Materac, O marce, Kontakt**. Blog i FAQ trafiają do sekcji „O marce”, nie na osobną stronę.

### Hero

**J2 — Układ i treść hero** (y=849)
> Zrobmy minimalnie wezsza hero section. Kolorystycznie jest ladniej niż w tych szarościach, ale jednak jak button jest na srodku to jest lepiej - tutaj go pomijasz i zjezdzasz nizej, w ukladzie centralnym chcesz go kliknąć.
> Treść - nagłówek 1 "Manufaktura dobrego snu"
> nagłówek 2 "Twój dobry sen zaczyna się tutaj"
> Button cta "Zamów materac"

Hero nieco niższy/węższy, treść **wyśrodkowana** (nie w lewej kolumnie), bo przy układzie do lewej CTA jest pomijane. Nagłówek nadrzędny „Manufaktura dobrego snu”, pod nim „Twój dobry sen zaczyna się tutaj”, CTA „Zamów materac”.

**J5 — Zdjęcie hero OK** (y=593)
> Zdjęcie - to mi się mega podoba, ale pytanie jak ułożysz box z tekstem i to CTA.

**J11 — Przywrócić belkę zaufania** (y=1009)
> Tu brakuje tej belki, ktora byla w poprzednim widoku z tymi emotkami - Darmowy test 100 nocy, 5 lat gwarancji, Marka Polska,

Pasek pod hero wraca, z trzema pozycjami: **Darmowy test 100 nocy, 5 lat gwarancji, Marka Polska**. (Wcześniej były cztery, z „Darmowa dostawa”.)

### Dual Comfort

**J12 — Główny przekaz** (y=1155)
> Jeden materac, wiele możliwości - to powinien być core komunikat - musimy podkreślać, ze jest JEDEN i to wystarczy. Nie musisz wybierać.

**J6 — Zdjęcie z warstwami** (y=1433)
> Tutaj dałbym zdjęcie, gdzie widać materac od boku i masz warstwy widoczne dobrze

**J7 — odpowiedź w wątku**
> Raczej bez dłoni, poprostu z metką

Czyli zdjęcie materaca od boku, bez dłoni w kadrze, z widoczną metką. Obecnie wstawione `dual-comfort-side.jpg` (zdjęcie 130) — bez dłoni, z metką. Zgodne.

### Warstwy materaca

**J8 — Render pokrowca** (y=2094)
> Pytanie, czy tu AI nie moze zrobic naszego pokrowca i pokazać tego rozwarstwienia co jest na lewym zdjeciu ale na naszym materacu

**J9 — Zdjęcie modelki** (y=2310)
> Tu zdjecie modelki lezacej/spiacej

**J10 — Zdjęcie z zamkiem** (y=2303)
> tu foto z zamkiem bo copy mowi o latwym sciaganiu

### Wybierz materac (ciemna sekcja)

**J13 — Kolorystyka** (y=2582)
> kolorystycznie minimalnie jaśniej, ale fajnie, że jest takie mocne odcięcie

**J14 — Nagłówek** (y=2654)
> Nie wybierz, tylko tu dajmy "Wiele potrzeb. Jeden materac."

**J15 — CTA** (y=2698) → „Zamów teraz”

**J16 — Lead** (y=2723)
> Wybierz rozmiar jakiego potrzebujesz oraz dostosuj formę i datę dostawy. To wystarczy, by cieszyć się dobrym snem.

### Kategorie

**J17 — Ta sekcja out** (y=2991)

Sekcja kafelków kategorii do usunięcia w całości.

### Opinie klientów

**J18 — Landing „/opinie” z formularzem** (y=3962)
> To spoko, już robimy pod to mailing, stwórz prosze prosty LP na stilco.pl/opinie tak, zeby był tam formularz do opinii - 5 gwiazdkową skale oceny, imię, nazwisko, mail, telefon, miejsce na opinię tekstową, miejsce na dodanie fot/video, zgody - to bedzie spadało na skrzynkę a my sobie będziemy publikować

**J19 — Podstrona ze wszystkimi opiniami** (y=4693)
> To powinno prowadzic do podstrony gdzie będzie zbiór wszystkich opinii, mozemy też dać to w stopce strony "Opinie"

Przycisk pod opiniami prowadzi do podstrony ze zbiorem wszystkich opinii, nie do zakładki na stronie produktu (dziś linkuje do `/produkt/materac-stilco/#reviews`).

### Blog

**J20 — Przycisk pod sekcją** (y=5728)
> ten button jest z czapy - niech bedzie pod sekcja, wysrodkowany i w kolorystyce strony - jak button o sprawdzaniu opinii naszych Klientów

Przycisk w sekcji Blog ma trafić pod sekcję, wyśrodkowany, w kolorystyce strony — tak jak przycisk przy opiniach.

## Sprzeczności z wdrożonymi uwagami Filipa

Wdrożone już zmiany (commit `a8ed8d6`, `5f334f2`) opierały się na FigJam Filipa. Jakub prosi inaczej:

| Temat | Filip (FigJam) — wdrożone | Jakub (ten plik) |
| --- | --- | --- |
| Menu | Materac, **Strefa wiedzy**, O nas, Kontakt (4 pozycje) | Materac, **O marce**, Kontakt (3 pozycje) |
| Blog + FAQ | osobna strona „Strefa wiedzy” (`page-strefa-wiedzy.php`) | sekcja wewnątrz strony „O marce” |
| CTA w headerze | „Skonfiguruj”, wyśrodkowane | „Zamów materac” |
| Hero — układ | treść w lewej kolumnie | treść **wyśrodkowana**, hero nieco niższy |
| Hero — nagłówki | H1 „Twój dobry sen zaczyna się tutaj.” | nadrzędny „Manufaktura dobrego snu” + „Twój dobry sen zaczyna się tutaj” |
| Belka zaufania pod hero | usunięta razem ze starym hero | **przywrócić**: Darmowy test 100 nocy, 5 lat gwarancji, Marka Polska |
| Sekcja Kategorie | przebudowana na 2 kafelki | **usunąć całą sekcję** |

Zgodne u obu: ulubione out, 5 lat gwarancji, zdjęcie materaca od boku w Dual Comfort, mocniejszy przekaz „jeden materac”.

## Nowe rzeczy poza dotychczasowym zakresem

- **J1** — „Twój rozmiar” w konfiguratorze z formularzem wyceny zamiast koszyka.
- **J18** — nowy landing `/opinie` z formularzem opinii (ocena 5-gwiazdkowa, dane kontaktowe, treść, foto/wideo, zgody), wysyłka na skrzynkę.
- **J19** — podstrona ze zbiorem wszystkich opinii + link „Opinie” w stopce (link w stopce już istnieje, prowadzi do `/opinie`).
- **J8, J9, J10** — nowe zdjęcia do sekcji warstw: render pokrowca z rozwarstwieniem, modelka śpiąca, detal zamka.

## Jak pobrać ponownie

```sh
FIGMA_FILE_KEY=d0WzwsfTTDJDUHUfsxpErW python scripts/figma_comments.py
```

Wymaga tokena z zakresem `file_comments:read` w `.env` jako `FIGMA_TOKEN`. Bez tokena komentarze można odczytać z zalogowanej sesji przeglądarki przez `/api/file/<fileKey>/comments`.
