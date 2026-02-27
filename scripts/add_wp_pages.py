import os
import requests
import re
import glob

# Wczytanie wspólnych ustawień API z modułu wp_api
from wp_api import get_wp_api_url, get_wp_auth

API_WP_URL = get_wp_api_url("wp/v2")

# Wtyczki często udostępniają endpoint `menus` w `/wp-json/wp/v2/menus` lub własnym namespacie.
API_MENUS_URL = get_wp_api_url("wp/v2/menus")
API_MENU_ITEMS_URL = get_wp_api_url("wp/v2/menu-items")

DOCS_DIR = os.path.join(os.path.dirname(__file__), '..', 'docs', 'pages')

def parse_markdown_file(filepath):
    """Parsuje plik markdown, wydobywając metadane z komentarza HTML na początku pliku i resztę jako treść HTML"""
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Wyciąganie metadanych z komentarza <!-- ... -->
    meta_match = re.search(r'<!--\s*(.*?)\s*-->', content, re.DOTALL)
    
    metadata = {
        "title": os.path.basename(filepath).replace('.md', '').capitalize(),
        "slug": os.path.basename(filepath).replace('.md', ''),
        "menu_order": 1,
        "status": "publish"
    }
    
    # Usuwamy metadane by zostawić sam content
    clean_content = content
    
    if meta_match:
        meta_text = meta_match.group(1)
        clean_content = content.replace(meta_match.group(0), '', 1).strip()
        
        # Proste parsowanie klucz: wartość
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
                    
    import markdown
    html_content = markdown.markdown(clean_content, extensions=['tables', 'fenced_code'])
    
    metadata['content'] = html_content
    return metadata

def create_wp_page(page_data):
    """Tworzy lub aktualizuje stronę w WordPress używając REST API"""
    slug = page_data['slug']
    print(f"Sprawdzanie istnienia strony: {page_data['title']} ({slug})...")
    
    post_data = {
        "title": page_data['title'],
        "content": page_data['content'],
        "status": page_data['status'],
        "slug": slug,
        "menu_order": page_data['menu_order']
    }
    
    # Najpierw sprawdzamy po slug, czy strona już istnieje
    search_res = requests.get(f"{API_WP_URL}/pages?slug={slug}", auth=get_wp_auth())
    
    existing_id = None
    if search_res.status_code == 200:
        pages = search_res.json()
        if len(pages) > 0:
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

def setup_menu(page_ids, menu_name="Menu Główne Stilco"):
    """
    Tworzy menu i dodaje do niego strony.
    Uwaga: Wymaga wtyczki wspierającej /wp/v2/menus i menu-items w REST API
    Jeśli wtyczka nie jest dostępna, wyświetli komunikat informacyjny.
    """
    print(f"\nKonfiguracja Menu: '{menu_name}'...")
    
    # Próba utworzenia samego Menu
    menu_data = {
        "name": menu_name,
        "description": "Wygenerowane automatycznie",
    }
    
    menu_res = requests.post(
        API_MENUS_URL,
        auth=get_wp_auth(),
        json=menu_data
    )
    
    if menu_res.status_code == 404:
        print("ℹ️ REST API dla Menus nie zostało znalezione.")
        print("💡 Aby zautomatyzować dodawanie menu, zainstaluj wtyczkę udostępniającą endpointy menu lub stwórz menu ręcznie w kokpicie WP: Wygląd -> Menu, i dodaj utworzone przed chwilą strony.")
        return
        
    menu_id = None
    if menu_res.status_code in [200, 201]:
        menu_id = menu_res.json().get('id', menu_res.json().get('term_id'))
        print(f"✅ Utworzono Menu, ID: {menu_id}")
    elif menu_res.status_code == 400 and "menu_exists" in menu_res.text:
        # Prawdopodobnie "term_id" zostanie zwrócone w dodatkach błędu, albo trzeba wyszukać.
        search_res = requests.get(API_MENUS_URL, auth=get_wp_auth())
        if search_res.status_code == 200:
            for menu in search_res.json():
                if menu.get('name') == menu_name:
                    menu_id = menu.get('id', menu.get('term_id'))
                    break
        if menu_id:
            print(f"🔄 Menu już istnieje, używam istniejącego ID: {menu_id}")
            
    if menu_id:
        # Dodawanie stron do menu
        # Sortowanie według menu_order (wykorzystujemy dane zebrane wcześniej)
        sorted_pages = sorted(page_ids, key=lambda x: x['order'])
        
        for p in sorted_pages:
            item_data = {
                "menus": menu_id,
                "menu_order": p['order'],
                "object_id": p['id'],
                "object": "page",
                "type": "post_type",
                "status": "publish",
                "title": p['title']
            }
            
            i_res = requests.post(
                API_MENU_ITEMS_URL,
                auth=get_wp_auth(),
                json=item_data
            )
            
            if i_res.status_code in [200, 201]:
                print(f"  ✅ Dodano do menu: {p['title']}")
            else:
                print(f"  ❌ Błąd dodawania do menu: {i_res.text}")
                
    else:
        print(f"❌ Nie udało się utworzyć menu: {menu_res.text}")

def main():
    if not os.path.exists(DOCS_DIR):
        print(f"❌ Katalog '{DOCS_DIR}' nie istnieje. Upewnij się, że uruchamiasz skrypt w odpowiednim miejscu.")
        return

    print("=== Generator Stron Stilco ===")
    
    markdown_files = glob.glob(os.path.join(DOCS_DIR, '*.md'))
    if not markdown_files:
        print("❌ Nie znaleziono żadnych plików .md w katalogu docs/pages/.")
        return
        
    created_pages = []
    
    # 1. Tworzenie stron z MD
    for filepath in markdown_files:
        page_data = parse_markdown_file(filepath)
        page_id = create_wp_page(page_data)
        
        if page_id:
            created_pages.append({
                "id": page_id,
                "title": page_data['title'],
                "order": page_data.get('menu_order', 99)
            })

    # 2. Tworzenie Menu (Opcjonalne, w zależności od dostępności endpointu w WP API)
    if created_pages:
        setup_menu(created_pages)

    print("\n✅ Wszystkie zadania zakończone.")

if __name__ == "__main__":
    main()
