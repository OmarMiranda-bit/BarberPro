<section class="gallery">
    <div class="gallery__header">
        <h3 class="gallery__title">Nuestra Galería</h3>
        <p class="gallery__subtitle">Descubre nuestro trabajo y estilo</p>
    </div>

    <div class="gallery__filters">
        <button class="gallery__filter-btn gallery__filter-btn--active" data-filter="all">Todos</button>
        <button class="gallery__filter-btn" data-filter="cortes">Cortes</button>
        <button class="gallery__filter-btn" data-filter="barbas">Barbas</button>
        <button class="gallery__filter-btn" data-filter="degradados">Degradados</button>
        <button class="gallery__filter-btn" data-filter="estilos">Estilos</button>
    </div>

    <div class="gallery__grid gallery__grid--full">
        <!-- Cortes -->
        <div class="gallery__item gallery__item--full" data-category="cortes" data-index="0">
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

        <div class="gallery__item gallery__item--full" data-category="cortes" data-index="1">
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

        <div class="gallery__item gallery__item--full" data-category="cortes" data-index="2">
            <picture>
                <source srcset="/build/img/galeria/corte_moderno.avif" type="image/avif">
                <source srcset="/build/img/galeria/corte_moderno.webp" type="image/webp">
                <img loading="lazy" src="/build/img/galeria/corte_moderno.jpg" alt="Corte Moderno">
            </picture>
            <div class="gallery__overlay">
                <div class="gallery__item-title">Corte Moderno</div>
                <div class="gallery__item-description">Estilo actual</div>
            </div>
        </div>

        <!-- Barbas -->
        <div class="gallery__item gallery__item--full" data-category="barbas" data-index="3">
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

        <div class="gallery__item gallery__item--full" data-category="barbas" data-index="4">
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

        <div class="gallery__item gallery__item--full" data-category="barbas" data-index="5">
            <picture>
                <source srcset="/build/img/galeria/barba_mediana.avif" type="image/avif">
                <source srcset="/build/img/galeria/barba_mediana.webp" type="image/webp">
                <img loading="lazy" src="/build/img/galeria/barba_mediana.jpg" alt="Barba Mediana">
            </picture>
            <div class="gallery__overlay">
                <div class="gallery__item-title">Barba Media</div>
                <div class="gallery__item-description">Balance perfecto</div>
            </div>
        </div>

        <!-- Degradados -->
        <div class="gallery__item gallery__item--full" data-category="degradados" data-index="6">
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

        <div class="gallery__item gallery__item--full" data-category="degradados" data-index="7">
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

        <div class="gallery__item gallery__item--full" data-category="degradados" data-index="8">
             <picture>
                <source srcset="/build/img/galeria/degradado_bajo.avif" type="image/avif">
                <source srcset="/build/img/galeria/degradado_bajo.webp" type="image/webp">
                <img loading="lazy" src="/build/img/galeria/degradado_bajo.jpg" alt="Degradado Bajo">
            </picture>
            <div class="gallery__overlay">
                <div class="gallery__item-title">Fade Bajo</div>
                <div class="gallery__item-description">Sutil y elegante</div>
            </div>
        </div>

        <!-- Estilos -->
        <div class="gallery__item gallery__item--full" data-category="estilos" data-index="9">
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

        <div class="gallery__item gallery__item--full" data-category="estilos" data-index="10">
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

        <div class="gallery__item gallery__item--full" data-category="estilos" data-index="11">
             <picture>
                <source srcset="/build/img/galeria/estilo_ejecutivo.avif" type="image/avif">
                <source srcset="/build/img/galeria/estilo_ejecutivo.webp" type="image/webp">
                <img loading="lazy" src="/build/img/galeria/estilo_ejecutivo.jpg" alt="Estilo Ejecutivo">
            </picture>
            <div class="gallery__overlay">
                <div class="gallery__item-title">Estilo Ejecutivo</div>
                <div class="gallery__item-description">Profesional y refinado</div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Full -->
<div id="galleryModalFull" class="gallery__modal gallery__modal--full">
    <span class="gallery__modal-close gallery__modal-close--full">&times;</span>
    <span class="gallery__modal-nav gallery__modal-nav--prev-full">&#10094;</span>
    <span class="gallery__modal-nav gallery__modal-nav--next-full">&#10095;</span>
    <img class="gallery__modal-content" id="galleryModalImageFull" alt="">
    <div class="gallery__modal-info">
        <h3 class="gallery__modal-title" id="galleryModalTitleFull"></h3>
        <p class="gallery__modal-description" id="galleryModalDescriptionFull"></p>
    </div>
</div>