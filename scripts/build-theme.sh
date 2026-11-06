#!/usr/bin/env bash
#
# Build the installable Nocturne WordPress theme package.
#
#   bash scripts/build-theme.sh            → dist/nocturne.zip
#   bash scripts/build-theme.sh --dev      → also copies into dist/nocturne-dev/
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
SRC="$ROOT/template"
DIST="$ROOT/dist"

if [ ! -f "$SRC/style.css" ]; then
  echo "❌ template/style.css not found — is the repo structure intact?" >&2
  exit 1
fi

echo "▸ Cleaning"
rm -rf "$DIST"
mkdir -p "$DIST"

STAGE="$DIST/nocturne"
mkdir -p "$STAGE/assets/css" "$STAGE/assets/js" "$STAGE/assets/img" "$STAGE/inc"

echo "▸ Copying theme files"
cp "$SRC/style.css"                "$STAGE/"
cp "$SRC/functions.php"            "$STAGE/"
cp "$SRC/header.php"               "$STAGE/"
cp "$SRC/footer.php"               "$STAGE/"
cp "$SRC/front-page.php"           "$STAGE/"
cp "$SRC/page-client.php"           "$STAGE/"
cp "$SRC/screenshot.png"           "$STAGE/" 2>/dev/null || true
cp "$SRC/inc/"*.php                "$STAGE/inc/"
cp "$SRC/assets/css/"*.css         "$STAGE/assets/css/"
cp "$SRC/assets/js/"*.js           "$STAGE/assets/js/"
cp "$SRC/assets/img/"*.svg         "$STAGE/assets/img/" 2>/dev/null || true
cp "$SRC/assets/img/"*.jpg         "$STAGE/assets/img/" 2>/dev/null || true
mkdir -p "$STAGE/assets/img/client"
cp "$SRC/assets/img/client/"*.jpg   "$STAGE/assets/img/client/" 2>/dev/null || true
cp "$SRC/assets/img/client/"*.png   "$STAGE/assets/img/client/" 2>/dev/null || true

echo "▸ Copying documentation into the package"
mkdir -p "$STAGE/docs"
cp -r "$ROOT/docs/guides/." "$STAGE/docs/" 2>/dev/null || true

# Read the version straight from style.css so there is a single source of truth
VERSION=$(grep -m1 "^Version:" "$SRC/style.css" | awk '{print $2}' | tr -d ' ')
echo "▸ Version: $VERSION"

echo "▸ Building zip"
cd "$DIST"
rm -f "nocturne.zip"
zip -qr "nocturne.zip" "nocturne"

# The zip must contain style.css at the root of the theme folder, or
# WordPress will refuse to install it.
LISTING="$(mktemp)"
unzip -Z1 "nocturne.zip" > "$LISTING"

if ! grep -qx "nocturne/style.css" "$LISTING"; then
  echo "❌ style.css is not at the zip root — WordPress would reject this" >&2
  echo "   Archive contents:" >&2
  sed 's/^/     /' "$LISTING" >&2
  exit 1
fi

# The theme must also contain its entry points.
for required in \
  nocturne/functions.php \
  nocturne/header.php \
  nocturne/footer.php \
  nocturne/front-page.php \
  nocturne/inc/shortcodes.php \
  nocturne/inc/demo-content.php \
  nocturne/assets/css/effects.css \
  nocturne/assets/css/parchment.css \
  nocturne/assets/js/effects.js \
  nocturne/assets/img/logo.svg \
  nocturne/assets/img/monogram.svg \
  nocturne/assets/img/favicon.svg \
  nocturne/assets/css/client.css \
  nocturne/assets/js/client.js \
  nocturne/assets/js/client-data.js \
  nocturne/inc/client-shortcodes.php \
  nocturne/inc/client-header.php \
  nocturne/inc/client-footer.php \
  nocturne/inc/client-overlay.php \
  nocturne/page-client.php \
  nocturne/assets/img/client/logo-ci-transparent.png \
  nocturne/assets/img/client/cover-speranta.jpg \
  nocturne/assets/img/client/author-portrait.jpg \
  nocturne/screenshot.png ; do
  if ! grep -qx "$required" "$LISTING"; then
    echo "❌ $required is missing from the package" >&2
    exit 1
  fi
done

rm -rf "$STAGE"

SIZE=$(du -h "nocturne.zip" | cut -f1)
echo "✅ Built dist/nocturne.zip ($SIZE)"

if [ "${1:-}" = "--dev" ]; then
  mkdir -p "$DIST/nocturne-dev"
  cp -r "$SRC/." "$DIST/nocturne-dev/"
  echo "✅ Also copied the unpacked theme to dist/nocturne-dev/"
fi
