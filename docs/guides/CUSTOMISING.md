# Customising Nocturne

A developer-facing guide. For day-to-day site management, see
[`HANDOFF-GUIDE.md`](HANDOFF-GUIDE.md).

---

## 1. Architecture in 60 seconds

```
functions.php
 ├── Enqueues assets/css/nocturne.css  (the whole design system)
 │              assets/css/modules.css  (WooCommerce + Elementor)
 │              assets/js/nocturne.js   (behaviour)
 ├── Registers the Customizer panel (colours + hero slides)
 ├── Registers the [nocturne_hero] shortcode
 ├── Registers the demo importer admin page
 ├── Wires CF7 spam protection
 └── Styles WooCommerce output
```

Everything visual lives in CSS custom properties. Everything behavioural lives
in `nocturne.js`. `functions.php` only wires things together — if you are
adding visual logic, it belongs in the stylesheet.

---

## 2. The design tokens

All tokens live in `:root` at the top of `assets/css/nocturne.css`.

```css
:root {
  /* Surfaces */
  --vl-ink:      #0B0A0D;
  --vl-ink-2:    #100E13;
  --vl-charcoal: #17151B;
  --vl-plum:     #1E1922;
  --vl-ash:      #2E2833;

  /* Ink (text) */
  --vl-ivory:     #F4EFE6;
  --vl-ivory-dim: #E2DACD;
  --vl-muted:     #A79FB2;
  --vl-muted-2:   #7C7489;

  /* Accents */
  --vl-oxblood:    #8A1F2E;
  --vl-oxblood-lt: #B23646;
  --vl-oxblood-dk: #5A1220;
  --vl-gold:       #C9A227;
  --vl-gold-lt:    #E3C25C;

  /* Lines */
  --vl-line:      rgba(244,239,230,0.10);
  --vl-line-2:    rgba(244,239,230,0.05);
  --vl-line-gold: rgba(201,162,39,0.28);

  /* Shadows */
  --vl-shadow-sm: 0 2px 10px rgba(0,0,0,.45);
  --vl-shadow:    0 18px 44px -18px rgba(0,0,0,.85);
  --vl-shadow-lg: 0 44px 90px -30px rgba(0,0,0,.92);

  /* Type */
  --vl-font-display: 'Playfair Display', Georgia, serif;
  --vl-font-body:    'Inter', -apple-system, sans-serif;
  --vl-font-serif:   'Cormorant Garamond', Georgia, serif;

  /* Spacing — 4px grid */
  --vl-s-1: 4px;   --vl-s-2: 8px;   --vl-s-3: 12px;
  --vl-s-4: 16px;  --vl-s-5: 24px;  --vl-s-6: 32px;
  --vl-s-7: 44px;  --vl-s-8: 64px;  --vl-s-9: 96px;
  --vl-s-10: 132px; --vl-s-11: 176px;

  /* Layout */
  --vl-container: 1280px;
  --vl-container-narrow: 860px;
  --vl-gutter: clamp(20px, 5vw, 64px);

  /* Motion */
  --vl-ease: cubic-bezier(.22,.61,.36,1);
  --vl-ease-out: cubic-bezier(.16,1,.3,1);
  --vl-dur: .55s;
}
```

### Overriding them in a child theme

```css
/* your-theme/style.css */
:root {
  --vl-oxblood: #2F4858;   /* a cold slate instead of oxblood */
  --vl-gold:    #D4A574;   /* warmer gold */
}
```

### Overriding them in the Customizer

The Customizer writes an inline `<style id="nocturne-vars">` block on
`wp_head`, which lands after the stylesheet — so it wins without needing
`!important`.

---

## 3. The hero slider

### The anatomy

```
.vl-hero                    overflow:hidden, position:relative
└── .vl-hero__stage         width:100vw; margin-left:calc(50% - 50vw)   ← BREAKOUT
    └── .vl-hero__track     display:flex; transform:translate3d(...)
        └── .vl-slide       flex:0 0 100%
            ├── .vl-slide__media   absolute, inset:0  ← NO PADDING
            ├── .vl-slide__scrim   gradient overlay
            └── .vl-slide__content ← HAS the gutter
```

### The rule that matters

**The media layer has zero horizontal padding. The content layer keeps the
gutter.** If you collapse both, the text touches the screen edge and becomes
unreadable. If you pad both, the slider stops being edge-to-edge.

```css
@media (max-width: 900px) {
  .vl-hero__stage,
  .vl-hero__track,
  .vl-slide,
  .vl-slide__media { padding-inline: 0 !important; }

  .vl-slide__content {
    padding: calc(var(--vl-header-h) + 26px) var(--vl-gutter) 104px;
  }
}
```

### JavaScript API

```js
// Re-initialise after AJAX content is injected
window.Nocturne.init();

// Access the slider instance (the module keeps its state private,
// so this is the supported surface)
const track = document.querySelector('.vl-hero__track');
track.style.transform;   // e.g. "translate3d(-100%, 0, 0)"
```

### Adding a slide programmatically

```js
const slide = document.createElement('article');
slide.className = 'vl-slide';
slide.dataset.duration = '7';
slide.innerHTML = `
  <div class="vl-slide__media">
    <video autoplay muted loop playsinline poster="${poster}">
      <source src="${mp4}" type="video/mp4">
    </video>
  </div>
  <div class="vl-slide__scrim"></div>
  <div class="vl-slide__content">
    <div class="vl-slide__inner">
      <span class="vl-slide__kicker"><i></i><span>Kicker</span></span>
      <h2 class="vl-slide__title">Title</h2>
      <p class="vl-slide__excerpt">Excerpt</p>
      <div class="vl-slide__cta">
        <a href="#" class="vl-btn">Button</a>
      </div>
    </div>
  </div>`;

document.querySelector('.vl-hero__track').appendChild(slide);
```

