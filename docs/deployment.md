# Architektura Deploymentu WordPress z Własnym Motywem

Ten dokument opisuje kompletny przepływ pracy (workflow) dla deploymentu platformy WordPress z autorskim motywem, przy wykorzystaniu Cloudflare (DNS), Hetzner Cloud (Serwery), GitHub (Repozytorium kodu) oraz Laravel Forge (Narzędzie provisioningu i deploymentu).

## 1. Architektura i Przepływ

- **Cloudflare (DNS i CDN):** Zarządza domeną, zapewnia ochronę DDoS, cache'owanie i zarządza certyfikatami SSL. Domena (A record) będzie wskazywała na adres IP serwera VPS na Hetznerze.
- **Hetzner Cloud (Produkcja VPS):** Hosting całej aplikacji WordPress (Nginx, PHP, MariaDB/MySQL).
- **Laravel Forge:** Automatyzuje proces konfiguracji VPS'a na Hetznerze i instalację na nim środowiska (LEMP) oraz samego WordPressa. Obsługuje też powiązanie konkretnego katalogu z repozytorium GitHub i uruchamia własny skrypt deploymentu.
- **GitHub Actions (CI/CD):** Narzędzie, które sprawdza jakość kodu (testy, lintery, budowanie zasobów) *zanim* kod trafi na serwer produkcyjny. 

---

## 2. Krok po Kroku: Konfiguracja Środowiska

