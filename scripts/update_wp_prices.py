import os
import sys

# Dodajemy folder scripts do ścieżki (jeśli jesteśmy w rootcie projektu)
sys.path.append(os.path.join(os.path.dirname(__file__)))
from add_wp_products import variations_data
from wp_api import get_wc_api

wcapi = get_wc_api()

def update_product_prices():
    print("Szukanie produktu 'Materac Stilco'...")
    
    # Pobierz produkt Materac Stilco
    response = wcapi.get("products", params={"search": "Materac Stilco"})
    
    if response.status_code != 200:
        print(f"Błąd pobierania produktów: {response.text}")
        return

    products = response.json()
    if not products:
        print("Nie znaleziono produktu 'Materac Stilco'.")
        return
        
    product_id = products[0]['id']
    print(f"Znaleziono produkt. ID: {product_id}")
    
    # Pobierz warianty
    var_response = wcapi.get(f"products/{product_id}/variations")
    if var_response.status_code != 200:
        print(f"Błąd pobierania wariantów: {var_response.text}")
        return
        
    variations = var_response.json()
    
    # Mapuj oczekiwane ceny dla rozmiarów
    price_map = {v['size']: {"regular_price": v['regular_price'], "sale_price": v['sale_price']} for v in variations_data}
    
    for var in variations:
        # Odczytaj atrybut rozmiaru (pierwszy atrybut)
        size_attr = next((a['option'] for a in var['attributes'] if a['name'] == 'Rozmiar'), None)
        
        if size_attr and size_attr in price_map:
            new_prices = price_map[size_attr]
            print(f"Aktualizacja wariantu ID {var['id']} (Rozmiar: {size_attr})")
            
            update_data = {
                "regular_price": new_prices['regular_price'],
                "sale_price": new_prices['sale_price']
            }
            
            upd_response = wcapi.put(f"products/{product_id}/variations/{var['id']}", update_data)
            
            if upd_response.status_code in [200, 201]:
                print(f" ✅ Zaktualizowano pomyślnie na: {new_prices['sale_price']} zł (przekreślone {new_prices['regular_price']} zł)")
            else:
                print(f" ❌ Błąd aktualizacji: {upd_response.text}")
        else:
            print(f"Pominięto wariant ID {var['id']} - brak dopasowania rozmiaru.")
            
    print("\nGotowe!")

if __name__ == "__main__":
    update_product_prices()
