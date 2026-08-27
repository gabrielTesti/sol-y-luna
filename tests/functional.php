<?php
// Ejecutar: C:\xampp\php\php.exe tests/functional.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../data/products.php';
require_once __DIR__ . '/../data/categories.php';

function check($condition, string $message): void {
    if (!$condition) throw new RuntimeException($message);
    echo "OK: $message\n";
}

function render_home(): DOMXPath {
    global $products, $categories;
    ob_start();
    include __DIR__ . '/../index.php';
    $html = ob_get_clean();
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    libxml_clear_errors();
    return new DOMXPath($dom);
}

$page = render_home();
check($page->query('//article[@data-product-id]')->length === 9, 'Se renderizan los nueve productos');
check($page->query('//article[@data-is-custom="true"]')->length === 4, 'Cuatro pedidos especiales separados del carrito');
check($page->query('//article[@data-is-custom="true"]//button[contains(@class,"btn-add-cart")]')->length === 0, 'Los pedidos especiales no tienen botón Agregar');
foreach ($page->query('//img[@src] | //link[@rel="preload"]') as $element) {
    $path = $element->getAttribute($element->tagName === 'img' ? 'src' : 'href');
    check(is_file(__DIR__ . '/../' . $path), 'Imagen existente: ' . $path);
}
foreach ($page->query('//script[@src] | //link[@rel="stylesheet"]') as $element) {
    $path = $element->getAttribute($element->tagName === 'script' ? 'src' : 'href');
    if (strpos($path, 'assets/') !== 0) continue;
    check((bool)preg_match('/\?v=[a-f0-9]{12}$/', $path), 'Recurso versionado: ' . $path);
}
check($page->query('//img[@class="about-image__logo"]')->length === 1, 'HTML de la imagen de Nosotros corregido');
check(product_image('no-existe.jpg') === null, 'Fotos faltantes usan marcador visual');
check(strpos(rawurldecode(whatsapp_custom_link('120 lapiceras')), '120 lapiceras') !== false, 'Consulta personalizada conserva la idea');
check(strpos(whatsapp_link(), 'https://wa.me/5493424090182?text=') === 0, 'Número centralizado');
check(format_price(12000) === '$12.000', 'Formato de precio argentino');
$products[0]['is_available'] = false;
$products[1]['allows_cart'] = true;
$products[1]['price'] = 999;
$categories[1]['is_active'] = false;
$page = render_home();
check($page->query('//article[@data-product-id="1"]')->length === 0, 'Productos no disponibles ocultos');
check($page->query('//button[@data-category="sublimacion"]')->length === 0, 'Categorías inactivas ocultas');
check($page->query('//article[@data-product-id="2" and @data-price="null" and @data-allows-cart="false"]')->length === 1, 'Un pedido especial nunca calcula precio ni permite carrito aunque los datos sean inconsistentes');
