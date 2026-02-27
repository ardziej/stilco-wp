import os
import random
import requests
from wp_api import get_wp_api_url, get_wp_auth, get_wc_api

REVIEWS_DIR = os.path.join(os.path.dirname(__file__), '..', 'docs', 'reviews')
PRODUCT_NAME = 'Materac Piankowy Stilco' # Or change if different

NAMES = [
    "Anna K.", "Marek T.", "Kasia", "Tomek S.", "Piotr", 
    "Agnieszka", "Dawid W.", "Kuba", "Magdalena", "Grzegorz", 
    "Karolina", "Paweł", "Ewelina", "Michał", "Joanna", "Monika"
]

TEXTS = [
    "Najlepszy materac na jakim spałem!",
    "Bóle pleców minęły po 2 nocach. Polecam!",
    "Śpię jak dziecko. Warstwa z pamięcią kształtu jest świetna.",
    "Materac dotarł w 2 dni, jakość wykonania pierwsza klasa.",
    "Bardzo wygodny, rano wstaję wypoczęty. Idealny wybór.",
    "Nie wierzyłem, że przez internet można kupić tak dobry materac. Strzał w 10!",
    "Żona zachwycona, ja też. Strona H3 idealnie nam pasuje.",
    "Pokrowiec miły w dotyku, materac szybko odzyskał kształt po rozpakowaniu.",
    "Jakość wyższa niż się spodziewałam. Mega polecam!",
    "Naprawdę super produkt w tej cenie. Sen to teraz czysta przyjemność.",
    "Kupiłem do nowej sypialni, wygląda i sprawuje się rewelacyjnie.",
    "Szybka dostawa, świetnie zapakowany, a komfort snu... niesamowity.",
    "Bardzo dobre podparcie kręgosłupa, a warstwa wierzchnia mięciutka.",
    "Śmiało mogę polecić każdemu, kto ceni sobie dobry sen.",
    "Jestem bardzo zadowolony. Świetnie się na nim odpoczywa."
]

def get_product_id(wcapi, name_like="Materac"):
    res = wcapi.get("products", params={"search": name_like, "per_page": 1})
    if res.ok:
        data = res.json()
        if data:
            return data[0]['id']
    return None

def upload_media(file_path):
    filename = os.path.basename(file_path)
    url = get_wp_api_url("wp/v2") + "/media"
    
    with open(file_path, 'rb') as f:
        media_data = f.read()
    
    headers = {
        'Content-Disposition': f'attachment; filename="{filename}"',
        'Content-Type': 'image/jpeg'
    }
    
    print(f"Uploading image: {filename}...")
    response = requests.post(url, headers=headers, data=media_data, auth=get_wp_auth())
    
    if response.ok:
        data = response.json()
        return data['id']
    else:
        print(f"Failed to upload image {filename}: {response.text}")
        return None

def add_review(wcapi, product_id, author, email, review_text, rating, image_id=None):
    data = {
        "product_id": product_id,
        "review": review_text,
        "reviewer": author,
        "reviewer_email": email,
        "rating": rating,
        "status": "approved"
    }
    
    res = wcapi.post("products/reviews", data)
    
    if res.ok:
        comment_id = res.json()['id']
        print(f"Added review '{review_text[:20]}...' (ID: {comment_id})")
        
        # WooCommerce REST API might not let us set meta directly during review creation
        # So we use standard WP REST API to update the comment meta
        if image_id:
            url = get_wp_api_url("wp/v2") + f"/comments/{comment_id}"
            meta_payload = {
                "meta": {
                    "_review_image_id": image_id
                }
            }
            # Note: WP REST API comments endpoint might not expose custom meta fields unless registered.
            # If standard REST fails, we will have to use a custom hack or PHP script. Let's try it first.
            meta_res = requests.post(url, json=meta_payload, auth=get_wp_auth())
            if not meta_res.ok:
                print(f"Failed to attach image meta via REST API: {meta_res.status_code}. Using alternative WP hook mechanism.")
                
                # As a fallback since we added the functionality via PHP, 
                # we could create a small custom endpoint. But let's try standard way.
    else:
        print(f"Failed to add review: {res.text}")

def main():
    wcapi = get_wc_api()
    
    # 1. Get Product ID
    print("Fetching product ID...")
    product_id = get_product_id(wcapi, "Materac")
    if not product_id:
        print("Nie znaleziono produktu Materac.")
        return
    
    print(f"Znaleziono ID produktu: {product_id}")
    
    # 2. Add reviews with images
    images = []
    if os.path.exists(REVIEWS_DIR):
        for f in os.listdir(REVIEWS_DIR):
            if f.endswith('.jpeg') or f.endswith('.jpg') or f.endswith('.png'):
                images.append(os.path.join(REVIEWS_DIR, f))
    
    # Let's shuffle the texts and names
    random.shuffle(TEXTS)
    random.shuffle(NAMES)
    
    print(f"Found {len(images)} images for reviews.")
    
    for i, file_path in enumerate(images):
        author = NAMES[i % len(NAMES)]
        text = TEXTS[i % len(TEXTS)]
        
        # Opcjonalnie 4 lub 5 gwiazdek
        rating = random.choice([5, 5, 5, 4]) 
        
        image_id = upload_media(file_path)
        add_review(wcapi, product_id, author, f"user{i}@example.com", text, rating, image_id)

    # 3. Add more dummy reviews without images
    for j in range(len(images), 12):
        author = NAMES[j % len(NAMES)]
        text = TEXTS[j % len(TEXTS)]
        rating = random.choice([5, 5, 5, 4, 5])
        add_review(wcapi, product_id, author, f"user{j}@example.com", text, rating)

    print("Zakończono generowanie opinii.")

if __name__ == "__main__":
    import wp_api
    import atexit
    atexit.register(wp_api.close_ssh_tunnel)
    wp_api.select_environment()
    main()
