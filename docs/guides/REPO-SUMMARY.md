# Nocturne — Repository Summary

Everything in this repository, and why it is here.

---

## 🎯 The short version

**Nocturne** is a luxury editorial dark WordPress theme for authors,
publishers and creative studios. It ships with:

- A complete CSS custom-property design system
- A full-bleed, video-ready hero slider that is genuinely edge-to-edge on mobile
- Full WooCommerce support for selling books (digital + physical)
- A one-click demo content importer
- An automated browser QA suite
- GitHub Actions CI

The commercial version of this work was built for a specific freelance
contest. This repository is the **generalised, reusable, open-source form** of
it — same engineering, no client-specific branding, ready for anyone to use.

---

## 📁 The map

```
nocturne/
│
├── 📦 template/                    ← THE THEME
│   ├── style.css                   theme header, parent declaration
│   ├── functions.php               wiring: enqueue, customizer, shortcode, admin
│   ├── header.php                  site header
│   ├── footer.php                  site footer
│   ├── inc/
│   │   └── demo-content.php        one-click importer + setup admin page
│   └── assets/
│       ├── css/
│       │   ├── nocturne.css        the entire design system (1,257 lines)
│       │   └── modules.css         WooCommerce + Elementor modules (313 lines)
│       ├── js/
│       │   └── nocturne.js         all behaviour (481 lines)
│       └── img/
│           ├── logo.svg            hand-drawn vector wordmark
│           └── favicon.svg
│
├── 🖥️ preview/                     ← LIVE STATIC DEMO
│   ├── index.html                  home
│   ├── book.html                   single book (WooCommerce-style product page)
│   └── shop.html                   catalogue
│       └── assets/                 mirrored CSS/JS/images
│
├── 📚 docs/
│   ├── ARCHITECTURE.md             how it all fits together
│   ├── elementor-home-kit.json     Elementor Global Style Kit
│   ├── guides/
│   │   ├── HANDOFF-GUIDE.md        for the client — running the site
│   │   ├── DEPLOYMENT.md           for the client — hosting, SSL, going live
│   │   ├── WOOCOMMERCE-SETUP.md    for the client — every shop screen
│   │   ├── CUSTOMISING.md          for developers — extending it
│   │   ├── TESTING.md              for developers — the QA suite
│   │   ├── CONTRIBUTING.md         for contributors
│   │   ├── ROADMAP.md              what comes next
│   │   └── GITHUB-SETUP.md         publishing and growing the repo
│   └── screenshots/                for the README
│
├── 🔧 scripts/
│   ├── build-theme.sh              builds dist/nocturne.zip
│   ├── test_theme.py               full browser QA suite
│   └── test_bleed.py               the edge-to-edge hero check
│
├── ⚙️ .github/
│   ├── workflows/
│   │   ├── ci.yml                  lint · build · security · style
│   │   ├── release.yml             tagged releases with the zip attached
│   │   └── pages.yml               live preview on GitHub Pages
│   └── ISSUE_TEMPLATE/
│       ├── bug_report.yml
│       └── feature_request.yml
│
├── 📄 CHANGELOG.md · LICENSE · README.md
├── ⚙️ .editorconfig · .gitignore · .vscode/
└── 📦 dist/nocturne.zip            the installable package
```

---

## ✅ What has been verified

| Check | Result |
|---|---|
| PHP syntax on all 4 PHP files | ✅ clean |
| CSS brace balance | ✅ balanced |
| JavaScript parses | ✅ |
| JSON validity | ✅ |
| Hero edge-to-edge at 375 / 390 / 768 / 1440 / 1920 px | ✅ zero horizontal scrollbar |
| Horizontal overflow, all pages, all viewports | ✅ none |
| Broken images | ✅ none |
| Missing `alt` attributes | ✅ none |
| Interactive elements without accessible names | ✅ none |
| Heading hierarchy | ✅ exactly one `h1` per page |
| Placeholder text (lorem ipsum / TODO / FIXME) | ✅ none |
| Console errors | ✅ none |
| Slider: advance, dots, pause toggle | ✅ working |
| Mobile nav drawer | ✅ opens flush to the viewport edge |
| Cart drawer | ✅ opens and closes |
| Form validation | ✅ blocks empty submits with clear messages |
| FAQ accordion | ✅ opens |
| Demo importer idempotency | ✅ no duplicates on re-run |
| Theme zip validity (`style.css` at root) | ✅ WordPress will accept it |

---

## 🔑 The three ideas worth stealing

### 1. The edge-to-edge breakout

```css
.vl-hero__stage {
  width: 100vw;
  margin-left: calc(50% - 50vw);
  overflow: hidden;
}
```

The **media** layer gets zero padding. The **content** layer keeps the gutter.
Getting that distinction right is what separates a slider that bleeds to the
screen edge from one that bleeds but has unreadable text.

### 2. Product pages as book pages

A book needs two formats, one URL, and no manual work when the next book
arrives. A WooCommerce **variable product** with format variations gives you
all three for free — and the product page *is* the book page.

### 3. The design system as a single source of truth

Every colour is a CSS custom property, overridable from the Customizer. Change
one value and the whole site updates. No component ever hard-codes a hex value.

---

## 🚀 Quick start

```bash
# See it running (no WordPress needed)
cd preview && python3 -m http.server 8080

# Install it in WordPress
# → Appearance → Themes → Add New → Upload Theme → nocturne.zip
# → Appearance → Nocturne Setup → Import demo content

# Build it
bash scripts/build-theme.sh

# Test it
NOCTURNE_BASE=http://localhost:8080 python3 scripts/test_theme.py
```

---

## 📈 The next eight releases

See [`docs/guides/ROADMAP.md`](guides/ROADMAP.md). In short:

**v1.1** Gutenberg blocks → **v1.2** theme.json + dark/light →
**v1.3** token pipeline → **v1.4** performance budget →
**v1.5** commerce depth → **v1.6** reading experience →
**v1.7** extensibility → **v1.8** i18n + RTL → **v2.0** standalone FSE theme

---

<div align="center">

**Nocturne** — a dark theme for people who care about the reading,
not just the selling.

*GPL-2.0-or-later · Use it commercially · Build on it · Make it yours.*

</div>
