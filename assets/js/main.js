/**
 * main.js — Inicialización de Sol & Luna
 * Punto de entrada: se ejecuta cuando el DOM está listo.
 */

'use strict';

document.addEventListener('DOMContentLoaded', () => {

  // Inicializar módulos
  Cart.init();
  Gallery.init();
  Animations.init();

  // Vincular botones de WhatsApp estáticos de la página
  _bindStaticWhatsAppButtons();

  // Smooth scroll para los links del nav que van a anclas
  _initSmoothScroll();

  // Resaltar link activo en el nav al hacer scroll
  _initActiveNav();

});

// ─── Botones WhatsApp estáticos ─────────────────────────────────────────────────

function _bindStaticWhatsAppButtons() {

  // Botón del hero "Consultar por WhatsApp"
  document.querySelectorAll('[data-wa="hero"]').forEach(btn => {
    btn.addEventListener('click', () => WhatsApp.generalContact());
  });

  // Botón "Contanos tu idea" (personalizados)
  document.querySelectorAll('[data-wa="custom"]').forEach(btn => {
    btn.addEventListener('click', () => WhatsApp.customOrder());
  });

  // Contacto general (sección de contacto, footer)
  document.querySelectorAll('[data-wa="contact"]').forEach(btn => {
    btn.addEventListener('click', () => WhatsApp.generalContact());
  });

}

// ─── Smooth scroll personalizado ─────────────────────────────────────────────────
// (CSS scroll-behavior: smooth ya maneja la mayoría de los casos,
// pero esto ajusta el offset para el header fijo)

function _initSmoothScroll() {
  const headerHeight = parseInt(
    getComputedStyle(document.documentElement).getPropertyValue('--header-height') || '70',
    10
  );

  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', e => {
      const targetId = anchor.getAttribute('href').slice(1);
      if (!targetId) return;

      const target = document.getElementById(targetId);
      if (!target) return;

      e.preventDefault();

      const offsetTop = target.getBoundingClientRect().top + window.scrollY - headerHeight - 16;

      window.scrollTo({ top: offsetTop, behavior: 'smooth' });
    });
  });
}

// ─── Nav activo al hacer scroll ───────────────────────────────────────────────────

function _initActiveNav() {
  const sections = document.querySelectorAll('section[id]');
  const navLinks  = document.querySelectorAll('.nav-link');

  if (!sections.length || !navLinks.length) return;

  const headerHeight = 80;

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;

      const id = entry.target.id;
      navLinks.forEach(link => {
        const href = link.getAttribute('href');
        link.classList.toggle('is-active', href === `#${id}`);
      });
    });
  }, {
    rootMargin: `-${headerHeight}px 0px -60% 0px`,
    threshold: 0,
  });

  sections.forEach(s => observer.observe(s));
}
