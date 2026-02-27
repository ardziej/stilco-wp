import requests
from wp_api import get_wp_api_url, get_wp_auth

API_WP_URL = get_wp_api_url("wp/v2")

def cleanup_duplicate_pages():
    auth = get_wp_auth()
    response = requests.get(f"{API_WP_URL}/pages?per_page=100", auth=auth)
    if response.status_code != 200:
        print("Error fetching pages", response.text)
        return
        
    pages = response.json()
    slugs = {}
    
    for page in pages:
        title = page['title']['rendered']
        if title not in slugs:
            slugs[title] = [page]
        else:
            slugs[title].append(page)
            
    for title, grouped_pages in slugs.items():
        if len(grouped_pages) > 1:
            print(f"Znaleziono duplikaty dla tytułu '{title}': {[p['slug'] for p in grouped_pages]}")
            grouped_pages.sort(key=lambda x: x['id'])
            original = grouped_pages[0]
            duplicates = grouped_pages[1:]
            
            for dup in duplicates:
                print(f"Usuwanie duplikatu ID {dup['id']} (slug: {dup['slug']})")
                del_res = requests.delete(f"{API_WP_URL}/pages/{dup['id']}?force=true", auth=auth)
                if del_res.status_code == 200:
                    print("Usunięto.")
                else:
                    print("Błąd podczas usuwania:", del_res.text)
        else:
            print(f"Brak duplikatów dla: '{title}'")

if __name__ == '__main__':
    cleanup_duplicate_pages()
