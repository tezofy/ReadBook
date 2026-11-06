/* ==========================================================================
   NOCTURNE — Effects Engine
   --------------------------------------------------------------------------
   Drives the reveal-on-scroll system, the 3D book tilt, pointer parallax,
   magnetic buttons and the product rail.

   No dependencies. No build step.
   ========================================================================== */
(function () {
  'use strict';

  const $  = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));
  const on = (el, ev, fn, opt) => el && el.addEventListener(ev, fn, opt);
  const clamp = (v, min, max) => Math.min(Math.max(v, min), max);
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ------------------------------------------------------------------
     1. REVEAL ON SCROLL
     Elements with [data-animate] get .is-in when they enter the viewport.
     Children of [data-group] are staggered automatically.
     ------------------------------------------------------------------ */
  function initReveal() {
    const items = $$('[data-animate]');
    if (!items.length) return;

    // Apply per-element duration and delay from data attributes.
    items.forEach(el => {
      const dur = el.dataset.duration;
      const del = el.dataset.delay;
      if (dur) el.style.setProperty('--vl-anim-duration', dur + 'ms');
      if (del) el.style.setProperty('--vl-anim-delay', del + 'ms');
    });

    // Stagger groups: children of [data-group] get an incremental --i.
    $$('[data-group]').forEach(group => {
      const step = parseInt(group.dataset.group, 10) || 90;
      $$('[data-animate]', group).forEach((child, i) => {
        child.style.setProperty('--i', i);
        child.style.setProperty('--vl-anim-delay',
          (parseInt(child.dataset.delay || 0, 10) + i * step) + 'ms');
      });
    });

    // Letter stagger: split the text into spans, each with an --i index.
    $$('[data-animate="letter-stagger"]').forEach(el => {
      if (el.dataset.split === 'done') return;
      const text = el.textContent.trim();
      if (!text) return;
      el.setAttribute('aria-label', text);
      el.textContent = '';
      Array.from(text).forEach((ch, i) => {
        const span = document.createElement('span');
        span.setAttribute('aria-hidden', 'true');
        span.style.setProperty('--i', i);
        span.textContent = ch === ' ' ? ' ' : ch;
        el.appendChild(span);
      });
      el.dataset.split = 'done';
    });

    if (reduced || !('IntersectionObserver' in window)) {
      items.forEach(el => el.classList.add('is-in'));
      return;
    }

    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-in');
        io.unobserve(entry.target);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

    items.forEach(el => io.observe(el));
  }

  /* ------------------------------------------------------------------
     2. PRODUCT RAIL — arrows + drag + keyboard
     ------------------------------------------------------------------ */
  function initRail() {
    $$('.vl-rail').forEach(rail => {
      const track = $('.vl-rail__track', rail);
      if (!track) return;

      // The arrows may live inside the rail (inline nav) or in a sibling
      // wrapper (a centred nav below the track). Search the rail first, then
      // its immediate family — never the whole document, because a page can
      // hold more than one rail and they must not steal each other's arrows.
      const family = rail.parentElement;
      const find = (sel) => {
        const inRail = $(sel, rail);
        if (inRail) return inRail;
        if (!family) return null;
        // Only accept a match that is not already claimed by another rail.
        const candidates = $$(sel, family);
        for (const el of candidates) {
          const owner = el.closest('.vl-rail');
          if (!owner || owner === rail) return el;
        }
        return null;
      };
      const prev = find('[data-rail-prev]');
      const next = find('[data-rail-next]');

      const metrics = () => {
        const first = track.firstElementChild;
        const style = getComputedStyle(track);
        const gap = parseFloat(style.columnGap || style.gap) || 0;
        const lead = parseFloat(style.paddingLeft) || 0;
        const step = first ? first.getBoundingClientRect().width + gap : 320;
        const max = Math.max(0, track.scrollWidth - track.clientWidth);
        return { step, lead, max };
      };

      const update = () => {
        const { lead, max } = metrics();
        // The track has leading padding so the first card sits flush with the
        // page gutter; the true start of the content is that padding away.
        const atStart = track.scrollLeft <= 2;
        const atEnd = track.scrollLeft >= max - 2;
        if (prev) prev.disabled = atStart;
        if (next) next.disabled = atEnd || max <= 0;
        rail.classList.toggle('is-at-start', atStart);
        rail.classList.toggle('is-at-end', atEnd);
      };

      // Snap to a card boundary instead of nudging by a raw pixel count, so
      // the rail always lands cleanly on a card.
      const goTo = (dir) => {
        const { step, lead, max } = metrics();
        const current = track.scrollLeft;
        const index = Math.round(current / step) + dir;
        const target = Math.min(max, Math.max(0, index * step));
        track.scrollTo({ left: target, behavior: reduced ? 'auto' : 'smooth' });
        // Update immediately so the button states do not lag behind the
        // animation; the scroll listener corrects them once it settles.
        setTimeout(update, reduced ? 0 : 420);
      };

      on(prev, 'click', () => goTo(-1));
      on(next, 'click', () => goTo(1));
      on(track, 'scroll', update, { passive: true });
      on(window, 'resize', update);

      // Drag to scroll with a pointer.
      let down = false, startX = 0, startLeft = 0, moved = false;
      on(track, 'pointerdown', (e) => {
        if (e.pointerType === 'touch') return;   // native touch scrolling is better
        down = true; moved = false;
        startX = e.clientX; startLeft = track.scrollLeft;
        track.style.scrollBehavior = 'auto';
        track.style.cursor = 'grabbing';
      });
      on(window, 'pointermove', (e) => {
        if (!down) return;
        const dx = e.clientX - startX;
        if (Math.abs(dx) > 4) moved = true;
        track.scrollLeft = startLeft - dx;
      });
      on(window, 'pointerup', () => {
        if (!down) return;
        down = false;
        track.style.scrollBehavior = '';
        track.style.cursor = '';
      });
      // Stop a click from firing after a drag.
      on(track, 'click', (e) => { if (moved) { e.preventDefault(); moved = false; } }, true);

      update();
    });
  }

  /* ------------------------------------------------------------------
     3. POINTER TILT
     Elements with [data-tilt] lean toward the cursor.
     ------------------------------------------------------------------ */
  function initTilt() {
    if (reduced) return;
    const els = $$('[data-tilt]');
    if (!els.length || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

    els.forEach(el => {
      const max = parseFloat(el.dataset.tilt) || 9;

      on(el, 'pointermove', (e) => {
        const r = el.getBoundingClientRect();
        const px = (e.clientX - r.left) / r.width  - .5;
        const py = (e.clientY - r.top)  / r.height - .5;
        el.classList.add('is-tilting');
        el.style.transform =
          `perspective(1000px) rotateY(${px * max}deg) rotateX(${-py * max}deg) translateZ(0)`;
      });

      on(el, 'pointerleave', () => {
        el.classList.remove('is-tilting');
        el.style.transform = '';
      });
    });
  }

  /* ------------------------------------------------------------------
     4. MAGNETIC BUTTONS
     Elements with [data-magnetic] drift toward the cursor.
     ------------------------------------------------------------------ */
  function initMagnetic() {
    if (reduced) return;
    if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

    $$('[data-magnetic]').forEach(el => {
      const strength = parseFloat(el.dataset.magnetic) || .28;

      on(el, 'pointermove', (e) => {
        const r = el.getBoundingClientRect();
        const x = (e.clientX - (r.left + r.width / 2)) * strength;
        const y = (e.clientY - (r.top + r.height / 2)) * strength;
        el.style.transform = `translate3d(${x}px, ${y}px, 0)`;
      });

      on(el, 'pointerleave', () => { el.style.transform = ''; });
    });
  }

  /* ------------------------------------------------------------------
     5. PARALLAX
     Elements with [data-parallax="0.2"] move slower than the scroll.
     ------------------------------------------------------------------ */
  function initParallax() {
    const els = $$('[data-parallax]');
    if (!els.length || reduced) return;

    let ticking = false;
    const update = () => {
      const vh = window.innerHeight;
      els.forEach(el => {
        const r = el.getBoundingClientRect();
        if (r.bottom < -200 || r.top > vh + 200) return;
        const speed = parseFloat(el.dataset.parallax) || .2;
        const centre = r.top + r.height / 2 - vh / 2;
        el.style.setProperty('--parallax-y', (-centre * speed).toFixed(2) + 'px');
      });
      ticking = false;
    };

    on(window, 'scroll', () => {
      if (!ticking) { requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
    on(window, 'resize', update);
    update();
  }

  /* ------------------------------------------------------------------
     6. SPLIT HERO — the video panel cycles with the cover
     ------------------------------------------------------------------ */
  function initSplitHero() {
    $$('.vl-split-hero').forEach(hero => {
      const slides = $$('.vl-split-hero__slide', hero);
      if (slides.length < 2) return;

      let index = 0;
      let timer = null;

      const show = (i) => {
        index = (i + slides.length) % slides.length;
        slides.forEach((s, n) => s.classList.toggle('is-active', n === index));

        // Sync the 3D book with the active slide.
        const book = $('.vl-3d-book', hero);
        const art  = $('.vl-3d-book__face--front img', hero);
        const src  = slides[index].dataset.cover;
        if (book && art && src && art.getAttribute('src') !== src) {
          art.src = src;
          book.classList.remove('vl-3d-book--enter');
          void book.offsetWidth;
          book.classList.add('vl-3d-book--enter');
        }

        // Only the active slide's video plays.
        slides.forEach((s, n) => {
          const v = $('video', s);
          if (!v) return;
          if (n === index) { const p = v.play(); if (p && p.catch) p.catch(() => {}); }
          else v.pause();
        });
      };

      const start = () => {
        stop();
        if (reduced) return;
        timer = setInterval(() => show(index + 1), 7000);
      };
      const stop = () => { if (timer) { clearInterval(timer); timer = null; } };

      const splitFind = (sel) =>
        $(sel, hero) || $(sel, hero.parentElement) || $(sel, document);
      on(splitFind('[data-split-next]'), 'click', () => { show(index + 1); start(); });
      on(splitFind('[data-split-prev]'), 'click', () => { show(index - 1); start(); });

      if ('IntersectionObserver' in window) {
        new IntersectionObserver((es) => {
          es.forEach(e => {
            if (e.isIntersecting) start();
            else { stop(); slides.forEach(s => { const v = $('video', s); if (v) v.pause(); }); }
          });
        }, { threshold: .2 }).observe(hero);
      }

      on(document, 'visibilitychange', () => {
        if (document.hidden) { stop(); slides.forEach(s => { const v = $('video', s); if (v) v.pause(); }); }
        else start();
      });

      show(0);
      start();
    });
  }

  /* ------------------------------------------------------------------
     7. STICKY-SHRINK HEADER
     The bar condenses once the page scrolls past the configured start.
     ------------------------------------------------------------------ */
  function initStickyHeader() {
    const header = $('.vl-header');
    if (!header) return;

    const startAt = parseInt(header.dataset.stickyStart, 10) || 10;
    let ticking = false;

    const update = () => {
      const stuck = window.scrollY > startAt;
      header.classList.toggle('is-stuck', stuck);
      ticking = false;
    };

    on(window, 'scroll', () => {
      if (!ticking) { requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
    on(window, 'resize', update);
    update();
  }

  /* ------------------------------------------------------------------
     8. BOOT
     ------------------------------------------------------------------ */
  function boot() {
    initStickyHeader();
    initReveal();
    initRail();
    initTilt();
    initMagnetic();
    initParallax();
    initSplitHero();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }

  window.Nocturne = window.Nocturne || {};
  window.Nocturne.effects = { boot: boot };
  window.Nocturne.init = function () {
    boot();
    if (window.Nocturne.init && window.Nocturne.init !== boot) window.Nocturne.init();
  };
})();
