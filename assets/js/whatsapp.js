/**
 * whatsapp.js — Generación de mensajes y enlaces de WhatsApp
 * 
 * Responsabilidades:
 *  - Construir mensajes para el carrito
 *  - Construir mensajes para consultas individuales
 *  - Construir mensaje para pedidos personalizados
 *  - Abrir wa.me/ con el mensaje codificado
 */

'use strict';

const WhatsApp = (() => {

  // Número cargado desde la config inyectada por PHP en footer.php
  const getNumber = () => window.SOL_LUNA_CONFIG?.whatsappNumber || '';

  /**
   * Construye la URL de WhatsApp con mensaje codificado.
   * @param {string} message
   * @returns {string}
   */
  function buildUrl(message) {
    return `https://wa.me/${getNumber()}?text=${encodeURIComponent(message)}`;
  }

  /**
   * Abre WhatsApp en una nueva pestaña.
   * @param {string} message
   */
  function open(message) {
    window.open(buildUrl(message), '_blank', 'noopener,noreferrer');
  }

  /**
   * Genera el mensaje para el carrito completo y abre WhatsApp.
   * Llamado desde cart.js al presionar "ENVIAR PEDIDO POR WHATSAPP".
   */
  function sendCart() {
    const items = Cart.getItems();

    if (items.length === 0) {
      alert('Tu carrito está vacío. Agregá productos antes de enviar.');
      return;
    }

    const { total, hasConsultItems } = Cart.getTotal();

    // ─── Construir mensaje ─────────────────────────────────────────────────
    let msg = `Hola! 👋 Soy cliente de *Sol & Luna* y quiero consultar sobre los siguientes productos:\n\n`;
    msg += `🛍️ *Mi selección:*\n\n`;

    items.forEach((item, i) => {
      msg += `${i + 1}. *${item.name}*`;
      if (item.variant) msg += ` — ${item.variant}`;
      msg += `\n`;
      msg += `   Cantidad: ${item.qty}\n`;

      if (item.price !== null) {
        const subtotal = item.price * item.qty;
        msg += `   Precio: $${_fmt(item.price)} c/u`;
        if (item.qty > 1) msg += ` (subtotal: $${_fmt(subtotal)})`;
      } else {
        msg += `   Precio: a consultar`;
      }
      msg += `\n\n`;
    });

    // ─── Total ─────────────────────────────────────────────────────────────
    if (total > 0 && !hasConsultItems) {
      msg += `💰 *Total: $${_fmt(total)}*\n\n`;
    } else if (total > 0 && hasConsultItems) {
      msg += `💰 *Total estimado: $${_fmt(total)}*\n`;
      msg += `_(No incluye ítems con precio a consultar)_\n\n`;
    } else {
      msg += `_(Los precios se confirmarán por WhatsApp)_\n\n`;
    }

    // ─── Cierre ────────────────────────────────────────────────────────────
    msg += `📍 Me gustaría saber disponibilidad y coordinar los detalles del pedido y la entrega.\n`;
    msg += `¡Muchas gracias! 😊`;

    open(msg);
  }

  /**
   * Consulta individual por un producto específico.
   * @param {string} productName
   */
  function consultProduct(productName) {
    const msg =
      `Hola! 👋 Vi en la página de *Sol & Luna* el producto *${productName}* y quería hacer una consulta.`;
    open(msg);
  }

  /**
   * Pedido personalizado — abre WhatsApp con plantilla para completar.
   */
  function customOrder() {
    const msg =
      `Hola! 👋 Quería consultar por un *pedido personalizado* de Sol & Luna.\n\n` +
      `✏️ *Producto o idea:*\n\n` +
      `📦 *Cantidad aproximada:*\n\n` +
      `📅 *Fecha en que lo necesito:*\n\n` +
      `🎨 *Detalles de personalización* (colores, texto, temática, etc.):\n\n` +
      `Quería consultar si es posible realizarlo y conocer el presupuesto. ¡Muchas gracias! 🌟`;
    open(msg);
  }

  /**
   * Contacto general.
   */
  function generalContact() {
    const msg = `Hola! 👋 Me comunico desde la página de *Sol & Luna*. Quería hacer una consulta.`;
    open(msg);
  }

  // ─── Helpers ──────────────────────────────────────────────────────────────────

  function _fmt(n) {
    return Number(n).toLocaleString('es-AR');
  }

  // ─── Exponer API ──────────────────────────────────────────────────────────────

  return { sendCart, consultProduct, customOrder, generalContact, buildUrl };

})();
