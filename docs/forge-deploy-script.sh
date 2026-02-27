#!/bin/bash

# ==========================================
# SKRYPT DEPLOYMENTU DLA LARAVEL FORGE
# ==========================================
# Wklej poniższą zawartość w zakładce "Deploy Script" 
# dla Twojej strony (Site) w Laravel Forge.

# Upewnij się, że Forge jest pobierany na poprawny branch projektu.
# Zwykle polecenia dla całego WP to katalog public_html
cd /home/forge/twojadomena.pl/public_html

# Pobieranie zmian z repozytorium GitHub
git pull origin $FORGE_SITE_BRANCH

# Przechodzimy do katalogu naszego motywu
cd /home/forge/twojadomena.pl/public_html/wp-content/themes/stilco-theme

# Instalacja zależności NPM
npm install

# Budowanie statycznych plików (CSS/JS) poprzez Vite
npm run build

# Opcjonalnie: Instalacja pakietów PHP jeśli są obsługiwane z Composer wewnątrz motywu
# composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Poinformowanie Forge o zakończeniu instalacji
echo "Wdrożenie zakończone pomyślnie!"

# Opcjonalne: Wyczyszczenie cache WP za pomocą WP-CLI
cd /home/forge/twojadomena.pl/public_html
if wp core is-installed --allow-root 2>/dev/null; then
    wp cache flush --allow-root
    echo "WP Object Cache wyczyszczone."
fi

# Przeładowanie procesu PHP FPM aby odświeżyć OPcache
# Zmień wersję php8.3-fpm na taką jaką zainstalowałeś (np. 8.1, 8.2)
# Upewnij się, że użytkownik forge ma prawa sudo bez hasła do tej komendy (domyślnie tak jest)
echo "" | sudo -S service php8.3-fpm reload
