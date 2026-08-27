// Ejecutar: node --test tests/cart.test.cjs (sin dependencias ni almacenamiento real).
const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');
const cartSource = fs.readFileSync(path.join(__dirname, '../assets/js/cart.js'), 'utf8');
const waSource = fs.readFileSync(path.join(__dirname, '../assets/js/whatsapp.js'), 'utf8');
const product = { id: 1, name: 'Vela', price: 12000, images: [], allows_cart: true };

function harness(saved = null, cards = {}) {
  const nodes = new Map();
  const opened = [];
  const storage = { value: saved };
  function element() {
    return {
      hidden: false, innerHTML: '', textContent: '', dataset: {},
      classList: { add() {}, remove() {}, contains() { return false; } },
      addEventListener() {}, querySelectorAll() { return []; }, focus() {},
      setAttribute() {}, removeAttribute() {}, getAttribute() { return null; },
    };
  }
  const context = vm.createContext({
    console, setTimeout,
    document: {
      body: { style: {} }, addEventListener() {},
      getElementById(id) {
        if (!nodes.has(id)) nodes.set(id, element());
        return nodes.get(id);
      },
      querySelector(selector) {
        const id = selector.match(/^\[data-product-id="(\d+)"\]$/)?.[1];
        return cards[id] || null;
      },
    },
    localStorage: {
      getItem() { return storage.value; },
      setItem(key, value) { storage.value = value; },
    },
    window: {
      SOL_LUNA_CONFIG: {
        whatsappNumber: '5493424090182',
        customOrderMessage: 'Pedido personalizado\n*Producto o idea:*\n*Cantidad aproximada:*',
      },
      open(url) { opened.push(url); },
    },
    alert() {},
  });
  vm.runInContext(cartSource + '\n' + waSource, context);
  const cart = vm.runInContext('Cart', context);
  const wa = vm.runInContext('WhatsApp', context);
  cart.init();
  return { cart, wa, nodes, storage, opened };
}

function card({ price = '15000', allowsCart = 'true', variants = ['Lavanda'] } = {}) {
  return {
    dataset: { price, allowsCart, category: 'velas', image: '' },
    querySelector() { return { textContent: 'Vela actualizada' }; },
    querySelectorAll() {
      return variants.length ? [{ options: variants.map(value => ({ value })) }] : [];
    },
  };
}

test('Agregar, agrupar variantes, cantidades, eliminar y vaciar', () => {
  const { cart, nodes } = harness();
  assert.equal(cart.add(product, 'Lavanda'), true);
  cart.add(product, 'Lavanda');
  cart.add(product, 'Rosa');
  assert.equal(cart.getItems().length, 2);
  assert.equal(cart.getCount(), 3);
  assert.equal(cart.getTotal().total, 36000);
  assert.equal(nodes.get('cart-empty').hidden, true);
  assert.equal(nodes.get('cart-footer').hidden, false);
  cart.updateQty('1__Lavanda', -10);
  assert.equal(cart.getCount(), 2);
  cart.remove('1__Rosa');
  assert.equal(cart.getTotal().total, 12000);
  cart.clear();
  assert.equal(cart.getCount(), 0);
  assert.equal(nodes.get('cart-empty').hidden, false);
  assert.equal(nodes.get('cart-footer').hidden, true);
});

test('Rechazar personalizados y cantidades/precios inválidos', () => {
  const { cart } = harness();
  assert.equal(cart.add({ ...product, allows_cart: false }), false);
  assert.equal(cart.add(product, 'Personalizado'), false);
  for (const qty of [0, -1, 1.5, NaN, Infinity]) assert.equal(cart.add(product, '', qty), false);
  assert.equal(cart.add({ ...product, price: NaN }), false);
  assert.equal(cart.getCount(), 0);
});

test('Datos guardados corruptos no bloquean la página', () => {
  for (const saved of ['{', '{}', 'null', '42', '[null, {}, {"qty":-5}]']) {
    assert.equal(harness(saved).cart.getCount(), 0);
  }
});

test('Persistir y restaurar con catálogo vigente; no conservar precios/fotos viejos', () => {
  const first = harness();
  first.cart.add(product, 'Lavanda', 2);
  const restored = harness(first.storage.value, { 1: card() });
  assert.equal(restored.cart.getCount(), 2);
  assert.equal(restored.cart.getTotal().total, 30000);
  assert.equal(restored.cart.getItems()[0].name, 'Vela actualizada');
  assert.equal(restored.cart.getItems()[0].image, null);
  assert.equal(harness(first.storage.value, { 1: card({ allowsCart: 'false' }) }).cart.getCount(), 0);
  assert.equal(harness(first.storage.value, {}).cart.getCount(), 0);
});

test('Descartar variantes que ya no existen o requieren presupuesto', () => {
  const saved = JSON.stringify([{ productId: 1, variant: 'Personalizado', qty: 120 }]);
  assert.equal(harness(saved, { 1: card({ variants: ['Personalizado'] }) }).cart.getCount(), 0);
  const old = JSON.stringify([{ productId: 1, variant: 'Viejo', qty: 1 }]);
  assert.equal(harness(old, { 1: card() }).cart.getCount(), 0);
});

test('Escapar HTML en nombres y variantes al renderizar', () => {
  const { cart, nodes } = harness();
  cart.add({ ...product, name: '<script>alert(1)</script>' }, '<img src=x>');
  const html = nodes.get('cart-items').innerHTML;
  assert.ok(html.includes('&lt;script&gt;'));
  assert.ok(html.includes('&lt;img src=x&gt;'));
  assert.ok(!html.includes('<script>'));
});

test('WhatsApp genera una sola apertura, variantes, cantidades y total estimado', () => {
  const { cart, wa, opened } = harness();
  cart.add(product, 'Lavanda', 2);
  wa.sendCart();
  assert.equal(opened.length, 1);
  const url = new URL(opened[0]);
  assert.equal(url.pathname, '/5493424090182');
  const text = url.searchParams.get('text');
  assert.match(text, /Lavanda/);
  assert.match(text, /Cantidad: 2/);
  assert.match(text, /Total estimado: \$24.000/);
  wa.customOrder('120 lapiceras');
  assert.match(new URL(opened[1]).searchParams.get('text'), /Producto o idea:\* 120 lapiceras/);
});

test('Total mixto no incluye ítems a consultar', () => {
  const { cart, wa, opened } = harness();
  cart.add(product);
  cart.add({ ...product, id: 10, price: null });
  assert.equal(cart.getTotal().total, 12000);
  assert.equal(cart.getTotal().hasConsultItems, true);
  wa.sendCart();
  assert.match(new URL(opened[0]).searchParams.get('text'), /No incluye ítems con precio a consultar/);
});
