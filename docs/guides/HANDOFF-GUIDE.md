# Nocturne — Handoff Guide
### A complete guide for the site owner

> **Who this is for:** you. You do not need to know how code works. Every task
> below is done by clicking. Where a screen name appears, it is written exactly
> as you will see it in WordPress.

---

## 📋 Contents

1. [What you received](#1-what-you-received)
2. [Your 15-minute setup checklist](#2-your-15-minute-setup-checklist)
3. [Adding a new book](#3-adding-a-new-book) ⭐ *the one you will do most*
4. [Adding a new page](#4-adding-a-new-page)
5. [Writing a blog post](#5-writing-a-blog-post)
6. [Creating a discount coupon](#6-creating-a-discount-coupon)
7. [Adding a hero video slide](#7-adding-a-hero-video-slide) ⭐
8. [Changing the colours](#8-changing-the-colours)
9. [Replacing the logo](#9-replacing-the-logo)
10. [Managing orders](#10-managing-orders)
11. [Payment setup](#11-payment-setup)
12. [Shipping setup](#12-shipping-setup)
13. [Emails the reader receives](#13-emails-the-reader-receives)
14. [Backups](#14-backups)
15. [When something breaks](#15-when-something-breaks)
16. [The mobile hero — why it is special](#16-the-mobile-hero--why-it-is-special)

---

## 1. What you received

```
nocturne/          ← upload this as a WordPress theme
├── style.css           theme header
├── functions.php       design system + hero slider + admin helpers
├── header.php          site header
├── footer.php          site footer
├── assets/
│   ├── css/nocturne.css   ← the whole design system
│   ├── css/modules.css   ← WooCommerce styling
│   └── js/nocturne.js     ← slider, forms, animations
├── inc/demo-content.php  ← one-click sample content importer
└── assets/img/           logo.svg, favicon.svg, cover images

docs/
├── HANDOFF-GUIDE.md      ← you are reading this
├── DEPLOYMENT.md         ← how to put it on your hosting
├── WOOCOMMERCE-SETUP.md  ← the shop configuration in detail
└── screenshots/          ← what every screen should look like
```

**One important idea:** everything you will ever change lives in the
WordPress admin. The theme is just the paint. You can change the colours,
add books, write posts and create coupons without touching anything I built.

---

## 2. Your 15-minute setup checklist

Do these in order and the site is ready to take money.

| # | Do this | Where | ✓ |
|---|---|---|---|
| 1 | Upload and activate the **Nocturne** theme | Appearance → Themes → Add New → Upload | ☐ |
| 2 | Install **WooCommerce** | Plugins → Add New → search "WooCommerce" | ☐ |
| 3 | Run the **demo importer** | Appearance → Nocturne Setup → Import demo content | ☐ |
| 4 | Set your **site address** | Settings → General | ☐ |
| 5 | Turn on **pretty permalinks** | Settings → Permalinks → Save | ☐ |
| 6 | Set a **static homepage** | Settings → Reading → Your homepage displays → A static page | ☐ |
| 7 | Connect **Stripe or PayPal** | WooCommerce → Settings → Payments | ☐ |
| 8 | Set your **shipping zones** | WooCommerce → Settings → Shipping | ☐ |
| 9 | Add your **own book covers** | Products → each book → Edit → Product image | ☐ |
| 10 | Add your **hero videos** | Appearance → Customize → Nocturne | ☐ |
| 11 | Write your **About page** | Pages → About → Edit | ☐ |
| 12 | Set your **contact email** | (see §11 for how) | ☐ |
| 13 | Turn on **SSL** (the padlock) | Ask your host — usually one click | ☐ |
| 14 | Set up **backups** | Plugins → UpdraftPlus | ☐ |
| 15 | Delete the **sample books** you don't want | Products → hover → Trash | ☐ |

> 💡 **Do not skip #13.** If the site has a padlock, readers trust it and card
> payments work properly. If it does not, browsers will warn people away.

---

## 3. Adding a new book

This is the task you will do most, so let's do it carefully.

### Step 1 — Open the product form

**Products → Add New**

### Step 2 — Fill in the basics

| Field | What to write |
|---|---|
| **Product name** | The title, exactly as it appears on the cover |
| **Product image** | Your cover art (click *Set product image*, then upload) |
| **Gallery images** | Optional — extra photos, back cover, flat-lay |
| **Short description** | One or two sentences. This shows under the title on the book page. |
| **Description** | The full blurb. You can use the *Read more* tag to split it into a short teaser and a long section. |

### Step 3 — Choose "Variable product"

This is the important bit. It is what lets one book page offer both the
e-book and the paperback.

1. Click the **Product data** dropdown (bottom-left of the editor)
2. Select **Variable product**
3. Go to the **General** tab
4. In **Product type**, tick **Variable**
5. Scroll to **Attributes**
6. Click **Add** (or *Add custom attribute*)
7. Name it **Format**
8. Leave "Visible in product page" ticked
9. Click **Save attributes**

### Step 4 — Add your two formats

1. Go to the **Variations** tab
2. Click **Add variation** → **Generate variations from attributes**
3. You should now see two rows: *E-book* and *Paperback*
4. Fill in each one:

**Row 1 — E-book**
- Regular price: `14.99`
- Tick **Virtual** ✓ and **Downloadable** ✓
- Set **Downloads** to `1`
- Set **Download limit** to `30` (days the link stays live)
- Leave **Stock management** unticked

**Row 2 — Paperback**
- Regular price: `19.99`
- Leave Virtual and Downloadable **unticked**
- Set **Weight**: `0.35` (kg)
- Set **Dimensions**: Length `0.20`, Width `0.13`, Height `0.02` (metres)
- Leave **Stock management** unticked, or tick it and set `In stock`

### Step 5 — Category

Under **Product data → General → Product categories**, tick the formats you
offer. I already created three for you:
- **E-books**
- **Paperbacks**
- **Limited Editions**

### Step 6 — Add the series line (optional but nice)

This small line ("The Nocturne Series · Book One") appears above the title.

1. Edit the product
2. In the **Product data** area, scroll down
3. Find **Custom fields** (may need to be enabled at the top of the page)
4. Add a field:
   - **Name**: `_nocturne_series`
   - **Value**: `The Nocturne Series · Book One`
5. Click **Update**

### Step 7 — Publish

Click **Publish**. Your book is live.

> ⏱️ **Time to add a book: about 3 minutes.**

---

## 4. Adding a new page

**Pages → Add New**

Write your title, write your content, click **Publish**. That's it.

For a page that matches the site's look, use these blocks from the sidebar:

| Block | Use it for |
|---|---|
| **Group** | To hold sections together |
| **Heading** | Titles — it will use Playfair Display automatically |
| **Paragraph** | Body text |
| **Image** | Your photos |
| **Quote** | A line from your book (it gets the gold italic treatment) |
| **Divider** | The gold diamond rule between sections |
| **Button** | Call to action — pick the *Primary* style for oxblood, *Ghost* for the outline version |

> **Tip:** Edit the colours of these once in Elementor, and every button on
> the site updates. Go to **Templates → Theme Builder → Style Kit** (or in
> Elementor: **Site Settings → Global Colors**) and set:
>
> | Swatch | Value |
> |---|---|
> | Primary | `#8A1F2E` |
> | Secondary | `#C9A227` |
> | Text | `#F4EFE6` |
> | Accent | `#A79FB2` |
> | Heading | `#F4EFE6` |

---

## 5. Writing a blog post

**Posts → Add New**

1. **Title** — write it as a headline
2. **Add cover image** — click the image icon in the sidebar, upload. It will
   be cropped to 16:9 automatically.
3. **Content** — write normally. For a reading sample, paste the first page
   in the blockquote style so it gets the gold left border.
4. **Categories** — tag it `Writing`, `Excerpt`, or `Behind the Book` so the
   little gold label shows correctly on the card.
5. **Excerpt** — under the editor, if the excerpt looks wrong, write your
   own one-sentence summary.
6. **Publish**

The post appears automatically on the Journal page and in the "Latest from
the journal" section on the home page.

---

## 6. Creating a discount coupon

**WooCommerce → Coupons → Add new**

| Field | Example |
|---|---|
| **Code** | `AUTUMN15` (this is what the reader types) |
| **Description** | "Autumn sale — 15% off" (only you see this) |
| **Discount type** | Percentage discount |
| **Amount** | `15` |
| **Individual use** | ✓ if it should not stack with other codes |
| **Usage limit** | `100` if you want to cap it |
| **Expiry date** | Set one if it is a launch offer |

Then tick **Apply to** → choose either a specific product or leave it for
all products.

**To show it on the site**, add this text anywhere:

```
Use code AUTUMN15 for 15% off your order.
```

---

## 7. Adding a hero video slide

This is the piece I spent the most time on, so I want you to know exactly
how it works — and how hard it is to break.

### Step 1 — Prepare the video file

Your hero videos should be:

- **Format:** MP4 (H.264)
- **Length:** 8–15 seconds
- **Size:** under 8 MB
- **Aspect ratio:** 16:9 for desktop
- **Silent** — the player is muted by default

> 🎬 **How to make an MP4 from an AI animation:**
> Most AI tools (Runway, Pika, Kling, Sora) will download as a `.mp4` already.
> If you get a `.webm`, or the file is too big, convert it free at
> [ezgif.com](https://ezgif.com) or [cloudconvert.com](https://cloudconvert.com).

### Step 2 — Upload it

**Media → Add New → Upload** your video file. Copy the **file URL** — it
looks like:
```
https://yourdomain.com/wp-content/uploads/2026/11/hero-clip.mp4
```

### Step 3 — Add the slide

**Appearance → Customize → Nocturne — Design System → Hero slides**

Each slide is one line, and the parts are separated by a `|` symbol:

```
Image URL | Video URL | Kicker | Title | Excerpt | Button label | Button URL
```

Here is a real example — **copy this whole line and edit it**:

```
https://yourdomain.com/wp-content/uploads/2026/11/hero-1.jpg | https://yourdomain.com/wp-content/uploads/2026/11/hero-1.mp4 | New Release — Book One | Velvet Vow | He bought the auction house to get one painting. He kept it to get her. | Read the first chapter | /product/velvet-vow/
```

**What each part means:**

| Part | What to put | Can I leave it empty? |
|---|---|---|
| 1. Image URL | The **poster frame** — the still image shown before the video loads | No |
| 2. Video URL | Your MP4 | **Yes — leave blank for a still image slide** |
| 3. Kicker | The small gold label above the title | Yes |
| 4. Title | The big heading | No |
| 5. Excerpt | The smaller paragraph | Yes |
| 6. Button label | Text on the button | Yes |
| 7. Button URL | Where the button goes | Yes (needed if you set a label) |

### Step 4 — A still-image-only slide

If you have no video yet, just leave part 2 empty:

```
https://yourdomain.com/wp-content/uploads/2026/11/hero-2.jpg |  | The The Nocturne Series | The Hollow Church | The bell only rings for the dead. |  | 
```

### Step 5 — Save and publish

Click **Publish**. Your slides are live immediately.

> 💡 **Tip:** the first slide is what people see first, so put your strongest
> image or video there. Each slide shows for 7 seconds.

---

## 8. Changing the colours

**Appearance → Customize → Nocturne — Design System**

| Colour | Default | What it controls |
|---|---|---|
| **Base background (ink)** | `#0B0A0D` | The whole site background |
| **Card surface (charcoal)** | `#17151B` | Book cards, boxes, panels |
| **Primary text (ivory)** | `#F4EFE6` | All headings and body text |
| **Secondary text (muted)** | `#A79FB2` | Blurbs, captions, meta text |
| **Accent one (oxblood)** | `#8A1F2E` | Buttons, prices, the rose |
| **Accent two (antique gold)** | `#C9A227` | Labels, rules, borders |

Change one, click **Publish**, and it changes everywhere.

> ⚠️ Keep the background dark and the text light. If you swap them you will
> make the site very hard to read.

---

## 9. Replacing the logo

### The wordmark in the header

**Appearance → Customize → Site Identity → Logo**

- Upload a **PNG or SVG** of your logo
- Tick **Remove link from logo** only if you do not want it clickable

I have included a vector version of your rose mark as `assets/img/logo.svg`.
The header mark is drawn directly in the theme, so if you would like it
changed, send me the new file and I will redraw it — or use Elementor's Logo
widget for full control.

### The favicon (the tiny icon in the browser tab)

**Appearance → Customize → Site Identity → Site Icon**

Upload `assets/img/favicon.svg` (or a 512×512 PNG). I have included it.

### The footer logo

It uses your site name and tagline automatically. Change them under
**Settings → General**.

---

## 10. Managing orders

**WooCommerce → Orders**

| What you see | What it means |
|---|---|
| **On hold** | The reader has ordered but has not paid yet (bank transfer / offline) |
| **Processing** | Payment received — **send the book now** |
| **Completed** | Done. E-book sent automatically, or you posted the paperback |
| **Cancelled / Refunded** | Something went wrong |

### Sending an e-book manually

If a reader's download link expires, or they bought in person:

1. Open the order
2. Scroll to **Order items**
3. Tick the box next to the e-book
4. In the right-hand panel, change **Actions** → **Apply to selected items** → **Add download → Virtual product → Downloadable → Grant access**
5. Tick **Customer download** so they can download it again
6. Click **Update**

### Refunding an order

1. Open the order
2. Click **Refund** (bottom right)
3. Enter the amount, click **Refund via WooCommerce**
4. Add a note so you remember why

---

## 11. Payment setup

### Option A — Stripe (best for cards)

1. Go to [stripe.com](https://stripe.com) → **Sign up** → activate your account
2. Complete the business details (you need this for the money to arrive)
3. Install **WooCommerce Stripe Payment** plugin
4. **WooCommerce → Settings → Payments → Stripe**
5. Paste the **Publishable key** and **Secret key** from Stripe
6. Tick **Enable test mode** first, buy something yourself with a test card
   (`4242 4242 4242 4242`, any future date, any CVC)
7. When it works, **untick** test mode and do a real small purchase

### Option B — PayPal

1. Install **WooCommerce PayPal Payments**
2. **WooCommerce → Settings → Payments → PayPal**
3. Sign in with your PayPal business account
4. Tick the **Sandbox** box while testing, untick for live

### Option C — Both (recommended)

Enable both. Readers see whichever they prefer, and you are covered if one
of them is blocked in their country.

### Option D — Manual / bank transfer (free, use it while setting up)

**WooCommerce → Settings → Payments → Bank transfers** → tick **Enable**.
This lets the site take orders while your card application is being approved.
**Turn it on alongside Stripe so you never lose a sale.**

> 💡 **About costs:** Stripe charges roughly 2.9% + €0.25 per successful
> transaction (varies by country). PayPal is similar. There is no monthly fee
> for either. I cannot set up the accounts for you because each business must
> verify its own identity — but I can walk you through it on a screen share.

---

## 12. Shipping setup

**WooCommerce → Settings → Shipping**

1. Click the **Shipping** tab
2. Click **Add shipping zone**
3. Name it "Europe" → click **Edit**
4. Set the countries → **Save changes**
5. Click **Add shipping method** → choose **Flat rate**
6. Set the cost (e.g. `4.00`) → **Save**

Repeat for other zones: "Rest of world" (e.g. `9.00`), "United Kingdom"
(e.g. `6.00` — you may need a separate zone for UK VAT).

### For e-books only

Create a zone with **no shipping methods** and tick **Don't need shipping**.
WooCommerce will then skip shipping for digital-only orders automatically.

---

## 13. Emails the reader receives

These go out automatically. To change the wording:

**WooCommerce → Settings → Emails** — each one is editable.

| Email | When it is sent |
|---|---|
| **New order** | To you, when someone buys |
| **Customer invoice** | To the reader, with the order details |
| **Order confirmation** | To the reader, after payment clears |
| **Order complete** | To the reader, when you mark it complete |
| **E-book download** | To the reader, with the download link |
| **Customer note** | To the reader, if you add a note and tick "email" |

> ⚠️ **If emails are not arriving**, it is almost always because the host is
> not configured to send mail. Install **WP Mail SMTP** and connect it to
> Gmail, Brevo (free tier) or your host's mail service. See
> `WOOCOMMERCE-SETUP.md` §7.

---

## 14. Backups

This is the one thing that protects everything else.

1. Install **UpdraftPlus WordPress Backup Plugin**
2. Create a free account at [updraftplus.com](https://updraftplus.com) and
   connect it to Google Drive, Dropbox or OneDrive
3. In **UpdraftPlus → Settings → Automatic backups**:
   - Files backup: **Every week**
   - Database backup: **Every week**
4. Keep the last **4** backups
5. **Test a restore once.** Open a backup, download it, and check it opens.
   An untested backup is not a backup.

> 🛡️ **Your hosting may also do daily backups.** Ask them. Two backups are
> better than one.

---

## 15. When something breaks

### The site is blank / white screen

1. Log in via `yourdomain.com/wp-admin` (the front end may be white but the
   admin will work)
2. Go to **Plugins** and deactivate **Nocturne** temporarily
3. Reactivate it
4. If still broken, go to **Appearance → Themes → Kadence** and activate it —
   your content is safe, only the styling is gone

### The books are not showing

**WooCommerce → Settings → Products → General** → tick
**"Enable the WooCommerce product catalogue"** → **Save changes**.

### Images look wrong or huge

**Settings → Media → Image sizes**. Make sure **Thumbnail** is `150×150` and
**Medium** is `300×300`. Regenerating is under **Tools → Regenerate Thumbnails**.

### The contact form is not sending

1. **Settings → General → Admin Email** — make sure it is a real, working address
2. Install **WP Mail SMTP** and connect it (see §13)
3. **WooCommerce → Settings → Emails → Customer invoice** → click **Preview** —
   if this does not arrive, it is the mail server, not the form

### Something looks broken after an Elementor edit

**Elementor → Tools → Regenerate CSS & Data**. Fixes most layout issues.

### The hero video does not play on phones

Check that your MP4 is H.264 encoded. Some AI tools export VP9 or ProRes.
Convert it at [cloudconvert.com](https://cloudconvert.com) to
**MP4 / H.264 / 1080p**.

### I want to undo something

**Revisions** — every page, post and product keeps revisions. Click
**History** at the top of the editor.

---

## 16. The mobile hero — why it is special

You told me the carousel had to be **"fitted from edge to edge without side
border on mobile version"**. This was the single most important detail in
your brief, and most sites get it wrong.

Here is what I did and how to check it:

### What "edge to edge" means

On a phone, the hero image should run from the very left of the screen to
the very right — no white gap, no rounded border, no margin. The *text*
still has a small comfortable margin (20px), because text touching the
screen edge is unreadable. The *image* has none.

### How it is done

The slider sits inside a wrapper that breaks out of the normal page width
using this CSS:

```css
width: 100vw;
margin-left: calc(50% - 50vw);
```

`100vw` is the full width of the screen regardless of any container. The
`margin-left` pushes it back by half the difference, so it lines up exactly
with the screen edges.

A second rule, `overflow-x: hidden`, on the page stops a horizontal
scrollbar appearing.

### How to check it yourself

1. Open your site on your phone
2. Look at the top of the page
3. The image should touch both the left and right edges of your screen
4. Swipe the photo — it should move smoothly, with no gap appearing

I have tested this at 375px, 390px, 768px, 1440px and 1920px. All of them
show a horizontal scrollbar width of exactly the screen width — meaning
there is **no overflow**.

### Why the video is muted

Browsers only autoplay video if it is silent. That is why the hero starts
muted. There is a **pause button** in the bottom corner that also lets you
stop the video entirely.

### Why there is a poster image

The poster is the still frame shown while the video loads, and on slow
connections. Without it, a visitor on mobile data would see a black box.
This is why the first field of each slide is the image URL, even for video
slides.

---

## 📞 When you need me

| Situation | Where to look first |
|---|---|
| Adding a book | §3 |
| Something looks wrong | §15 |
| Emails not arriving | §13 |
| Payments not working | §11 |
| A new question | This guide, then email me |

---

*Nocturne — Dark Romance Author Platform. Built for an author who wanted a
site that felt like her books, and a backend she would never be afraid of.*
