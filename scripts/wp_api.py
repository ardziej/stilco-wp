import os
import requests
from dotenv import load_dotenv

# Wczytanie zmiennych z pliku .env
env_path = os.path.join(os.path.dirname(__file__), '..', '.env')
load_dotenv(env_path)

# ─────────────────────────────────────────────
# Konfiguracja środowisk
# ─────────────────────────────────────────────

ENVIRONMENTS = {
    "local": {
        "wp_url":    os.environ.get("LOCAL_WP_URL",    "http://localhost:8080"),
        "wp_user":   os.environ.get("LOCAL_WP_USER",   os.environ.get("WP_USER", "admin")),
        "wp_pass":   os.environ.get("LOCAL_WP_APP_PASSWORD", os.environ.get("WP_APP_PASSWORD", "")),
        "wc_key":    os.environ.get("LOCAL_WC_CONSUMER_KEY",    os.environ.get("WC_CONSUMER_KEY", "")),
        "wc_secret": os.environ.get("LOCAL_WC_CONSUMER_SECRET", os.environ.get("WC_CONSUMER_SECRET", "")),
    },
    "dev": {
        # Bezpośredni adres serwera zdalnego – dostęp przez SSH nie jest potrzebny,
        # bo WordPress jest publicznie dostępny pod tym adresem.
        "wp_url":    os.environ.get("DEV_WP_URL",    "https://stilco.on-forge.com"),
        "wp_user":   os.environ.get("DEV_WP_USER",   os.environ.get("WP_USER", "admin")),
        "wp_pass":   os.environ.get("DEV_WP_APP_PASSWORD", os.environ.get("WP_APP_PASSWORD", "")),
        "wc_key":    os.environ.get("DEV_WC_CONSUMER_KEY",    os.environ.get("WC_CONSUMER_KEY", "")),
        "wc_secret": os.environ.get("DEV_WC_CONSUMER_SECRET", os.environ.get("WC_CONSUMER_SECRET", "")),
    },
}

# Aktywna konfiguracja – ustawiana przez select_environment()
_active_env = None


def select_environment():
    """
    Pyta użytkownika o środowisko (local lub dev) i ustawia aktywną konfigurację.
    Zwraca nazwę wybranego środowiska.
    """
    global _active_env

    print("\n" + "═" * 50)
    print("  Wybierz środowisko docelowe:")
    print("  1 - local  (http://localhost:8080 – Docker)")
    print("  2 - dev    (https://stilco.on-forge.com)")
    print("═" * 50)

    while True:
        choice = input("Twój wybór [1/2]: ").strip()
        if choice in ("1", "local"):
            _active_env = ENVIRONMENTS["local"]
            print("✅ Wybrano: local\n")
            return "local"
        elif choice in ("2", "dev"):
            _active_env = ENVIRONMENTS["dev"]
            print("✅ Wybrano: dev\n")
            return "dev"
        else:
            print("❌ Nieprawidłowy wybór. Wpisz 1 lub 2.")


def close_ssh_tunnel():
    """Placeholder – tunel SSH nie jest używany, zostawiony dla kompatybilności."""
    pass


# ─────────────────────────────────────────────
# Pomocnicze funkcje API (używają aktywnej konfiguracji)
# ─────────────────────────────────────────────

def _cfg():
    """Zwraca aktywną konfigurację. Rzuca błąd jeśli nie wybrano środowiska."""
    if _active_env is None:
        raise RuntimeError(
            "Środowisko nie zostało wybrane. Wywołaj najpierw select_environment()."
        )
    return _active_env


def get_wp_url():
    return _cfg()["wp_url"]


def get_wp_api_url(endpoint="wp/v2"):
    """Zwraca podstawowy URL dla danego namespace REST API."""
    return f"{get_wp_url()}/wp-json/{endpoint}"


def get_wp_auth():
    """Zwraca krotkę (user, password) do autoryzacji HTTP Basic."""
    cfg = _cfg()
    return (cfg["wp_user"], cfg["wp_pass"])


def get_wc_api():
    """Odtwarza instancję API WooCommerce na podstawie aktywnej konfiguracji."""
    from woocommerce import API
    cfg = _cfg()
    return API(
        url=cfg["wp_url"],
        consumer_key=cfg["wc_key"],
        consumer_secret=cfg["wc_secret"],
        version="wc/v3",
    )
