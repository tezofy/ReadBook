# Contributing to Nocturne

Thanks for helping make this theme better. This document covers everything you
need to get set up and get a change merged.

---

## The golden rule

> **Never change the parent theme.** Every change goes into the child theme
> (`template/`). If you find yourself editing a Kadence/GeneratePress file,
> you are doing it wrong — and you will lose it on the next parent update.

---

## Getting set up

```bash
git clone https://github.com/YOUR-USERNAME/nocturne.git
cd nocturne

# Optional but recommended
composer install
npm install
```

You will need:

| Tool | Version | For |
|---|---|---|
| PHP | 8.1+ | The theme itself |
| Composer | 2.x | WordPress Coding Standards |
| Node | 18+ | CSS/JS linting (optional) |
| Playwright | latest | The QA suite |

---

## Repository layout

```
template/        the actual theme — all your changes go here
docs/guides/     client-facing documentation
preview/         a static HTML preview (no WordPress needed)
scripts/         build + test tooling
```

---

## Running the preview

No WordPress install needed for a quick look:

```bash
cd preview && python3 -m http.server 8080
```

Then open <http://localhost:8080>.

For a full WordPress environment, use **LocalWP**, **WP Engine Local** or
**wp-env**:

```bash
npx @wordpress/env start
```

---

## Running the tests

```bash
# The QA suite (needs the preview server running)
pip install playwright && playwright install chromium
python3 scripts/test_theme.py

# Just the edge-to-edge hero check
python3 scripts/test_bleed.py
```

Point them at a different site with environment variables:

```bash
NOCTURNE_BASE=https://mysite.test NOCTURNE_OUT=./shots python3 scripts/test_theme.py
```

**Every pull request must pass the suite.** It checks horizontal overflow,
the hero bleed, interactions, accessibility basics and placeholder text.

---

## Coding standards

### PHP — WordPress Coding Standards

```bash
composer global require wp-coding-standards/wpcs
composer global require phpcompatibility/phpcompatibility-wp
phpcs --standard=WordPress template/
```

Key rules this theme follows:

- **Prefix everything.** Functions, classes, options and hooks all start with
  `nocturne_`. Global namespace pollution is a theme-review rejection.
- **Escape on output.** `esc_html()`, `esc_attr()`, `esc_url()`,
  `wp_kses_post()`.
- **Sanitize on input.** `sanitize_text_field()`, `absint()`,
  `wp_kses()`, `sanitize_hex_color()`.
- **Never touch `$_GET`/`$_POST` directly.** Use
  `filter_input()` or `wp_unslash()` + a sanitiser.
- **Text domain** — every string goes through `esc_html__( '…', 'nocturne' )`.
- **PHP 8.1+ compatible**, but avoid 8.2-only syntax so the theme runs on
  older hosts.

### CSS

- **BEM-ish naming**: `.vl-block__element--modifier`.
- **All colours as custom properties.** Never hard-code a hex value in a
  component — use `var(--vl-ink)` etc. so the Customizer controls everything.
- **Mobile-first.** Base styles are for small screens; add breakpoints upward.
- **Never break the hero bleed.** The stage/track/slide/media must stay at
  `padding-inline: 0`. If you touch this, the QA suite will catch it.
- **Respect `prefers-reduced-motion`.**

### JavaScript

- **Vanilla JS.** No jQuery, no framework. This theme ships zero dependencies.
- **ES5-safe syntax** in the shipped file, or transpile it.
- **Guard every `querySelector`.** The theme runs on pages that do not have
  every component.
- **Use the `vl-` / `nocturne_` prefix** on any global you expose.

---

## Adding a new component

1. Add the CSS to `template/assets/css/nocturne.css` under a numbered section
2. Add the markup to the relevant template part
3. Add the behaviour to `template/assets/js/nocturne.js` inside an `init*()`
   function, and call it from `boot()`
4. Add a test to `scripts/test_theme.py`
5. Document it in `docs/guides/CUSTOMISING.md`

---

## Commit messages

Use [Conventional Commits](https://www.conventionalcommits.org/):

```
feat: add a "series" filter to the book archive
fix: hero slide content overflowing at 320px
docs: clarify the Stripe setup steps
refactor: extract the slider into its own module
style: normalise spacing scale to the 4px grid
chore: bump version to 1.1.0
```

---

## Pull requests

1. Fork the repo and branch from `main`
2. Make your change
3. Run the test suite — it must pass
4. Update the documentation if you changed behaviour
5. Open the PR with a clear title and a short "why"

**A PR that changes the design system** should include a before/after
screenshot. A PR that changes the hero slider **must** include a mobile
screenshot at 375px — that is the component everyone looks at.

---

## Versioning

Semantic versioning:

| Change | Bump |
|---|---|
| New feature, no breaking changes | `1.X.0` |
| Bug fix, visual tweak | `1.X.Y` |
| Breaking change to markup or hooks | `2.0.0` |

Update the `Version:` line in `template/style.css` and the
`NOCTURNE_VERSION` constant in `template/functions.php`.

---

## Reporting a bug

Open an issue with:

- The theme version and the parent theme you are using
- Your PHP version
- What you expected, and what happened instead
- A screenshot or a link
- Browser and device (especially if it is mobile-specific)

**For mobile bugs, always say the viewport width.** Most theme bugs live
between 320px and 480px.

---

## Security

Report security issues **privately** to the maintainer rather than opening a
public issue. Please do not include exploit details in a public thread.

---

## Code of conduct

Be decent to each other. Reviews are about the code, not the person.

---

<div align="center">

**Thank you for contributing.** ✨

</div>
