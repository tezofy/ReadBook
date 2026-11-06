# Changelog

All notable changes to this project are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

The motion layer, the 3D product rail and the sticky-shrink header.

### Added

#### The client edition — Christiana Ciocan
- A complete second design edition that reproduces the client's approved
  design: *Imperiul Umbrelor*, the standalone titles, the reviews, the
  journal, the vault (cart) and the navigation overlay.
- `assets/css/client.css` — the client design system (`--cl-*` tokens):
  the shell, the header, the overlay, the vault, the welcome panel, the
  author block, the three gateways, the book rail, the standalone grid,
  the reviews, the journal, the future-projects block and the contact form.
- `assets/js/client-data.js` — the catalogue: 4 series books, 2 standalone
  titles, 16 named reviews, 3 journal posts, the author bio and the
  contact details.
- `assets/js/client.js` — the behaviour: the draggable rail, the vault
  (persisted in `localStorage`), the overlay, the review renderer with a
  star picker, and the contact form handler.
- `inc/client-shortcodes.php` — four shortcodes so the client layout can be
  dropped into any WordPress page:
  `[nocturne_client_gates]`, `[nocturne_client_series]`,
  `[nocturne_client_standalone]`, `[nocturne_client_journal]`.
- `assets/img/client/` — the client's eight source images. Three arrive as
  open-book spreads; the front cover is cropped out and the original is
  preserved as `spread-*.jpg`.
- The home page (`front-page.php`) and the header now carry the client's
  navigation overlay and the full client layout.

#### Effects system (`assets/css/effects.css`, `assets/js/effects.js`)
- A **self-contained, dependency-free** motion layer. No library, no build
  step, nothing to install — add an attribute and the effect runs.
- **Reveal on scroll** driven by `IntersectionObserver`. Fourteen effects:
  `fade`, `fade-up`, `fade-down`, `fade-left`, `fade-right`, `zoom-in`,
  `zoom-out`, `slide-up`, `slide-down`, `blur-in`, `flip-y`, `reveal-mask`,
  `text-entrance`, `letter-stagger`.
  - `data-delay` and `data-duration` tune the timing per element
  - `data-group="90"` cascades a group of cards, 90 ms apart
- **The 3D book cover** — a real three-dimensional object. The cover turns
  towards the reader on hover, and the striped page block appears along the
  fore-edge. Sized with `--book-w`, `--book-h` and `--book-depth`.
  - `data-tilt` adds pointer tracking so the cover leans towards the cursor
- **The product rail** — a horizontal carousel with no library behind it.
  The arrows step one whole card at a time and disable themselves at each
  end; the track also responds to drag and to the keyboard.
- **The split hero** — a 3D cover beside a looping video. The cover swaps
  when the video changes; only the visible slide plays.
- **Sticky-shrink header** — the bar condenses from 96 px to 70 px after
  10 px of scroll and the logo swaps to a smaller lockup.
- **Ticker** — a slow marquee of accolades with a seamless loop.
- **Magnetic buttons** (`data-magnetic`) and **scroll parallax**
  (`data-parallax`).
- **`.vl-prose`** — long-form text styling for pages, posts and reading
  samples.
- Every effect respects `prefers-reduced-motion`: reveals appear at once,
  the ticker stops and the tilt is disabled.

#### Shortcodes
- `[nocturne_showcase]` — the 3D product rail, built from your real
  WooCommerce products and their featured images.
- `[nocturne_split_hero]` — the cover-and-video hero. One slide per line:
  `Image | Video | Title | Excerpt | Cover`.
- `[nocturne_ticker]` — the marquee of accolades.

#### Theme
- `front-page.php` — a landing layout that assembles the split hero, the
  3D rail and the ticker out of the theme's own components.
- Customizer field **Split hero slides**, so the split hero can be filled in
  from the admin without touching a shortcode.
- The header now carries a trust badge, a search form and pill-style
  navigation with dropdown panels.

