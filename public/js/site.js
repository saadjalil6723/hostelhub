(function () {
  'use strict';

  var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* --- Sticky navbar: solidify after scrolling past the hero edge --- */
  var navbar = document.querySelector('.hh-navbar');
  if (navbar) {
    var onScroll = function () {
      navbar.classList.toggle('is-scrolled', window.scrollY > 24);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* --- Scroll reveal: fade/rise sections into view once --- */
  var revealEls = document.querySelectorAll('.reveal');
  if (revealEls.length) {
    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
      revealEls.forEach(function (el) { el.classList.add('is-visible'); });
    } else {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-visible');
              observer.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
      );
      revealEls.forEach(function (el, i) {
        el.style.transitionDelay = Math.min(i * 60, 240) + 'ms';
        observer.observe(el);
      });
    }
  }

  /* --- Key wall: give each tag a small, deterministic-looking tilt and
     stagger its swing-in animation. Deterministic (not fully random) so
     the layout doesn't jitter between renders/SSR and hydration. --- */
  var keyTags = document.querySelectorAll('.key-tag');
  keyTags.forEach(function (tag, i) {
    var tilt = ((i % 5) - 2) * 3.4; // -6.8deg .. 6.8deg
    tag.style.setProperty('--tilt', tilt + 'deg');
    tag.style.setProperty('--delay', Math.min(i * 70, 900) + 'ms');
  });
})();
