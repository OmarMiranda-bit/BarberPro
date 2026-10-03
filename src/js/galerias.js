/*Maneja tanto la galería del home como la galería completa*/
const Gallery = (() => {
    // Variables privadas para la galeria (Home)
    let homeCurrentIndex = 0;
    let homeItems = [];
    let homeModal = null;
    let homeModalImg = null;
    let homeModalTitle = null;
    let homeModalDescription = null;
    let homeCloseBtn = null;
    let homePrevBtn = null;
    let homeNextBtn = null;

    // Variables privadas para la galeria (Full)
    let fullCurrentIndex = 0;
    let fullVisibleItems = [];
    let fullAllItems = [];
    let fullModal = null;
    let fullModalImg = null;
    let fullModalTitle = null;
    let fullModalDescription = null;
    let fullCloseBtn = null;
    let fullPrevBtn = null;
    let fullNextBtn = null;

    /* Inicializa la galería del home*/
    const initHome = () => {
        // Buscar items de la galería home
        homeItems = Array.from(document.querySelectorAll('.gallery__item--home'));
        
        // Buscar elementos del modal home
        homeModal = document.getElementById('galleryModalHome');
        homeModalImg = document.getElementById('galleryModalImageHome');
        homeModalTitle = document.getElementById('galleryModalTitleHome');
        homeModalDescription = document.getElementById('galleryModalDescriptionHome');
        homeCloseBtn = document.querySelector('.gallery__modal-close--home');
        homePrevBtn = document.querySelector('.gallery__modal-nav--prev-home');
        homeNextBtn = document.querySelector('.gallery__modal-nav--next-home');

        // Event listeners para items
        homeItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                homeCurrentIndex = index;
                openHomeModal();
            });
        });

        // Event listeners para controles del modal
        if (homeCloseBtn) { //cerrar el modal (home)
            homeCloseBtn.addEventListener('click', closeHomeModal);
        }

        if (homePrevBtn) { //Regresar a la imagen anterior (home)
            homePrevBtn.addEventListener('click', () => changeHomeImage(-1));
        }

        if (homeNextBtn) { //Avanzar a la siguiente imagen (home)
            homeNextBtn.addEventListener('click', () => changeHomeImage(1));
        }

        // al dar click fuera del modal se cierra 
        if (homeModal) {
            homeModal.addEventListener('click', (e) => {
                if (e.target === homeModal) {
                    closeHomeModal();
                }
            });
        }

        // console.log('Galería home inicializada correctamente');
    };

    /*Abre el modal de la galería home*/
    const openHomeModal = () => {
        const item = homeItems[homeCurrentIndex];
        if (!item) return;

        const img = item.querySelector('img');
        const picture = item.querySelector('picture');
        const title = item.querySelector('.gallery__item-title').textContent;
        const description = item.querySelector('.gallery__item-description').textContent;

        // Priorizar AVIF, luego WebP, luego JPG
        let imageSrc = img.src; // fallback
        if (picture) {
            const avifSource = picture.querySelector('source[type="image/avif"]');
            const webpSource = picture.querySelector('source[type="image/webp"]');
            if (avifSource) {
                imageSrc = avifSource.srcset;
            } else if (webpSource) {
                imageSrc = webpSource.srcset;
            }
        }

        homeModal.style.display = 'block';
        homeModalImg.src = imageSrc;
        homeModalImg.alt = img.alt;
        homeModalTitle.textContent = title;
        homeModalDescription.textContent = description;
        document.body.style.overflow = 'hidden';
    };

    /*Cierra el modal de la galería home*/
    const closeHomeModal = () => {
        homeModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    };

    /*Cambia la imagen del modal home*/
    const changeHomeImage = (direction) => {
        homeCurrentIndex += direction;

        if (homeCurrentIndex >= homeItems.length) {
            homeCurrentIndex = 0;
        } else if (homeCurrentIndex < 0) {
            homeCurrentIndex = homeItems.length - 1;
        }

        const item = homeItems[homeCurrentIndex];
        const img = item.querySelector('img');
        const picture = item.querySelector('picture');
        const title = item.querySelector('.gallery__item-title').textContent;
        const description = item.querySelector('.gallery__item-description').textContent;

        // Priorizar AVIF, luego WebP, luego JPG
        let imageSrc = img.src; // fallback
        if (picture) {
            const avifSource = picture.querySelector('source[type="image/avif"]');
            const webpSource = picture.querySelector('source[type="image/webp"]');
            if (avifSource) {
                imageSrc = avifSource.srcset;
            } else if (webpSource) {
                imageSrc = webpSource.srcset;
            }
        }

        homeModalImg.src = imageSrc;
        homeModalImg.alt = img.alt;
        homeModalTitle.textContent = title;
        homeModalDescription.textContent = description;
    };

    /** --------------- GALERIA COMPLETA (filtros) ----------*/

    /** Inicializa la galería completa con filtros*/
    const initFull = () => {
        
        // Buscar items de la galería completa
        fullAllItems = Array.from(document.querySelectorAll('.gallery__item--full'));
        
        // Buscar elementos del modal full
        fullModal = document.getElementById('galleryModalFull');
        fullModalImg = document.getElementById('galleryModalImageFull');
        fullModalTitle = document.getElementById('galleryModalTitleFull');
        fullModalDescription = document.getElementById('galleryModalDescriptionFull');
        fullCloseBtn = document.querySelector('.gallery__modal-close--full');
        fullPrevBtn = document.querySelector('.gallery__modal-nav--prev-full');
        fullNextBtn = document.querySelector('.gallery__modal-nav--next-full');

        // Inicializar items visibles
        updateFullVisibleItems();

        // Event listeners para items
        fullAllItems.forEach((item) => {
            item.addEventListener('click', () => {
                const index = fullVisibleItems.indexOf(item);
                if (index !== -1) {
                    fullCurrentIndex = index;
                    openFullModal();
                }
            });
        });

        // Event listeners para controles del modal
        if (fullCloseBtn) { //cerrar el modal (full)
            fullCloseBtn.addEventListener('click', closeFullModal);
        }

        if (fullPrevBtn) { //Regresar a la imagen anterior (full)
            fullPrevBtn.addEventListener('click', () => changeFullImage(-1));
        }

        if (fullNextBtn) { //Avanzar a la siguiente imagen (full)
            fullNextBtn.addEventListener('click', () => changeFullImage(1));
        }

        // Click fuera del modal
        if(fullModal){
            fullModal.addEventListener('click', (e) => {
                if (e.target === fullModal) {
                    closeFullModal();
                }
            });
        }

        // Inicializar filtros
        initFilters();
    };

    /** Actualiza los items visibles (usado en filtros) */
    const updateFullVisibleItems = () => {
        fullVisibleItems = fullAllItems.filter(item => {
            const display = window.getComputedStyle(item).display;
            return display !== 'none';
        });
    };

    /* Abre el modal de la galería completa*/
    const openFullModal = () => {
        const item = fullVisibleItems[fullCurrentIndex];
        if (!item) return;

        const img = item.querySelector('img');
        const picture = item.querySelector('picture');
        const title = item.querySelector('.gallery__item-title').textContent;
        const description = item.querySelector('.gallery__item-description').textContent;

        // Priorizar AVIF, luego WebP, luego JPG
        let imageSrc = img.src; // fallback
        if (picture) {
            const avifSource = picture.querySelector('source[type="image/avif"]');
            const webpSource = picture.querySelector('source[type="image/webp"]');
            if (avifSource) {
                imageSrc = avifSource.srcset;
            } else if (webpSource) {
                imageSrc = webpSource.srcset;
            }
        }

        fullModal.style.display = 'block';
        fullModalImg.src = imageSrc;
        fullModalImg.alt = img.alt;
        fullModalTitle.textContent = title;
        fullModalDescription.textContent = description;
        document.body.style.overflow = 'hidden';
    };

    /**Cierra el modal de la galería completa */
    const closeFullModal = () => {
        fullModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    };

    /**Cambia la imagen del modal full */
    const changeFullImage = (direction) => {
        fullCurrentIndex += direction;

        if (fullCurrentIndex >= fullVisibleItems.length) {
            fullCurrentIndex = 0;
        } else if (fullCurrentIndex < 0) {
            fullCurrentIndex = fullVisibleItems.length - 1;
        }

        const item = fullVisibleItems[fullCurrentIndex];
        const img = item.querySelector('img');
        const picture = item.querySelector('picture');
        const title = item.querySelector('.gallery__item-title').textContent;
        const description = item.querySelector('.gallery__item-description').textContent;

        // Priorizar AVIF, luego WebP, luego JPG
        let imageSrc = img.src; // fallback
        if (picture) {
            const avifSource = picture.querySelector('source[type="image/avif"]');
            const webpSource = picture.querySelector('source[type="image/webp"]');
            if (avifSource) {
                imageSrc = avifSource.srcset;
            } else if (webpSource) {
                imageSrc = webpSource.srcset;
            }
        }

        fullModalImg.src = imageSrc;
        fullModalImg.alt = img.alt;
        fullModalTitle.textContent = title;
        fullModalDescription.textContent = description;
    };

    /**Inicializa los filtros de la galería completa*/
    const initFilters = () => {
        const filterButtons = document.querySelectorAll('.gallery__filter-btn');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remover clase activa de todos los botones
                filterButtons.forEach(btn => btn.classList.remove('gallery__filter-btn--active'));
                
                // Agregar clase activa al botón clickeado
                button.classList.add('gallery__filter-btn--active');

                // Obtener el filtro seleccionado
                const filter = button.getAttribute('data-filter');

                // Filtrar items
                fullAllItems.forEach(item => {
                    const category = item.getAttribute('data-category');
                    
                    if (filter === 'all' || category === filter) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Actualizar array de items visibles
                updateFullVisibleItems();
            });
        });
    };

    /**Manejo global de teclado*/
    const initKeyboardControls = () => {
        document.addEventListener('keydown', (e) => {
            // Determinamos el contexto activo
            const isHome = homeModal?.style.display === 'block';
            const isFull = fullModal?.style.display === 'block';

            if (!isHome && !isFull) return;

            // Definimos qué funciones usar dinámicamente
            const closeFn = isHome ? closeHomeModal : closeFullModal;
            const moveFn = isHome ? changeHomeImage : changeFullImage;

            // Diccionario de acciones por tecla
            const keyActions = {
                'Escape': () => closeFn(),
                'ArrowLeft': () => moveFn(-1),
                'ArrowRight': () => moveFn(1)
            };

            // Ejecuta la función si la tecla existe en el diccionario
            keyActions[e.key]?.(); 
        });
    };

    // API pública del módulo
    return {
        initHome,
        initFull,
        init: () => {
            initHome();
            initFull();
            initKeyboardControls();
        }
    };
})();

// Auto-inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    // console.log('DOM cargado, inicializando galerías...');
    Gallery.init();
});