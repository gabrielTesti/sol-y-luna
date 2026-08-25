document.addEventListener('DOMContentLoaded', () => {
  const imageEl = document.getElementById('custom-slider-img');
  const prevBtn = document.getElementById('custom-prev');
  const nextBtn = document.getElementById('custom-next');

  if (!imageEl || !prevBtn || !nextBtn) return;

  const images = [
    'assets/images/llaveroresina.jpg',
    'assets/images/velasfloresgrandes.png',
    'assets/images/lapicerasresinajpg.jpg',
    'assets/images/velas-aromaticas.jpg',
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

  function showImage(index, direction = 'next', manual = false) {
    if (isChanging) return;

    isChanging = true;

    const exitClass =
      direction === 'next'
        ? 'exit-left'
        : 'exit-right';

    const enterClass =
      direction === 'next'
        ? 'enter-from-right'
        : 'enter-from-left';

    // Manual = más rápido
    const exitDelay = manual ? 150 : 280;
    const unlockDelay = manual ? 180 : 280;

    imageEl.classList.add(exitClass);

    setTimeout(() => {
      imageEl.src = images[index];

      imageEl.classList.remove(
        'exit-left',
        'exit-right'
      );

      imageEl.classList.add(enterClass);

      const finishTransition = () => {
        requestAnimationFrame(() => {
          requestAnimationFrame(() => {
            imageEl.classList.remove(enterClass);

            setTimeout(() => {
              isChanging = false;
            }, unlockDelay);
          });
        });
      };

      // Si ya estaba cargada en caché
      if (imageEl.complete) {
        finishTransition();
      } else {
        imageEl.onload = finishTransition;
      }
    }, exitDelay);
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

  startAutoSlide();
});