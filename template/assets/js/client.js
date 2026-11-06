/* ==========================================================================
   NOCTURNE — CLIENT EDITION · BEHAVIOUR
   The rail, the "seif" (vault) cart, the navigation overlay and the review
   form. Dependency-free, and it layers on top of the existing Nocturne
   scripts rather than replacing them.
   ========================================================================== */
(function (global, doc) {
  'use strict';

  var D = global.NocturneClient;
  if (!D) return;

  var $  = function (s, c) { return (c || doc).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || doc).querySelectorAll(s)); };

  /* ── The vault (cart) ──────────────────────────────────────────────── */
  var STORAGE_KEY = 'nocturne_vault';

  function readVault() {
    try {
      var raw = global.localStorage.getItem(STORAGE_KEY);
      return raw ? JSON.parse(raw) : [];
    } catch (e) { return []; }
  }

  function writeVault(items) {
    try { global.localStorage.setItem(STORAGE_KEY, JSON.stringify(items)); } catch (e) {}
    renderVault();
    updateVaultCount();
  }

  /* Resolve an asset path from the data file against the current page, so
     the same catalogue works from the theme root and from preview/client/. */
  function assetUrl(path) {
    if (!path) return '';
    if (/^(https?:)?\/\//.test(path) || path.charAt(0) === '/') return path;
    /* The data file stores paths relative to the site root. */
    var here = window.location.pathname;
    var root = here.replace(/[^/]*$/, '');            /* …/client/       */
    var siteRoot = root.replace(/client\/$/, '');      /* …/             */
    return siteRoot + path;
  }

  function findBook(id) {
    var all = D.series.concat(D.standalone);
    for (var i = 0; i < all.length; i++) if (all[i].id === id) return all[i];
    return null;
  }

  function addToVault(id) {
    var items = readVault();
    var found = null;
    for (var i = 0; i < items.length; i++) if (items[i].id === id) found = items[i];
    if (found) {
      found.qty += 1;
    } else {
      var book = findBook(id);
      if (!book) return;
      items.push({ id: id, title: book.title, price: book.price, img: assetUrl(book.img), qty: 1 });
    }
    writeVault(items);
    flash(id);
  }

  function changeQty(id, delta) {
    var items = readVault().map(function (it) {
      return it.id === id ? Object.assign({}, it, { qty: Math.max(1, it.qty + delta) }) : it;
    });
    writeVault(items);
  }

  function removeItem(id) {
    writeVault(readVault().filter(function (it) { return it.id !== id; }));
  }

  function vaultTotal() {
    return readVault().reduce(function (sum, it) { return sum + it.price * it.qty; }, 0);
  }

  function updateVaultCount() {
    var items = readVault();
    var count = items.reduce(function (n, it) { return n + it.qty; }, 0);
    $$('[data-vault-count]').forEach(function (el) {
      el.textContent = count;
      el.style.display = count ? '' : 'none';
    });
    var total = items.reduce(function (sum, it) { return sum + it.price * it.qty; }, 0);
    $$('[data-vault-total]').forEach(function (el) { el.textContent = total; });
  }

  function renderVault() {
    var items = readVault();
    var body = $('[data-vault-body]');
    if (!body) return;

    if (!items.length) {
      body.innerHTML =
        '<div class="cl-vault-empty">' +
          '<div class="cl-vault-empty__mark">♡</div>' +
          '<h3>Seiful e gol</h3>' +
          '<p>Adaugă cărți din colecție și ele vor rămâne aici.</p>' +
          '<button class="cl-btn" style="width:auto;display:inline-block;padding:14px 26px;margin-top:24px;background:transparent;border:1px solid #5A7A7A;color:#0E0E10" ' +
            'onclick="NocturneClient.closeOverlay();location.href=\'serie.html\'">VEZI CĂRȚILE</button>' +
        '</div>';
      return;
    }

    body.innerHTML = items.map(function (it) {
      return '' +
        '<div class="cl-vault-item" data-vault-item="' + it.id + '">' +
          '<img src="' + it.img + '" alt="' + it.title + '">' +
          '<div class="cl-vault-item__body">' +
            '<p class="cl-vault-item__title">' + it.title + '</p>' +
            '<p class="cl-vault-item__price">' + it.price + ' LEI x ' + it.qty + '</p>' +
            '<div class="cl-vault-item__row">' +
              '<button class="cl-qty" data-vault-dec="' + it.id + '" aria-label="Scade cantitatea">&minus;</button>' +
              '<button class="cl-qty" data-vault-inc="' + it.id + '" aria-label="Crește cantitatea">+</button>' +
              '<button class="cl-remove" data-vault-del="' + it.id + '">ȘTERGE</button>' +
            '</div>' +
          '</div>' +
        '</div>';
    }).join('');
  }

  function flash(id) {
    var btn = doc.querySelector('[data-add="' + id + '"]');
    if (!btn) return;
    var original = btn.textContent;
    btn.textContent = 'ADĂUGAT ✓';
    btn.disabled = true;
    global.setTimeout(function () {
      btn.textContent = original;
      btn.disabled = false;
    }, 1400);
  }

  /* ── The navigation / contact overlay ───────────────────────────────── */
  function openOverlay() {
    var ov = $('[data-overlay]');
    if (!ov) return;
    ov.classList.add('is-open');
    doc.body.style.overflow = 'hidden';
    var close = $('[data-overlay-close]', ov);
    if (close) close.focus();
  }

  function closeOverlay() {
    var ov = $('[data-overlay]');
    if (!ov) return;
    ov.classList.remove('is-open');
    doc.body.style.overflow = '';
  }

  /* ── The rail ───────────────────────────────────────────────────────── */
  function initRail(rail) {
    var track = $('[data-rail-track]', rail);
    if (!track) return;
    var prev = $('[data-rail-prev]', rail);
    var next = $('[data-rail-next]', rail);
    var dots = $('[data-rail-dots]', rail);
    var items = $$('.cl-book', track);

    function step() {
      var item = items[1] || items[0];
      if (!item) return track.clientWidth;
      var gap = parseFloat(getComputedStyle(track).columnGap || '20') || 20;
      return item.getBoundingClientRect().width + gap;
    }

    function maxScroll() { return track.scrollWidth - track.clientWidth; }

    function update() {
      var x = track.scrollLeft;
      if (prev) prev.disabled = x <= 2;
      if (next) next.disabled = x >= maxScroll() - 2;
      if (dots) {
        var index = Math.min(items.length - 1, Math.round(x / step()));
        $$('button', dots).forEach(function (b, i) { b.classList.toggle('is-active', i === index); });
      }
    }

    function go(dir) {
      var s = step();
      track.scrollBy({ left: dir * s, behavior: 'smooth' });
      global.setTimeout(update, 420);
    }

    if (prev) prev.addEventListener('click', function () { go(-1); });
    if (next) next.addEventListener('click', function () { go(1); });

    if (dots) {
      items.forEach(function (item, i) {
        var b = doc.createElement('button');
        b.type = 'button';
        b.setAttribute('aria-label', 'Carte ' + (i + 1));
        b.addEventListener('click', function () {
          track.scrollTo({ left: i * step(), behavior: 'smooth' });
          global.setTimeout(update, 420);
        });
        dots.appendChild(b);
      });
    }

    track.addEventListener('scroll', update, { passive: true });
    global.addEventListener('resize', update);
    update();
  }

  /* ── Reviews ────────────────────────────────────────────────────────── */
  function starMarkup(count, size) {
    var out = '';
    for (var i = 0; i < 5; i++) {
      out += '<span class="' + (i < count ? '' : 'is-off') + '">★</span>';
    }
    return '<span class="cl-stars" style="font-size:' + (size || '12px') + '">' + out + '</span>';
  }

  function renderReviews() {
    $$('[data-reviews]').forEach(function (host) {
      var bookId = host.getAttribute('data-reviews');
      var list = D.reviews[bookId] || [];
      var expanded = host.getAttribute('data-reviews-open') === 'true';

      var html = '<div class="cl-reviews" data-open="' + (expanded ? 'true' : 'false') + '">' +
        '<div class="cl-reviews__head">' +
          '<span class="cl-reviews__title">' + D.copy.reviewsTitle + '</span>' +
          '<button class="cl-reviews__toggle" data-reviews-toggle>' +
            (expanded ? 'ASCUNDE REVIEW-URI' : 'Vezi toate review-urile') +
          '</button>' +
        '</div>';

      var shown = expanded ? list : list.slice(0, 2);
      html += shown.map(function (r) {
        return '<div class="cl-review">' +
          starMarkup(r.stars) +
          '<p class="cl-review__quote">“' + r.text + '”</p>' +
          '<p class="cl-review__by">' + r.name + '</p>' +
        '</div>';
      }).join('');

      html += '<button class="cl-reviews__toggle" data-review-new style="margin-top:12px;color:#5A7A7A">' +
              'LASĂ UN REVIEW</button>';

      host.innerHTML = html;
    });
  }

  function renderReviewForm(host) {
    if (host.getAttribute('data-form-ready') === 'true') return;
    host.setAttribute('data-form-ready', 'true');
    host.innerHTML =
      '<div class="cl-review-form">' +
        '<label for="rv-name-' + host.getAttribute('data-reviews') + '">NUME</label>' +
        '<input id="rv-name-' + host.getAttribute('data-reviews') + '" type="text" placeholder="Numele tău">' +
        '<label>STELE</label>' +
        '<div class="cl-review-form__stars" data-star-picker>' +
          [1,2,3,4,5].map(function (n) {
            return '<button type="button" data-star="' + n + '">★</button>';
          }).join('') +
        '</div>' +
        '<label for="rv-text-' + host.getAttribute('data-reviews') + '">REVIEW</label>' +
        '<textarea id="rv-text-' + host.getAttribute('data-reviews') + '" placeholder="Ce ți-a rămas în minte?"></textarea>' +
        '<button class="cl-add" data-review-send style="width:100%;padding:12px">TRIMITE REVIEW</button>' +
        '<p class="cl-note">Review-ul se salvează local în sesiunea ta, previzualizare instant.</p>' +
      '</div>';
  }

  /* ── Wiring ─────────────────────────────────────────────────────────── */
  function init() {
    renderVault();
    updateVaultCount();
    renderReviews();

    /* Add to vault */
    doc.addEventListener('click', function (e) {
      var add = e.target.closest('[data-add]');
      if (add) { addToVault(add.getAttribute('data-add')); return; }

      var inc = e.target.closest('[data-vault-inc]');
      if (inc) { changeQty(inc.getAttribute('data-vault-inc'), 1); return; }

      var dec = e.target.closest('[data-vault-dec]');
      if (dec) { changeQty(dec.getAttribute('data-vault-dec'), -1); return; }

      var del = e.target.closest('[data-vault-del]');
      if (del) { removeItem(del.getAttribute('data-vault-del')); return; }

      if (e.target.closest('[data-overlay-open]')) { openOverlay(); return; }
      if (e.target.closest('[data-overlay-close]')) { closeOverlay(); return; }

      if (e.target.matches('[data-overlay]')) { closeOverlay(); return; }

      /* Reviews */
      var toggle = e.target.closest('[data-reviews-toggle]');
      if (toggle) {
        var host = toggle.closest('[data-reviews]');
        host.setAttribute('data-reviews-open', host.getAttribute('data-reviews-open') === 'true' ? 'false' : 'true');
        renderReviews();
        return;
      }

      var newBtn = e.target.closest('[data-review-new]');
      if (newBtn) {
        var h2 = newBtn.closest('[data-reviews]');
        if (!$('.cl-review-form', h2)) renderReviewForm(h2);
        return;
      }

      var star = e.target.closest('[data-star]');
      if (star) {
        var picker = star.closest('[data-star-picker]');
        var n = parseInt(star.getAttribute('data-star'), 10);
        $$('button', picker).forEach(function (b) {
          b.classList.toggle('is-on', parseInt(b.getAttribute('data-star'), 10) <= n);
        });
        picker.setAttribute('data-value', n);
        return;
      }

      var send = e.target.closest('[data-review-send]');
      if (send) {
        var form = send.closest('.cl-review-form');
        var bookId = form.closest('[data-reviews]').getAttribute('data-reviews');
        var name = $('input', form).value.trim();
        var text = $('textarea', form).value.trim();
        if (!name || !text) { send.textContent = 'COMPLETEAZĂ NUMELE ȘI REVIEW-UL'; return; }
        var stars = parseInt($('[data-star-picker]', form).getAttribute('data-value') || '5', 10);
        D.reviews[bookId].push({ id: 'local_' + Date.now(), name: name, stars: stars, text: text });
        renderReviews();
      }
    });

    /* Escape closes the overlay */
    doc.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeOverlay();
    });

    /* Rails */
    $$('[data-rail]').forEach(initRail);

    /* The contact form is a demo — no backend in the static preview. */
    $$('[data-contact-form]').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var note = $('[data-contact-status]', form);
        if (note) note.textContent = 'Mesajul a fost pregătit • vei fi contactată pe email';
      });
    });
  }

  /* Expose a tiny API for the theme templates. */
  global.NocturneClient.openOverlay = openOverlay;
  global.NocturneClient.closeOverlay = closeOverlay;
  global.NocturneClient.addToVault = addToVault;

  if (doc.readyState === 'loading') {
    doc.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})(window, document);
