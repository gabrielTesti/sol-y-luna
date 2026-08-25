<?php
/**
 * includes/header.php
 * Header y navegación principal de Sol & Luna.
 * Se incluye en index.php y todas las páginas.
 */
require_once __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- SEO -->
  <title><?= META_TITLE ?></title>
  
  <!-- Favicon -->
  <!-- <link rel="icon" type="image/png" href="/sol-y-luna/assets/images/favicon.png?v=4"> -->
   <link rel="icon" href="http://localhost/sol-y-luna/assets/images/favicon.png?v=6">
  <meta name="description" content="<?= META_DESCRIPTION ?>">
  <meta name="keywords"    content="<?= META_KEYWORDS ?>">
  <meta name="author"      content="<?= SITE_NAME ?>">

  <!-- Open Graph / Redes sociales -->
  <meta property="og:type"        content="website">
  <meta property="og:title"       content="<?= META_TITLE ?>">
  <meta property="og:description" content="<?= META_DESCRIPTION ?>">
  <meta property="og:image"       content="<?= META_OG_IMAGE ?>">

  

  <!-- Google Fonts: Cormorant Garamond + Jost -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/variables.css">
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/hero.css">
  <link rel="stylesheet" href="assets/css/gallery.css">
  <link rel="stylesheet" href="assets/css/cart.css">
  <link rel="stylesheet" href="assets/css/sections.css">
  <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>

<!-- ─── HEADER ──────────────────────────────────────────────────────────────── -->
<header class="site-header" id="site-header">
  <div class="header-inner container">

    <!-- Logo / Nombre -->
    <a href="#inicio" class="site-logo" aria-label="<?= SITE_NAME ?> — Inicio">
      <span class="logo-text"><?= SITE_NAME ?></span>
    </a>

    <!-- Navegación principal -->
    <nav class="site-nav" id="site-nav" aria-label="Navegación principal">
      <ul class="nav-list" role="list">
        <li><a href="#galeria"     class="nav-link">Galería</a></li>
        <li><a href="#nosotros"    class="nav-link">Nosotros</a></li>
        <li><a href="#como-comprar" class="nav-link">¿Cómo comprar?</a></li>
        <li><a href="#contacto"    class="nav-link">Contacto</a></li>
      </ul>
    </nav>

    <!-- Acciones del header -->
    <div class="header-actions">

      <!-- Botón carrito -->
      <button class="cart-btn" id="cart-btn" aria-label="Ver carrito" aria-expanded="false">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
          <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
          <path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>
        <span class="cart-count" id="cart-count" aria-live="polite" hidden>0</span>
      </button>

      <!-- Hamburguesa mobile -->
      <button class="menu-toggle" id="menu-toggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="site-nav">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
      </button>

    </div>
  </div>
</header>

<!-- Overlay para menú mobile -->
<div class="nav-overlay" id="nav-overlay" aria-hidden="true"></div>
