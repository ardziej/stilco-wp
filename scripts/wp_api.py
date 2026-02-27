import os
import requests
from dotenv import load_dotenv

# Wczytanie zmiennych z pliku .env (wymaga zainstalowania python-dotenv)
env_path = os.path.join(os.path.dirname(__file__), '..', '.env')
load_dotenv(env_path)

WP_URL = os.environ.get('WP_URL', 'http://localhost:8080')

# WordPress REST API standard
WP_USER = os.environ.get('WP_USER', 'admin')
WP_APP_PASSWORD = os.environ.get('WP_APP_PASSWORD', '')

# WooCommerce REST API
WC_KEY = os.environ.get('WC_CONSUMER_KEY', '')
WC_SECRET = os.environ.get('WC_CONSUMER_SECRET', '')

def get_wp_api_url(endpoint="wp/v2"):
    """Zwraca podstawowy URL dla danego namespace REST API"""
    return f"{WP_URL}/wp-json/{endpoint}"

def get_wp_auth():
    """Zwraca krotkę (user, password) do autoryzacji HTTP Basic"""
    return (WP_USER, WP_APP_PASSWORD)

def get_wc_api():
    """Odtwarza instancję API WooCommerce na podstawie ustawień .env"""
    from woocommerce import API
    return API(
        url=WP_URL,
        consumer_key=WC_KEY,
        consumer_secret=WC_SECRET,
        version="wc/v3"
    )
