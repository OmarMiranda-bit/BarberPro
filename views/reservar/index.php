<section class="reservar section">
    <div class="reservar__container">
        <!-- Header -->
        <div class="section__header">
            <h2 class="section__title">Reserva tu Cita</h2>
            <p class="section__subtitle">Elige cómo deseas continuar</p>
        </div>

        <!-- Opciones principales -->
        <div class="reservar__opciones">
            <!-- Opción 1: Agendar Nueva Cita -->
            <div class="reservar__card">
                <div class="reservar__card-icon">
                    <i class="fa-solid fa-calendar-plus"></i>
                </div>
                <h3 class="reservar__card-title">Agendar Nueva Cita</h3>
                <p class="reservar__card-description">
                    Inicia sesión o crea una cuenta para agendar tu próxima visita con nosotros.
                </p>
                <button type="button" class="reservar__card-btn" id="btnLogin">
                    <i class="fa-solid fa-arrow-right"></i>
                    Continuar
                </button>
            </div>

            <!-- Opción 2: Verificar Status de cita agendada -->
            <div class="reservar__card">
                <div class="reservar__card-icon reservar__card-icon--secondary">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <h3 class="reservar__card-title">Verificar mi Cita</h3>
                <p class="reservar__card-description">
                    Consulta el estado de tu cita agendada, fecha, hora y detalles de tu reservación.
                </p>
                <button type="button" class="reservar__card-btn reservar__card-btn--secondary" id="btnVerificar">
                    <i class="fa-solid fa-arrow-right"></i>
                    Consultar
                </button>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="reservar__info">
            <div class="reservar__info-card">
                <i class="fa-solid fa-phone"></i>
                <div>
                    <strong>¿Prefieres llamar?</strong>
                    <p>55-1234-5678</p>
                </div>
            </div>
            <div class="reservar__info-card">
                <i class="fa-brands fa-whatsapp"></i>
                <div>
                    <strong>WhatsApp</strong>
                    <p>55-8765-4321</p>
                </div>
            </div>
            <div class="reservar__info-card">
                <i class="fa-solid fa-clock"></i>
                <div>
                    <strong>Horario</strong>
                    <p>Lun - vier: 09:00 - 20:00</p>
                    <p>Sab: 09:00 - 19:00</p>
                    <p>Dom: 10:00 - 16:00</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Login -->
<div class="reservar__modal" id="modalLogin">
    <div class="reservar__modal-content">
        <button type="button" class="reservar__modal-close" id="closeLogin">
            <i class="fa-solid fa-xmark"></i>
        </button>
        
        <div class="reservar__modal-header">
            <div class="reservar__modal-icon">
                <i class="fa-solid fa-user-lock"></i>
            </div>
            <h3 class="reservar__modal-title">Iniciar Sesión</h3>
            <p class="reservar__modal-subtitle">Accede a tu cuenta para continuar</p>
        </div>
<!-- funciona con una API usando Ajax -->
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <!-- Incluir directamente el formulario de login -->
        <form action="/api/login" class="reservar__form" id="formLogin" method="POST">
            <!-- Campo que indica que viene desde el modal -->
            <input type="hidden" name="desde_modal" value="1">
            
            <div class="reservar__form-group">
                <label for="email" class="reservar__form-label">
                    <i class="fa-solid fa-envelope"></i>
                    Email
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="reservar__form-input"
                    placeholder="tu@email.com"
                    required
                >
            </div>

            <div class="reservar__form-group">
                <label for="password" class="reservar__form-label">
                    <i class="fa-solid fa-lock"></i>
                    Contraseña
                </label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="reservar__form-input"
                    placeholder="••••••••"
                    required
                >
            </div>

            <button type="submit" class="reservar__form-submit">
                <i class="fa-solid fa-right-to-bracket"></i>
                Iniciar Sesión
            </button>
        </form>

        <div class="reservar__modal-footer">
            <a href="/crear-cuenta" class="reservar__modal-link">
                <i class="fa-solid fa-user-plus"></i>
                ¿No tienes cuenta? Regístrate aquí
            </a>
            <a href="/olvide" class="reservar__modal-link">
                <i class="fa-solid fa-key"></i>
                ¿Olvidaste tu contraseña?
            </a>
        </div>
    </div>
</div>

<!-- Modal Verificar Cita -->
<div class="reservar__modal" id="modalVerificar">
    <div class="reservar__modal-content">
        <button type="button" class="reservar__modal-close" id="closeVerificar">
            <i class="fa-solid fa-xmark"></i>
        </button>
        
        <div class="reservar__modal-header">
            <div class="reservar__modal-icon reservar__modal-icon--secondary">
                <i class="fa-solid fa-search"></i>
            </div>
            <h3 class="reservar__modal-title">Verificar Cita</h3>
            <p class="reservar__modal-subtitle">Ingresa tu email y numero de folio</p>
        </div>

        <form action="/api/verificar-cita" method="POST" class="reservar__form" id="formVerificar">
            <div class="reservar__form-group">
                <label for="email_verificar" class="reservar__form-label">
                    <i class="fa-solid fa-envelope"></i>
                    Email
                </label>
                <input 
                    type="email" 
                    id="email_verificar" 
                    name="email" 
                    class="reservar__form-input"
                    placeholder="tu@email.com"
                    required
                >
            </div>

            <div class="reservar__form-group">
                <label for="codigo" class="reservar__form-label">
                    <i class="fa-solid fa-hashtag"></i>
                    Numero de Folio
                </label>
                <input 
                    type="text" 
                    id="codigo" 
                    name="codigo" 
                    class="reservar__form-input"
                    placeholder="BBR-20260112-143052-a5f3c2"
                    required
                >
                <small class="reservar__form-help">
                    El Folio fue enviado a tu email al agendar
                </small>
            </div>
            <div class="reservar__form-group">
                <label for="email_verificar" class="reservar__form-label">
                    <i class="fa-solid fa-key"></i>
                    Contraseña
                </label>
                <input 
                    type="password" 
                    id="email_verificar" 
                    name="password" 
                    class="reservar__form-input"
                    placeholder="••••••••"
                    required
                >
            </div>

            <button type="submit" class="reservar__form-submit">
                <i class="fa-solid fa-magnifying-glass"></i>
                Consultar mi Cita
            </button>
        </form>

        <div class="reservar__modal-footer">
            <p class="reservar__modal-info">
                <i class="fa-solid fa-circle-info"></i>
                Si no encuentras tu código, revisa tu bandeja de spam
            </p>
        </div>
    </div>
</div>