# Komentarze z FigJam „Stilco - strona”

Źródło: https://www.figma.com/board/uQIPjHyVC5lDahjtyoUxiQ/Stilco---strona
Autor wszystkich: Filip Tobis, 2026-08-29. Stan: 22 komentarze, żaden nierozwiązany/rozwiązany (wszystkie otwarte).

Mapowanie node → ekran: `1:6` Główna, `3:26` Konfigurator, `1:9` O nas, `1:3` Blog, `1:5` FAQ, `1:4` Blog wpis, `1:8` Kontakt, `0:1` cała tablica.

## Ogólne (cała tablica)

- **Menu górne**: Materac → strona produktowa (przewagi, struktura, technikalia). Blog + FAQ → jedno: „Strefa wiedzy”. O nas, Kontakt zostają. Te 4 linki do lewej. Główne CTA „Skonfiguruj” na środku, innym kolorem, bold (jak dziś „zamów materac”). Ulubione, koszyk, profil do prawej. Ulubione — raczej out.
- Zdjęcia w sekcjach strony klikalne, z powiększeniem (lightbox).
- Koszyk i checkout: brak widoku, komentarze dojdą po dostępie.

## Główna (`1:6`)

- Zmiana na „5 lat gwarancji”.
- Button prowadzący do sekcji poniżej — czy potrzebny?
- **Kategorie — do przebudowania**: kafelek 1 (materac) OK. Kafelek 2 (akcesoria) out, ew. poduszka z bezpośrednim linkiem — raczej out. Kafelek 3 → podstrona z przewagami materaca i opisem „dlaczego Stilco, a nie np. Ikea”.
- „To jest bardzo spoko, kwestia jak technicznie można to zrobić” (sekcja interaktywna).
- Zmiana tekstu: „Skontaktuj się z nami i poznaj ofertę dla firm”.
- Ulubione — out.
- CTA: może „Dlaczego Stilco?”.
- Zmiana copy na wypracowane wcześniej:
  > „Przez lata pracowaliśmy nad materacem, który spełniałby nasze oczekiwania w kwestii komfortowego odpoczynku, zdrowego ciała i dobranej mieszanki materiałów. Efektem jest materac, który dziś z dumą proponujemy Tobie. Każda z dwóch stron daje inne doświadczenia. Każda dopasowana do tego, czego potrzebujesz dla najlepszej regeneracji.”
- Nowe copy (?): „Sprawdź opinie naszych wyspanych klientów”.

## Konfigurator (`3:26`)

- Elementy budowy materaca klikalne, z opisem — do przemyślenia.
- „5 lat gwarancji”.

## O nas (`1:9`)

- Czy „globalny zasięg” pasuje do narracji „Stilco to nie korporacja”? Buduje wiarygodność, ale wątpliwość.
- Czy to nasze własne pianki? Jeśli tak — napisać, że taki produkt tylko u nas.
- Zmiana tekstu: „Przejdź do konfiguratora”.

## Blog (`1:3`)

- Wyrównać kafelki — każdy taki sam.

## FAQ (`1:5`)

Dodać pytania:
- Gdzie materac jest produkowany?
- Skąd jest wysyłka?
- Czy można gdzieś przetestować materac przed zakupem?
- Ile może być przechowywany materac w formie rolowanej?
- Dlaczego jest tylko jeden rodzaj materaca? (chcemy mieć doskonały produkt)
- Jakie są opcje dostawy?

## Blog wpis (`1:4`)

- 3 kafelki (powiązane wpisy).

## Kontakt (`1:8`)

- Walidacja inline: błąd od razu (za krótki telefon, brak `@` w mailu), nie dopiero przy wysyłce.
- Checkbox zapisu na newsletter w formularzu.

## Jak pobrać ponownie (REST)

Wymaga personal access tokena (Figma → Settings → Security → Personal access tokens, scope `file_comments:read`). Zapisz jako `FIGMA_TOKEN` w `.env`, potem:

```sh
python scripts/figma_comments.py
```
