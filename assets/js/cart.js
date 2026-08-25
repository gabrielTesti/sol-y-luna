/**
 * cart.js — Lógica del carrito de Sol & Luna
 * 
 * Responsabilidades:
 *  - Agregar / actualizar / eliminar ítems
 *  - Persistencia en localStorage
 *  - Renderizar el drawer del carrito
 *  - Actualizar el contador del header
 *  - Abrir y cerrar el drawer
 */

'use strict';

const Cart = (() => {

  // ─── Clave de localStorage ──────────────────────────────────────────────────
  const STORAGE_KEY = 'solyluna_cart_v1';

  // ─── Estado ─────────────────────────────────────────────────────────────────
  let items = [];

  // ─── Init ───────────────────────────────────────────────────────────────────
  function init() {
    _load();
    _bindDrawer();
    _render();
  }

  // ─── Persistencia ───────────────────────────────────────────────────────────

  function _load() {
    try {
      const saved = localStorage.getItem(STORAGE_KEY);
      items = saved ? JSON.parse(saved) : [];
    } catch {
      items = [];
    }
  }

  function _save() {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
    } catch {
      console.warn('Sol & Luna: no se pudo guardar el carrito.');
    }
  }

  // ─── API pública ─────────────────────────────────────────────────────────────

  /**
   * Agrega un producto al carrito.
   * @param {Object} product  - Datos del producto
   * @param {string} variant  - Variante seleccionada (opcional)
   * @param {number} qty      - Cantidad inicial (default 1)
   */
  function add(product, variant = '', qty = 1) {
    const cartItemId = _buildId(product.id, variant);
    const existing   = items.find(i => i.cartItemId === cartItemId);

    if (existing) {
      existing.qty += qty;
    } else {
      items.push({
        cartItemId,
        productId:   product.id,
        name:        product.name,
        category:    product.category_name || '',
        price:       product.price,         // null = a consultar
        image:       product.images?.[0] || null,
        variant,
        qty,
        allowsCart:  product.allows_cart,
      });
    }

    _save();
    _render();
    _animateCount();

    // Feedback visual en el botón
    const btn = document.querySelector(`[data-product-id="${product.id}"] .btn-add-cart`);
    if (btn) {
      btn.classList.add('adding');
      btn.addEventListener('animationend', () => btn.classList.remove('adding'), { once: true });
    }
  }

  /**
   * Cambia la cantidad de un ítem.
   * @param {string} cartItemId
   * @param {number} delta  +1 o -1
   */
  function updateQty(cartItemId, delta) {
    const item = items.find(i => i.cartItemId === cartItemId);
    if (!item) return;

    item.qty = Math.max(1, item.qty + delta);
    _save();
    _render();
  }

  /**
   * Elimina un ítem del carrito.
   * @param {string} cartItemId
   */
  function remove(cartItemId) {
    items = items.filter(i => i.cartItemId !== cartItemId);
    _save();
    _render();
  }

  /**
   * Vacía el carrito.
   */
  function clear() {
    items = [];
    _save();
    _render();
  }

  /**
   * Retorna todos los ítems actuales.
   */
  function getItems() {
    return [...items];
  }

  /**
   * Retorna el total de unidades en el carrito.
   */
  function getCount() {
    return items.reduce((sum, i) => sum + i.qty, 0);
  }

  /**
   * Retorna el total estimado (solo ítems con precio).
   * @returns {{ total: number, hasConsultItems: boolean }}
   */
  function getTotal() {
    let total = 0;
    let hasConsultItems = false;

    for (const item of items) {
      if (item.price !== null) {
        total += item.price * item.qty;
      } else {
        hasConsultItems = true;
      }
    }

    return { total, hasConsultItems };
  }

  // ─── Render ──────────────────────────────────────────────────────────────────

  function _render() {
    _renderCount();
    _renderItems();
    _renderTotal();
  }

  function _renderCount() {
    const badge = document.getElementById('cart-count');
    if (!badge) return;
    const count = getCount();
    badge.textContent = count;
    badge.hidden = count === 0;
  }

  function _renderItems() {
    const container = document.getElementById('cart-items');
    const emptyEl   = document.getElementById('cart-empty');
    if (!container) return;

    if (items.length === 0) {
      container.innerHTML = '';
      if (emptyEl) emptyEl.hidden = false;
      return;
    }

    if (emptyEl) emptyEl.hidden = true;

    container.innerHTML = items.map(item => _itemHTML(item)).join('');

    // Vincular botones de cantidad y eliminar
    container.querySelectorAll('.cart-qty-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const id    = btn.dataset.id;
        const delta = parseInt(btn.dataset.delta, 10);
        updateQty(id, delta);
      });
    });

    container.querySelectorAll('.cart-item__remove').forEach(btn => {
      btn.addEventListener('click', () => remove(btn.dataset.id));
    });
  }

  function _itemHTML(item) {
    const priceHTML = item.price !== null
      ? `<span class="cart-item__price">$${_fmt(item.price * item.qty)} <small>(${_fmt(item.price)} c/u)</small></span>`
      : `<span class="cart-item__price cart-item__price--consult">Precio a consultar</span>`;

    const variantHTML = item.variant
      ? `<span class="cart-item__variant">${item.variant}</span>`
      : '';

    const imgHTML = item.image
      ? `<img class="cart-item__image" src="assets/images/products/${item.image}" alt="${_esc(item.name)}" loading="lazy">`
      : `<div class="cart-item__image cart-item__image--placeholder" aria-hidden="true">🛍️</div>`;

    return `
      <article class="cart-item" data-cart-item-id="${_esc(item.cartItemId)}">
        ${imgHTML}
        <div class="cart-item__info">
          <span class="cart-item__name" title="${_esc(item.name)}">${_esc(item.name)}</span>
          ${variantHTML}
          ${priceHTML}
          <div class="cart-item__qty" role="group" aria-label="Cantidad de ${_esc(item.name)}">
            <button class="cart-qty-btn"
                    data-id="${_esc(item.cartItemId)}"
                    data-delta="-1"
                    aria-label="Reducir cantidad">−</button>
            <span class="cart-qty-value" aria-live="polite">${item.qty}</span>
            <button class="cart-qty-btn"
                    data-id="${_esc(item.cartItemId)}"
                    data-delta="1"
                    aria-label="Aumentar cantidad">+</button>
          </div>
        </div>
        <button class="cart-item__remove"
                data-id="${_esc(item.cartItemId)}"
                aria-label="Eliminar ${_esc(item.name)} del carrito">✕</button>
      </article>
    `;
  }

  function _renderTotal() {
    const footer    = document.getElementById('cart-footer');
    const amountEl  = document.getElementById('cart-total-amount');
    const noteEl    = document.getElementById('cart-total-note');

    if (!footer) return;
    footer.hidden = items.length === 0;

    if (items.length === 0) return;

    const { total, hasConsultItems } = getTotal();

    if (amountEl) {
      amountEl.textContent = total > 0 ? `$${_fmt(total)}` : '—';
    }

    if (noteEl) {
      if (hasConsultItems && total > 0) {
        noteEl.textContent = '* No incluye ítems con precio a consultar';
        noteEl.hidden = false;
      } else if (hasConsultItems && total === 0) {
        noteEl.textContent = 'Los precios se confirmarán por WhatsApp';
        noteEl.hidden = false;
      } else {
        noteEl.hidden = true;
      }
    }
  }

  // ─── Drawer ──────────────────────────────────────────────────────────────────

  function open() {
    const drawer  = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-overlay');
    const cartBtn = document.getElementById('cart-btn');

    drawer?.classList.add('is-open');
    overlay?.classList.add('is-visible');
    cartBtn?.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';

    // Focus en el botón de cierre para accesibilidad
    setTimeout(() => document.getElementById('cart-close')?.focus(), 50);
  }

  function close() {
    const drawer  = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-overlay');
    const cartBtn = document.getElementById('cart-btn');

    drawer?.classList.remove('is-open');
    overlay?.classList.remove('is-visible');
    cartBtn?.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
    cartBtn?.focus();
  }

  function _bindDrawer() {
    document.getElementById('cart-btn')?.addEventListener('click', open);
    document.getElementById('cart-close')?.addEventListener('click', close);
    document.getElementById('cart-overlay')?.addEventListener('click', close);

    // Cerrar con Escape
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && document.getElementById('cart-drawer')?.classList.contains('is-open')) {
        close();
      }
    });

    // Botón "Enviar pedido por WhatsApp"
    document.getElementById('btn-send-cart')?.addEventListener('click', () => {
      if (typeof WhatsApp !== 'undefined') {
        WhatsApp.sendCart();
      }
    });
  }

  // ─── Helpers ─────────────────────────────────────────────────────────────────

  function _buildId(productId, variant) {
    return variant ? `${productId}__${variant}` : String(productId);
  }

  function _fmt(n) {
    return Number(n).toLocaleString('es-AR');
  }

  function _esc(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function _animateCount() {
    const badge = document.getElementById('cart-count');
    if (!badge) return;
    badge.classList.remove('bump');
    void badge.offsetWidth; // reflow para reiniciar la animación
    badge.classList.add('bump');
  }

  // ─── Exponer API pública ──────────────────────────────────────────────────────

  return { init, add, updateQty, remove, clear, getItems, getCount, getTotal, open, close };

})();
