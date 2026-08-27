/**
 * gallery.js — Filtros de categoría y comportamiento de la galería
 */

'use strict';

const Gallery = (() => {

  let currentFilter = 'todos';

  function init() {
    _bindFilters();
    _bindProductButtons();
  }

  // ─── Filtros ─────────────────────────────────────────────────────────────────

  function _bindFilters() {
    const filterBtns = document.querySelectorAll('.filter-btn');

    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const category = btn.dataset.category;
        if (category === currentFilter) return;

        currentFilter = category;

        // Actualizar estado activo
        filterBtns.forEach(b => {
          b.classList.toggle('active', b === btn);
          b.setAttribute('aria-pressed', String(b === btn));
        });

        // Filtrar tarjetas
        _filterCards(category);
      });
    });
  }

  function _filterCards(category) {
    const cards = document.querySelectorAll('.product-card');

    cards.forEach((card, i) => {
      const cardCategory = card.dataset.category;
      const hasCustomOption = [...card.querySelectorAll('option')]
        .some(option => option.value.toLowerCase() === 'personalizado');
      const shouldShow = category === 'todos' || cardCategory === category ||
        (category === 'personalizados' && (card.dataset.isCustom === 'true' || hasCustomOption));

      if (shouldShow) {
        card.classList.remove('is-hidden');
        // Escalonar la aparición
        card.style.transitionDelay = `${i * 40}ms`;
        requestAnimationFrame(() => card.classList.add('is-visible'));
      } else {
        card.classList.add('is-hidden');
        card.style.transitionDelay = '0ms';
        card.classList.remove('is-visible');
      }
    });
  }

  // ─── Botones en tarjetas ──────────────────────────────────────────────────────

  function _bindProductButtons() {
    // Delegación en el grid para manejar dinámicos
    const grid = document.getElementById('products-grid');
    if (!grid) return;

    grid.querySelectorAll('.product-card').forEach(card => _syncVariantActions(card));
    grid.addEventListener('change', e => {
      const card = e.target.closest('.product-card');
      if (card) _syncVariantActions(card);
    });

    grid.addEventListener('click', e => {

      // Botón "Agregar al carrito"
      const addBtn = e.target.closest('.btn-add-cart');
      if (addBtn) {
        _handleAddToCart(addBtn);
        return;
      }

      // Los enlaces Consultar navegan normalmente: una sola apertura de WhatsApp.

    });
  }

  function _handleAddToCart(btn) {
    const card = btn.closest('.product-card');
    if (!card) return;

    const product   = _getProductData(card);

    const variant = _selectedVariants(card).join(' / ');
    if (_isCustomSelection(card)) {
      WhatsApp.customOrder(product.name);
      return;
    }

    if (typeof Cart !== 'undefined') {
      if (!Cart.add(product, variant, 1)) return;

      // Feedback en el botón
      const originalText = btn.textContent;
      btn.textContent = '✓ Agregado';
      btn.disabled = true;

      setTimeout(() => {
        btn.textContent = originalText;
        btn.disabled = false;
        _syncVariantActions(card);
      }, 1500);
    }
  }

  function _selectedVariants(card) {
    return [...card.querySelectorAll('.product-card__variants select')].map(select => select.value);
  }

  function _isCustomSelection(card) {
    return _selectedVariants(card).some(value => value.toLowerCase() === 'personalizado');
  }

  function _syncVariantActions(card) {
    const custom = _isCustomSelection(card);
    const addBtn = card.querySelector('.btn-add-cart');
    const consult = card.querySelector('.btn-consult');
    const name = card.querySelector('.product-card__name')?.textContent.trim() || '';
    if (addBtn && !addBtn.disabled) {
      addBtn.textContent = custom ? 'Consultar diseño' : 'Agregar';
      addBtn.setAttribute('aria-label', custom ? `Consultar diseño de ${name}` : `Agregar ${name} al carrito`);
    }
    if (consult) {
      if (!consult.dataset.baseUrl) consult.dataset.baseUrl = consult.href;
      const url = new URL(custom ? card.dataset.customUrl : consult.dataset.baseUrl);
      const variants = _selectedVariants(card).join(' / ');
      if (!custom && variants) {
        url.searchParams.set('text', url.searchParams.get('text') + `\nVariante: ${variants}`);
      }
      consult.href = url.toString();
    }
    const price = card.querySelector('.product-card__price');
    if (price) price.hidden = custom;
  }

  /**
   * Extrae los datos del producto desde los atributos del DOM.
   * En V1 los datos vienen del HTML generado por PHP.
   */
  function _getProductData(card) {
    return {
      id:           parseInt(card.dataset.productId, 10),
      name:         card.querySelector('.product-card__name')?.textContent?.trim() || '',
      category_name:card.dataset.category || '',
      price:        card.dataset.price !== undefined
                      ? (card.dataset.price === 'null' ? null : parseFloat(card.dataset.price))
                      : null,
      images:       card.dataset.image ? [card.dataset.image] : [],
      allows_cart:  card.dataset.allowsCart === 'true',
    };
  }

  return { init };

})();
