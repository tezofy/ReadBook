# Nocturne — Luxury Editorial Dark WordPress Theme

A premium, mobile-first dark theme for **authors, publishers and creative studios** —
built as a WordPress child theme with WooCommerce, Elementor support and a
**full-bleed media slider** that works flawlessly down to a 375px phone.

> Built from a real Freelancer contest brief. Production-tested, accessibility
> checked, and documented so a non-developer can run the finished site.

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-8892BF?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-21759B?style=flat-square&logo=wordpress&logoColor=white)](https://wordpress.org/)
[![License](https://img.shields.io/badge/License-GPL--2.0%2B-green?style=flat-square)](#license)
[![Tests](https://img.shields.io/badge/tests-automated%20QA-8c9a3f?style=flat-square)](docs/guides/TESTING.md)

---

## 📸 Preview

<table>
<tr>
<td width="50%" valign="top"><img src="docs/screenshots/home-desktop.jpg" alt="Homepage — full-bleed hero slider" /><br /><sub><b>Home</b> — the edge-to-edge hero slider, on desktop</sub></td>
<td width="50%" valign="top"><img src="docs/screenshots/home-mobile.jpg" alt="Homepage on mobile — edge to edge" /><br /><sub><b>Home</b> — 375px. Note the hero bleeding to both screen edges</sub></td>
</tr>
<tr>
<td width="50%" valign="top"><img src="docs/screenshots/book-desktop.jpg" alt="Single book page with format variations" /><br /><sub><b>Book page</b> — one title, two format variations</sub></td>
<td width="50%" valign="top"><img src="docs/screenshots/shop-desktop.jpg" alt="The catalogue" /><br /><sub><b>Catalogue</b> — cover-forward grid with generous whitespace</sub></td>
</tr>
</table>

---

## ✨ Why this theme exists

Most author themes fall into one of two traps: either they look like a generic
WordPress template, or they look beautiful but the client cannot run them.

Nocturne does neither. It is:

- **Editorial by default** — a real type scale, a real colour system, and
  whitespace treated as a design material, not an afterthought.
- **Edge-to-edge on mobile** — the hero slider breaks out of its container to
  bleed to the physical screen edge, with **zero horizontal scrollbar** at any
  breakpoint. Verified by an automated test suite, not by eye.
- **Maintainable by a non-developer** — every colour, every book, every
  coupon and every hero slide is editable from the WordPress admin.
- **Commerce-complete** — variable products with format variations, digital
  delivery, tax zones, multiple gateways, transactional email templates.

---

## 🎬 The hero slider — edge-to-edge, video-ready

This is the hardest thing to get right in a WordPress theme, and the most
common thing clients complain about.

```css
.vl-hero__stage {
  width: 100vw;
  margin-left: calc(50% - 50vw);   /* break out of any container */
  overflow: hidden;
}
```

The **media layer** has zero horizontal padding at every breakpoint. The
**content layer** keeps a comfortable gutter so text never touches the screen
edge. Getting that distinction right is the whole trick.

**Features**

| | |
|---|---|
| Video slides | `<video muted playsinline loop>` — iOS Safari autoplays correctly |
| Poster frames | Every video slide shows a still image while loading and on slow connections |
| Touch swipe | Vertical gestures are ignored so page scroll never fights the slider |
| Progress dots | Animated fill that matches the real slide duration |
| Pause control | Stops autoplay **and** pauses the videos (saves mobile data) |
| Off-screen pause | `IntersectionObserver` stops playback when scrolled away or the tab is hidden |
| Keyboard | Arrow keys, plus `aria-hidden` on inactive slides |
| `prefers-reduced-motion` | Fully honoured |

**Adding a slide from the admin** — no code, no FTP:

```
Appearance → Customize → Nocturne — Design System → Hero slides
```

One line per slide:

```
Image URL | Video URL | Kicker | Title | Excerpt | Button label | Button URL
```

Leave the video field empty for a still-image slide.

---

## 🎨 The design system

Every value is a CSS custom property, and every one is overridable from the
Customizer without touching a line of code.

```
SURFACES            ACCENTS           TYPE
  ink      #0B0A0D    oxblood  #8A1F2E   Display  Playfair Display 600/700
  ink-2    #100E13    oxblood+ #B23646   Serif    Cormorant Garamond italic
  charcoal #17151B    gold     #C9A227   UI/Body  Inter 400/500/600
  plum     #1E1922    gold-lt  #E3C25C
  ash      #2E2833
TEXT
  ivory    #F4EFE6      LINE           rgba(244,239,230,0.10)
  dim      #E2DACD      HAIRLINE GOLD  rgba(201,162,39,0.28)
  muted    #A79FB2      TEXTURE        SVG fractal noise @ 2.8%
  muted-2  #7C7489      SHADOW         18px 44px -18px rgba(0,0,0,.85)
```

**Spacing scale** — 4 / 8 / 12 / 16 / 24 / 32 / 44 / 64 / 96 / 132 / 176
**Container** — 1280px (1520px for full-bleed headers)
**Radius** — 2px (4px on large surfaces) — sharp, editorial, not bubbly
**Motion** — `cubic-bezier(.22,.61,.36,1)`, 300–550ms

---

## 🛒 Commerce

| Feature | How it works |
|---|---|
| Book pages | One WooCommerce **variable product** per title — the product page *is* the book page |
| Formats | Product variations: E-book (virtual + downloadable) and Paperback (physical, weight + dimensions) |
| Digital delivery | Download permission: `order`, 30-day limit, customer can re-download |
| Tax | Per-product tax class — printed books and e-books often qualify for different EU VAT rates |
| Shipping | Zone-based flat rate, with a digital-only zone that skips shipping automatically |
| Gateways | Stripe, PayPal and offline/manual enabled side by side |
| Emails | All templates editable in **WooCommerce → Settings → Emails** |

---

## 🚀 Install

### Option A — WordPress admin

1. **Appearance → Themes → Add New → Upload Theme**
2. Choose `nocturne.zip`
3. **Install Now → Activate**
4. Install **WooCommerce**
5. **Appearance → Nocturne Setup → Import demo content**

The importer creates 3 books with both format variations, a full blog post,
the About/Contact/FAQ/Journal pages, a primary menu and a sample coupon.
It is idempotent — running it twice creates no duplicates.

### Option B — WP-CLI

```bash
wp theme install nocturne.zip --activate
wp plugin install woocommerce --activate
wp eval 'nocturne_import_demo_content();'
```

### Parent theme

Declares `Template: kadence`. Also compatible with **GeneratePress**, **Astra**
and **OceanWP** — change the `Template:` line in `style.css` to switch.

---

## 📁 Structure

```
nocturne/
├── template/
│   ├── style.css                 theme header + parent declaration
│   ├── functions.php             design system, slider, customizer, admin helpers
│   ├── header.php                site header
│   ├── footer.php                site footer
│   ├── inc/demo-content.php      one-click demo importer
│   └── assets/
│       ├── css/nocturne.css      the entire design system
│       ├── css/modules.css       WooCommerce + Elementor modules
│       ├── js/nocturne.js        slider, forms, reveal, cart drawer
│       └── img/                  logo.svg, favicon.svg
├── docs/
│   ├── guides/                   client-facing documentation
│   └── screenshots/
├── scripts/                      build + release tooling
└── .github/workflows/            CI
```

---

## 🧪 Testing

```bash
pip install playwright && playwright install chromium
python3 scripts/test_theme.py       # requires a running WordPress
```

The suite is browser-driven and covers:

- ✅ Horizontal overflow at 375 / 390 / 768 / 1440 / 1920 px
- ✅ Hero slider edge-to-edge bleed (stage width, padding, position)
- ✅ Slider advance, dot navigation, pause toggle, swipe
- ✅ Mobile nav drawer and cart drawer
- ✅ Form validation blocks empty submits
- ✅ Missing `alt` attributes, accessible names, heading hierarchy
- ✅ Broken images, console errors, leftover placeholder text
- ✅ Elements clipping outside the viewport

See [`docs/guides/TESTING.md`](docs/guides/TESTING.md).

---

## ♿ Accessibility

- Semantic landmarks and heading hierarchy (exactly one `h1` per page)
- Visible `:focus-visible` rings with a gold accent
- AA contrast on the dark palette
- `alt` text on every meaningful image; decorative art is marked
- `prefers-reduced-motion` disables transforms and autoplay
- Slider slides use `aria-hidden`; controls are labelled
- Form errors are announced and written in plain language

---

## 🔐 Security & privacy

- **Spam protection** — honeypot field plus a server-side
  minimum-time-to-submit check, wired into Contact Form 7
- **GDPR cookie banner** — included, with accept/decline and `localStorage`
- **No external dependencies** — no CDN, no jQuery, no icon-font library
- **Escaped output** — every dynamic value passes through `esc_html`,
  `esc_attr` or `esc_url`
- **Structured data** — `Person`, `Book`, `Product` + `AggregateOffer`,
  `BlogPosting`

---

## 📚 Documentation

| Guide | For |
|---|---|
| [`HANDOFF-GUIDE.md`](docs/guides/HANDOFF-GUIDE.md) | The client — running the site day to day |
| [`DEPLOYMENT.md`](docs/guides/DEPLOYMENT.md) | The client — hosting, SSL, going live |
| [`WOOCOMMERCE-SETUP.md`](docs/guides/WOOCOMMERCE-SETUP.md) | The client — every shop screen |
| [`CUSTOMISING.md`](docs/guides/CUSTOMISING.md) | Developers — extending the design system |
| [`TESTING.md`](docs/guides/TESTING.md) | Developers — the QA suite |
| [`CONTRIBUTING.md`](docs/guides/CONTRIBUTING.md) | Contributors — how to help |
| [`ROADMAP.md`](docs/guides/ROADMAP.md) | Everyone — what comes next |
| [`GITHUB-SETUP.md`](docs/guides/GITHUB-SETUP.md) | Maintainers — publishing and growing the repo |
| [`ARCHITECTURE.md`](docs/ARCHITECTURE.md) | Developers — how the layers fit together |
| [`REPO-SUMMARY.md`](docs/guides/REPO-SUMMARY.md) | Everyone — the complete map |

---

## 🛠 Roadmap

| Version | Focus |
|---|---|
| **v1.0** | ✅ Complete — design system, hero slider, WooCommerce, demo importer, QA suite |
| **v1.1** | Gutenberg blocks + block patterns |
| **v1.2** | `theme.json` alignment, designed dark/light modes |
| **v1.3** | Token pipeline — one file drives CSS, Elementor and Figma |
| **v1.4** | Performance budget enforced in CI |
| **v1.5** | Commerce depth — subscriptions, bundles, product add-ons |
| **v1.6** | The reading experience — typography controls, progress tracking |
| **v1.7** | Extensibility — filters, options API, hook reference |
| **v1.8** | Internationalisation and RTL |
| **v2.0** | Standalone Full Site Editing theme |

Full detail in [`docs/guides/ROADMAP.md`](docs/guides/ROADMAP.md).

**Contributing:** work branches from `develop`, never straight to `main`.

---

## 📄 License

**GPL-2.0-or-later** — the same license as WordPress.

You may use, modify and redistribute this theme, including commercially.
Derivative themes must also be GPL-2.0-or-later.

See [`LICENSE`](LICENSE).

---

## 💬 Contributing

Issues and pull requests are welcome — see
[`docs/guides/CONTRIBUTING.md`](docs/guides/CONTRIBUTING.md).

If this theme saved you a weekend, a star genuinely helps other people find it.

---

<div align="center">

**Nocturne** — a dark theme for people who care about the reading,
not just the selling.

</div>
