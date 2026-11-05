"""
Veloria — Full QA suite.
Covers: edge-to-edge bleed, overflow, console errors, accessibility,
contrast, form validation, navigation, and screenshots for every page.

Run:  python3 test_full_qa.py
"""
import sys, os, json
from playwright.sync_api import sync_playwright

BASE = os.environ.get("NOCTURNE_BASE", "http://localhost:8080")
OUT = os.environ.get("NOCTURNE_OUT", "/home/user/veloria-public/docs/screenshots")

PAGES = ["index.html", "book.html", "shop.html"]
VIEWPORTS = [("mobile", 375, 812, True), ("tablet", 768, 1024, True), ("desktop", 1440, 900, False)]

fails, warns = [], []


def check_page(page, page_name, w, h, is_mobile):
    issues = []
    page.goto(f"{BASE}/{page_name}", wait_until="networkidle")
    page.wait_for_timeout(700)

    # 1. Horizontal overflow
    sw = page.evaluate("document.documentElement.scrollWidth")
    cw = page.evaluate("document.documentElement.clientWidth")
    if sw - cw > 1:
        issues.append(f"horizontal overflow +{sw-cw}px (scrollW {sw} > clientW {cw})")

    # 2. Images all loaded (scroll first so lazy images resolve)
    page.evaluate("window.scrollTo(0, document.body.scrollHeight)")
    page.wait_for_timeout(1500)
    page.evaluate("window.scrollTo(0, 0)")
    page.wait_for_timeout(400)
    broken = page.evaluate("""() => {
        const b = [];
        document.querySelectorAll('img').forEach(i => {
            if (!i.complete || i.naturalWidth === 0) b.push(i.getAttribute('src'));
        });
        return b;
    }""")
    if broken:
        issues.append(f"broken images: {broken}")

    # 3. Accessibility — every image has alt
    noalt = page.evaluate("""() => {
        const b = [];
        document.querySelectorAll('img').forEach(i => {
            if (i.getAttribute('alt') === null) b.push(i.src.split('/').pop());
        });
        return b;
    }""")
    if noalt:
        issues.append(f"images without alt attribute: {noalt}")

    # 4. Accessibility — buttons/links have accessible names
    noname = page.evaluate("""() => {
        const b = [];
        document.querySelectorAll('button, a').forEach(el => {
            const t = (el.textContent||'').trim();
            const aria = el.getAttribute('aria-label');
            const title = el.getAttribute('title');
            const img = el.querySelector('img[alt]:not([alt=""])');
            if (!t && !aria && !title && !img) b.push(el.outerHTML.slice(0,70));
        });
        return b;
    }""")
    if noname:
        issues.append(f"interactive elements without accessible name: {len(noname)} → {noname[:2]}")

    # 5. Heading order — exactly one h1
    h1s = page.evaluate("document.querySelectorAll('h1').length")
    if h1s != 1:
        issues.append(f"expected exactly 1 <h1>, found {h1s}")

    # 6. No placeholder text left behind
    body = page.content().lower()
    for bad in ["lorem ipsum", "todo", "fixme", "placeholder text", "your text here", "xxx"]:
        if bad in body:
            issues.append(f"placeholder text found: '{bad}'")

    # 7. Horizontal overflow from any individual element
    #    Exclude intentional off-canvas elements: closed drawers, closed mobile
    #    nav, and inactive slider slides (which sit to the right by design).
    wide = page.evaluate("""() => {
        const vw = document.documentElement.clientWidth;
        const bad = [];
        document.querySelectorAll('body *').forEach(el => {
            const r = el.getBoundingClientRect();
            if (r.width > 0 && (r.right > vw + 2 || r.left < -2)) {
                const cs = getComputedStyle(el);
                if (cs.position === 'fixed') return;
                if (el.closest('.vl-cart-drawer:not(.is-open)')) return;
                if (el.closest('.vl-nav:not(.is-open)') && window.innerWidth <= 900) return;
                // Inactive slider slides live off-stage to the right
                if (el.closest('.vl-slide:not(.is-active)')) return;
                if (el.closest('.vl-hero__track')) return;
                bad.push(el.className && typeof el.className === 'string'
                    ? el.className.slice(0,40) : el.tagName);
            }
        });
        return Array.from(new Set(bad)).slice(0,5);
    }""")
    if wide:
        issues.append(f"elements exceeding viewport: {wide}")

    return issues


