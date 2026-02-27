import os
import requests
import json

from wp_api import get_wp_api_url, get_wp_auth, get_wc_api

product_description = """
<p>Materac piankowy Stilco oferowany jest standardowo w sześciu rozmiarach: 80x200, 90x200, 120x200, 140x200, 160x200 oraz 180x200. Grubość to 22 cm. Istnieje możliwość zamówienia materaca w niestandardowym rozmiarze.</p>
<p>Materac Stilco wykonany jest z połączenia dwóch wysokogatunkowych pianek. Rdzeń stanowi pianka typu HR tzw wysokoelastyczna o gęstości 40 kg/m3. Górna warstwa to pianka typu Visco o właściwościach termoelastycznych i gęstości 45 kg/m3. Pianka ta dopasowuje się do ciała.</p>
<p>Do klejenia używany jest klej wodny.</p>
<p>Obszycie materaca Stilco to włoskie tkaniny połączone zamkiem rozdzielczym co ułatwia pranie pokrowca.</p>
"""

variations_data = [
    {"size": "80x200", "regular_price": "2590", "sale_price": ""},
    {"size": "90x200", "regular_price": "2890", "sale_price": ""},
    {"size": "120x200", "regular_price": "3290", "sale_price": ""},
    {"size": "140x200", "regular_price": "3990", "sale_price": ""},
    {"size": "160x200", "regular_price": "4490", "sale_price": ""},
    {"size": "180x200", "regular_price": "4990", "sale_price": ""}
]

def create_woocommerce_product():
    """Tworzy produkt z wariantami za pomocą WooCommerce REST API"""
    wcapi = get_wc_api()
    print(f"Tworzenie produktu w WooCommerce pod adresem: {get_wp_api_url('wc/v3')}/products...")
    
    # Upload and assign images
    from sync_images import get_existing_media, filename_to_slug, upload_via_ssh, upload_via_rest, _is_dev_env

    image_ids = []
    images_dir = os.path.join(os.path.dirname(__file__), '..', 'docs', 'mattresses')
    if os.path.exists(images_dir):
        existing_media = get_existing_media()
        use_ssh = _is_dev_env()
        for filename in sorted(os.listdir(images_dir)):
            if not filename.startswith('.') and not filename.endswith('.md'):
                filepath = os.path.join(images_dir, filename)
                slug = filename_to_slug(filename)
                if slug in existing_media:
                    image_ids.append({"id": existing_media[slug]})
                else:
                    if use_ssh:
                        media_id = upload_via_ssh(filepath, existing_media)
                    else:
                        media_id = upload_via_rest(filepath, existing_media)
                    if media_id:
                        image_ids.append({"id": media_id})
    

    # 1. Główny produkt (variable)
    product_data = {
        "name": "Materac Stilco",
        "type": "variable",
        "description": product_description,
        "short_description": "Wysokogatunkowy materac z pianki HR i Visco. Dostępny w 6 rozmiarach, grubość 22 cm.",
        "images": image_ids,
        "attributes": [
            {
                "name": "Rozmiar",
                "position": 0,
                "visible": True,
                "variation": True,
                "options": [v["size"] for v in variations_data]
            }
        ]
    }
    
    response = wcapi.post("products", product_data)
    
    if response.status_code in [200, 201]:
        product = response.json()
        product_id = product['id']
        print(f"✅ Utworzono główny produkt. ID: {product_id}")
        
        # 2. Tworzenie wariantów (rozmiar i cena)
        for var in variations_data:
            variation_data = {
                "regular_price": var["regular_price"],
                "sale_price": var["sale_price"],
                "attributes": [
                    {
                        "name": "Rozmiar",
                        "option": var["size"]
                    }
                ]
            }
            
            var_response = wcapi.post(f"products/{product_id}/variations", variation_data)
            
            if var_response.status_code in [200, 201]:
                print(f"  ✅ Dodano wariant {var['size']} (Cena: {var['regular_price']} zł)")
            else:
                print(f"  ❌ Błąd dodawania wariantu {var['size']}: {var_response.text}")
                
        print("\nZakończono pomyślnie!")
    else:
        print(f"❌ Błąd tworzenia produktu: {response.text}")

def create_standard_wp_post():
    """Fallback - tworzy standardowy wpis w WP (bez WooCommerce)"""
    print(f"Tworzenie wpisu w natywnym WP REST API: {get_wp_api_url('wp/v2')}/posts...")
    
    content = product_description + "\n<h2>Cennik</h2><ul>"
    for var in variations_data:
        content += f"<li>{var['size']}: {var['regular_price']} zł brutto</li>"
    content += "</ul>"
    
    post_data = {
        "title": "Materac Stilco",
        "content": content,
        "status": "publish"
    }
    
    response = requests.post(
        f"{get_wp_api_url('wp/v2')}/posts",
        auth=get_wp_auth(),
        json=post_data
    )
    
    if response.status_code in [200, 201]:
        print(f"✅ Utworzono wpis w natywnym WP. ID: {response.json()['id']}")
    else:
        print(f"❌ Błąd tworzenia wpisu: {response.text}")

if __name__ == "__main__":
    import wp_api
    import atexit
    atexit.register(wp_api.close_ssh_tunnel)
    wp_api.select_environment()

    print("Wybierz tryb instalacji:")
    print("1 - WooCommerce REST API (Rekomendowane - tworzy produkt variable)")
    print("2 - Natywny WordPress REST API (Tworzy standardowy wpis z HTML)")

    try:
        choice = input("Twój wybór (1/2) [1]: ").strip()
        if choice == "2":
            create_standard_wp_post()
        else:
            create_woocommerce_product()
    except EOFError:
        # W przypadku uruchomienia bez tty
        create_woocommerce_product()
