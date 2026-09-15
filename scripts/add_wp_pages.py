import os
import requests
import re
import glob

# Wczytanie wspólnych ustawień API z modułu wp_api
from wp_api import get_wp_api_url, get_wp_auth

DOCS_DIR = os.path.join(os.path.dirname(__file__), '..', 'docs', 'pages')

# ─────────────────────────────────────────────────────────────────────────────
# Pozycje w menu głównym (primary).
# Tylko te strony pojawią się w nawigacji – kolejność wg 'order'.
# ─────────────────────────────────────────────────────────────────────────────
PRIMARY_NAV = [
    {"slug": "materac-stilco", "label": "Materac",       "order": 1, "is_product": True},
    {"slug": "strefa-wiedzy",  "label": "Strefa wiedzy", "order": 2},
    {"slug": "about",          "label": "O nas",         "order": 3},
    {"slug": "contact",        "label": "Kontakt",       "order": 4},
]


def parse_markdown_file(filepath):
    """
    Parsuje plik markdown:
    - Tytuł → pierwszy nagłówek H1 (# ...) z treści pliku
    - Slug  → nazwa pliku bez rozszerzenia
    - Metadane opcjonalne w komentarzu HTML <!-- key: value -->
    """
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    slug = os.path.basename(filepath).replace('.md', '')

    # Domyślne metadane
    metadata = {
        "slug":       slug,
        "menu_order": 99,
        "status":     "publish",
    }

    # Wyciągnięcie metadanych z komentarza <!-- ... -->
    clean_content = content
    meta_match = re.search(r'<!--\s*(.*?)\s*-->', content, re.DOTALL)
    if meta_match:
        meta_text = meta_match.group(1)
        clean_content = content.replace(meta_match.group(0), '', 1).strip()
        for line in meta_text.split('\n'):
            line = line.strip()
            if ':' in line:
                key, val = line.split(':', 1)
                key = key.strip()
                val = val.strip().strip('"').strip("'")
                if key == 'menu_order':
                    metadata[key] = int(val)
                else:
                    metadata[key] = val

    # Tytuł z pierwszego nagłówka H1 w treści
    h1_match = re.search(r'^#\s+(.+)$', clean_content, re.MULTILINE)
    if h1_match:
        metadata['title'] = h1_match.group(1).strip()
    elif 'title' not in metadata:
        # Fallback – nazwa pliku skapitalizowana (tylko gdy nie ma H1 ani meta)
        metadata['title'] = slug.replace('-', ' ').replace('_', ' ').capitalize()

    import markdown
    html_content = markdown.markdown(clean_content, extensions=['tables', 'fenced_code'])
    metadata['content'] = html_content
    return metadata


def create_wp_page(page_data):
    """Tworzy lub aktualizuje stronę w WordPress używając REST API."""
    slug = page_data['slug']
    print(f"Sprawdzanie istnienia strony: {page_data['title']} ({slug})...")

    post_data = {
        "title":      page_data['title'],
        "content":    page_data['content'],
        "status":     page_data['status'],
        "slug":       slug,
        "menu_order": page_data['menu_order'],
    }

    # Mapowanie popularnych slugów na odpowiednie szablony motywu
    templates_map = {
        'faq': 'page-faq.php',
        'about': 'page-about.php',
        'contact': 'page-contact.php',
        'privacy': 'page-legal.php',
        'terms': 'page-legal.php',
        'returns': 'page-legal.php'
    }
    
    if slug in templates_map:
        post_data['template'] = templates_map[slug]

    API_WP_URL = get_wp_api_url("wp/v2")
    search_res = requests.get(f"{API_WP_URL}/pages?slug={slug}", auth=get_wp_auth())

    existing_id = None
    if search_res.status_code == 200:
        pages = search_res.json()
        if pages:
            existing_id = pages[0]['id']

    if existing_id:
        print(f"🔄 Strona istnieje (ID: {existing_id}). Aktualizowanie...")
        response = requests.post(
            f"{API_WP_URL}/pages/{existing_id}",
            auth=get_wp_auth(),
            json=post_data
        )
        if response.status_code in [200, 201]:
            print(f"✅ Zaktualizowano pomyślnie. ID strony: {existing_id}")
            return existing_id
        else:
            print(f"❌ Błąd aktualizacji strony: {response.status_code} - {response.text}")
            return None
    else:
        print(f"➕ Strona nie istnieje. Tworzenie nowej strony...")
        response = requests.post(
            f"{API_WP_URL}/pages",
            auth=get_wp_auth(),
            json=post_data
        )
        if response.status_code in [200, 201]:
            page = response.json()
            print(f"✅ Sukces! Utworzono nową stronę. ID: {page['id']}")
            return page['id']
        else:
            print(f"❌ Błąd tworzenia strony: {response.status_code} - {response.text}")
            return None


def _get_page_id_by_slug(slug):
    """Pomocnicza: pobiera ID strony po slug."""
    API_WP_URL = get_wp_api_url("wp/v2")
    res = requests.get(f"{API_WP_URL}/pages?slug={slug}", auth=get_wp_auth())
    if res.status_code == 200 and res.json():
        return res.json()[0]['id']
    return None


