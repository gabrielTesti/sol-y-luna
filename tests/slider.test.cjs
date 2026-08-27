const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');
const source = fs.readFileSync(path.join(__dirname, '../assets/js/slider.js'), 'utf8');

function setup() {
  let time = 0, timerId = 0;
  const timers = new Map();
  const loaded = [];
  function element() {
    const classes = new Set();
    return {
      disabled: false, listeners: {},
      addEventListener(name, fn) { this.listeners[name] = fn; },
      classList: { add(...names) { names.forEach(n => classes.add(n)); }, remove(...names) { names.forEach(n => classes.delete(n)); } },
      matches() { return false; }, contains() { return false; },
    };
  }
  const slider = element(), prev = element(), next = element(), image = element();
  image.src = 'assets/images/llaveroresina.jpg';
  image.closest = () => slider;
  const document = { ...element(), hidden: false, activeElement: null,
    getElementById(id) { return { 'custom-prev': prev, 'custom-next': next, 'custom-slider-img': image }[id]; },
  };
  const schedule = (fn, ms) => { const id = ++timerId; timers.set(id, { at: time + ms, fn }); return id; };
  const context = vm.createContext({
    document,
    window: { matchMedia: () => ({ matches: false, addEventListener() {} }) },
    Image: class { constructor() { loaded.push(this); } },
    setTimeout: schedule, clearTimeout: id => timers.delete(id),
    setInterval: () => ++timerId, clearInterval() {},
    requestAnimationFrame: fn => schedule(fn, 16),
  });
  vm.runInContext(source, context);
  document.listeners.DOMContentLoaded();
  function tick(ms) {
    const until = time + ms;
    while (true) {
      const entry = [...timers.entries()].sort((a, b) => a[1].at - b[1].at)[0];
      if (!entry || entry[1].at > until) break;
      time = entry[1].at;
      timers.delete(entry[0]);
      entry[1].fn();
    }
    time = until;
  }
  return { image, next, prev, loaded, tick };
}

test('La cuarta foto es la nueva vela; las flechas funcionan en ambos sentidos', () => {
  const s = setup();
  for (let i = 0; i < 3; i++) {
    s.next.listeners.click();
    s.loaded.at(-1).onload();
    s.tick(500);
  }
  assert.equal(s.image.src, 'assets/images/velaaromatica2.jpg');
  assert.equal(s.next.disabled, false);
  s.prev.listeners.click();
  s.loaded.at(-1).onload();
  s.tick(500);
  assert.equal(s.image.src, 'assets/images/lapicerasresinajpg.jpg');
});

test('Foto fallida conserva la anterior y permite continuar', () => {
  const s = setup();
  s.next.listeners.click();
  s.loaded.at(-1).onerror();
  assert.equal(s.image.src, 'assets/images/llaveroresina.jpg');
  assert.equal(s.next.disabled, false);
  s.next.listeners.click();
  s.loaded.at(-1).onload();
  s.tick(500);
  assert.equal(s.image.src, 'assets/images/lapicerasresinajpg.jpg');
});

test('Una descarga que no termina libera las flechas después del límite', () => {
  const s = setup();
  s.next.listeners.click();
  assert.equal(s.next.disabled, true);
  s.tick(8000);
  assert.equal(s.next.disabled, false);
  assert.equal(s.loaded.at(-1).onload, null);
});
