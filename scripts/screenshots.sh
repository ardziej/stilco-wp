#!/bin/bash
# Full-page screenshots of the main pages, desktop 1440 and mobile 390.
#
#   scripts/screenshots.sh docs/screenshots/<date>-<label> [base-url]
#
# Needs agent-browser and ImageMagick (magick). Defaults to the local preview
# on port 8083. Lazy images are switched to eager first, otherwise sections
# below the fold come out empty.
set -u
OUT="$1"
BASE="${2:-http://localhost:8083}"
mkdir -p "$OUT"

shoot() {
	local name="$1" url="$2"
	agent-browser open "$BASE$url" >/dev/null 2>&1
	agent-browser wait 1500 >/dev/null 2>&1
	agent-browser eval "(async () => {
		document.querySelectorAll('img[loading=lazy]').forEach(i => { i.loading = 'eager'; });
		for (let y = 0; y < document.body.scrollHeight; y += 600) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 120)); }
		document.querySelectorAll('.animate-on-scroll,.animate-slide-left,.animate-slide-right,.animate-zoom').forEach(e => { e.style.opacity = 1; e.style.transform = 'none'; });
		window.scrollTo(0, 0);
	})()" >/dev/null 2>&1
	agent-browser wait 1000 >/dev/null 2>&1
	agent-browser screenshot --full "$OUT/$name.png" >/dev/null 2>&1
	magick "$OUT/$name.png" -quality 82 "$OUT/$name.jpg" && rm "$OUT/$name.png"
}

PAGES=(
	"home /"
	"o-nas /o-nas/"
	"strefa-wiedzy /strefa-wiedzy/"
	"opinie /opinie/"
	"produkt /produkt/materac-stilco/"
	"kontakt /kontakt/"
)

for viewport in "desktop 1440 900" "mobile 390 844"; do
	read -r label width height <<<"$viewport"
	agent-browser set viewport "$width" "$height" >/dev/null 2>&1
	for p in "${PAGES[@]}"; do
		read -r name url <<<"$p"
		shoot "$name-$label" "$url"
	done
done

ls -la "$OUT" | awk '{print $5, $9}'