def setup_primary_menu(menu_name="Menu Główne"):
    """
    Konfiguruje menu nawigacyjne:
    - Tworzy lub odnajduje menu o podanej nazwie
    - Usuwa wszystkie istniejące pozycje
    - Dodaje tylko pozycje zdefiniowane w PRIMARY_NAV
    - Przypisuje menu do lokalizacji 'primary' motywu
    """
    print(f"\nKonfiguracja menu nawigacyjnego: '{menu_name}'...")

    API_MENUS_URL      = get_wp_api_url("wp/v2/menus")
    API_MENU_ITEMS_URL = get_wp_api_url("wp/v2/menu-items")

    if requests.get(API_MENUS_URL, auth=get_wp_auth()).status_code == 404:
        print("ℹ️  REST API dla Menus niedostępne (brak wtyczki). Pomijam konfigurację menu.")
        return

    # ── 1. Znajdź lub utwórz menu ──────────────────────────────────────────
    menu_id = None
    menus_res = requests.get(API_MENUS_URL, auth=get_wp_auth())
    if menus_res.status_code == 200:
        for m in menus_res.json():
            if m.get('name') == menu_name:
                menu_id = m.get('id')
                print(f"🔄 Istniejące menu znalezione (ID: {menu_id}).")
                break

    if not menu_id:
        create_res = requests.post(
            API_MENUS_URL,
            auth=get_wp_auth(),
            json={"name": menu_name}
        )
        if create_res.status_code in [200, 201]:
            menu_id = create_res.json().get('id')
            print(f"✅ Utworzono menu (ID: {menu_id}).")
        else:
            print(f"❌ Nie udało się utworzyć menu: {create_res.text}")
            return

    # ── 2. Usuń wszystkie istniejące pozycje w tym menu ────────────────────
    existing_items_res = requests.get(
        f"{API_MENU_ITEMS_URL}?menus={menu_id}&per_page=100",
        auth=get_wp_auth()
    )
    if existing_items_res.status_code == 200:
        for item in existing_items_res.json():
            requests.delete(
                f"{API_MENU_ITEMS_URL}/{item['id']}?force=true",
                auth=get_wp_auth()
            )
        print(f"🗑️  Usunięto stare pozycje menu.")

    # ── 3. Dodaj właściwe pozycje nawigacyjne ──────────────────────────────
    for nav in PRIMARY_NAV:
        
        is_product = nav.get('is_product', False)
        object_id = None
        object_type = "page"
        
        if is_product:
            from wp_api import get_wc_api
            wcapi = get_wc_api()
            res = wcapi.get("products", params={"search": "Materac", "per_page": 1})
            if res.status_code == 200:
                data = res.json()
                if data:
                    object_id = data[0]['id']
                    object_type = "product"
                    
            if not object_id:
                print(f"  ⚠️  Produkt '{nav['slug']}' nie znaleziony – pomijam.")
                continue
        else:
            object_id = _get_page_id_by_slug(nav['slug'])
            if not object_id:
                print(f"  ⚠️  Strona '{nav['slug']}' nie znaleziona – pomijam.")
                continue

        item_data = {
            "menus":      menu_id,
            "menu_order": nav['order'],
            "object_id":  object_id,
            "object":     object_type,
            "type":       "post_type",
            "status":     "publish",
            "title":      nav['label'],
        }
        i_res = requests.post(API_MENU_ITEMS_URL, auth=get_wp_auth(), json=item_data)
        if i_res.status_code in [200, 201]:
            print(f"  ✅ Dodano do menu: {nav['label']} → /{nav['slug']}/ (typ: {object_type})")
        else:
            print(f"  ❌ Błąd dodawania '{nav['label']}': {i_res.text}")

    # ── 4. Przypisz menu do slotu 'primary' motywu ─────────────────────────
    assign_res = requests.post(
        f"{API_MENUS_URL}/{menu_id}",
        auth=get_wp_auth(),
        json={"locations": ["primary"]}
    )
    if assign_res.status_code in [200, 201]:
        print(f"✅ Menu przypisane do lokalizacji 'primary'.")
    else:
        # Niektóre wersje WP nie obsługują PATCH locations — fallback informacja
        print(f"⚠️  Nie udało się przypisać lokalizacji przez REST API (kod: {assign_res.status_code}).")
        print(f"   Przypisz ręcznie: WP Admin → Wygląd → Menu → przypisz '{menu_name}' do lokalizacji 'Primary Menu'.")


def main():
    if not os.path.exists(DOCS_DIR):
        print(f"❌ Katalog '{DOCS_DIR}' nie istnieje.")
        return

    print("=== Generator Stron Stilco ===")

    markdown_files = glob.glob(os.path.join(DOCS_DIR, '*.md'))
    if not markdown_files:
        print("❌ Nie znaleziono żadnych plików .md w katalogu docs/pages/.")
        return

    # 1. Tworzenie / aktualizacja stron
    for filepath in markdown_files:
        page_data = parse_markdown_file(filepath)
        create_wp_page(page_data)

    # 2. Konfiguracja menu nawigacyjnego
    setup_primary_menu()

    print("\n✅ Wszystkie zadania zakończone.")


if __name__ == "__main__":
    import wp_api
    import atexit
    atexit.register(wp_api.close_ssh_tunnel)
    wp_api.select_environment()
    main()
