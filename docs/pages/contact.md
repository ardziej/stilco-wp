# Kontakt

> **Global Layout:** Ta strona dziedziczy Header i Footer opisane w pliku `global_layout.md`. Konstrukcja w siatce (grid) na maksymalną szerokość 1280px (jak home.md), bez poczucia pustej strony.

## 1. Hero Banner (Pełna szerokość)
- **Tło:** Subtelne zdjęcie detalu materaca z przygaszonym oświetleniem. Pełna szerokość z szeryfowym nagłówkiem "Porozmawiajmy o dobrym śnie". Szeroki margines.

---

## 2. Podwójna Kolumna: Formularz i Informacje
Lewa i prawa kolumna zajmują 50/50 miejsca na ekranach dekstopowych:

### Kolumna Lewa: Bezpośredni Kontakt (Sticky)
Sekcja unosi się w miejscu przy obniżaniu strony.
- Kafelki kontaktowe z zaokrągleniami, aktywne podświetlenia `Terakotą` po najechaniu kursem.
- **Telefon:** +48 123 456 789 (Czynny pn-pt 8:00 - 16:00)
- **E-mail:** kontakt@stilco.pl
- **Adres:** Stilco Sp. z o.o., ul. Przykładowa 12, 00-001 Miasto.

### Kolumna Prawa: Formularz Kontaktowy (WCAG 2.2)
Biały box z delikatnym cieniowaniem umieszczony na perłowo-szarym lub szałwiowym tle obok.
- Input fields z aktywnymi krawędziami `Terakoty` i wielkimi labelkami na focus ("zaprojektowany jak pływający tekst").
- **Imię**, **Email**, **Firma**, **NIP**, **Opcjonalnie Telefon** i **Szczegóły wiadomości** (duże pole).
- Dla wejść z sekcji B2B formularz powinien jasno komunikować kontekst biznesowy.
- Wielki interaktywny przycisk **Terakota**: "Wyślij Wiadomość".

### Walidacja inline (FigJam #21)
Motyw ładuje `assets/js/contact-form-validation.js` na stronie kontaktu. Pola `input[type=email]` i `input[type=tel]` w formularzu CF7 są sprawdzane po opuszczeniu pola (i dalej w trakcie pisania), komunikat pojawia się pod polem (`.stilco-inline-error`, `aria-live`). Walidacja serwerowa CF7 zostaje bez zmian.

### Checkbox newslettera (FigJam #22) — TODO: wkleić w wp-admin
Formularz CF7 żyje w bazie (Kontakt → Formularze), nie w repo. Przed przyciskiem wysyłki dodać:

```
[acceptance newsletter optional] Chcę otrzymywać newsletter Stilco z poradami o śnie i informacjami o nowościach. [/acceptance]
```

W zakładce „Mail” dopisać `Newsletter: [newsletter]`. Zapis do systemu newslettera (np. MailerLite/Mailchimp) trzeba podpiąć osobno — obecnie brak integracji. Pole telefonu ma zostać opcjonalne: `[tel telefon]`, e-mail wymagany: `[email* email]`.

---

## 3. Zobacz, gdzie jesteśmy (Mapa)
- Pokazujemy interaktywny element - **Mapa Google** osadzona na 100% szerokości boxa na krawędzi "soft edge" (duże zaokrąglenia ekranu).
- Użytkownicy uwielbiają firmy realne. Oznaczenie na mapie naszego biura nadaje 100% zaufania w e-commerce.

---

## 4. Często Zadawane Pytania
Płynny pasek rozciągnięty u dołu ekranu. 
"Nie lubisz czekać na odpowiedź? Sprawdź nasze [FAQ](/faq), w którym odpowiadamy wprost na najczęstsze pytania dotyczące materaca, dostawy i testu 100 nocy."