### Fixed
- `scripts/build-theme.sh` no longer appends to a stale archive, and the
  package check no longer trips over a closed pipe (`grep -q` closing the
  pipe early made `unzip` exit non-zero under `set -o pipefail`).

### Changed
- The old `[data-reveal]` markup was folded into the unified `[data-animate]`
  system. The legacy attribute still works, so older pages do not break.
- The header no longer hides on scroll. It condenses instead, which is the
  behaviour shoppers expect and keeps the navigation reachable.
- `scripts/test_theme.py` now excludes the product rail, the ticker and
  inactive split-hero slides from the overflow check — their content is
  meant to sit outside the viewport.
- `scripts/test_bleed.py` no longer captures full-page screenshots; the page
  is now long enough to exceed Chromium's texture limit.

---

## [1.0.0] — 2026-11-05

The first public release. Built from a real freelance contest brief
(*Dark Romance Author WordPress Site*) and hardened for reuse.

### Added

#### Design system
- Complete CSS custom-property token set — surfaces, accents, type, spacing,
  radius, shadow, motion easings
- SVG fractal-noise film-grain texture at 2.8% opacity, plus a radial ambient
  vignette
- Playfair Display (display), Cormorant Garamond (italic accents) and Inter
  (UI/body) type scale
- Fluid type and spacing via `clamp()`
- Gold hairline dividers with a centred diamond rule

#### Hero media slider
- **Full-bleed edge-to-edge layout** — verified to zero horizontal scrollbar
  at 375 / 390 / 768 / 1440 / 1920 px
- Video slides with `muted` + `playsinline` so iOS Safari autoplays correctly
- Poster-frame fallback for every video slide
- Touch swipe with vertical-gesture detection so page scroll never conflicts
- Animated progress dots matching the real slide duration
- Pause control that stops autoplay *and* pauses the videos
- `IntersectionObserver` pauses playback off-screen and on tab switch
- Keyboard navigation, `aria-hidden` on inactive slides
- `prefers-reduced-motion` fully honoured
- Slides editable from the Customizer — one line per slide, no code

#### Commerce
- WooCommerce variable-product book pages with format variations
- Virtual + downloadable e-book variations with 30-day customer re-download
- Physical paperback variations with weight and dimensions
- Per-product `_veloria_series` custom field for the series line
- Full WooCommerce styling — loop, single product, cart, checkout, account,
  order received, pagination
- EU tax-class guidance for printed books vs. e-books
- Stripe, PayPal and offline/manual gateways documented side by side

#### WordPress integration
- Child theme for Kadence, GeneratePress, Astra and OceanWP
- Elementor compatibility with documented Global Style Kit values
- One-click demo importer — 3 books with both format variations, a full blog
  post, 5 pages, a menu and a sample coupon; idempotent
- Customizer panel for the whole palette plus the hero slides
- Contact Form 7 honeypot + minimum-time-to-submit spam protection
- GDPR cookie banner with accept/decline and `localStorage` persistence
- Welcome screen in the dashboard pointing at the next steps
- Structured data — `Person`, `Book`, `Product` + `AggregateOffer`, `BlogPosting`

#### Accessibility
- Semantic landmarks and exactly one `h1` per page
- Visible `:focus-visible` rings
- AA contrast on the dark palette
- `alt` text throughout, decorative art marked
- Form errors announced and written in plain language
- Slider controls labelled for screen readers

#### Performance
- Zero external dependencies — no jQuery, no CDN, no icon-font library
- `defer` on the theme script
- Lazy-loaded below-the-fold imagery
- Off-screen and hidden-tab playback suspension
- No render-blocking JavaScript

#### Tooling
- Automated QA suite — overflow, hero bleed, interactions, accessibility,
  broken images, console errors, placeholder text
- GitHub Actions CI — PHP lint, WordPress Coding Standards, security scan,
  CSS/JS sanity, placeholder check, build and package
- One-command theme packaging script
- Client-facing documentation — handoff, deployment, WooCommerce setup,
  customising, testing

[1.0.0]: https://github.com/YOUR-USERNAME/nocturne/releases/tag/v1.0.0
