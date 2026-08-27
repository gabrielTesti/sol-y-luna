# Sol & Luna — Instrucciones de instalación y uso

## Requisitos

- PHP 7.4 o superior (recomendado PHP 8.x)
- Servidor local: **XAMPP**, **WAMP**, **Laragon** o similar
- Navegador moderno

No se necesita base de datos para la V1.

---

## Instalación

1. Copiá la carpeta `sol-y-luna/` dentro de `htdocs/` (XAMPP) o `www/` (WAMP).
2. Abrí tu servidor local.
3. Entrá a `http://localhost/sol-y-luna/` en el navegador.

---

## Configuración inicial (obligatorio)

Abrí `config.php` y reemplazá estos valores:

```php
// Número de WhatsApp real (formato: 549 + código de área + número)
define('WHATSAPP_NUMBER', '5493424090182'); // ← CAMBIAR

// Instagram (ya configurado)
define('INSTAGRAM_HANDLE', 'sol_luna.ideas');
define('INSTAGRAM_URL',    'https://www.instagram.com/sol_luna.ideas');
```

---

## Estructura del proyecto

```
sol-y-luna/
├── index.php              ← Página principal
├── config.php             ← ⭐ Configuración centralizada
├── README.md
│
├── includes/
│   ├── header.php         ← Header + meta + CSS imports
│   ├── footer.php         ← Footer + JS imports
│   └── cart-drawer.php    ← Drawer del carrito
│
├── data/
│   ├── categories.php     ← Categorías (→ MySQL en V2)
│   └── products.php       ← Productos (→ MySQL en V2)
│
├── assets/
│   ├── css/
│   │   ├── variables.css  ← ⭐ Colores, fuentes, tokens
│   │   ├── main.css
│   │   ├── header.css
│   │   ├── hero.css
│   │   ├── gallery.css
│   │   ├── cart.css
│   │   ├── sections.css
│   │   └── responsive.css
│   │
│   ├── js/
│   │   ├── cart.js        ← Carrito + localStorage
│   │   ├── whatsapp.js    ← Mensajes de WhatsApp
│   │   ├── gallery.js     ← Filtros + tarjetas
│   │   ├── animations.js  ← Scroll + header + menú mobile
│   │   └── main.js        ← Inicialización
│   │
│   └── images/
│       ├── hero/          ← Foto principal del hero
│       ├── products/      ← Fotos de productos
│       └── about/         ← Fotos del proceso/espacio
```

---

## Agregar productos

Editá `data/products.php` y agregá un nuevo ítem al array `$products`:

```php
[
    'id'            => 10,               // Número único
    'name'          => 'Nombre del producto',
    'category_slug' => 'velas',          // Debe coincidir con una categoría
    'category_name' => 'Velas',
    'description'   => 'Descripción...',
    'price'         => 15000,            // null si es "a consultar"
    'has_variants'  => false,
    'variants'      => [],
    'images'        => ['mi-foto.jpg'],  // En assets/images/products/
    'is_featured'   => false,
    'is_available'  => true,
    'allows_cart'   => true,             // false = solo consulta
],
```

---

## Agregar fotos

- **Hero**: Colocar imagen en `assets/images/hero/hero-bg.jpg` y descomentar la línea `<img>` en `index.php` (sección Hero).
- **Productos**: Colocar imágenes en `assets/images/products/` y referenciar el nombre en `data/products.php`.
- **Nosotros / Proceso**: Colocar imagen en `assets/images/about/` y descomentar la línea `<img>` en la sección "Sobre Sol & Luna".

### Slider de personalizados y caché

Las fotos del slider se eligen en `assets/js/slider.js` y están en `assets/images/`.
La foto `velaaromatica2.jpg` ocupa el cuarto lugar. Este listado es independiente
de las tarjetas del catálogo (`data/products.php`).

`Untracked` significa que Git todavía no registró un archivo nuevo; no impide
que XAMPP o el navegador lo muestren. Guardá los cambios y refrescá localhost.
CSS y JavaScript llevan una versión calculada desde su contenido para evitar
caché vieja. Si ya tenías la página abierta, probá una vez `Ctrl + F5`.

El slider pausa el avance automático mientras tiene el mouse encima o el foco
del teclado; las flechas siguen funcionando. También respeta movimiento reducido.

### Reglas funcionales del catálogo

- Pedido hecho a medida: `is_custom => true` y `allows_cart => false`.
- Una variante llamada `Personalizado` abre consulta directa y no calcula precio.
- El filtro Personalizados incluye pedidos a medida y productos con esa variante.
- Las fotos inexistentes muestran el marcador visual de la categoría.
- Productos con `is_available => false` y categorías inactivas no se muestran.
- Al recuperar el carrito, se actualizan precios/fotos desde el catálogo y se
  descartan productos retirados, consultas personalizadas y cantidades inválidas.

### Pruebas locales (sin enviar mensajes reales)

```powershell
node --test tests/cart.test.cjs tests/slider.test.cjs
C:\xampp\php\php.exe tests/functional.php
```

Las pruebas JavaScript usan almacenamiento simulado. Node solo se necesita para
ejecutar estas pruebas, no para servir la V1. No publicar la carpeta `tests/`.

---

## Cambiar colores

Editá `assets/css/variables.css` — todos los colores están ahí como variables CSS:

```css
--color-primary:  #C17F5E; /* Terracota */
--color-accent:   #B8837A; /* Rosa viejo */
--color-text:     #6B4226; /* Marrón texto */
```

---

## Agregar una categoría

1. En `data/categories.php`, agregar al array `$categories`.
2. En `data/products.php`, usar el nuevo `category_slug` en los productos correspondientes.

---

## Migración a MySQL (V2)

Cuando estés lista para incorporar la base de datos:

1. Crear las tablas con esta estructura (compatible con los datos actuales):

```sql
CREATE TABLE categorias (
  id            INT PRIMARY KEY AUTO_INCREMENT,
  name          VARCHAR(100) NOT NULL,
  slug          VARCHAR(100) UNIQUE NOT NULL,
  icon          VARCHAR(10),
  display_order INT DEFAULT 0,
  is_active     TINYINT(1) DEFAULT 1
);

CREATE TABLE productos (
  id            INT PRIMARY KEY AUTO_INCREMENT,
  name          VARCHAR(255) NOT NULL,
  category_slug VARCHAR(100) NOT NULL,
  category_name VARCHAR(100) NOT NULL,
  description   TEXT,
  price         DECIMAL(10,2) DEFAULT NULL,
  has_variants  TINYINT(1) DEFAULT 0,
  variants      JSON DEFAULT NULL,
  images        JSON DEFAULT NULL,
  is_featured   TINYINT(1) DEFAULT 0,
  is_available  TINYINT(1) DEFAULT 1,
  allows_cart   TINYINT(1) DEFAULT 1,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

2. En `data/products.php`, reemplazar el array por una consulta PDO:

```php
require_once __DIR__ . '/../db.php'; // Archivo de conexión
$stmt = $pdo->query("SELECT * FROM productos WHERE is_available = 1 ORDER BY is_featured DESC, id ASC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
```

El resto de la aplicación **no necesita cambios**.

---

## Seguridad (cuando agregues admin en V2)

- Usar PDO con prepared statements
- `password_hash()` y `password_verify()` para passwords
- Sesiones PHP para proteger el panel admin
- Validar y sanitizar todos los inputs

---

*Sol & Luna V1 — Hecho con ❤️ en Santa Fe Capital*
