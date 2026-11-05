# Deployment — Nocturne to Your Hosting

A step-by-step guide for putting the site live on your own hosting.
**Time required: 60–90 minutes** (most of it is waiting for the host to
provision things).

---

## Before you start — check these three things

| Check | Why it matters | Minimum |
|---|---|---|
| **Hosting is ready** | You need somewhere to install WordPress | PHP **8.1+**, MySQL **5.7+** (or MariaDB 10.4+), 2 GB RAM, 25 GB disk |
| **Domain is pointed at the host** | The site only works if the domain points here | An **A record** to your host's IP |
| **SSL is available** | Cards will not work properly without it | Let's Encrypt (free) — every host has it |

> **Do these first.** If the domain is not pointing at the host, nothing you
> install will show up on the right address, and you will spend an afternoon
> confused.

---

## Phase 1 — Install WordPress

1. Log into your hosting control panel (cPanel, Plesk, or your host's
   dashboard)
2. Find **"WordPress"** or **"Install WordPress"** under **Websites / Domains /
   AutoInstaller**
3. Choose:
   - **Domain:** `yourdomain.com` (not `www.` — you can redirect later)
   - **Directory:** `/` (root) — unless you already have a site there
   - **Version:** the newest stable
   - **Admin username:** something that is *not* `admin`
   - **Password:** a long one. Save it somewhere safe.
   - **Email:** your real address
4. Click **Install**

**Your login:** `https://yourdomain.com/wp-admin`

### 🔐 Immediately after logging in

1. **Settings → General**
   - Site Title: your name or pen name
   - Tagline: `Dark Romance Author` (this appears under the logo)
   - **Admin Email**: a real address you check
   - Tick **"Users can register"** → **Untick** it (unless you want readers
     creating accounts)
2. **Settings → Permalinks** → choose **Post name** → **Save Changes**
   *(this is what turns on `/books/velvet-vow/` style URLs)*
3. **Users → Profile** → set your display name and a **gravatar**
4. **Users → All Users** → **Edit** your own profile → tick
   **"Dismiss this notice"** under "Screen Options" so you don't get nagged

---

## Phase 2 — Install the theme

### Option A — by FTP/SFTP (recommended, you can send it to your host)

1. Zip the `nocturne` folder so you have `nocturne.zip`
2. **Appearance → Themes → Add New → Upload Theme**
3. Click **Choose File** → select the zip → **Install Now**
4. Click **Activate**

> **Note:** the theme declares `Template: kadence`. If you do not have Kadence
> installed, WordPress will offer to install the parent theme automatically.
> If you would rather use **GeneratePress** instead, open `style.css` and
> change `Template: kadence` to `Template: generatepress`. The theme works
> with Kadence, GeneratePress, Astra or OceanWP.

### Option B — upload by FTP

1. Install FileZilla (free) on your computer
2. Connect using the FTP details from your host
3. Drag the `nocturne` folder into `/public_html/`
4. Go to **Appearance → Themes** → activate **Nocturne**

---

## Phase 3 — Install the plugins

**Plugins → Add New**, search for each, install and activate:

### Required
| Plugin | Purpose | Free? |
|---|---|---|
| **WooCommerce** | The shop | ✅ |
| **Elementor** | Page building | ✅ |
| **WPForms Lite** or **Contact Form 7** | Contact form | ✅ |
| **WP Mail SMTP** | Makes email actually deliver | ✅ |
| **Yoast SEO** or **Rank Math** | Search engines | ✅ |
| **UpdraftPlus** | Backups | ✅ |
| **Complianz** or **CookieYes** | GDPR cookie banner (EU) | ✅ |

### Optional but recommended
| Plugin | Purpose |
|---|---|
| **Safe SVG** | Lets you upload your logo as SVG (vector) |
| **ShortPixel** or **WP Rocket** | Speed — if the site feels slow |
| **Mailchimp for WooCommerce** | Newsletter capture if you have a list |

### Optional for the payment gateway you choose
- **WooCommerce Stripe Payment** — for cards
- **WooCommerce PayPal Payments** — for PayPal

---

## Phase 4 — Import the demo content

**Appearance → Nocturne Setup → Import demo content → Import**

