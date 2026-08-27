<?php
/**
 * data/products.php — Productos de Sol & Luna (V1 estática)
 *
 * En V2, este array se reemplazará por una consulta MySQL via PDO:
 *   $stmt = $pdo->prepare("SELECT * FROM productos WHERE is_available = 1");
 *   $stmt->execute();
 *   $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
 *
 * La estructura de campos es compatible con la futura tabla `productos`:
 *   id | name | category_slug | description | price | has_variants |
 *   variants (JSON) | images (JSON) | is_featured | is_available | allows_cart
 */

$products = [

    // ── VELAS ────────────────────────────────────────────────────────────────

    [
        'id'            => 1,
        'name'          => 'Vela aromática artesanal',
        'category_slug' => 'velas',
        'category_name' => 'Velas',
        'description'   => 'Vela de soja artesanal con fragancia natural. Tiempo de combustión aproximado: 30 horas. Contenedor de vidrio reutilizable.',
        'price'         => 12000,
        'has_variants'  => true,
        'variants'      => [
            ['name' => 'Aroma', 'options' => ['Lavanda', 'Vainilla', 'Coco', 'Rosa', 'Jazmín']],
        ],
        'images'        => ['placeholder-vela.jpg'],
        'is_featured'   => true,
        'is_available'  => true,
        'allows_cart'   => true,  // tiene precio → puede ir al carrito
    ],
    [
        'id'            => 2,
        'name'          => 'Vela personalizada — nombre o frase',
        'category_slug' => 'velas',
        'category_name' => 'Velas',
        'description'   => 'Vela artesanal con etiqueta personalizada. Ideal para regalar. El diseño, aroma y tamaño se coordinan por WhatsApp.',
        'price'         => null,  // precio según personalización
        'has_variants'  => false,
        'variants'      => [],
        'images'        => ['placeholder-vela-personalizada.jpg'],
        'is_featured'   => false,
        'is_available'  => true,
        'is_custom'     => true,
        'allows_cart'   => false, // Personalización a presupuestar por WhatsApp
    ],

    // ── RESINA ───────────────────────────────────────────────────────────────

    [
        'id'            => 3,
        'name'          => 'Pieza de resina — diseño floral',
        'category_slug' => 'resina',
        'category_name' => 'Resina',
        'description'   => 'Pieza artesanal de resina epoxi con flores prensadas. Cada pieza es única e irrepetible.',
        'price'         => 8500,
        'has_variants'  => false,
        'variants'      => [],
        'images'        => ['placeholder-resina.jpg'],
        'is_featured'   => true,
        'is_available'  => true,
        'allows_cart'   => true,
    ],
    [
        'id'            => 4,
        'name'          => 'Cuadro decorativo en resina',
        'category_slug' => 'resina',
        'category_name' => 'Resina',
        'description'   => 'Cuadro artesanal en resina con pigmentos y elementos naturales. Medidas, colores y diseño a coordinar.',
        'price'         => null,
        'has_variants'  => false,
        'variants'      => [],
        'images'        => ['placeholder-cuadro-resina.jpg'],
        'is_featured'   => false,
        'is_available'  => true,
        'is_custom'     => true,
        'allows_cart'   => false,
    ],

    // ── SUBLIMACIÓN ──────────────────────────────────────────────────────────

    [
        'id'            => 5,
        'name'          => 'Porta vaso sublimado',
        'category_slug' => 'sublimacion',
        'category_name' => 'Sublimación',
        'description'   => 'Porta vaso de neoprene sublimado con diseño personalizable. Resistente y lavable.',
        'price'         => 3500,
        'has_variants'  => true,
        'variants'      => [
            ['name' => 'Diseño', 'options' => ['Floral', 'Geométrico', 'Personalizado']],
        ],
        'images'        => ['placeholder-portavaso.jpg'],
        'is_featured'   => false,
        'is_available'  => true,
        'allows_cart'   => true,
    ],
    [
        'id'            => 6,
        'name'          => 'Llavero sublimado',
        'category_slug' => 'sublimacion',
        'category_name' => 'Sublimación',
        'description'   => 'Llavero acrílico sublimado con diseño o foto personalizable. Perfecto como souvenir o regalo.',
        'price'         => 2500,
        'has_variants'  => true,
        'variants'      => [
            ['name' => 'Forma', 'options' => ['Redondo', 'Rectangular', 'Corazón']],
        ],
        'images'        => ['placeholder-llavero.jpg'],
        'is_featured'   => false,
        'is_available'  => true,
        'allows_cart'   => true,
    ],

    // ── SOUVENIRS ────────────────────────────────────────────────────────────

    [
        'id'            => 7,
        'name'          => 'Souvenirs personalizados para eventos',
        'category_slug' => 'souvenirs',
        'category_name' => 'Souvenirs',
        'description'   => 'Souvenirs artesanales personalizados para cumpleaños, casamientos, bautismos y eventos especiales. Consultá por diseño y cantidad.',
        'price'         => null,
        'has_variants'  => false,
        'variants'      => [],
        'images'        => ['placeholder-souvenir.jpg'],
        'is_featured'   => true,
        'is_available'  => true,
        'is_custom'     => true,
        'allows_cart'   => false, // Solo consulta: pedido personalizado por cantidad
    ],

    // ── REGALOS ──────────────────────────────────────────────────────────────

    [
        'id'            => 8,
        'name'          => 'Set regalo artesanal',
        'category_slug' => 'regalos',
        'category_name' => 'Regalos',
        'description'   => 'Set de regalo que incluye vela + pieza de resina + detalle. Presentación en caja kraft decorada a mano.',
        'price'         => 22000,
        'has_variants'  => false,
        'variants'      => [],
        'images'        => ['placeholder-set-regalo.jpg'],
        'is_featured'   => true,
        'is_available'  => true,
        'allows_cart'   => true,
    ],

    // ── DECORACIÓN ───────────────────────────────────────────────────────────

    [
        'id'            => 9,
        'name'          => 'Maceta decorativa artesanal',
        'category_slug' => 'decoracion',
        'category_name' => 'Decoración',
        'description'   => 'Maceta de cerámica o yeso decorada a mano con técnica artesanal. Colores y diseño a coordinar.',
        'price'         => null,
        'has_variants'  => false,
        'variants'      => [],
        'images'        => ['placeholder-maceta.jpg'],
        'is_featured'   => false,
        'is_available'  => true,
        'is_custom'     => true,
        'allows_cart'   => false,
    ],

];
