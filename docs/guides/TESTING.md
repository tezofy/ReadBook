# Testing Nocturne

Two layers of testing: a **fast static check** that runs in CI on every push,
and a **browser-driven QA suite** that drives a real browser.

---

## Layer 1 — CI (automatic on every push)

The workflow in `.github/workflows/ci.yml` runs four jobs:

| Job | What it checks |
|---|---|
| `lint` | PHP syntax on every file, WordPress Coding Standards, common theme-review failures (unescaped superglobals, unwrapped `wp_head`) |
| `build` | Builds the zip and verifies `style.css` sits at the root — otherwise WordPress refuses to install it |
| `security` | gitleaks secret scan, `composer audit` |
| `style` | JSON validity, CSS brace balance, JS parses, **no placeholder text ships** |

```bash
# Run locally what CI runs
find template -name "*.php" -print0 | xargs -0 -n1 php -l
bash scripts/build-theme.sh
```

---

## Layer 2 — Browser QA suite

Real Chromium, real viewport sizes, real interactions.

### Setup

```bash
pip install playwright
playwright install chromium
playwright install-deps chromium   # Linux only
```

### Run it

```bash
# Terminal 1 — serve the preview
cd preview && python3 -m http.server 8080

# Terminal 2
python3 scripts/test_theme.py
```

Point it at any site:

```bash
NOCTURNE_BASE=https://mysite.test \
NOCTURNE_OUT=./shots \
python3 scripts/test_theme.py
```

### What it asserts

**Layout integrity**
- `document.scrollWidth === document.clientWidth` — no horizontal scrollbar
- No individual element clips outside the viewport
- Images all resolve (`naturalWidth > 0` after scrolling past lazy-load)
- No leftover `lorem ipsum`, `TODO`, `FIXME` or placeholder text

**The hero bleed** *(the reason this suite exists)*
- `.vl-hero__stage` left edge is exactly `0`
- `.vl-hero__stage` right edge is exactly the viewport width
- `.vl-hero__stage` horizontal padding is `0px`
- The active slide spans the full width
- The hero image starts at `left: 0`

**Interactions**
- The slider advances on the next arrow
- Dot 3 activates slide 3
- The pause toggle sets the paused state
- The cart drawer opens and closes
- An empty form submit produces validation errors
- The FAQ accordion opens

**Accessibility basics**
- Every image has an `alt` attribute
- Every button and link has an accessible name
- Exactly one `<h1>` per page
- No console errors

**Viewports tested**

| Name | Width × height |
|---|---|
| mobile | 375 × 812 |
| tablet | 768 × 1024 |
| desktop | 1440 × 900 |

---

## Adding a test

```python
def check_my_new_thing(page):
    issues = []
    if not page.evaluate("!!document.querySelector('.vl-my-thing')"):
        issues.append("component missing")
    return issues
```

Then call it from `check_page()` or `check_interactions()`.

Keep assertions **specific**. "Element exists" passes on a broken page; "the
stage's right edge equals the viewport width" catches the actual bug.

---

## Testing against real WordPress

For the parts the static preview cannot cover (WooCommerce cart, checkout,
the Customizer), run a local WordPress:

```bash
npx @wordpress/env start
```

Or use **LocalWP** — the "preferred" site setup is WordPress + WooCommerce.

Then point the suite at it:

```bash
NOCTURNE_BASE=http://nocturne.local python3 scripts/test_theme.py
```

---

## Manual checklist before a release

- [ ] Install the theme on a clean WordPress — no PHP notices
- [ ] Run the demo importer twice — no duplicates
- [ ] Place a real test order, both digital and physical
- [ ] Check the order confirmation email arrives
- [ ] Check the e-book download link works and re-downloads
- [ ] Test the hero on a real phone, in portrait, with the video
- [ ] Test the nav drawer and cart drawer by touch
- [ ] Keyboard-only pass: tab through the whole page
- [ ] Confirm `prefers-reduced-motion` disables the slider animation
- [ ] Run the site through Lighthouse — aim for 95+ on all four categories

---

## Common failures and what they mean

| Failure | Meaning |
|---|---|
| `horizontal overflow +Npx` | Something is wider than the viewport. Almost always padding added to `.vl-hero__stage`, `.vl-hero__track`, `.vl-slide` or `.vl-slide__media`. |
| `stage right X != viewport` | The breakout is broken — check `margin-left: calc(50% - 50vw)` is still present. |
| `broken images` | A path is wrong, or the image was never uploaded. |
| `images without alt attribute` | Accessibility regression — add `alt=""` for decorative or a description for meaningful images. |
| `expected exactly 1 <h1>, found 0` | The template is missing its main heading. |
| `placeholder text found` | Do not ship lorem ipsum. Write real copy. |
| `elements exceeding viewport` | Usually a closed off-canvas drawer — check the selector excludes `.vl-cart-drawer:not(.is-open)`. |

---

<div align="center">

**Tests are a feature.** Ship them with the feature.

</div>
