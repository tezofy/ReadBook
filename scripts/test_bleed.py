"""
Veloria QA — verifies the client's #1 requirement:
the hero slider must be EDGE-TO-EDGE on mobile with NO side border
and NO horizontal scrollbar.

Run:  python3 test_bleed.py
"""
import sys, os, tempfile
from playwright.sync_api import sync_playwright

BASE = os.environ.get("NOCTURNE_BASE", "http://localhost:8080")
# Write QA artefacts outside the repo by default so a test run never
# leaves multi-megabyte PNGs behind in docs/screenshots.
OUT = os.environ.get(
    "NOCTURNE_OUT",
    os.path.join(tempfile.gettempdir(), "nocturne-qa"),
)
os.makedirs(OUT, exist_ok=True)

VIEWPORTS = [
    ("mobile-375",  375,  812,  True),
    ("mobile-390",  390,  844,  True),
    ("tablet-768",  768,  1024, True),
    ("laptop-1440", 1440, 900,  False),
    ("desktop-1920",1920, 1080, False),
]

fails = []

def check(page, name, w, h, is_mobile):
    errs = []
    page.on("console", lambda m: errs.append(m.text) if m.type == "error" else None)
    page.on("pageerror", lambda e: errs.append(str(e)))

    page.goto(f"{BASE}/index.html", wait_until="networkidle")
    page.wait_for_timeout(900)

    # ---- 1. No horizontal scrollbar on the document ----
    scroll_w = page.evaluate("document.documentElement.scrollWidth")
    client_w = page.evaluate("document.documentElement.clientWidth")
    overflow = scroll_w - client_w
    if overflow > 1:
        errs.append(f"horizontal overflow: scrollWidth={scroll_w} clientWidth={client_w} (+{overflow}px)")

    # ---- 2. The hero stage must span the FULL viewport width ----
    box = page.evaluate("""() => {
        const el = document.querySelector('.vl-hero__stage');
        if (!el) return null;
        const r = el.getBoundingClientRect();
        return {left: r.left, right: r.right, width: r.width,
                padL: getComputedStyle(el).paddingLeft,
                padR: getComputedStyle(el).paddingRight};
    }""")
    if not box:
        errs.append("hero stage not found")
        return errs, scroll_w, client_w

    if abs(box["left"]) > 1:
        errs.append(f"hero stage left edge = {box['left']:.1f}px (expected 0)")
    if abs(box["right"] - w) > 1:
        errs.append(f"hero stage right edge = {box['right']:.1f}px (expected {w})")
    if abs(box["width"] - w) > 1:
        errs.append(f"hero stage width = {box['width']:.1f}px (expected {w})")
    if box["padL"] not in ("0px", "0") or box["padR"] not in ("0px", "0"):
        errs.append(f"hero stage has horizontal padding L={box['padL']} R={box['padR']}")

    # ---- 3. The visible slide must also be edge-to-edge ----
    sbox = page.evaluate("""() => {
        const el = document.querySelector('.vl-slide.is-active');
        if (!el) return null;
        const r = el.getBoundingClientRect();
        return {left: r.left, right: r.right, width: r.width};
    }""")
    if sbox:
        if abs(sbox["left"]) > 1:
            errs.append(f"active slide left = {sbox['left']:.1f}px (expected 0)")
        if abs(sbox["right"] - w) > 1:
            errs.append(f"active slide right = {sbox['right']:.1f}px (expected {w})")

    # ---- 4. Media (img) must fill the slide with no letterboxing ----
    mbox = page.evaluate("""() => {
        const el = document.querySelector('.vl-slide.is-active .vl-slide__media img');
        if (!el) return null;
        const r = el.getBoundingClientRect();
        return {left: r.left, right: r.right, width: r.width};
    }""")
    if mbox:
        if abs(mbox["left"]) > 1:
            errs.append(f"hero image left = {mbox['left']:.1f}px (expected 0)")

    # ---- 5. Slider actually advances ----
    before = page.evaluate("document.querySelector('.vl-hero__track').style.transform")
    page.click(".vl-hero__arrow--next") if not is_mobile else page.evaluate(
        "document.querySelector('.vl-hero__arrow--next').click()")
    page.wait_for_timeout(1100)
    after = page.evaluate("document.querySelector('.vl-hero__track').style.transform")
    if before == after:
        errs.append(f"slider did not advance (transform stuck at '{after}')")

    # ---- 6. Mobile nav drawer opens ----
    if is_mobile:
        page.evaluate("document.querySelector('.vl-burger').click()")
        page.wait_for_timeout(650)
        open_ = page.evaluate("document.querySelector('.vl-nav').classList.contains('is-open')")
        if not open_:
            errs.append("mobile nav drawer did not open")
        page.evaluate("document.querySelector('.vl-burger').click()")
        page.wait_for_timeout(500)

    # ---- 7. Console errors ----
    real = [e for e in errs if "favicon" not in e.lower()]
    return real, scroll_w, client_w


with sync_playwright() as p:
    browser = p.chromium.launch()

    print("=" * 74)
    print("VELORIA QA — hero edge-to-edge bleed + console health")
    print("=" * 74)

    for name, w, h, mob in VIEWPORTS:
        ctx = browser.new_context(
            viewport={"width": w, "height": h},
            device_scale_factor=2,
            is_mobile=mob,
            has_touch=mob,
        )
        page = ctx.new_page()
        errs, sw, cw = check(page, name, w, h, mob)

        status = "PASS" if not errs else "FAIL"
        print(f"\n[{status}] {name}  ({w}x{h})")
        print(f"       scrollWidth={sw}  clientWidth={cw}")
        for e in errs:
            print(f"       ✗ {e}")
            fails.append((name, e))

        page.screenshot(path=f"{OUT}/shot-{name}.png", full_page=False)
        # A full-page capture of the whole landing page exceeds Chromium's
        # texture limit now that the page carries several extra sections.
        # The viewport shot above is the artefact this test needs.
        ctx.close()

    # Book page smoke test
    print("\n" + "-" * 74)
    print("SINGLE BOOK PAGE")
    print("-" * 74)
    for name, w, h, mob in [("mobile-375", 375, 812, True), ("desktop-1440", 1440, 900, False)]:
        ctx = browser.new_context(viewport={"width": w, "height": h}, device_scale_factor=2, is_mobile=mob, has_touch=mob)
        page = ctx.new_page()
        errs = []
        page.on("pageerror", lambda e: errs.append(str(e)))
        page.goto(f"{BASE}/book.html", wait_until="networkidle")
        page.wait_for_timeout(700)
        sw = page.evaluate("document.documentElement.scrollWidth")
        cw = page.evaluate("document.documentElement.clientWidth")
        bad = [e for e in errs if "favicon" not in e.lower()]
        if sw - cw > 1:
            bad.append(f"horizontal overflow +{sw-cw}px")
        print(f"[{'PASS' if not bad else 'FAIL'}] book.html @ {name}  ({sw}/{cw})")
        for b in bad:
            print(f"       ✗ {b}")
            fails.append((name, b))
        page.screenshot(path=f"{OUT}/book-{name}.png", full_page=False)
        ctx.close()

    browser.close()

print("\n" + "=" * 74)
if fails:
    print(f"RESULT: {len(fails)} FAILURE(S)")
    for n, e in fails:
        print(f"  [{n}] {e}")
    sys.exit(1)
print("RESULT: ALL CHECKS PASSED ✅")
print("=" * 74)
