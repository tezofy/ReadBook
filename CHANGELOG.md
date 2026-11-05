# Changelog

All notable changes to this project are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
