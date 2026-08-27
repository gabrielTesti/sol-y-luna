<?php
/**
 * config.php — Configuración centralizada de Sol & Luna
 * Modificar este archivo para actualizar datos sin tocar el resto del código.
 */

// ─── Datos del emprendimiento ──────────────────────────────────────────────────

define('SITE_NAME',        'Sol & Luna');
define('SITE_TAGLINE',     'Artesanías únicas hechas a mano para momentos especiales.');
define('SITE_DESCRIPTION', 'En Sol & Luna creamos piezas artesanales hechas con dedicación, transformando ideas en detalles únicos para regalar, decorar y acompañar momentos especiales.');

// ─── Contacto ──────────────────────────────────────────────────────────────────

// Número en formato internacional sin + ni espacios: 549 + código de área + número
// Ejemplo Santa Fe: 5493424XXXXXX
define('WHATSAPP_NUMBER',  '5493424090182');

define('INSTAGRAM_HANDLE', 'sol_luna.ideas');
define('INSTAGRAM_URL',    'https://www.instagram.com/sol_luna.ideas');

// ─── Ubicación y entregas ──────────────────────────────────────────────────────

define('LOCATION',       'Santa Fe Capital, Argentina');
define('DELIVERY_INFO',  'Entregas en Santa Fe Capital · Punto de encuentro o domicilio');
define('DELIVERY_DETAIL','Las entregas se coordinan por WhatsApp dentro de Santa Fe Capital. La modalidad (punto de encuentro o domicilio) y cualquier costo asociado se confirman según cada pedido.');

// ─── SEO ───────────────────────────────────────────────────────────────────────

define('META_TITLE',       'Sol & Luna | Artesanías y personalizados en Santa Fe');
define('META_DESCRIPTION', 'Artesanías únicas hechas a mano en Santa Fe Capital. Sublimación, resina, velas, souvenirs y regalos personalizados. Consultá por tu pedido especial.');
define('META_KEYWORDS',    'artesanías Santa Fe, personalizados Santa Fe, regalos personalizados, souvenirs, sublimación, resina, velas, productos artesanales, Santa Fe Capital');
define('META_OG_IMAGE',    'assets/images/favicon.png');

// ─── Mensajes de WhatsApp (plantillas PHP para enlaces estáticos) ──────────────

define('WA_CUSTOM_ORDER_MSG',
    "Hola! 👋 Quería consultar por un *pedido personalizado* de Sol & Luna.\n\n" .
    "✏️ *Producto o idea:*\n\n" .
    "📦 *Cantidad aproximada:*\n\n" .
    "📅 *Fecha en que lo necesito:*\n\n" .
    "🎨 *Detalles de personalización* (colores, texto, temática, etc.):\n\n" .
    "Quería consultar si es posible realizarlo y conocer el presupuesto. ¡Muchas gracias! 🌟"
);

define('WA_INDIVIDUAL_MSG',
    "Hola! 👋 Vi en la página de Sol & Luna el producto *%s* y quería hacer una consulta."
);

define('WA_GENERAL_MSG',
    "Hola! 👋 Me comunico desde la página de Sol & Luna. Quería hacer una consulta."
);

// ─── Funciones helper ──────────────────────────────────────────────────────────

/** Actualiza la URL cuando cambia el archivo para evitar versiones en caché. */
function asset_url(string $path): string {
    $file = __DIR__ . '/' . $path;
    return is_file($file) ? $path . '?v=' . substr(sha1_file($file), 0, 12) : $path;
}

/** No generar enlaces rotos mientras se completan las fotos del catálogo. */
function product_image(?string $filename): ?string {
    return $filename && is_file(__DIR__ . '/assets/images/products/' . $filename)
        ? $filename : null;
}

/**
 * Genera un enlace wa.me con mensaje pre-armado.
 */
function whatsapp_link(string $message = ''): string {
    return 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . rawurlencode($message ?: WA_GENERAL_MSG);
}

/**
 * Genera enlace de consulta individual por producto.
 */
function whatsapp_product_link(string $product_name): string {
    return whatsapp_link(sprintf(WA_INDIVIDUAL_MSG, $product_name));
}

/**
 * Genera enlace para pedidos personalizados.
 */
function whatsapp_custom_link(string $product_name = ''): string {
    $message = WA_CUSTOM_ORDER_MSG;
    if ($product_name !== '') {
        $message = str_replace('*Producto o idea:*', '*Producto o idea:* ' . $product_name, $message);
    }
    return whatsapp_link($message);
}

/**
 * Formatea precio en pesos argentinos.
 * @param int|float|null $price
 */
function format_price($price): string {
    if ($price === null) return 'Consultar precio';
    return '$' . number_format((float)$price, 0, ',', '.');
}
