<header class="header" data-usuario-logueado="<?php echo hayUsuarioLogueado() ? 'true' : 'false'; ?>">
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="navbar__container">
            <!-- Logo -->
            <a class="navbar__logo" href="/">
                <span class="navbar__logo-text">Barber</span>
                <span class="navbar__logo-text navbar__logo-text--bold">PRO</span>
            </a>

            <!-- Menu Toggle (móvil) - Reemplazado con Font Awesome -->
            <button class="navbar__toggle" id="navbarToggle" aria-label="Abrir menú">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Navigation -->
            <ul class="navbar__menu" id="navbarMenu">
                <li class="navbar__item">
                    <a href="/" class="navbar__link <?php echo pagina_actual('/') && !pagina_actual('/services') && !pagina_actual('/galeria') && !pagina_actual('/equipo') && !pagina_actual('/contacto') ? 'navbar__link--active' : ''; ?>">
                        Inicio
                    </a>
                </li>
                <li class="navbar__item">
                    <a href="/services" class="navbar__link <?php echo pagina_actual('/services') ? 'navbar__link--active' : ''; ?>">
                        Servicios
                    </a>
                </li>
                <li class="navbar__item">
                    <a href="/galeria" class="navbar__link <?php echo pagina_actual('/galeria') ? 'navbar__link--active' : ''; ?>">
                        Galería
                    </a>
                </li>
                <li class="navbar__item">
                    <a href="/equipo" class="navbar__link <?php echo pagina_actual('/equipo') ? 'navbar__link--active' : ''; ?>">
                        Equipo
                    </a>
                </li>
                <li class="navbar__item">
                    <a href="/contacto" class="navbar__link <?php echo pagina_actual('/contacto') ? 'navbar__link--active' : ''; ?>">
                        Contacto
                    </a>
                </li>
                <li class="navbar__item navbar__item--cta">
                    <?php if (!pagina_actual('/reservar') && !hayUsuarioLogueado()) : ?>
                        <a href="/reservar" class="navbar__btn">Reservar Cita</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero" <?php echo hayUsuarioLogueado() ? 'style="display: none;"' : ''; ?>>
        <div class="hero__content">
            <h1 class="hero__title">
                <span class="hero__title-line">Estilo & Elegancia</span>
                <span class="hero__title-line hero__title-line--accent">Redefinidos</span>
            </h1>

            <p class="hero__description">
                Expertos en cortes clásicos y modernos con más de 10 años de experiencia.
                Tu imagen, nuestra pasión.
            </p>

            <div class="hero__actions">
                <?php if (!pagina_actual('/reservar')) : ?>
                    <a href="/reservar" class="hero__btn hero__btn--primary">
                        <i class="fa-solid fa-calendar-days hero__btn-icon"></i>
                        Reservar Ahora
                    </a>
                <?php endif; ?>
                <!-- Solo mostrar si NO estamos en /galeria -->
                <?php if (!pagina_actual('/galeria')) : ''?>
                    <a href="/galeria" class="hero__btn hero__btn--secondary">
                        <i class="fa-solid fa-images hero__btn-icon"></i>
                        Ver Trabajos
                    </a>
                <?php endif; ?>
            </div>

            <!-- Features -->
            <div class="hero__features">
                <div class="hero__feature">
                    <div class="hero__feature-icon">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <div class="hero__feature-text">
                        <strong>10+</strong> Años de experiencia
                    </div>
                </div>
                <div class="hero__feature">
                    <div class="hero__feature-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="hero__feature-text">
                        <strong>5000+</strong> Clientes satisfechos
                    </div>
                </div>
                <div class="hero__feature">
                    <div class="hero__feature-icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="hero__feature-text">
                        <strong>100%</strong> Garantía de calidad
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>