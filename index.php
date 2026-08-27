<?php

/**
 * index.php — Página principal de Sol & Luna
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/data/categories.php';
require_once __DIR__ . '/data/products.php';

include __DIR__ . '/includes/header.php';
?>

<!-- ═══════════════════════════════════════════════════════════════════════════
     SECCIÓN 1: HERO
     ═══════════════════════════════════════════════════════════════════════════ -->

<section class="hero" id="inicio" aria-label="Inicio">

  <!-- Imagen de fondo (reemplazar hero-bg.jpg con una foto real) -->
  <div class="hero-bg">
    <div class="hero-bg--placeholder" aria-hidden="true"></div>
    <!--
      Cuando tengas la foto, reemplazar la línea anterior por:
      <img src="assets/images/hero/hero-bg.jpg"
           alt="Artesanías Sol & Luna — piezas hechas a mano"
           loading="eager" fetchpriority="high">
    -->
  </div>

  <div class="hero-overlay" aria-hidden="true"></div>

  <div class="hero-content container">

    <span class="hero-pretitle">
      Artesanías únicas · Santa Fe Capital
    </span>

    <h1 class="hero-title">Sol &amp; Luna</h1>

    <p class="hero-subtitle">
      Piezas artesanales hechas a mano para momentos especiales.
    </p>

    <div class="hero-actions">
      <a href="#galeria" class="btn btn-primary">
        Ver galería
      </a>
      <button class="btn btn-secondary" data-wa="hero">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z" />
        </svg>
        Consultar por WhatsApp
      </button>
    </div>

    <a href="#galeria" class="hero-scroll" aria-label="Ir a la galería">
      <span>Descubrir</span>
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path d="M12 5v14M5 12l7 7 7-7" />
      </svg>
    </a>

  </div>
</section>


<!-- ═══════════════════════════════════════════════════════════════════════════
     SECCIÓN 2: GALERÍA
     ═══════════════════════════════════════════════════════════════════════════ -->

<section class="gallery-section section" id="galeria" aria-label="Galería de productos">
  <div class="container">

    <div class="section-header fade-up">
      <h2 class="section-title">Nuestras creaciones</h2>
      <p class="section-subtitle">Cada pieza, hecha con dedicación y amor artesanal.</p>
    </div>

    <!-- Filtros de categoría -->
    <div class="category-filters fade-up delay-1" role="group" aria-label="Filtrar por categoría">
      <?php foreach ($categories as $cat): if (!$cat['is_active']) continue; ?>
        <button class="filter-btn <?= $cat['slug'] === 'todos' ? 'active' : '' ?>"
          data-category="<?= htmlspecialchars($cat['slug']) ?>"
          aria-pressed="<?= $cat['slug'] === 'todos' ? 'true' : 'false' ?>">
          <?= htmlspecialchars($cat['name']) ?>
        </button>
      <?php endforeach; ?>
    </div>

    <!-- Grid de productos -->
    <div class="products-grid" id="products-grid">

      <?php foreach ($products as $i => $p):

        if (!$p['is_available']) continue;

        $isCustom    = $p['is_custom'] ?? false;
        $hasPrice    = !$isCustom && $p['price'] !== null;
        $allowsCart  = !$isCustom && $p['allows_cart'];
        $priceText   = $hasPrice ? format_price($p['price']) : 'Consultar precio';
        $imageSrc    = product_image($p['images'][0] ?? null);
        $consultUrl  = $isCustom ? whatsapp_custom_link($p['name']) : whatsapp_product_link($p['name']);
        $delay       = min($i, 4); // Delay máximo 4 para no acumular demasiado

      ?>
        <article class="product-card fade-up delay-<?= $delay ?>"
          data-category="<?= htmlspecialchars($p['category_slug']) ?>"
          data-product-id="<?= (int)$p['id'] ?>"
          data-price="<?= $hasPrice ? htmlspecialchars((string)$p['price']) : 'null' ?>"
          data-is-custom="<?= $isCustom ? 'true' : 'false' ?>"
          data-custom-url="<?= htmlspecialchars(whatsapp_custom_link($p['name'])) ?>"
          data-image="<?= htmlspecialchars($imageSrc ?? '') ?>"
          data-allows-cart="<?= $allowsCart ? 'true' : 'false' ?>">

          <!-- Imagen -->
          <div class="product-card__image-wrap">
            <?php if ($imageSrc): ?>
              <img class="product-card__image"
                src="assets/images/products/<?= htmlspecialchars($imageSrc) ?>"
                alt="<?= htmlspecialchars($p['name']) ?>"
                loading="lazy">
            <?php else: ?>
              <div class="product-card__image product-card__image--placeholder" aria-hidden="true">
                <?php
                $icons = [
                  'velas' => '🕯️',
                  'resina' => '💎',
                  'sublimacion' => '🖨️',
                  'souvenirs' => '🎁',
                  'regalos' => '🎀',
                  'decoracion' => '🌿'
                ];
                echo $icons[$p['category_slug']] ?? '✦';
                ?>
              </div>
            <?php endif; ?>

            <span class="product-card__badge <?= $p['is_featured'] ? 'product-card__badge--featured' : '' ?>">
              <?= $p['is_featured'] ? 'Destacado' : htmlspecialchars($p['category_name']) ?>
            </span>
          </div>

          <!-- Cuerpo -->
          <div class="product-card__body">

            <h3 class="product-card__name"><?= htmlspecialchars($p['name']) ?></h3>

            <p class="product-card__description"><?= htmlspecialchars($p['description']) ?></p>

            <!-- Variantes -->
            <?php if ($p['has_variants'] && !empty($p['variants'])): ?>
              <div class="product-card__variants">
                <?php foreach ($p['variants'] as $variantIndex => $variant): ?>
                  <label for="variant-<?= (int)$p['id'] ?>-<?= $variantIndex ?>">
                    <?= htmlspecialchars($variant['name']) ?>
                  </label>
                  <select id="variant-<?= (int)$p['id'] ?>-<?= $variantIndex ?>"
                    name="variant-<?= $p['id'] ?>">
                    <?php foreach ($variant['options'] as $opt): ?>
                      <option value="<?= htmlspecialchars($opt) ?>"><?= htmlspecialchars($opt) ?></option>
                    <?php endforeach; ?>
                  </select>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <!-- Precio -->
            <div class="product-card__price">
              <?php if ($hasPrice): ?>
                <span class="product-card__price-amount"><?= $priceText ?></span>
              <?php else: ?>
                <span class="product-card__price-consult"><?= $priceText ?></span>
              <?php endif; ?>
            </div>

            <!-- Acciones -->
            <div class="product-card__actions <?= !$allowsCart ? 'product-card__actions--consult-only' : '' ?>">

              <!-- Consultar por WhatsApp -->
              <a href="<?= htmlspecialchars($consultUrl) ?>"
                class="btn btn-outline btn-consult"
                target="_blank" rel="noopener noreferrer"
                aria-label="Consultar por <?= htmlspecialchars($p['name']) ?> por WhatsApp">
                Consultar
              </a>

              <!-- Agregar al carrito (solo si allows_cart) -->
              <?php if ($allowsCart): ?>
                <button class="btn btn-primary btn-add-cart"
                  aria-label="Agregar <?= htmlspecialchars($p['name']) ?> al carrito">
                  Agregar
                </button>
              <?php else: ?>
                <!-- Pedido personalizado: solo consulta -->
                <a class="btn btn-primary" href="<?= htmlspecialchars(whatsapp_custom_link($p['name'])) ?>"
                  target="_blank" rel="noopener noreferrer" aria-label="Hacer pedido personalizado de <?= htmlspecialchars($p['name']) ?>">
                  Pedir especial
                </a>
              <?php endif; ?>

            </div>

          </div><!-- /.product-card__body -->
        </article>
      <?php endforeach; ?>

    </div><!-- /.products-grid -->
  </div>
</section>


<!-- ═══════════════════════════════════════════════════════════════════════════
     SECCIÓN 3: PERSONALIZADOS
     ═══════════════════════════════════════════════════════════════════════════ -->

<section class="custom-section section" id="personalizados" aria-label="Pedidos personalizados">
  <div class="container">
    <div class="custom-section__inner">

      <!-- Texto -->
      <div class="custom-section__text fade-up">

        <h2 class="section-title">Hacemos realidad tu idea</h2>

        <p class="custom-section__lead">
          Además de nuestras creaciones, realizamos trabajos completamente personalizados
          para eventos, regalos y momentos especiales. Contanos qué tenés en mente
          y lo hacemos realidad juntas.
        </p>

        <!-- Ejemplos -->
        <ul class="custom-list" aria-label="Ejemplos de pedidos personalizados">
          <li>Souvenirs para eventos</li>
          <li>Regalos especiales</li>
          <li>Diseños con nombres o frases</li>
          <li>Temáticas personalizadas</li>
          <li>Pedidos para cumpleaños</li>
          <li>Productos para emprendimientos</li>
          <li>Colores específicos</li>
          <li>Pedidos por cantidad</li>
        </ul>

        <!-- Nota pedidos por cantidad -->
        <div class="custom-bulk-note">
          <strong>¿Necesitás varias unidades?</strong><br>
          Consultanos y armamos una propuesta según tu pedido.
          Precio, tiempos y materiales se confirman por WhatsApp.
        </div>

        <button class="btn btn-primary" data-wa="custom" style="align-self:flex-start;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z" />
          </svg>
          Contanos tu idea
        </button>

      </div>

      <!-- Imagen -->



      <!-- <div class="custom-section__image fade-in delay-2">
        <div class="custom-section__image--placeholder" aria-hidden="true">💡</div>

         <div class="about-image fade-in">
          <img
          src="assets/images/favicon.png"
          alt="Logo de Sol & Luna
          class="about-image__logo"
          loading="lazy"
        >
      </div>


      </div> -->

   <div class="custom-section__image fade-in delay-2">
  <div class="custom-slider">

    <button
      class="custom-slider__btn custom-slider__btn--prev"
      id="custom-prev"
      aria-label="Imagen anterior">
      &#10094;
    </button>

    <div class="custom-slider__viewport">
      <img
        src="assets/images/llaveroresina.jpg"
        alt="Trabajo personalizado de Sol & Luna"
        class="custom-slider__img"
        id="custom-slider-img"
        loading="eager"
        fetchpriority="high">
    </div>

    <button
      class="custom-slider__btn custom-slider__btn--next"
      id="custom-next"
      aria-label="Imagen siguiente">
      &#10095;
    </button>

  </div>
</div>

    </div>
  </div>
</section>


<!-- ═══════════════════════════════════════════════════════════════════════════
     SECCIÓN 4: CÓMO COMPRAR
     ═══════════════════════════════════════════════════════════════════════════ -->

<section class="how-section section" id="como-comprar" aria-label="Cómo comprar">
  <div class="container">

    <div class="section-header fade-up">
      <h2 class="section-title">¿Cómo comprar?</h2>
      <p class="section-subtitle">Simple, cercano y a tu ritmo.</p>
    </div>

    <div class="steps-grid">

      <div class="step-card fade-up delay-1">
        <span class="step-number" aria-hidden="true">01</span>
        <div class="step-icon" aria-hidden="true">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.8">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
            <circle cx="12" cy="12" r="3" />
          </svg>
        </div>
        <h3 class="step-title">Inspirate</h3>
        <p class="step-desc">Explorá nuestra galería y descubrí nuestras creaciones artesanales.</p>
      </div>

      <div class="step-card fade-up delay-2">
        <span class="step-number" aria-hidden="true">02</span>
        <div class="step-icon" aria-hidden="true">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.8">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
          </svg>
        </div>
        <h3 class="step-title">Elegí o contanos</h3>
        <p class="step-desc">Seleccioná productos del catálogo o consultá por algo completamente personalizado.</p>
      </div>

      <div class="step-card fade-up delay-3">
        <span class="step-number" aria-hidden="true">03</span>
        <div class="step-icon" aria-hidden="true">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.8">
            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
            <line x1="3" y1="6" x2="21" y2="6" />
            <path d="M16 10a4 4 0 0 1-8 0" />
          </svg>
        </div>
        <h3 class="step-title">Armá tu consulta</h3>
        <p class="step-desc">Agregá productos al carrito o contactanos directamente por WhatsApp para un pedido especial.</p>
      </div>

      <div class="step-card fade-up delay-4">
        <span class="step-number" aria-hidden="true">04</span>
        <div class="step-icon" aria-hidden="true">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.8">
            <rect x="1" y="3" width="15" height="13" rx="1" />
            <path d="M16 8h4l3 5v3h-7V8z" />
            <circle cx="5.5" cy="18.5" r="2.5" />
            <circle cx="18.5" cy="18.5" r="2.5" />
          </svg>
        </div>
        <h3 class="step-title">Coordinamos</h3>
        <p class="step-desc">
          Por WhatsApp confirmamos disponibilidad, precio y modalidad de entrega dentro de
          <strong>Santa Fe Capital</strong> — punto de encuentro o domicilio.
        </p>
      </div>

    </div>
  </div>
</section>


<!-- ═══════════════════════════════════════════════════════════════════════════
     SECCIÓN 5: SOBRE SOL & LUNA
     ═══════════════════════════════════════════════════════════════════════════ -->

<section class="about-section section" id="nosotros" aria-label="Sobre Sol & Luna">
  <div class="container">
    <div class="about-inner">

      <!-- Imagen -->

      <!-- <div class="about-image fade-in">
        <div class="about-image--placeholder" aria-hidden="true">🌙</div>
      </div> -->



      <div class="about-image fade-in">
        <img
          src="assets/images/favicon.png"
          alt="Logo de Sol & Luna"
          class="about-image__logo"
          loading="lazy">
      </div>












      <!-- Texto -->
      <div class="about-text fade-up delay-1">

        <h2 class="section-title">Sobre nosotros</h2>

        <p class="about-lead">
          En Sol &amp; Luna creamos piezas artesanales hechas con dedicación,
          transformando ideas en detalles únicos para regalar, decorar
          y acompañar momentos especiales.
        </p>

        <p class="about-lead">
          <!-- Texto provisional — reemplazar con historia real del emprendimiento -->

          Cada pieza que sale de nuestras manos lleva tiempo, cuidado y mucho amor.
          Trabajamos con sublimación, resina, velas y mucho más, siempre con
          el foco puesto en la calidad y en que cada cliente reciba algo único.
        </p>

        <span class="about-location">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
            <circle cx="12" cy="10" r="3" />
          </svg>
          <?= LOCATION ?>
        </span>

        <a href="<?= INSTAGRAM_URL ?>" class="btn btn-outline" target="_blank" rel="noopener noreferrer">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
          </svg>
          @<?= INSTAGRAM_HANDLE ?>
        </a>

      </div>
    </div>
  </div>
</section>


<!-- ═══════════════════════════════════════════════════════════════════════════
     SECCIÓN 6: CONTACTO
     ═══════════════════════════════════════════════════════════════════════════ -->

<section class="contact-section section" id="contacto" aria-label="Contacto">
  <div class="container">

    <div class="section-header fade-up">
      <h2 class="section-title">Contacto</h2>
      <p class="section-subtitle">Estamos para ayudarte. Consultanos cualquier duda.</p>
    </div>

    <div class="contact-grid">

      <!-- Instagram -->
      <a href="<?= INSTAGRAM_URL ?>"
        class="contact-card contact-card--link fade-up delay-1"
        target="_blank" rel="noopener noreferrer"
        aria-label="Ver Instagram de Sol & Luna">
        <div class="contact-card__icon contact-card__icon--instagram" aria-hidden="true">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.8">
            <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
          </svg>
        </div>
        <h3 class="contact-card__title">Instagram</h3>
        <span class="contact-card__handle">@<?= INSTAGRAM_HANDLE ?></span>
        <p class="contact-card__detail">Seguinos para ver nuestras últimas creaciones.</p>
      </a>

      <!-- WhatsApp -->
      <button class="contact-card contact-card--link fade-up delay-2" data-wa="contact"
        aria-label="Contactar por WhatsApp">
        <div class="contact-card__icon contact-card__icon--whatsapp" aria-hidden="true">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z" />
          </svg>
        </div>
        <h3 class="contact-card__title">WhatsApp</h3>
        <p class="contact-card__detail">Escribinos para consultas, pedidos y coordinar la entrega.</p>
      </button>

      <!-- Ubicación -->
      <div class="contact-card fade-up delay-3">
        <div class="contact-card__icon contact-card__icon--location" aria-hidden="true">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.8">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
            <circle cx="12" cy="10" r="3" />
          </svg>
        </div>
        <h3 class="contact-card__title">Ubicación</h3>
        <p class="contact-card__detail"><?= LOCATION ?></p>
        <p class="contact-card__detail" style="font-size:0.8rem;margin-top:4px;">
          Hecho con amor en Santa Fe Capital.
        </p>
      </div>

      <!-- Entregas -->
      <div class="contact-card fade-up delay-4">
        <div class="contact-card__icon contact-card__icon--delivery" aria-hidden="true">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.8">
            <rect x="1" y="3" width="15" height="13" rx="1" />
            <path d="M16 8h4l3 5v3h-7V8z" />
            <circle cx="5.5" cy="18.5" r="2.5" />
            <circle cx="18.5" cy="18.5" r="2.5" />
          </svg>
        </div>
        <h3 class="contact-card__title">Entregas</h3>
        <p class="contact-card__detail">
          Dentro de Santa Fe Capital.<br>
          Punto de encuentro o domicilio.<br>
          Los detalles se coordinan por WhatsApp.
        </p>
      </div>

    </div>
  </div>
</section>


<?php
// Carrito drawer (antes del footer)
include __DIR__ . '/includes/cart-drawer.php';

// Footer cierra </body> y </html>
include __DIR__ . '/includes/footer.php';
?>
