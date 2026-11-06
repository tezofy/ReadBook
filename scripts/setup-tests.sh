#!/usr/bin/env bash
#
# Install the browser test tooling — ONLY when you want to run the QA suite.
#
# The theme itself has zero dependencies. This script is only needed if you
# want to run scripts/test_theme.py or scripts/test_bleed.py, which drive a
# real headless browser.
#
#   bash scripts/setup-tests.sh          → install (~730 MB in the browser cache)
#   bash scripts/setup-tests.sh --remove → delete it again (~730 MB back)
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
CACHE_DIR="${PLAYWRIGHT_BROWSERS_PATH:-$HOME/.cache/ms-playwright}"

if [ "${1:-}" = "--remove" ]; then
  echo "▸ Removing the Playwright browser cache"
  rm -rf "$CACHE_DIR"
  echo "✅ Freed $(du -sh "$HOME/.cache" 2>/dev/null | cut -f1 || echo '~730 MB')"
  exit 0
fi

echo "▸ Installing the test tooling"
echo "  This downloads ~730 MB into $CACHE_DIR"
echo "  The theme itself does not need this — only the QA scripts do."
echo ""

python3 -m pip install --quiet --upgrade playwright || {
  echo "❌ pip install failed. Try: python3 -m pip install playwright" >&2
  exit 1
}

python3 -m playwright install chromium || {
  echo "❌ Browser download failed." >&2
  exit 1
}

# Linux needs these system libraries for Chromium to launch.
if [ "$(uname)" = "Linux" ]; then
  echo "▸ Installing the Linux system libraries"
  if command -v sudo >/dev/null 2>&1; then
    sudo python3 -m playwright install-deps chromium || \
      echo "  ⚠️  Could not install system libraries automatically."
  fi
fi

echo ""
echo "✅ Done. Run the suite with:"
echo "   cd preview && python3 -m http.server 8080 &"
echo "   NOCTURNE_BASE=http://localhost:8080 python3 scripts/test_theme.py"
echo ""
echo "   To free the space again: bash scripts/setup-tests.sh --remove"