### Krok 2.1: Hetzner Cloud + Laravel Forge
1. **Wygenerowanie Tokenu API:** Na koncie [Hetzner Cloud](https://console.hetzner.cloud/) stwórz nowy projekt i wygeneruj odpowiedni Token API ze zgodą na odczyt i zapis (Read & Write).
2. **Dodanie Providera w Forge:** Podłącz Hetzner API token do konta Laravel Forge.
3. **Provisioning Serwera:** Użyj Forge, by utworzyć nowy serwer VPS w Hetznerze. Forge samodzielnie połączy się przez API, zainstaluje system operacyjny (np. Ubuntu 24.04), paczki dla PHP, Nginx, MySQL oraz Redis (jeśli wymagane).

### Krok 2.2: Konfiguracja strony (Site) w Laravel Forge
1. Po przygotowaniu serwera utwórz w Forge nową stronę ("Site").
2. Podaj ostateczną nazwę domeny (np. `twojadomena.pl`).
3. Jako **Project Type** wybierz **WordPress** (Forge automatycznie przygotuje pod kątem WP pliki konfiguracyjne i założy czystą instalację w `public_html`).
4. Jako bazę bazy danych podaj dowolną nazwę i hasło (będą zapisane w wp-config.php).

### Krok 2.3: Konfiguracja Cloudflare po stronie DNS
1. W Cloudflare utwórz rekord **A**, by główna domena oraz subdomena `www` (lub odpowiednie domeny) prowadziły na **adres IP** wygenerowany przed chwilą w Hetzner/Laravel Forge.
2. Zależnie od preferecnji w Cloudflare możesz wyłączyć status prxied ("chmurka na szaro" - DNS only) na czas instalacji certyfikatu SSL (Let's Encrypt) od strony Forge. Po udanym zainstalowaniu SSL w Forge włącz "proxy" (pomarańczowa chmurka) i ustaw standard SSL w Cloudflare na **Full (Strict)**.

---

## 3. Repozytorium GitHub i GitHub Actions (CI/CD)

Ponieważ rozwijamy customowy motyw, zwykle naszym repozytorium jest sam katalog motywu (np. `wp-content/themes/nasz-motyw`), rzadziej cały WordPress z katalogiem `wp-content`. 
Załóżmy przypadek najczęstszy: w repozytorium **GitHub** trzymasz **jedynie pliki swojego motywu** (i ewentualnie customowych pluginów).

### Przepływ (Workflow) w GitHub Actions

Stworzymy w repozytorium plik instalujący akcję (np. `.github/workflows/deploy.yml`).

Kiedy pushujemy nową wersję na branch `main`:
1. **GitHub Actions odpala instancję typu runner** i pobiera repozytorium.
2. **Zainstalowanie zależności (PHP/Node.js):** Pobiera paczki (Composer, NPM z np. paczką Vite/Tailwind, itp.).
3. **Uruchomienie Testów i Linterów:**
   - np. PHPUnit dla kodu PHP (np. jeśli mamy specyficzny backend dla WP).
   - np. ESLint dla kodu Javascript.
4. **Trigger Deploymentu w Laravel Forge:**
   Jeżeli kroki w punkcie 2 i 3 **przejdą bez błędów** (Success), GitHub Actions wykonuje polecenie curl wyzwalającej "CURL Webhook Deployment" zawarty w panelu Laravel Forge dla Twojej strony.

Przykładowy zarys pliku:
```yaml
# .github/workflows/deploy.yml
name: Build, Test and Deploy Theme

on:
  push:
    branches:
      - main

jobs:
  test:
    name: Run Tests
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: '20'
          
      - name: Install NPM dependencies
        run: npm ci
        
      - name: Run JS Linters / Tests
        run: npm run lint
        
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          
      - name: Install Composer dependencies
        run: composer install --prefer-dist --no-progress
        
      # Tutaj możesz dodać testy np:
      # - name: Run PHPUnit
      #   run: vendor/bin/phpunit
        
  deploy:
    name: Deploy to Server via Forge
    needs: test # Zależy od przejścia joba "test"
    runs-on: ubuntu-latest
    steps:
      - name: Trigger Forge Deployment
        run: |
          curl -X POST "${{ secrets.FORGE_DEPLOY_WEBHOOK }}"
```

> **UWAGA:** W ustawieniach Repozytorium w GitHub (Settings > Secrets and variables > Actions), dodajesz zmienną `FORGE_DEPLOY_WEBHOOK`, która zawiera pełen adres URL Triggera Deploymentu z Laravel Forge.

---

## 4. Konfiguracja Skryptu Deploymentu w Laravel Forge

Gdy Forge odbierze powiadomienie z GitHuba, że wszystko przeszło poprawnie na runnerach, na serwerze (Hetzner) wyzwoli się zdefiniowany przeze nas *Deploy Script*.
W Forge, w zakładce "Apps", ustawiasz Repozytorium podając odpowiedni folder projektu (jeśli montujemy sam motyw, ścieżkę do root wpisujemy jako `/public_html/wp-content/themes/nazwa-motywu`). Zaznacz też "Quick Deploy" na wyłączone (bo odpalenie idzie przez webhook po przejściu akcji ci/cd z gita).

Przykładowy Laravel Forge Deploy Script:
```bash
# Zapewnienie, że znajdujemy się w odpowiednim katalogu motywu
cd /home/forge/twojadomena.pl/public_html/wp-content/themes/nasz-motyw
git pull origin $FORGE_SITE_BRANCH

# Pobieranie zależności PHP na produkcję
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Instalacja paczek NPM i wybudowanie minifikowanych wersji CSS / JS
npm install
npm run build

# Opcjonalne: wyczyszczenie pamieci cache obiektu WP
wp cache flush --path=/home/forge/twojadomena.pl/public_html

# Przeładowanie procesu PHP FPM aby zapobiec użyciu starych plików OPcache
echo "" | sudo -S service php8.3-fpm reload
```

## Podsumowanie Całego Cyklu:

1. **Ty (Developer):** Piszesz kod motywu. Robisz commit i push na `main` w GitHub.
2. **GitHub:** Rozpoznaje push'a -> uruchamia plik deploy.yml w Actions.
3. **GitHub Actions (CI/CD):** Instaluje zależności i odpala lintery oraz testy. 
4. **GitHub Actions (Wysyłka sygnału):** Jeżeli są błędy - stop, nikt nic nie wgrywa. Jeżeli jest "Success" - uderza metodą POST w unikalny URL twojego Laravel Forge.
5. **Laravel Forge:** Chwyta informację z webhooka, loguje się poprzez skrypt na VPS (Hetzner).
6. **Hetzner (Produkcja):** Uprzednio ustalony Deploy Script na Forge aktualizuje pliki gita (`git pull`), na nowo buduje paczkę produkcyjną (`npm run build`, `composer install`) i przeładowuje FPM / zrzuca cache WP. Zmiany są live dla całego świata za maskowaniem proxy Cloudflare.
