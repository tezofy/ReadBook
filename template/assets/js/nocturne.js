/* ==========================================================================
   NOCTURNE — Interactions
   1. Header behaviour (sticky / hide on scroll down)
   2. Mobile navigation drawer
   3. Hero media slider  ⭐ full-bleed, video-capable, swipeable
   4. Reveal-on-scroll
   5. Cart drawer + quantity
   6. Newsletter / contact form validation
   7. Counters
   8. Product gallery on single-book pages
   ========================================================================== */
(function () {
  'use strict';

  const $  = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));
  const on = (el, ev, fn, opt) => el && el.addEventListener(ev, fn, opt);
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ------------------------------------------------------------------
     1. HEADER
     ------------------------------------------------------------------ */
  function initHeader() {
    const header = $('.vl-header');
    if (!header) return;
    let ticking = false;

    // The sticky-shrink behaviour lives in effects.js (it owns the
    // [data-sticky-start] contract). This block only keeps the class in
    // sync for markup that has no effects.js loaded.
    if (window.Nocturne && window.Nocturne.effects) return;

    const update = () => {
      header.classList.toggle('is-stuck', window.scrollY > 10);
      ticking = false;
    };

    on(window, 'scroll', () => {
      if (!ticking) { requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
    update();
  }

  /* ------------------------------------------------------------------
     2. MOBILE NAV
     ------------------------------------------------------------------ */
  function initNav() {
    const burger = $('.vl-burger');
    const nav    = $('.vl-nav');
    const backdrop = $('.vl-nav__backdrop');
    if (!burger || !nav) return;

    const setOpen = (open) => {
      burger.classList.toggle('is-open', open);
      nav.classList.toggle('is-open', open);
      if (backdrop) backdrop.classList.toggle('is-open', open);
      document.body.style.overflow = open ? 'hidden' : '';
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
      nav.setAttribute('aria-hidden', open ? 'false' : 'true');
    };

    on(burger, 'click', () => setOpen(!nav.classList.contains('is-open')));
    on(backdrop, 'click', () => setOpen(false));
    $$('.vl-nav__link', nav).forEach(a => on(a, 'click', () => setOpen(false)));
    on(document, 'keydown', e => { if (e.key === 'Escape') setOpen(false); });
  }

  /* ------------------------------------------------------------------
     3. HERO MEDIA SLIDER  ⭐ THE KEY DELIVERABLE
     ------------------------------------------------------------------
     - Slides are laid out in a flex track, translated with transform.
     - The track lives inside .vl-hero__stage which breaks out of any
       container with width:100vw + margin-left:calc(50% - 50vw).
     - Because the stage has NO horizontal padding at any breakpoint and
       the section clips overflow, the slider is truly edge-to-edge on
       mobile with no side gutter and no horizontal scrollbar.
     - Supports image slides AND <video> slides (muted + playsinline
       so iOS/Safari will autoplay), each with a poster fallback.
     - Touch swipe, keyboard arrows, autoplay with progress-bar dots,
       and a pause/mute toggle that also pauses the videos.
     ------------------------------------------------------------------ */
  function initHeroSlider() {
    const hero = $('.vl-hero');
    if (!hero) return;

    const track  = $('.vl-hero__track', hero);
    const slides = $$('.vl-slide', hero);
    const dots   = $$('.vl-hero__dot', hero);
    const prev   = $('.vl-hero__arrow--prev', hero);
    const next   = $('.vl-hero__arrow--next', hero);
    const toggle = $('.vl-hero__toggle', hero);

    if (!track || slides.length === 0) return;

    const DUR = slides[0]?.dataset.duration
      ? parseInt(slides[0].dataset.duration, 10) * 1000
      : 7000;

    let index = 0;
    let timer = null;
    let paused = false;

    // Set the CSS var so the dot progress bar matches the real duration
    hero.style.setProperty('--vl-slide-dur', (DUR / 1000) + 's');

    const videos = () => $$('video', hero);

    function goTo(i) {
      index = (i + slides.length) % slides.length;

      track.style.transform = `translate3d(${-index * 100}%, 0, 0)`;

      slides.forEach((s, n) => {
        s.classList.toggle('is-active', n === index);
        s.setAttribute('aria-hidden', n === index ? 'false' : 'true');
      });

      dots.forEach((d, n) => {
        d.classList.toggle('is-active', n === index);
        // reset the progress fill on the new active dot
        const bar = d.querySelector('span');
        if (bar) { bar.style.width = '0%'; }
        if (n === index) {
          // force reflow so the transition restarts
          void d.offsetWidth;
        }
      });

      // Pause videos on inactive slides (saves CPU + mobile data)
      videos().forEach(v => {
        const inSlide = v.closest('.vl-slide') === slides[index];
        if (inSlide) {
          if (!paused) { const p = v.play(); if (p && p.catch) p.catch(() => {}); }
        } else {
          v.pause();
        }
      });
    }

    function start() {
      stop();
      if (reduced || paused) return;
      timer = setInterval(() => goTo(index + 1), DUR);
    }
    function stop() { if (timer) { clearInterval(timer); timer = null; } }

    function nextSlide() { goTo(index + 1); start(); }
    function prevSlide() { goTo(index - 1); start(); }

    on(next, 'click', nextSlide);
    on(prev, 'click', prevSlide);
    dots.forEach((d, n) => on(d, 'click', () => { goTo(n); start(); }));

    // Pause when the hero scrolls out of view — good for Core Web Vitals
    if ('IntersectionObserver' in window) {
      new IntersectionObserver((entries) => {
        entries.forEach(e => {
          if (e.isIntersecting) { start(); }
          else {
            stop();
            videos().forEach(v => { if (!v.closest('.vl-slide').classList.contains('is-active')) v.pause(); });
          }
        });
      }, { threshold: 0.15 }).observe(hero);
    }

    // Pause / resume toggle (also acts as the video mute-unmute entry point)
    on(toggle, 'click', () => {
      paused = !paused;
      toggle.classList.toggle('is-paused', paused);
      toggle.setAttribute('aria-label', paused ? 'Play slideshow' : 'Pause slideshow');
      dots.forEach(d => d.classList.toggle('is-paused', paused));
      if (paused) {
        stop();
        videos().forEach(v => v.pause());
      } else {
        start();
        const v = slides[index]?.querySelector('video');
        if (v) { const p = v.play(); if (p && p.catch) p.catch(() => {}); }
      }
    });

    // Touch swipe
    let startX = 0, startY = 0, deltaX = 0, isSwiping = false;
    const stage = $('.vl-hero__stage', hero);

    on(stage, 'touchstart', (e) => {
      startX = e.touches[0].clientX;
      startY = e.touches[0].clientY;
      deltaX = 0;
      isSwiping = true;
      stop();
    }, { passive: true });

    on(stage, 'touchmove', (e) => {
      if (!isSwiping) return;
      deltaX = e.touches[0].clientX - startX;
      const dy = e.touches[0].clientY - startY;
      // Ignore mostly-vertical gestures so page scrolling still works
      if (Math.abs(dy) > Math.abs(deltaX) && Math.abs(dy) > 12) { isSwiping = false; return; }
    }, { passive: true });

    on(stage, 'touchend', () => {
      if (!isSwiping) return;
      isSwiping = false;
      if (Math.abs(deltaX) > 48) {
        deltaX < 0 ? nextSlide() : prevSlide();
      } else {
        start();
      }
    });

    // Keyboard
    hero.setAttribute('tabindex', '-1');
    on(hero, 'keydown', (e) => {
      if (e.key === 'ArrowRight') { nextSlide(); }
      if (e.key === 'ArrowLeft')  { prevSlide(); }
    });

    // Pause on document hidden (tab switch)
    on(document, 'visibilitychange', () => {
      if (document.hidden) { stop(); videos().forEach(v => v.pause()); }
      else start();
    });

    // Init
    goTo(0);
    start();
  }

  /* ------------------------------------------------------------------
     4. REVEAL ON SCROLL
     ------------------------------------------------------------------ */
  function initReveal() {
    // Handle both the legacy [data-reveal] and the unified [data-animate].
    // effects.js owns [data-animate] when it is loaded; this is the fallback.
    if (window.Nocturne && window.Nocturne.effects) return;
    const items = $$('[data-reveal], [data-animate]');
    if (!items.length) return;
    if (!('IntersectionObserver' in window)) {
      items.forEach(i => i.classList.add('is-in'));
      return;
    }
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('is-in');
          io.unobserve(e.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });
    items.forEach(i => io.observe(i));
  }

  /* ------------------------------------------------------------------
     5. CART DRAWER
     ------------------------------------------------------------------ */
  function initCart() {
    const drawer = $('.vl-cart-drawer');
    const openers = $$('[data-cart-open]');
    if (!drawer) return;

    const panel   = $('.vl-cart-drawer__panel', drawer);
    const backdrop = $('.vl-cart-drawer__backdrop', drawer);
    const closeBtn = $('.vl-cart-drawer__close', drawer);
    const items   = $$('.vl-cart-item', drawer);
    const countEl = $$('.vl-cart__count');

    const setOpen = (open) => {
      drawer.classList.toggle('is-open', open);
      document.body.style.overflow = open ? 'hidden' : '';
      if (open) panel.focus();
    };

    openers.forEach(b => on(b, 'click', (e) => { e.preventDefault(); setOpen(true); }));
    on(backdrop, 'click', () => setOpen(false));
    on(closeBtn, 'click', () => setOpen(false));
    on(document, 'keydown', e => { if (e.key === 'Escape') setOpen(false); });

    // Fake quantity steppers (demo)
    $$('.vl-cart-item__qty button', drawer).forEach(btn => {
      on(btn, 'click', () => {
        const span = btn.parentElement.querySelector('span');
        const n = parseInt(span.textContent, 10) || 1;
        span.textContent = btn.dataset.step === 'up' ? n + 1 : Math.max(1, n - 1);
      });
    });

    // Keep count in sync with drawer contents
    function syncCount() {
      const n = items.length;
      countEl.forEach(el => el.textContent = n);
    }
    syncCount();
  }

  /* ------------------------------------------------------------------
     6. FORM VALIDATION
     ------------------------------------------------------------------ */
  function initForms() {
    $$('[data-vl-form]').forEach(form => {
      const fields = $$('input, textarea', form);
      const status = $('[data-form-status]', form);

      const validateField = (f) => {
        const err = f.parentElement.querySelector('.vl-field__error');
        let msg = '';
        const v = (f.value || '').trim();

        if (f.type === 'email') {
          if (v && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v)) msg = 'Enter a valid email address.';
        } else if (f.required && !v) {
          msg = 'This field is required.';
        } else if (f.type === 'checkbox' && f.required && !f.checked) {
          msg = 'Please confirm to continue.';
        }
        if (err) err.textContent = msg;
        f.style.borderColor = msg ? 'var(--vl-oxblood-lt)' : '';
        return !msg;
      };

      fields.forEach(f => {
        on(f, 'blur', () => validateField(f));
        on(f, 'input', () => { if (f.style.borderColor) validateField(f); });
      });

      on(form, 'submit', (e) => {
        e.preventDefault();
        const valid = fields.map(validateField).every(Boolean);
        if (!valid) {
          if (status) {
            status.textContent = 'Please correct the highlighted fields.';
            status.style.color = 'var(--vl-oxblood-lt)';
          }
          return;
        }
        const btn = form.querySelector('[type="submit"]');
        const original = btn ? btn.textContent : '';
        if (btn) { btn.disabled = true; btn.textContent = 'Sending…'; }

        // Demo: simulate async send. Replace with your AJAX handler.
        setTimeout(() => {
          if (btn) { btn.disabled = false; btn.textContent = original; }
          if (status) {
            status.textContent = 'Thank you — your message has arrived. I reply to every note within 48 hours.';
            status.style.color = 'var(--vl-gold)';
          }
          form.reset();
        }, 900);
      });
    });
  }

  /* ------------------------------------------------------------------
     7. COUNTERS
     ------------------------------------------------------------------ */
  function initCounters() {
    const nums = $$('[data-count]');
    if (!nums.length || !('IntersectionObserver' in window)) return;
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        const el = e.target;
        const target = parseFloat(el.dataset.count);
        const dur = 1400;
        const t0 = performance.now();
        const tick = (now) => {
          const p = Math.min((now - t0) / dur, 1);
          const eased = 1 - Math.pow(1 - p, 3);
          el.textContent = Math.round(target * eased).toLocaleString();
          if (p < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
        io.unobserve(el);
      });
    }, { threshold: 0.5 });
    nums.forEach(n => io.observe(n));
  }

  /* ------------------------------------------------------------------
     8. PRODUCT GALLERY (single book page)
     ------------------------------------------------------------------ */
  function initGallery() {
    const gallery = $('.vl-gallery');
    if (!gallery) return;
    const main = $('.vl-gallery__main img', gallery);
    const thumbs = $$('.vl-gallery__thumb', gallery);
    if (!main) return;

    thumbs.forEach(t => on(t, 'click', () => {
      const src = t.dataset.full || t.querySelector('img')?.src;
      if (!src) return;
      main.style.opacity = '0';
      setTimeout(() => {
        main.src = src;
        main.style.opacity = '1';
      }, 180);
      thumbs.forEach(x => x.classList.remove('is-active'));
      t.classList.add('is-active');
    }));
  }

  /* ------------------------------------------------------------------
     9. FAQ ACCORDION
     ------------------------------------------------------------------ */
  function initAccordion() {
    $$('[data-accordion] .vl-faq__q').forEach(btn => {
      on(btn, 'click', () => {
        const item = btn.parentElement;
        const open = item.classList.contains('is-open');
        $$('[data-accordion] .vl-faq').forEach(i => {
          i.classList.remove('is-open');
          const a = i.querySelector('.vl-faq__a');
          if (a) a.style.maxHeight = null;
        });
        if (!open) {
          item.classList.add('is-open');
          const a = item.querySelector('.vl-faq__a');
          if (a) a.style.maxHeight = a.scrollHeight + 'px';
        }
      });
    });
  }

  /* ------------------------------------------------------------------
     10. ACTIVE NAV HIGHLIGHT
     ------------------------------------------------------------------ */
  function initActiveNav() {
    const sections = $$('section[id], main section[id]');
    const links = $$('.vl-nav__link');
    if (!sections.length || !links.length) return;
    if (!('IntersectionObserver' in window)) return;

    const map = {};
    links.forEach(l => {
      const href = l.getAttribute('href');
      if (href && href.startsWith('#')) map[href.slice(1)] = l;
    });

    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        const link = map[e.target.id];
        if (!link) return;
        if (e.isIntersecting) {
          links.forEach(l => l.classList.remove('is-active'));
          link.classList.add('is-active');
        }
      });
    }, { threshold: 0.08, rootMargin: '-100px 0px -60% 0px' });

    sections.forEach(s => { if (map[s.id]) io.observe(s); });
  }

  /* ------------------------------------------------------------------
     BOOT
     ------------------------------------------------------------------ */
  function boot() {
    initHeader();
    initNav();
    initHeroSlider();
    initReveal();
    initCart();
    initForms();
    initCounters();
    initGallery();
    initAccordion();
    initActiveNav();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }

  // Expose a tiny API so the WordPress theme can drive the slider
  window.Nocturne = window.Nocturne || {};
  window.Nocturne.init = boot;
})();
