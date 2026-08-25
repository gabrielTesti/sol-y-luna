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
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Filtrar tarjetas
        _filterCards(category);
      });
    });
  }

  function _filterCards(category) {
    const cards = document.querySelectorAll('.product-card');

    cards.forEach((card, i) => {
      const cardCategory = card.dataset.category;
      const shouldShow   = category === 'todos' || cardCategory === category;

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

    grid.addEventListener('click', e => {

      // Botón "Agregar al carrito"
      const addBtn = e.target.closest('.btn-add-cart');
      if (addBtn) {
        _handleAddToCart(addBtn);
        return;
      }

      // Botón "Consultar por WhatsApp"
      const consultBtn = e.target.closest('.btn-consult');
      if (consultBtn) {
        _handleConsult(consultBtn);
        return;
      }

    });
  }

  function _handleAddToCart(btn) {
    const card = btn.closest('.product-card');
    if (!card) return;

    const productId = card.dataset.productId;
    const product   = _getProductData(card);

    // Obtener variante seleccionada si existe
    const variantSelect = card.querySelector('.product-card__variants select');
    const variant = variantSelect ? variantSelect.value : '';

    if (typeof Cart !== 'undefined') {
      Cart.add(product, variant, 1);

      // Feedback en el botón
      const originalText = btn.textContent;
      btn.textContent = '✓ Agregado';
      btn.disabled = true;

      setTimeout(() => {
        btn.textContent = originalText;
        btn.disabled = false;
      }, 1500);
    }
  }

  function _handleConsult(btn) {
    const card = btn.closest('.product-card');
    if (!card) return;

    const productName = card.querySelector('.product-card__name')?.textContent || 'este producto';

    if (typeof WhatsApp !== 'undefined') {
      WhatsApp.consultProduct(productName.trim());
    }
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
