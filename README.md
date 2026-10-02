# Stilco E-commerce

Ten projekt to platforma e-commerce klasy premium zbudowana na silniku WordPress i WooCommerce. Motyw (`stilco-theme`) korzysta z nowoczesnego stacku technologicznego bez ociężałych page-builderów – oparty jest na Vite, TailwindCSS oraz natywnym JavaScript (Vanilla JS).

## 🚀 Jak uruchomić projekt lokalnie

Do uruchomienia projektu w środowisku deweloperskim wymagane jest zainstalowanie [Docker](https://www.docker.com/) i [Docker Compose](https://docs.docker.com/compose/) oraz [Node.js](https://nodejs.org/) (minimum wersja 18+).

### 1. Uruchomienie środowiska WordPress + MySQL
Sklonuj to repozytorium na swój dysk, otwórz terminal w głównym katalogu projektu (tam, gdzie znajduje się plik `docker-compose.yml`) i wpisz:

```bash
docker compose up -d
```
Spowoduje to pobranie, zbudowanie i uruchomienie kontenerów z MySQL oraz WordPressem. Twój lokalny WordPress będzie osiągalny pod adresem:
👉 **[http://localhost:8080](http://localhost:8080)**

### 2. Instalacja zależności motywu i kompilacja assetów
Aby zintegrowany interfejs użytkownika oraz jego style na bazie TailwindCSS miały rację bytu, musisz zainstalować pakiety wewnątrz motywu.

Przejdź do katalogu motywu:
```bash
cd wp-content/themes/stilco-theme
```

Zainstaluj zależności (node_modules):
```bash
npm install
```

Wystartuj serwer developerski Vite, wpisując:
```bash
npm run dev
```
W tym trybie każda modyfikacja w plikach `.css`, `.js` lub `.php` natychmiastowo zaktualizuje się dzięki zaimplementowanemu na zapleczu Hot Module Replacement.

Jeżeli chcesz wygenerować i zaszyć finalną, zminifikowaną statyczną wersję (np. na produkcję), wpisz:
```bash
npm run build
```

---

## 📂 Struktura Projektu i Skrypty

Projekt korzysta z niestandardowej struktury katalogów by oddzielić motyw od skryptów automatyzujących i dokumentacji.

- `wp-content/themes/stilco-theme` - Rdzenny motyw sklepu.
- `scripts/` - Autorskie skrypty Python do automatyzacji WordPressa (tworzenie stron, produktów z dokumentacji, ekstrakcja kolorów z mocków).
- `docs/` - Baza dokumentów tekstowych (M.in. `pages/*.md`, `products.md`), z których skrypty generują właściwy kontent w bazie WP na REST API.
- `assets/mocks/` - Przechowywalnia plików graficznych, projektów i makiet w formacie `.png` niezbędnych do budowy front-endu.
- `utils/plugins/` - Paczki z pobranymi zewnętrznymi wtyczkami, m.in. wsparcie `Basic Auth` dla celów REST API.

### Konfiguracja połączenia REST API (.env)
Działanie skryptów wewnątrz widniejącego środowiska wirtualnego w repo, wymaga uprzedniej konfiguracji pliku środowiskowego. Utwórz `.env` na wzór `.env.example`:

```env
WP_URL=http://localhost:8080
WP_USER=stilco
WP_APP_PASSWORD=admin
WC_CONSUMER_KEY=przykladowy_klucz
WC_CONSUMER_SECRET=przykladowy_sekret
```

*Notatka:* WordPress wymaga pluginów autoryzujących typu Basic Auth lub haseł aplikacji, aby to połączenie REST API w pełni zadziałało. Sprawdź `utils/plugins` lub zainstaluj z WP repo.

### Generowanie treści przez Skrypty (Python)
Najpierw upewnij się, że jesteś w odpowiednim środowisku wirtualnym (lub zainstaluj paczki w globalnym) za pomocą `pip install -r scripts/requirements.txt`. Następnie, z poziomu **głównego katalogu projektu** wywołaj wybrany z poniższych punktów:

- Tworzenie postów ze składni MD na platformę (`Tworzy nowe / aktualizuje po slugu`):
  ```bash
  python scripts/add_wp_pages.py
  ```
- Generator powitalnego asortymentu w sklepie:
  ```bash
  python scripts/add_wp_products.py
  ```

---

## ☁️ Deployment

To repozytorium niczego nie deployuje. Forge wycofany. Frontend (SPA) deployuje `ardziej/stilco-spa` na Cloudflare Workers, backend (headless WP + plugin) deployuje `ardziej/stilco-api` na dhosting (konto `verano`). Szczegóły: [docs/deployment.md](docs/deployment.md).
