#!/usr/bin/env bash

set -euo pipefail

PLUGIN_SLUG="global-scripts-manager"
PLUGIN_MAIN="global-scripts-manager.php"

if [[ ! -f "$PLUGIN_MAIN" ]]; then
  echo "Error: $PLUGIN_MAIN not found. Run this script from the plugin root."
  exit 1
fi

VERSION="$(sed -nE "s/^[[:space:]]*\* Version:[[:space:]]*([^[:space:]]+).*$/\1/p" "$PLUGIN_MAIN" | head -n 1)"

if [[ -z "$VERSION" ]]; then
  echo "Error: Unable to read plugin version from $PLUGIN_MAIN"
  exit 1
fi

mkdir -p dist
ARCHIVE_PATH="dist/${PLUGIN_SLUG}-${VERSION}.zip"
LEGACY_ARCHIVE_PATH="dist/${PLUGIN_SLUG}.zip"

# Remove any prior plugin archives so each build produces one fresh zip.
find dist -maxdepth 1 -type f -name "${PLUGIN_SLUG}*.zip" -delete

# Extra cleanup in case the legacy archive name is ever recreated separately.
rm -f "$LEGACY_ARCHIVE_PATH"

# Build from parent so the archive contains the plugin folder at its root.
(
  cd ..
  zip -r "$PLUGIN_SLUG/$ARCHIVE_PATH" "$PLUGIN_SLUG" \
    -x "$PLUGIN_SLUG/dist/*" \
       "*/.git/*" \
       "*/.gitignore" \
       "*/.DS_Store" \
       "__MACOSX/*" \
       "*/__MACOSX/*"
)

echo "Created $ARCHIVE_PATH"