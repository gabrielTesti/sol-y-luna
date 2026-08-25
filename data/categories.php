<?php
/**
 * data/categories.php — Categorías de Sol & Luna
 * 
 * En V2, este array se reemplazará por una consulta MySQL:
 * SELECT * FROM categorias WHERE is_active = 1 ORDER BY display_order ASC
 * 
 * Estructura compatible con la futura tabla `categorias`:
 * id | name | slug | icon | display_order | is_active
 */

$categories = [
    [
        'id'            => 0,
        'name'          => 'Todos',
        'slug'          => 'todos',
        'icon'          => '✦',
        'display_order' => 0,
        'is_active'     => true,
    ],
    [
        'id'            => 1,
        'name'          => 'Sublimación',
        'slug'          => 'sublimacion',
        'icon'          => '🖨️',
        'display_order' => 1,
        'is_active'     => true,
    ],
    [
        'id'            => 2,
        'name'          => 'Resina',
        'slug'          => 'resina',
        'icon'          => '💎',
        'display_order' => 2,
        'is_active'     => true,
    ],
    [
        'id'            => 3,
        'name'          => 'Velas',
        'slug'          => 'velas',
        'icon'          => '🕯️',
        'display_order' => 3,
        'is_active'     => true,
    ],
    [
        'id'            => 4,
        'name'          => 'Souvenirs',
        'slug'          => 'souvenirs',
        'icon'          => '🎁',
        'display_order' => 4,
        'is_active'     => true,
    ],
    [
        'id'            => 5,
        'name'          => 'Regalos',
        'slug'          => 'regalos',
        'icon'          => '🎀',
        'display_order' => 5,
        'is_active'     => true,
    ],
    [
        'id'            => 6,
        'name'          => 'Decoración',
        'slug'          => 'decoracion',
        'icon'          => '🌿',
        'display_order' => 6,
        'is_active'     => true,
    ],
    [
        'id'            => 7,
        'name'          => 'Personalizados',
        'slug'          => 'personalizados',
        'icon'          => '✏️',
        'display_order' => 7,
        'is_active'     => true,
    ],
];
