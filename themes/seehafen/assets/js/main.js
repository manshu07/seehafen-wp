/* Seehafen theme JS — 1:1 port of the SPA behavior (menu, scroll reveal, offer showcase, load-more). */
(function () {
  'use strict';

  var body = document.body;
  var header = document.querySelector('.site-header');
  var navToggle = document.querySelector('.nav-toggle');
  var mainNav = document.getElementById('main-navigation');

  /* ---------- Nav dropdowns (desktop) + mobile menu ---------- */
  function closeMenu() {
    if (mainNav) mainNav.classList.remove('is-open');
    body.classList.remove('menu-open');
    document.querySelectorAll('.nav-dropdown.is-open').forEach(function (el) {
      el.classList.remove('is-open');
    });
    document.querySelectorAll('.nav-dropdown-trigger button').forEach(function (btn) {
      btn.setAttribute('aria-expanded', 'false');
    });
  }

  document.querySelectorAll('.nav-dropdown-trigger button').forEach(function (btn) {
    btn.addEventListener('click', function (event) {
      event.preventDefault();
      event.stopPropagation();
      var dropdown = btn.closest('.nav-dropdown');
      var wasOpen = dropdown.classList.contains('is-open');
      document.querySelectorAll('.nav-dropdown.is-open').forEach(function (el) {
        el.classList.remove('is-open');
        el.querySelector('.nav-dropdown-trigger button').setAttribute('aria-expanded', 'false');
      });
      if (!wasOpen) {
        dropdown.classList.add('is-open');
        btn.setAttribute('aria-expanded', 'true');
      }
    });
  });

  if (navToggle) {
    navToggle.addEventListener('click', function () {
      var open = mainNav.classList.toggle('is-open');
      body.classList.toggle('menu-open', open);
      navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      navToggle.setAttribute('aria-label', open ? 'Menü schliessen' : 'Menü öffnen');
    });
  }

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') closeMenu();
  });

  /* ---------- Scroll reveal (same selectors as the SPA) ---------- */
  var revealSelector = [
    '.hero-content',
    '.home-heading',
    '.home-service-card',
    '.split-heading',
    '.section-heading',
    '.process-grid article',
    '.offer-showcase-heading',
    '.offer-showcase-stage',
    '.reference-tile',
    '.page-hero-copy',
    '.page-hero-media',
    '.overview-links-heading',
    '.overview-link-card',
    '.company-about-copy',
    '.company-about-aside',
    '.company-section-heading',
    '.team-grid article',
    '.company-values-column',
    '.primary-service-card',
    '.secondary-service-grid article',
    '.service-detail-header-grid > div',
    '.service-detail-support > img',
    '.service-detail-points li',
    '.references-title h1',
    '.reference-archive-intro',
    '.contact-intro-copy',
    '.contact-direct-panel',
    '.contact-locations',
    '.contact-form',
    '.legal-content',
    '.contact-strip .content > *',
    '.footer-main > *',
    '.footer-bottom'
  ].join(',');

  var revealMediaSelector = [
    '.page-hero-media',
    '.home-service-card',
    '.offer-showcase-stage',
    '.reference-tile',
    '.overview-link-card',
    '.primary-service-card',
    '.secondary-service-grid article',
    '.service-detail-support > img'
  ].join(',');

  function initScrollReveal() {
    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var registered = new Set();
    var observer = null;

    function register(element) {
      if (registered.has(element)) return;
      registered.add(element);
      element.classList.add('scroll-reveal');
      if (element.matches(revealMediaSelector)) element.classList.add('scroll-reveal-media');
      var siblings = Array.prototype.filter.call(element.parentElement ? element.parentElement.children : [], function (sibling) {
        return sibling.matches ? sibling.matches(revealSelector) : false;
      });
      var siblingIndex = siblings.indexOf(element);
      element.style.setProperty('--reveal-delay', Math.min(Math.max(siblingIndex, 0), 4) * 70 + 'ms');
      if (reducedMotion || !observer) {
        element.classList.add('is-revealed');
      } else {
        observer.observe(element);
      }
    }

    if (!reducedMotion && 'IntersectionObserver' in window) {
      observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          entry.target.classList.add('is-revealed');
          observer.unobserve(entry.target);
        });
      }, { rootMargin: '0px 0px -8% 0px', threshold: 0.12 });
    }

    var scan = function () { document.querySelectorAll(revealSelector).forEach(register); };
    scan();
    var mutationObserver = new MutationObserver(scan);
    var main = document.getElementById('main-content');
    if (main) mutationObserver.observe(main, { childList: true, subtree: true });
  }

  /* ---------- Offer showcase (prev/next) ---------- */
  function initOfferShowcase() {
    var stages = document.querySelectorAll('.offer-showcase-stage');
    if (stages.length < 2) return;
    var current = 0;
    function show(index) {
      stages.forEach(function (s, i) {
        s.classList.toggle('is-active', i === index);
      });
      current = index;
    }
    document.querySelectorAll('.offer-showcase-controls button').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var next = btn.getAttribute('aria-label') === 'Nächstes Angebot' ? (current + 1) % stages.length : (current - 1 + stages.length) % stages.length;
        show(next);
      });
    });
    show(0);
  }

  /* ---------- References load-more ---------- */
  function initReferenceLoadMore() {
    var button = document.querySelector('.reference-show-more button');
    var grid = document.getElementById('reference-grid');
    if (!button || !grid) return;
    var all = grid.querySelectorAll('.reference-tile');
    var visibleCount = 9;
    all.forEach(function (tile, i) {
      if (i >= visibleCount) tile.style.display = 'none';
    });
    button.addEventListener('click', function () {
      all.forEach(function (tile) { tile.style.display = ''; });
      button.closest('.reference-show-more').style.display = 'none';
      grid.dispatchEvent(new CustomEvent('reveal-scan'));
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initScrollReveal();
    initOfferShowcase();
    initReferenceLoadMore();
  });
})();
