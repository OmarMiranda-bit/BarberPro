<?php
/** @var string $titulo */
include_once __DIR__ . '/prueba.php';
include_once __DIR__ . '/services.php';
?>

<section class="gallery section">
    <div class="gallery__header">
        <h3 class="gallery__title">Nuestro Trabajo</h3>
        <p class="gallery__subtitle">Descubre nuestro estilo</p>
    </div>

    <div class="gallery__grid gallery__grid--home">
        <!-- Item 1 -->
        <div class="gallery__item gallery__item--home" data-index="0">
            <picture>
                <source srcset="/build/img/galeria/corte_clasico.avif" type="image/avif">
                <source srcset="/build/img/galeria/corte_clasico.webp" type="image/webp">
                <img loading="lazy" src="/build/img/galeria/corte_clasico.jpg" alt="Corte Clásico">
            </picture>
            <div class="gallery__overlay">
                <div class="gallery__item-title">Corte Clásico</div>
                <div class="gallery__item-description">Estilo tradicional refinado</div>
            </div>
        </div>

        <!-- Item 2 -->
        <div class="gallery__item gallery__item--home" data-index="1">
            <picture>
                <source srcset="/build/img/galeria/barba_perfilada.avif" type="image/avif">
                <source srcset="/build/img/galeria/barba_perfilada.webp" type="image/webp">
                <img loading="lazy" src="/build/img/galeria/barba_perfilada.jpg" alt="Barba Perfilada">
            </picture>
            <div class="gallery__overlay">
                <div class="gallery__item-title">Barba Perfilada</div>
                <div class="gallery__item-description">Acabado profesional</div>
            </div>
        </div>

        <!-- Item 3 -->
        <div class="gallery__item gallery__item--home" data-index="2">
            <picture>
                <source srcset="/build/img/galeria/degradado_medio.avif" type="image/avif">
                <source srcset="/build/img/galeria/degradado_medio.webp" type="image/webp">
                <img loading="lazy" src="/build/img/galeria/degradado_medio.jpg" alt="Degradado Medio">
            </picture>
            <div class="gallery__overlay">
                <div class="gallery__item-title">Fade Moderno</div>
                <div class="gallery__item-description">Degradado perfecto</div>
            </div>
        </div>

        <!-- Item 4 -->
        <div class="gallery__item gallery__item--home" data-index="3">
            <picture>
                <source srcset="/build/img/galeria/estilo_urbano.avif" type="image/avif">
                <source srcset="/build/img/galeria/estilo_urbano.webp" type="image/webp">
                <img loading="lazy" src="/build/img/galeria/estilo_urbano.jpg" alt="Estilo Urbano">
            </picture>
            <div class="gallery__overlay">
                <div class="gallery__item-title">Estilo Urbano</div>
                <div class="gallery__item-description">Look contemporáneo</div>
            </div>
        </div>

        <!-- Item 5 -->
        <div class="gallery__item gallery__item--home" data-index="4">
            <picture>
                <source srcset="/build/img/galeria/corte_texturizado.avif" type="image/avif">
                <source srcset="/build/img/galeria/corte_texturizado.webp" type="image/webp">
                <img loading="lazy" src="/build/img/galeria/corte_texturizado.jpg" alt="Corte Texturizado">
            </picture>
            <div class="gallery__overlay">
                <div class="gallery__item-title">Corte Texturizado</div>
                <div class="gallery__item-description">Volumen natural</div>
            </div>
        </div>

        <!-- Item 6 -->
        <div class="gallery__item gallery__item--home" data-index="5">
            <picture>
                <source srcset="/build/img/galeria/barba_completa.avif" type="image/avif">
                <source srcset="/build/img/galeria/barba_completa.webp" type="image/webp">
                <img loading="lazy" src="/build/img/galeria/barba_completa.jpg" alt="Barba Completa">
            </picture>
            <div class="gallery__overlay">
                <div class="gallery__item-title">Barba Completa</div>
                <div class="gallery__item-description">Estilo distinguido</div>
            </div>
        </div>

        <!-- Item 7 -->
        <div class="gallery__item gallery__item--home" data-index="6">
            <picture>
                <source srcset="/build/img/galeria/degradado_alto.avif" type="image/avif">
                <source srcset="/build/img/galeria/degradado_alto.webp" type="image/webp">
                <img loading="lazy" src="/build/img/galeria/degradado_alto.jpg" alt="Degradado Alto">
            </picture>
            <div class="gallery__overlay">
                <div class="gallery__item-title">Degradado Alto</div>
                <div class="gallery__item-description">Transición impecable</div>
            </div>
        </div>

        <!-- Item 8 -->
        <div class="gallery__item gallery__item--home" data-index="7">
            <picture>
                <source srcset="/build/img/galeria/look_premium.avif" type="image/avif">
                <source srcset="/build/img/galeria/look_premium.webp" type="image/webp">
                <img loading="lazy" src="/build/img/galeria/look_premium.jpg" alt="Look Premium">
            </picture>
            <div class="gallery__overlay">
                <div class="gallery__item-title">Look Premium</div>
                <div class="gallery__item-description">Elegancia total</div>
            </div>
        </div>
    </div>

    <div class="gallery__cta">
        <a href="/galeria" class="gallery__cta-btn">Ver Galería Completa</a>
    </div>
</section>

<!-- Modal Home -->
<div id="galleryModalHome" class="gallery__modal gallery__modal--home">
    <span class="gallery__modal-close gallery__modal-close--home">&times;</span>
    <span class="gallery__modal-nav gallery__modal-nav--prev-home">&#10094;</span>
    <span class="gallery__modal-nav gallery__modal-nav--next-home">&#10095;</span>
    <img class="gallery__modal-content" id="galleryModalImageHome" alt="">
    <div class="gallery__modal-info">
        <h3 class="gallery__modal-title" id="galleryModalTitleHome"></h3>
        <p class="gallery__modal-description" id="galleryModalDescriptionHome"></p>
    </div>
</div>

<?php include_once __DIR__ . '/testimoniales.php'; ?>
<?php include_once __DIR__ . '/equipo.php'; ?>
<!-- <?php //include_once __DIR__ . '/paquetes.php'; ?> -->
<?php include_once __DIR__ . '/location.php'; ?>