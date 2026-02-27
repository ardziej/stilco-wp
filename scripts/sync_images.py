#!/usr/bin/env python3
"""
sync_images.py – Upload zdjęć do biblioteki mediów WordPress.

Skanuje katalogi:
  - docs/photos/     (zdjęcia lifestylowe)
  - docs/mattresses/ (zdjęcia materacy)

Strategia uploadu:
  - local: POST /wp/v2/media (REST API)
  - dev:   scp → serwer, następnie wp media import przez SSH
            (omija limit client_max_body_size nginx)

Sprawdza przed uploadem czy plik już istnieje w WP Media Library.
"""

import os
import re
import mimetypes
import subprocess
import requests

import wp_api
from wp_api import get_wp_api_url, get_wp_auth

# Katalogi ze zdjęciami
IMAGE_DIRS = [
    os.path.join(os.path.dirname(__file__), '..', 'docs', 'photos'),
    os.path.join(os.path.dirname(__file__), '..', 'docs', 'mattresses'),
]

SUPPORTED_EXTENSIONS = {'.jpg', '.jpeg', '.png', '.webp', '.gif', '.JPG', '.JPEG', '.PNG'}

# Tymczasowy katalog na serwerze zdalnym dla dev
REMOTE_TMP_DIR = "/tmp/stilco_media_import"

# Alias SSH dla środowiska dev
DEV_SSH_HOST = os.environ.get("DEV_SSH_HOST", "stilco-wp")
DEV_WP_PATH  = "/home/forge/stilco.on-forge.com/public"


def filename_to_slug(filename):
    """Konwertuje nazwę pliku (bez rozszerzenia) do WP slug."""
    name = os.path.splitext(filename)[0].lower()
    return re.sub(r'[^a-z0-9]+', '-', name).strip('-')


def get_existing_media():
    """Pobiera slugi istniejących mediów z WP (cache)."""
    API_WP_URL = get_wp_api_url("wp/v2")
    existing = {}
    page = 1
    while True:
        res = requests.get(
            f"{API_WP_URL}/media",
            auth=get_wp_auth(),
            params={"per_page": 100, "page": page, "media_type": "image"},
            timeout=30
        )
        if res.status_code != 200 or not res.json():
            break
        for item in res.json():
            existing[item['slug']] = item['id']
            source = item.get('source_url', '')
            if source:
                fname = os.path.basename(source).rsplit('.', 1)[0].lower()
                existing[fname] = item['id']
        if len(res.json()) < 100:
            break
        page += 1
    return existing


def _is_dev_env():
    """Sprawdza czy aktywne środowisko to dev."""
    cfg = wp_api._active_env
    return cfg is not None and cfg == wp_api.ENVIRONMENTS.get("dev")


def upload_via_rest(filepath, existing_slugs):
    """Upload przez WP REST API (dla local lub małych plików)."""
    filename = os.path.basename(filepath)
    slug = filename_to_slug(filename)

    if slug in existing_slugs:
        print(f"  ⏭️  Pominięto (już istnieje, ID: {existing_slugs[slug]}): {filename}")
        return existing_slugs[slug]

    mime_type, _ = mimetypes.guess_type(filepath)
    if not mime_type:
        mime_type = 'image/jpeg'

    print(f"  ⬆️  REST upload: {filename}...")
    API_WP_URL = get_wp_api_url("wp/v2")
    with open(filepath, 'rb') as f:
        res = requests.post(
            f"{API_WP_URL}/media",
            auth=get_wp_auth(),
            headers={
                'Content-Disposition': f'attachment; filename="{filename}"',
                'Content-Type': mime_type,
            },
            data=f.read(),
            timeout=60
        )
    if res.status_code in [200, 201]:
        media_id = res.json()['id']
        print(f"  ✅ Wgrano (ID: {media_id})")
        existing_slugs[slug] = media_id
        return media_id
    else:
        print(f"  ❌ Błąd REST {res.status_code}: {res.text[:200]}")
        return None


