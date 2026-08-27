document.addEventListener('DOMContentLoaded', () => {
  const imageEl = document.getElementById('custom-slider-img');
  const prevBtn = document.getElementById('custom-prev');
  const nextBtn = document.getElementById('custom-next');

  if (!imageEl || !prevBtn || !nextBtn) return;

  const images = [
    'assets/images/llaveroresina.jpg',
    'assets/images/velasfloresgrandes.png',
    'assets/images/lapicerasresinajpg.jpg',
    'assets/images/velaaromatica2.jpg',
    'assets/images/tazas.jpg',
    'assets/images/tazasspiderman.jpg',
    'assets/images/tudiseñoaqui.jpg',
    'assets/images/señaladores.jpg',
    'assets/images/stickers.jpg',
    'assets/images/rompecabezascorazon.jpg',
    'assets/images/almohadas.jpg',
    'assets/images/almohadastudiseño.jpg'
  ];

  let currentIndex = 0;
  let intervalId;
  let isChanging = false;
  const slider = imageEl.closest('.custom-slider');
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  function showImage(index, direction = 'next', manual = false) {
    if (isChanging) return;

    isChanging = true;
    prevBtn.disabled = true;
    nextBtn.disabled = true;

    const exitClass =
      direction === 'next'
        ? 'exit-left'
        : 'exit-right';

    const enterClass =
      direction === 'next'
        ? 'enter-from-right'
        : 'enter-from-left';

    // Manual = más rápido
    const exitDelay = reducedMotion.matches ? 0 : (manual ? 150 : 280);
    const unlockDelay = reducedMotion.matches ? 0 : (manual ? 180 : 280);

    // Cargar antes de ocultar la foto actual. Un error no debe trabar las flechas.
    const nextImage = new Image();
    const cancelLoad = () => {
      clearTimeout(loadTimeout);
      nextImage.onload = null;
      nextImage.onerror = null;
      isChanging = false;
      prevBtn.disabled = false;
      nextBtn.disabled = false;
    };
    const loadTimeout = setTimeout(cancelLoad, 8000);
    nextImage.onerror = cancelLoad;
    nextImage.onload = () => {
      clearTimeout(loadTimeout);
      nextImage.onload = null;
      nextImage.onerror = null;
      imageEl.classList.add(exitClass);

      setTimeout(() => {
        imageEl.src = nextImage.src;

        imageEl.classList.remove('exit-left', 'exit-right');
        imageEl.classList.add(enterClass);
        requestAnimationFrame(() => {
          requestAnimationFrame(() => {
            imageEl.classList.remove(enterClass);

            setTimeout(() => {
              isChanging = false;
              prevBtn.disabled = false;
              nextBtn.disabled = false;
            }, unlockDelay);
          });
        });
      }, exitDelay);
    };
    nextImage.src = images[index];
  }

  function nextImage(manual = false) {
    if (isChanging) return;

    currentIndex =
      (currentIndex + 1) % images.length;

    showImage(currentIndex, 'next', manual);
  }

  function prevImage(manual = false) {
    if (isChanging) return;

    currentIndex =
      (currentIndex - 1 + images.length) % images.length;

    showImage(currentIndex, 'prev', manual);
  }

  function startAutoSlide() {
    clearInterval(intervalId);
    if (document.hidden || reducedMotion.matches || slider.matches(':hover') ||
        slider.contains(document.activeElement)) return;

    intervalId = setInterval(() => {
      nextImage(false);
    }, 4000);
  }

  function resetAutoSlide() {
    clearInterval(intervalId);
    startAutoSlide();
  }

  nextBtn.addEventListener('click', () => {
    nextImage(true);
    resetAutoSlide();
  });

  prevBtn.addEventListener('click', () => {
    prevImage(true);
    resetAutoSlide();
  });

  slider.addEventListener('mouseenter', () => clearInterval(intervalId));
  slider.addEventListener('mouseleave', startAutoSlide);
  slider.addEventListener('focusin', () => clearInterval(intervalId));
  slider.addEventListener('focusout', () => setTimeout(startAutoSlide, 0));
  document.addEventListener('visibilitychange', startAutoSlide);
  reducedMotion.addEventListener('change', startAutoSlide);

  startAutoSlide();
});
