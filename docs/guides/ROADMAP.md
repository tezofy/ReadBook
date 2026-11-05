# Roadmap — Evolving Nocturne

This is the living plan for taking Nocturne from a good theme to a
**reference-grade premium dark editorial theme for WordPress**.

Each milestone below is a release. Nothing here is theoretical — every item
is scoped so it can be built, tested and shipped in a weekend or two.

---

## Philosophy

Three rules govern every decision:

1. **The hero must stay edge-to-edge.** If a change breaks the bleed, it does
   not ship. The QA suite enforces this.
2. **Zero dependencies.** No jQuery, no framework, no CDN. A theme that loads
   a 90 KB library to toggle a class is not premium.
3. **The client must still be able to run it.** Every new capability needs a
   no-code path, or it does not go in.

---

## v1.1 — Gutenberg blocks

*Target: the next 1–2 weeks*

WordPress.org reviewers increasingly expect a block theme path, and more
importantly, the **author editing her own site** should not have to open
Elementor for a simple change.

| Deliverable | Notes |
|---|---|
| `theme.json` | Full token support — colours, spacing, typography, layout all declared the WordPress way |
| `blocks/` | `hero`, `book-grid`, `post-grid`, `quote`, `newsletter`, `feature-strip` |
| Block patterns | PHP-registered patterns under `inc/patterns.php`, insertable from the inserter |
| Block styles | Variations for the button and heading blocks |
| Editable content | All block text editable in the editor — no hard-coded strings |
| FSE templates | `index`, `single`, `archive`, `page`, `404` |

**Definition of done:** a user installs the theme, clicks *Edit with the
editor*, and rebuilds the homepage without writing a line of HTML.

---

## v1.2 — Theme.json alignment

*The dark-mode problem, solved properly*

| Deliverable | Notes |
|---|---|
| `theme.json` v3 | `settings.color.palette` mapped 1:1 to the CSS custom properties |
| Dark + light schemes | `appearanceTools`, `color.schemes` — a light mode that is actually designed, not just inverted |
| `prefers-color-scheme` | Auto-switch when the user has not chosen |
| Manual toggle | A small JS toggle persisted to `localStorage`, respecting the admin choice |
| Editor styles | The block editor inherits the same typography as the front end |
| Contrast pass | Both schemes verified at AA |

**Definition of done:** the block editor looks exactly like the front end,
and the site is legible in daylight without a redesign.

---

## v1.3 — A proper design system

*Turning a theme into a system*

| Deliverable | Notes |
|---|---|
| `docs/tokens.json` | Every token in one machine-readable file |
| Token → CSS generator | `scripts/build-tokens.py` reads the JSON and emits `:root` |
| Token → Elementor export | Generates the Global Style Kit JSON automatically |
| Token → Figma export | A `.tokens.json` for Figma's Design Tokens plugin |
| Storybook-style gallery | A static page at `preview/components.html` showing every component in every state |
| Semantic colour roles | `--vl-bg`, `--vl-text`, `--vl-accent` — not `--vl-grey-900` |

**Definition of done:** changing a colour in one file updates the stylesheet,
the Elementor kit and the Figma tokens together.

---

## v1.4 — Performance

| Deliverable | Notes |
|---|---|
| Critical CSS inlining | Above-the-fold styles inlined, the rest deferred |
| Font subsetting | Self-hosted, subsetted, `font-display: swap` |
| Preload hints | Hero image and hero font preloaded |
| Responsive `srcset` | Every image served at the right size |
| Lazy video | Only the active slide loads video |
| No layout shift | Explicit dimensions on every media element |
| Core Web Vitals budget | LCP < 1.5s, CLS < 0.05, INP < 150ms — enforced in CI |

---

## v1.5 — Commerce depth

| Deliverable | Notes |
|---|---|
| Product Add-Ons | Signed copy, gift wrap, dedication — at checkout |
| Subscriptions | "New release on release day" via WooCommerce Subscriptions |
| Back-in-stock | Waitlist for a sold-out signed edition |
| Bundles | "The complete Ashgate series" as a bundle product |
| EU VAT classes | Already documented — now automated in the importer |
| Download expiry control | Per-product, not global |
| Address auto-fill | Via the EU/UK post-code lookup plugin |

---

## v1.6 — The reading experience

*What makes it a theme for authors, not just a shop*

| Deliverable | Notes |
|---|---|
| Reader progress bar | On long posts and reading samples |
| Typography controls | Reader picks serif or sans, size, line height — persisted |
| Dark/light reader mode | Independent of the site theme |
| Progress-based unlocks | Read 3 chapters, get the next free |
| Newsletter inline | After the reading sample, not before it |
| Web font "reader kit" | A drop-in typographic stylesheet for book pages |

---

## v1.7 — Extensibility

| Deliverable | Notes |
|---|---|
| `nocturne_get_option()` | A proper options API with defaults and sanitisation |
| Filters everywhere | Hero slides, card layout, footer columns — all filterable |
| Child-theme examples | A `docs/guides/EXTENDING.md` with six real recipes |
| Hook reference | Generated from the source |
| Plugin compatibility | Tested against WooCommerce Subscriptions, Rank Math, Yoast, Complianz, Polylang |

---

## v1.8 — Internationalisation

| Deliverable | Notes |
|---|---|
| Full RTL support | Logical properties throughout, `dir="rtl"` tested |
| `.pot` file | Every string extracted |
| RTL preview | A mirrored build for testing |
| Locale formatting | Prices, dates and numbers per locale |
| Multi-currency | Via the official WooCommerce multi-currency extension |

---

## v2.0 — The full-site theme

*No longer a child theme — a standalone theme*

| Deliverable | Notes |
|---|---|
| Standalone | No parent theme dependency at all |
| Site Editor only | WordPress-native, no Elementor required |
| Block patterns for everything | Header, footer, archive, single, page templates |
| Starter templates | Author, publisher, studio, magazine |
| Pattern library | 30+ patterns, all brand-consistent |
| Theme review compliance | WordPress.org directory ready |

---

## Ongoing — every single release

- [ ] QA suite green
- [ ] PHP lint + WPCS clean
- [ ] No placeholder text
- [ ] Screenshots regenerated
- [ ] CHANGELOG updated
- [ ] Version bumped in `style.css` and `functions.php`
- [ ] Tag released
- [ ] Blog post written

---

## How to contribute a milestone

1. Open an issue describing the goal — discussion before code
2. Fork and branch from `develop`
3. Work in small, reviewable commits
4. Open a PR against `develop` with before/after screenshots
5. A maintainer merges to `develop`, then to `main`, then tags the release

**Never merge straight to `main`** — `develop` is where work happens.

---

## Rough roadmap

```
v1.0  ✅  Complete now
v1.1  ⬜  Gutenberg blocks
v1.2  ⬜  theme.json + dark/light
v1.3  ⬜  Token pipeline
v1.4  ⬜  Performance budget
v1.5  ⬜  Commerce depth
v1.6  ⬜  Reading experience
v1.7  ⬜  Extensibility
v1.8  ⬜  i18n + RTL
v2.0  ⬜  Standalone FSE theme
```

---

<div align="center">

**Slow and steady.** A theme that improves every month beats one that
launches once and rots.

</div>
