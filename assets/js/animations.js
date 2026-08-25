/**
 * animations.js — Animaciones de scroll y comportamiento visual
 */

'use strict';

const Animations = (() => {

  function init() {
    _initScrollObserver();
    _initHeaderScroll();
    _initHeroImage();
    _initMobileMenu();
  }

  // ─── Intersection Observer (fade-up / fade-in al hacer scroll) ──────────────

  function _initScrollObserver() {
    // Soporte básico para navegadores sin IntersectionObserver
    if (!('IntersectionObserver' in window)) {
      document.querySelectorAll('.fade-up, .fade-in').forEach(el => {
        el.classList.add('is-visible');
      });
      return;
    }

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          // Una sola vez — dejar de observar
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold:   0.12,
      rootMargin: '0px 0px -40px 0px',
    });

    document.querySelectorAll('.fade-up, .fade-in').forEach(el => {
      observer.observe(el);
    });
  }

  // ─── Header: fondo al hacer scroll ──────────────────────────────────────────

  function _initHeaderScroll() {
    const header = document.getElementById('site-header');
    if (!header) return;

    const SCROLL_THRESHOLD = 60;

    const onScroll = () => {
      if (window.scrollY > SCROLL_THRESHOLD) {
        header.classList.add('is-scrolled');
      } else {
        header.classList.remove('is-scrolled');
      }
    };

    // Usar scroll pasivo para mejor rendimiento
    window.addEventListener('scroll', onScroll, { passive: true });

    // Verificar estado inicial (en caso de que la página cargue scrolleada)
    onScroll();
  }

  // ─── Hero: Ken Burns sobre la imagen ────────────────────────────────────────

  function _initHeroImage() {
    const heroImg = document.querySelector('.hero-bg img');
    if (!heroImg) return;

    if (heroImg.complete) {
      heroImg.classList.add('is-loaded');
    } else {
      heroImg.addEventListener('load', () => heroImg.classList.add('is-loaded'));
    }
  }

  // ─── Menú mobile ────────────────────────────────────────────────────────────

  function _initMobileMenu() {
    const toggle  = document.getElementById('menu-toggle');
    const nav     = document.getElementById('site-nav');
    const overlay = document.getElementById('nav-overlay');

    if (!toggle || !nav) return;

    function openMenu() {
      nav.classList.add('is-open');
      overlay?.classList.add('is-visible');
      toggle.setAttribute('aria-expanded', 'true');
      document.body.style.overflow = 'hidden';

      // Focus en el primer link para accesibilidad
      nav.querySelector('.nav-link')?.focus();
    }

    function closeMenu() {
      nav.classList.remove('is-open');
      overlay?.classList.remove('is-visible');
      toggle.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
      toggle.focus();
    }

    function toggleMenu() {
      const isOpen = nav.classList.contains('is-open');
      isOpen ? closeMenu() : openMenu();
    }

    toggle.addEventListener('click', toggleMenu);
    overlay?.addEventListener('click', closeMenu);

    // Cerrar al hacer click en un link del menú
    nav.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', closeMenu);
    });

    // Cerrar con Escape
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && nav.classList.contains('is-open')) {
        closeMenu();
      }
    });
  }

  return { init };

})();