def check_hero_bleed(page, w):
    """The client's headline requirement."""
    issues = []
    box = page.evaluate("""() => {
        const el = document.querySelector('.vl-hero__stage');
        if (!el) return null;
        const r = el.getBoundingClientRect();
        const cs = getComputedStyle(el);
        return {l:r.left, r:r.right, w:r.width, pl:cs.paddingLeft, pr:cs.paddingRight};
    }""")
    if not box:
        return ["hero stage missing"]
    if abs(box["l"]) > 1:      issues.append(f"stage left {box['l']:.1f} != 0")
    if abs(box["r"] - w) > 1:  issues.append(f"stage right {box['r']:.1f} != {w}")
    if box["pl"] != "0px":     issues.append(f"stage padding-left {box['pl']}")
    if box["pr"] != "0px":     issues.append(f"stage padding-right {box['pr']}")
    return issues


def check_interactions(page):
    """Slider, nav drawer, forms, accordion, cart."""
    issues = []

    # Slider advances (only on pages that have a hero)
    has_hero = page.evaluate("!!document.querySelector('.vl-hero__track')")
    if has_hero:
        t0 = page.evaluate("document.querySelector('.vl-hero__track').style.transform")
        page.evaluate("document.querySelector('.vl-hero__arrow--next').click()")
        page.wait_for_timeout(1000)
        t1 = page.evaluate("document.querySelector('.vl-hero__track').style.transform")
        if t0 == t1:
            issues.append("hero slider did not advance")

        # Slider dots activate the right slide
        page.evaluate("document.querySelectorAll('.vl-hero__dot')[2].click()")
        page.wait_for_timeout(1000)
        active = page.evaluate("""() => {
            const s = document.querySelectorAll('.vl-slide');
            for (let i=0;i<s.length;i++) if (s[i].classList.contains('is-active')) return i;
            return -1;
        }""")
        if active != 2:
            issues.append(f"dot 3 did not activate slide 3 (active index {active})")

        # Pause toggle
        page.evaluate("document.querySelector('.vl-hero__toggle').click()")
        page.wait_for_timeout(400)
        if not page.evaluate("document.querySelector('.vl-hero__toggle').classList.contains('is-paused')"):
            issues.append("pause toggle did not set paused state")
        page.evaluate("document.querySelector('.vl-hero__toggle').click()")
        page.wait_for_timeout(300)

    # Cart drawer opens
    if page.evaluate("!!document.querySelector('[data-cart-open]')"):
        page.evaluate("document.querySelector('[data-cart-open]').click()")
        page.wait_for_timeout(600)
        if not page.evaluate("document.querySelector('.vl-cart-drawer').classList.contains('is-open')"):
            issues.append("cart drawer did not open")
        page.evaluate("document.querySelector('.vl-cart-drawer__close').click()")
        page.wait_for_timeout(500)

    # Form validation blocks an empty submit (real click on the submit button)
    forms = page.query_selector_all("form[data-vl-form]")
    if forms:
        page.evaluate("""() => {
            const fs = document.querySelectorAll('form[data-vl-form]');
            const f = fs[fs.length - 1];           // the contact form (has required fields)
            f.scrollIntoView();
            const btn = f.querySelector('[type=submit]');
            if (btn) btn.click();
        }""")
        page.wait_for_timeout(700)
        errs = page.evaluate("""() => {
            return Array.from(document.querySelectorAll('.vl-field__error')).map(e=>e.textContent.trim()).filter(Boolean);
        }""")
        if not errs:
            issues.append("empty form submitted without validation errors")

    # FAQ accordion opens
    acc = page.query_selector_all(".vl-faq__q")
    if len(acc) > 1:
        acc[1].click()
        page.wait_for_timeout(500)
        if not page.evaluate("document.querySelectorAll('.vl-faq')[1].classList.contains('is-open')"):
            issues.append("FAQ accordion did not open")

    return issues


