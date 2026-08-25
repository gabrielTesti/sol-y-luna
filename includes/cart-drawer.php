<?php
/**
 * includes/cart-drawer.php — Drawer del carrito
 * Se incluye en index.php justo antes del footer.
 */
require_once __DIR__ . '/../config.php';
?>

<!-- ─── OVERLAY del carrito ──────────────────────────────────────────────── -->
<div class="cart-overlay" id="cart-overlay" aria-hidden="true"></div>

<!-- ─── DRAWER del carrito ───────────────────────────────────────────────── -->
<aside class="cart-drawer"
       id="cart-drawer"
       role="dialog"
       aria-modal="true"
       aria-label="Mi carrito de consulta">

  <!-- Header -->
  <div class="cart-header">
    <h2 class="cart-title">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="1.8" aria-hidden="true">
        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
        <line x1="3" y1="6" x2="21" y2="6"/>
        <path d="M16 10a4 4 0 0 1-8 0"/>
      </svg>
      Mi carrito
    </h2>
    <button class="cart-close" id="cart-close" aria-label="Cerrar carrito">✕</button>
  </div>

  <!-- Cuerpo: lista de ítems -->
  <div class="cart-body">

    <!-- Estado vacío (visible cuando no hay ítems) -->
    <div class="cart-empty" id="cart-empty">
      <svg class="cart-empty-icon" width="48" height="48" viewBox="0 0 24 24"
           fill="none" stroke="currentColor" stroke-width="1.2" aria-hidden="true">
        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
        <line x1="3" y1="6" x2="21" y2="6"/>
        <path d="M16 10a4 4 0 0 1-8 0"/>
      </svg>
      <p>Tu carrito está vacío.</p>
      <p class="text-muted" style="font-size:0.85rem;">
        Explorá nuestra galería y agregá lo que te guste.
      </p>
      <a href="#galeria" class="btn btn-outline" id="cart-go-gallery">
        Ver galería
      </a>
    </div>

    <!-- Ítems (generados dinámicamente por cart.js) -->
    <div class="cart-items" id="cart-items" aria-live="polite"></div>

  </div>

  <!-- Footer: total + CTA WhatsApp -->
  <div class="cart-footer" id="cart-footer" hidden>

    <div class="cart-total">
      <span class="cart-total__label">Total estimado</span>
      <strong class="cart-total__amount" id="cart-total-amount">—</strong>
    </div>
    <small class="cart-total__note" id="cart-total-note" hidden></small>

    <button class="btn btn-whatsapp btn-send-cart" id="btn-send-cart"
            aria-label="Enviar pedido por WhatsApp">
      <!-- WhatsApp icon -->
      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
      </svg>
      Enviar pedido por WhatsApp
    </button>

    <p class="cart-footer-note" style="font-size:0.75rem; color:var(--color-text-light); text-align:center; margin-top:var(--space-3); line-height:1.45;">
      Al enviar te contactaremos para confirmar disponibilidad,<br>
      precio final y coordinar la entrega en Santa Fe Capital.
    </p>

  </div>

</aside>
