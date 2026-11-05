# Architecture

How Nocturne is put together, and why.

---

## The layers

```
┌─────────────────────────────────────────────────────┐
│  WordPress core                                     │
├─────────────────────────────────────────────────────┤
│  Parent theme (Kadence / GeneratePress / Astra)      │
│  → provides layout, the block editor, the admin     │
├─────────────────────────────────────────────────────┤
│  Nocturne child theme                                │
│  ┌───────────────────────────────────────────────┐  │
│  │ functions.php — wiring only                   │  │
│  │   enqueue · customizer · shortcode · admin    │  │
│  ├───────────────────────────────────────────────┤  │
│  │ assets/css/nocturne.css — the design system   │  │
│  │   tokens · layout · components · responsive   │  │
│  ├───────────────────────────────────────────────┤  │
│  │ assets/css/modules.css — commerce & builder   │  │
│  │   WooCommerce · Elementor · blog             │  │
│  ├───────────────────────────────────────────────┤  │
│  │ assets/js/nocturne.js — behaviour only        │  │
│  │   slider · forms · reveal · cart · accord.   │  │
│  └───────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────┘
```

**The rule:** `functions.php` wires, CSS styles, JS behaves. A change that
belongs in one layer never leaks into another.

---

## Naming

| Thing | Convention | Example |
|---|---|---|
| CSS classes | `vl-` prefix + BEM | `.vl-book__title` |
| CSS custom properties | `--vl-` | `--vl-oxblood` |
| PHP functions | `nocturne_` | `nocturne_enqueue()` |
| PHP classes | `Nocturne_` | `Nocturne_Slider` |
| JS functions | `init<Thing>` | `initHeroSlider()` |
| Text domain | `nocturne` | `esc_html__( '…', 'nocturne' )` |
| Options | `nocturne_` prefix | `nocturne_footer_about` |
| Post meta | `_nocturne_` prefix | `_nocturne_series` |
| CSS handle | `nocturne` | `wp_enqueue_style( 'nocturne' )` |

The `vl-` prefix survives from the project's first iteration. It is internal
and never appears to end users.

---

## The design token pipeline

```
Author changes a colour in the Customizer
        ↓
functions.php writes an inline <style id="nocturne-vars"> on wp_head
        ↓
That block lands AFTER the stylesheet → it wins on specificity
        ↓
Every component using var(--vl-*) updates at once
        ↓
No component hard-codes a hex value
```

**Rule:** if you find a hex literal in a component rule, it belongs in
`:root` instead.

---

## The hero slider

The most technically demanding part. See
[`CUSTOMISING.md`](guides/CUSTOMISING.md#2-the-hero-slider) for the full
anatomy.

The core idea:

```css
.vl-hero__stage {
  width: 100vw;
  margin-left: calc(50% - 50vw);
}
```

The stage escapes its container to the physical screen edge. The **media**
layer inherits zero padding; the **content** layer keeps the gutter.

The JavaScript (`initHeroSlider`) is a self-contained module with:

- an internal index and autoplay timer
- `goTo(i)` as the single state-transition function
- `IntersectionObserver` for off-screen pausing
- touch, keyboard and click input paths
- `prefers-reduced-motion` handling

---

## File responsibilities

| File | May contain | Must not contain |
|---|---|---|
| `functions.php` | Enqueues, hooks, filters, shortcodes | CSS rules, markup |
| `nocturne.css` | Tokens, layout, components | PHP, inline styles |
| `modules.css` | WooCommerce/Elementor overrides | Core component styles |
| `nocturne.js` | Behaviour, event handling | Layout, visual styles |
| `header.php` / `footer.php` | Structural markup | Business logic |
| `inc/demo-content.php` | Importer, admin page | Styling |

---

## Extension points

| Hook | Type | Purpose |
|---|---|---|
| `nocturne_footer_about` | option | The footer description |
| `nocturne_email` | option | The contact email |
| `vl_hero_slides` | option | The Customizer slide list |
| `vl_ink` … `vl_gold` | options | The six palette colours |
| `_nocturne_series` | post meta | The series line on a book |
| `woocommerce_post_class` | filter | Adds `.vl-book` to loop items |
| `wpcf7_validate_contact_form` | filter | Spam protection |

---

## Testing strategy

| Layer | What | When |
|---|---|---|
| PHP lint | Syntax | Every commit (CI) |
| WPCS | WordPress standards | Every commit (CI) |
| Secret scan | No leaked credentials | Every commit (CI) |
| CSS/JS sanity | Balanced braces, parses, no placeholders | Every commit (CI) |
| Browser QA | Overflow, hero bleed, a11y, interactions | Before a release |
| Manual | Real order, real email, real phone | Before a release |

---

<div align="center">

**Clean layers, clear names, one source of truth for every value.**

</div>