def upload_via_ssh(filepath, existing_slugs):
    """
    Upload przez SSH: scp plik → serwer, potem wp media import.
    Omija limit nginx client_max_body_size.
    """
    filename = os.path.basename(filepath)
    slug = filename_to_slug(filename)

    if slug in existing_slugs:
        print(f"  ⏭️  Pominięto (już istnieje, ID: {existing_slugs[slug]}): {filename}")
        return existing_slugs[slug]

    print(f"  ⬆️  SSH upload: {filename}...")

    # 1. Utwórz katalog tymczasowy na serwerze
    subprocess.run(
        ["ssh", DEV_SSH_HOST, f"mkdir -p {REMOTE_TMP_DIR}"],
        capture_output=True
    )

    # 2. Skopiuj plik przez scp
    scp_result = subprocess.run(
        ["scp", "-q", filepath, f"{DEV_SSH_HOST}:{REMOTE_TMP_DIR}/{filename}"],
        capture_output=True, text=True
    )
    if scp_result.returncode != 0:
        print(f"  ❌ SCP błąd: {scp_result.stderr}")
        return None

    # 3. Importuj przez WP-CLI
    wp_cmd = (
        f"cd {DEV_WP_PATH} && "
        f"wp media import {REMOTE_TMP_DIR}/{filename} "
        f"--title='{filename}' --porcelain 2>&1"
    )
    wp_result = subprocess.run(
        ["ssh", DEV_SSH_HOST, wp_cmd],
        capture_output=True, text=True
    )
    output = wp_result.stdout.strip()

    # 4. Usuń plik tymczasowy
    subprocess.run(
        ["ssh", DEV_SSH_HOST, f"rm -f {REMOTE_TMP_DIR}/{filename}"],
        capture_output=True
    )

    if wp_result.returncode == 0 and output.isdigit():
        media_id = int(output)
        print(f"  ✅ Wgrano przez SSH (ID: {media_id})")
        existing_slugs[slug] = media_id
        return media_id
    elif "already registered" in output or "Success" in output:
        print(f"  ⏭️  Już zaimportowany: {filename}")
        return None
    else:
        print(f"  ❌ wp media import błąd: {output}")
        return None


def main():
    """Główna funkcja uploadu zdjęć."""
    print("=== Upload Zdjęć do WordPress Media Library ===\n")

    use_ssh = _is_dev_env()
    method  = "SSH (wp media import)" if use_ssh else "REST API"
    print(f"📡 Metoda uploadu: {method}\n")

    print("📋 Pobieranie listy istniejących mediów...")
    existing = get_existing_media()
    print(f"   Znaleziono {len(existing)} istniejących mediów.\n")

    media_map = {}
    total_uploaded = 0
    total_skipped  = 0
    total_errors   = 0

    for image_dir in IMAGE_DIRS:
        if not os.path.exists(image_dir):
            print(f"⚠️  Katalog nie istnieje, pomijam: {image_dir}")
            continue

        dir_name = os.path.basename(os.path.normpath(image_dir))
        files = sorted([
            f for f in os.listdir(image_dir)
            if os.path.splitext(f)[1] in SUPPORTED_EXTENSIONS
               and not f.startswith('.')
        ])

        if not files:
            print(f"📁 {dir_name}/ – brak plików graficznych.")
            continue

        print(f"📁 {dir_name}/ ({len(files)} plików):")

        for filename in files:
            filepath = os.path.join(image_dir, filename)
            slug = filename_to_slug(filename)

            if slug in existing:
                media_map[filename] = existing[slug]
                total_skipped += 1
                print(f"  ⏭️  Pominięto (już istnieje, ID: {existing[slug]}): {filename}")
                continue

            if use_ssh:
                media_id = upload_via_ssh(filepath, existing)
            else:
                media_id = upload_via_rest(filepath, existing)

            if media_id:
                media_map[filename] = media_id
                total_uploaded += 1
            else:
                total_errors += 1

        print()

    print(f"📊 Podsumowanie: wgrano {total_uploaded}, pominięto {total_skipped}, błędy {total_errors}.")
    return media_map


if __name__ == "__main__":
    import wp_api
    import atexit
    atexit.register(wp_api.close_ssh_tunnel)
    wp_api.select_environment()
    main()
