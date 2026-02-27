#!/usr/bin/env python3
"""
sync.py – Główny skrypt synchronizacji danych Stilco z WordPress/WooCommerce.

Użycie:
    python sync.py                  # tryb interaktywny (pyta o środowisko i akcję)
    python sync.py --env local      # z góry wybrany env, pyta o akcję
    python sync.py --env dev        # j.w. dla dev
    python sync.py --env local --action products
    python sync.py --env dev   --action pages

Dostępne akcje:
    products  – Dodaj / zaktualizuj produkt WooCommerce
    pages     – Dodaj / zaktualizuj strony WP z katalogu docs/pages/
    reviews   – Dodaj recenzje produktu
    prices    – Zaktualizuj ceny wariantów produktu
"""

import sys
import os
import argparse
import atexit

# Upewniamy się, że katalog scripts/ jest w ścieżce
sys.path.insert(0, os.path.dirname(__file__))

import wp_api


# ─────────────────────────────────────────────
# Mapowanie akcji → moduły
# ─────────────────────────────────────────────

ACTIONS = {
    "products": {
        "label": "Produkty WooCommerce  (add_wp_products.py)",
        "module": "add_wp_products",
        "fn":     "create_woocommerce_product",
    },
    "pages": {
        "label": "Strony WP             (add_wp_pages.py)",
        "module": "add_wp_pages",
        "fn":     "main",
    },
    "reviews": {
        "label": "Recenzje produktu      (add_wp_reviews.py)",
        "module": "add_wp_reviews",
        "fn":     "main",
    },
    "prices": {
        "label": "Aktualizacja cen       (update_wp_prices.py)",
        "module": "update_wp_prices",
        "fn":     "update_product_prices",
    },
}


def parse_args():
    parser = argparse.ArgumentParser(description="Stilco WP Sync")
    parser.add_argument(
        "--env",
        choices=["local", "dev"],
        help="Środowisko docelowe (local lub dev)",
    )
    parser.add_argument(
        "--action",
        choices=list(ACTIONS.keys()),
        help="Akcja do wykonania",
    )
    return parser.parse_args()


def choose_environment(pre_selected=None):
    """Wybiera środowisko – pyta użytkownika jeśli nie podano."""
    global _env_name

    if pre_selected:
        # Ustawiamy środowisko bezpośrednio bez interaktywnego pytania
        wp_api._active_env = wp_api.ENVIRONMENTS[pre_selected]
        env_name = pre_selected
        print(f"✅ Środowisko: {env_name}  ({wp_api.get_wp_url()})\n")
    else:
        env_name = wp_api.select_environment()

    print(f"🌐 URL WordPress: {wp_api.get_wp_url()}\n")
    return env_name


def choose_action(pre_selected=None):
    """Wybiera akcję – pyta użytkownika jeśli nie podano."""
    if pre_selected:
        return pre_selected

    print("═" * 50)
    print("  Co chcesz zsynchronizować?")
    print("═" * 50)
    for i, (key, val) in enumerate(ACTIONS.items(), start=1):
        print(f"  {i} - {val['label']}")
    print("═" * 50)

    keys = list(ACTIONS.keys())
    while True:
        choice = input(f"Twój wybór [1-{len(keys)}]: ").strip()
        if choice.isdigit() and 1 <= int(choice) <= len(keys):
            return keys[int(choice) - 1]
        elif choice in keys:
            return choice
        else:
            print(f"❌ Nieprawidłowy wybór. Wpisz 1-{len(keys)} lub nazwę akcji.")


def run_action(action_key):
    """Importuje i uruchamia wybraną akcję."""
    action = ACTIONS[action_key]
    print(f"\n{'═' * 50}")
    print(f"  ▶ Uruchamianie: {action['label'].strip()}")
    print(f"{'═' * 50}\n")

    import importlib
    module = importlib.import_module(action["module"])
    fn = getattr(module, action["fn"])
    fn()


def main():
    args = parse_args()

    # Rejestrujemy zamknięcie tunelu SSH przy wyjściu
    atexit.register(wp_api.close_ssh_tunnel)

    # 1. Wybór środowiska (ZAWSZE – zgodnie z wymaganiami)
    choose_environment(args.env)

    # 2. Wybór akcji
    action = choose_action(args.action)

    # 3. Potwierdzenie
    env_name = args.env or ("dev" if wp_api._active_env == wp_api.ENVIRONMENTS["dev"] else "local")
    print(f"\n⚡ Akcja: {action}  |  Środowisko: {env_name}  |  URL: {wp_api.get_wp_url()}")
    confirm = input("Kontynuować? [T/n]: ").strip().lower()
    if confirm in ("n", "nie", "no"):
        print("❌ Anulowano.")
        return

    # 4. Wykonanie
    run_action(action)

    print("\n✅ Synchronizacja zakończona.")


if __name__ == "__main__":
    main()