This creates:
- ✅ 3 books, each with **E-book** and **Paperback** variations
- ✅ 1 full blog post with real writing
- ✅ **Books**, **About**, **Contact**, **FAQ**, **Journal** pages
- ✅ A primary navigation menu
- ✅ A sample coupon (`WELCOME10`)

> It is safe to run this twice — it will not create duplicates.

---

## Phase 5 — Set up the shop

Follow `WOOCOMMERCE-SETUP.md` in this folder. The short version:

1. **WooCommerce → Settings → General** → set your address, currency
2. **WooCommerce → Settings → Products** → tick **"Enable the product catalogue"**
3. **WooCommerce → Settings → Shipping** → create your zones
4. **WooCommerce → Settings → Payments** → enable Stripe and/or PayPal
5. **WooCommerce → Settings → Emails** → read them, adjust the wording
6. **WooCommerce → Settings → Advanced** → tick **"Enable guest checkout"**

### The general → product settings screen

```
☑ Enable the WooCommerce product catalogue
☑ Enable reviews on products
☑ Display product weight
☑ Display product dimensions
☐ Enable product search
☐ Enable product filtering
```

---

## Phase 6 — Build the home page

### Step 1 — Set the homepage

**Settings → Reading → Your homepage displays → A static page** → select
**Home** → **Save Changes**.

If "Home" is not in the list, create it: **Pages → Add New**, title it
`Home`, publish, then come back.

### Step 2 — Edit it with Elementor

**Pages → Home → Edit with Elementor**

Build it in this order, top to bottom:

| Section | How |
|---|---|
| **1. Hero slider** | Use the **Nocturne Hero** widget (added by the theme) — or the shortcode `[nocturne_hero]` in a Shortcode widget |
| **2. Featured books** | A **WooCommerce Products** widget, 3 columns |
| **3. Author statement** | A **Text Editor** widget — the bio, centred, max-width 700px |
| **4. Quote** | A **Quote** widget or a Text Editor with the italic serif style |
| **5. Journal teaser** | A **Posts** widget, 3 posts |
| **6. Newsletter** | A Form widget or the shortcode `[nocturne_newsletter]` |
| **7. FAQ** | Text Editor or an accordion widget |

### Step 3 — Match the demo

The exact layout is in the `demo/` folder of this package. Open
`demo/index.html` in your browser and compare section by section.

**Key design values to match in Elementor's Style Kit:**

```
COLOURS
  Primary        #8A1F2E   oxblood
  Secondary      #C9A227   antique gold
  Text           #F4EFE6   ivory
  Accent         #A79FB2   muted
  Background 1   #0B0A0D   ink
  Background 2   #17151B   charcoal

TYPOGRAPHY
  H1–H6   Playfair Display  (600 / 700)
  Body    Inter             (400)
  Quote   Cormorant Garamond (italic 500)

SPACING
  Section padding   120px desktop / 64px mobile
  Content width     1280px
```

---

## Phase 7 — The hero videos

Follow **HANDOFF-GUIDE.md §7**. Short version:

1. **Media → Add New → Upload** your MP4s
2. Copy each file URL
3. **Appearance → Customize → Nocturne — Design System → Hero slides**
4. Paste one line per slide:
   ```
   Image URL | Video URL | Kicker | Title | Excerpt | Button label | Button URL
   ```
5. **Publish**

---

## Phase 8 — SSL

### Most hosts (one click)

1. Hosting panel → **SSL/TLS** or **Security → SSL**
2. Select your domain → **Issue / Activate**
3. Wait 5–30 minutes for the certificate to issue
4. Turn on **"Force HTTPS"** or **"Auto-SSL"**

### WordPress (if your host does not have it)

1. **Plugins → Add New** → search **"Really Simple SSL"**
2. Install, activate, follow the wizard
3. Let it run — it can take a few minutes

### Verify

Visit `https://yourdomain.com` — you should see a **padlock** 🔒 in the
address bar and the URL should start with `https://`.

---

## Phase 9 — Make email work

**This is the step most people skip, and it is why contact forms "don't work".**

1. Install **WP Mail SMTP**
2. Run its setup wizard
3. Choose a mailer:
   - **Brevo** (formerly Sendinblue) — 300 emails/day free ✅ *recommended*
   - **Gmail / Google Workspace** — if you use it
   - **Your host's SMTP** — ask them for the settings