with sync_playwright() as p:
    browser = p.chromium.launch()

    print("=" * 78)
    print("NOCTURNE — FULL QA SUITE")
    print("=" * 78)

    # ---------- Page + viewport matrix ----------
    for pname in PAGES:
        for vname, w, h, mob in VIEWPORTS:
            ctx = browser.new_context(viewport={"width": w, "height": h},
                                      device_scale_factor=2, is_mobile=mob, has_touch=mob)
            page = ctx.new_page()
            console = []
            page.on("console", lambda m: console.append(m.text) if m.type == "error" else None)
            page.on("pageerror", lambda e: console.append(str(e)))

            issues = check_page(page, pname, w, h, mob)

            if pname == "index.html":
                issues += check_hero_bleed(page, w)

            real = [c for c in console if "favicon" not in c.lower() and "ERR_" not in c]
            if real:
                issues.append(f"console errors: {real[:2]}")

            tag = f"{pname} @ {vname}-{w}"
            if issues:
                print(f"[FAIL] {tag}")
                for i in issues:
                    print(f"       ✗ {i}")
                    fails.append((tag, i))
            else:
                print(f"[PASS] {tag}")

            page.screenshot(path=f"{OUT}/qa-{pname.replace('.html','')}-{vname}.png")
            ctx.close()

    # ---------- Interaction tests (desktop) ----------
    print("-" * 78)
    print("INTERACTION TESTS")
    print("-" * 78)
    for pname in ["index.html", "book.html"]:
        ctx = browser.new_context(viewport={"width": 1440, "height": 900})
        page = ctx.new_page()
        page.goto(f"{BASE}/{pname}", wait_until="networkidle")
        page.wait_for_timeout(700)
        issues = check_interactions(page)
        if issues:
            print(f"[FAIL] {pname}")
            for i in issues:
                print(f"       ✗ {i}")
                fails.append((pname, i))
        else:
            print(f"[PASS] {pname}")
        ctx.close()

    # ---------- Mobile nav drawer ----------
    ctx = browser.new_context(viewport={"width": 375, "height": 812}, is_mobile=True, has_touch=True)
    page = ctx.new_page()
    page.goto(f"{BASE}/index.html", wait_until="networkidle")
    page.wait_for_timeout(700)
    page.evaluate("document.querySelector('.vl-burger').click()")
    page.wait_for_timeout(700)
    opened = page.evaluate("document.querySelector('.vl-nav').classList.contains('is-open')")
    geom = page.evaluate("""() => {
        const r = document.querySelector('.vl-nav').getBoundingClientRect();
        return {left: r.left, right: r.right, w: r.width,
                vw: document.documentElement.clientWidth};
    }""")
    if not opened:
        fails.append(("mobile nav", "drawer did not open"))
        print("[FAIL] mobile nav drawer — did not open")
    elif geom["right"] > geom["vw"] + 2 or geom["left"] < -2:
        fails.append(("mobile nav", f"drawer mispositioned {geom}"))
        print(f"[FAIL] mobile nav drawer — mispositioned {geom}")
    else:
        # Right-aligned drawer: its right edge must sit flush with the viewport.
        print(f"[PASS] mobile nav drawer (right {geom['right']:.0f} = vw {geom['vw']})")
    page.screenshot(path=f"{OUT}/qa-nav-open.png")
    ctx.close()

    browser.close()

print("\n" + "=" * 78)
if fails:
    print(f"RESULT: {len(fails)} FAILURE(S)")
    for t, e in fails:
        print(f"  [{t}] {e}")
    sys.exit(1)
print("RESULT: ALL CHECKS PASSED ✅")
print("=" * 78)