> Remember to add a matching dot and re-run `window.Nocturne.init()`.

### Video requirements

| Requirement | Why |
|---|---|
| MP4 / H.264 | Universally supported |
| `muted` | Browsers block autoplay with sound |
| `playsinline` | iOS plays inline instead of fullscreen |
| A poster image | Prevents a black box on slow connections |
| Under ~8 MB | Mobile data is expensive |
| 8–15 seconds | Long enough to land, short enough to hold attention |

---

## 4. WooCommerce hooks

### Product meta

```php
// The series line shown above the title on a product card
update_post_meta( $product_id, '_nocturne_series', 'Ashgate Series · Book One' );
```

### Filters available

| Hook | Purpose |
|---|---|
| `woocommerce_post_class` | Adds `.vl-book` to every product in the loop |
| `woocommerce_before_shop_loop_item_title` | Prints the series line |
| `woocommerce_before_add_to_cart_button` | Replaces the quantity input with `.vl-qty` |
| `woocommerce_get_price_html` | Price markup (extend as needed) |
| `wpcf7_validate_contact_form` | Runs the honeypot check |

### Templates

Override any WooCommerce template by creating
`template/woocommerce/<filename>.php` — WordPress will use yours over the
plugin's.

---

## 5. Extending the design system

### A new component

```css
/* 1. Tokens first */
:root {
  --vl-accent-3: #6B8E9E;
}

/* 2. Component, BEM-ish */
.vl-card {
  background: var(--vl-charcoal);
  border: 1px solid var(--vl-line-2);
  border-radius: var(--vl-radius-lg);
  padding: var(--vl-s-5);
  transition: border-color .3s var(--vl-ease);
}
.vl-card:hover { border-color: var(--vl-line-gold); }
.vl-card__title { font-family: var(--vl-font-display); font-size: 1.2rem; }
.vl-card__meta { color: var(--vl-muted); font-size: .82rem; }
```

### A new animation

Always use the existing easing tokens and respect reduced motion:

```css
.vl-reveal {
  opacity: 0;
  transform: translateY(28px);
  transition: opacity .9s var(--vl-ease-out), transform .9s var(--vl-ease-out);
}
.vl-reveal.is-in { opacity: 1; transform: none; }

@media (prefers-reduced-motion: reduce) {
  .vl-reveal { opacity: 1; transform: none; }
}
```

### A new JS behaviour

```js
function initMyThing() {
  const els = $$('.vl-my-thing');
  if (!els.length) return;          // always guard
  // ...
}

// register it in boot()
```

---

## 6. Elementor integration

The theme is designed so Elementor inherits its typography. To match it
exactly, set these in **Site Settings → Global Colors / Global Typography**:

| Swatch | Value |
|---|---|
| Primary | `#8A1F2E` |
| Secondary | `#C9A227` |
| Text | `#F4EFE6` |
| Accent | `#A79FB2` |
| Background 1 | `#0B0A0D` |
| Background 2 | `#17151B` |

| Typography | Font | Weight |
|---|---|---|
| H1–H6 | Playfair Display | 600 |
| Body | Inter | 400 |
| Quote | Cormorant Garamond | 500 italic |
| Label | Inter | 600, uppercase, 0.2em tracking |

A ready-to-import JSON of these values lives at
`docs/elementor-home-kit.json`.

---

## 7. Performance notes

- The theme ships **no external dependencies** — keep it that way
- `nocturne.js` is deferred and loaded in the footer
- Images use native `loading="lazy"`; the hero image uses `fetchpriority="high"`
- The slider pauses when off-screen — do not remove the `IntersectionObserver`
- If you add a webfont, preload it:
  ```php
  add_action( 'wp_head', function () {
      echo '<link rel="preload" as="font" type="font/woff2" href="…" crossorigin>';
  }, 1 );
  ```

---

## 8. Debugging

```php
// Turn on WordPress debug mode
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', true );
define( 'WP_DEBUG_LOG', true );
```

```js
// Inspect the slider state
document.querySelector('.vl-hero__track').style.transform
document.querySelectorAll('.vl-slide.is-active').length
```

```css
/* Visually debug the hero bleed — this should paint the media edge-to-edge */
.vl-hero__stage { outline: 2px solid red; }
.vl-slide__media { outline: 2px solid lime; }
.vl-slide__content { outline: 2px solid cyan; }
```

If the media outline does **not** touch both screen edges, something has added
horizontal padding to the stage, track, slide or media layer. Check for an
Elementor or page-builder section wrapper.

---

## 9. Common modifications

| Task | Where |
|---|---|
| Change the palette | Customizer, or `:root` in the stylesheet |
| Change the fonts | `nocturne_fonts()` in `functions.php` |
| Change the logo | Customizer → Site Identity |
| Add a hero slide | Customizer → Nocturne → Hero slides |
| Change the footer | `footer.php` |
| Change the header | `header.php` |
| Add a WooCommerce field | `inc/demo-content.php` or a `woocommerce_product_options` hook |
| Restyle the shop | `assets/css/modules.css` |

---

<div align="center">

**Nocturne** — extend it, don't fight it.

</div>