4. Add **yourdomain.com** to the SPF and DKIM records it gives you
5. Send a test email from **WP Mail SMTP → Email Test**

> 🔑 Why this matters: by default WordPress uses PHP's `mail()` function,
> which most hosts block. Result: no order confirmations, no contact form
> notifications, no password resets. This one plugin fixes all of it.

---

## Phase 10 — Go live checklist

Run through this before you announce it:

**Content**
- [ ] Site title and tagline set
- [ ] Your logo uploaded and showing in the header
- [ ] Favicon set
- [ ] About page written with your real bio
- [ ] Contact page has your real email
- [ ] **Sample books replaced with your real books** (or the sample ones deleted)
- [ ] Cover images uploaded
- [ ] Prices correct
- [ ] At least one blog post published

**Shop**
- [ ] A test order placed end-to-end (use a real card, then refund yourself)
- [ ] The e-book download link arrives and works
- [ ] The order confirmation email arrives
- [ ] The "new order" email arrives in **your** inbox
- [ ] Shipping rates look right for a few different countries
- [ ] Guest checkout enabled
- [ ] Terms and Privacy pages written (use the WooCommerce template, then edit it)

**Technical**
- [ ] SSL working (padlock)
- [ ] Backups running, and you have tested a restore
- [ ] `yourdomain.com` loads (not just `www.yourdomain.com`)
- [ ] `www` redirects to the non-www (or vice versa) — pick one
- [ ] Site tested on a real phone
- [ ] Site tested in Chrome, Safari, Firefox and Edge

**Legal (if you are in the EU)**
- [ ] Cookie banner enabled and working
- [ ] Privacy policy written
- [ ] Imprint / legal notice page (Germany: *Impressum*; legally required)
- [ ] VAT settings correct in **WooCommerce → Settings → General**
- [ ] 18+ content warning shown on book pages

---

## Phase 11 — After launch

### Day 1
- [ ] Place a second test order from a different device
- [ ] Check the email again after 24 hours (some hosts delay delivery)
- [ ] Submit your sitemap to Google Search Console

### Week 1
- [ ] Install **Rank Math / Yoast** and write meta descriptions
- [ ] Add your books to **Google Books** and **Amazon Author Central**
- [ ] Set up **Google Analytics 4**
- [ ] Check Core Web Vitals at [pagespeed.web.dev](https://pagespeed.web.dev)

### Ongoing
- [ ] Weekly backups verified
- [ ] Plugin updates applied (test on staging first if you can)
- [ ] A new blog post every 2–4 weeks

---

## 🆘 Troubleshooting

| Problem | Cause | Fix |
|---|---|---|
| WordPress install page shows | Database credentials wrong | Re-run the installer with the right details from your host |
| "The package could not be installed. The theme is missing the style.css stylesheet." | You zipped the wrong folder level | Zip the `nocturne` folder *itself*, so the zip contains `style.css` at the top |
| "The theme is missing the parent theme" | Kadence not installed | Install Kadence (free) first, or change `Template:` in style.css |
| White screen after activating | PHP error | Install **WP Debug Log** or check your host's error log. Usually a PHP version issue — ask your host for PHP 8.1+ |
| Elementor page loads blank | CSS not generated | **Elementor → Tools → Regenerate CSS & Data** |
| Homepage shows the blog list | Wrong reading setting | **Settings → Reading → A static page → Home** |
| Products do not appear | Catalogue disabled | **WooCommerce → Settings → Products → Enable the catalogue** |
| Emails not arriving | SMTP not configured | Install **WP Mail SMTP** |
| 404 on product pages | Permalinks not set | **Settings → Permalinks → Save** |
| Hero video not playing on iPhone | Wrong codec | Convert to MP4 / H.264 |
| Stripe says "no such account" | Wrong keys, or account not activated | Re-copy keys from Stripe; make sure your Stripe account is fully activated (not "restricted") |

---

## Contacts to have ready

| Who | For |
|---|---|
| **Your host's support** | Domain, DNS, email, PHP version, SSL, backups |
| **Stripe / PayPal support** | Payment account issues |
| **WordPress.org forums** | Plugin conflicts |
| **Your developer (me)** | Anything about the theme itself |

---

*Nocturne — Deployment Guide. Built to be followed by someone who has never
set up a website before, and who should not have to.*
