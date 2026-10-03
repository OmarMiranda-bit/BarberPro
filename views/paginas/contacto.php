<!-- Seccion de contacto con información y formulario de contacto. -->
<section class="contacto section">
    <div class="contacto__container">
        <!-- Header -->
        <div class="section__header">
            <h2 class="section__title">Contáctanos</h2>
            <p class="section__subtitle">Estamos aquí para atenderte</p>
        </div>

        <!-- Grid principal -->
        <div class="contacto__grid">
            <!-- Información de contacto -->
            <div class="contacto__info">
                <h3 class="contacto__info-title">Información de Contacto</h3>

                <!-- Dirección -->
                <div class="contacto__info-item">
                    <div class="contacto__info-icon contacto__info-icon--locacion">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div class="contacto__info-content">
                        <h4 class="contacto__info-label">Dirección</h4>
                        <p class="contacto__info-text">
                            Centro, Texcoco de Mora<br>
                            Estado de México, México
                        </p>
                        <a href="https://www.google.com/maps/search/?api=1&query=Centro%2C+Texcoco+de+Mora%2C+M%C3%A9xico" target="_blank" rel="noopener noreferrer" class="contacto__info-text--link">
                            <i class="fa-solid fa-map-location-dot"></i>
                            Ver en Google Maps
                        </a>
                    </div>
                </div>

                <!-- Teléfono -->
                <div class="contacto__info-item">
                    <div class="contacto__info-icon contacto__info-icon--telefono">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div class="contacto__info-content">
                        <h4 class="contacto__info-label">Teléfono</h4>
                        <a href="tel:5512345678" class="contacto__info-text contacto__info-text--link">
                            55-1234-5678
                        </a>
                    </div>
                </div>

                <!-- WhatsApp -->
                <div class="contacto__info-item">
                    <div class="contacto__info-icon contacto__info-icon--whatsapp">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div class="contacto__info-content">
                        <h4 class="contacto__info-label">WhatsApp</h4>
                        <a href="https://wa.me/5587654321" target="_blank" class="contacto__info-text contacto__info-text--link">
                            55-8765-4321
                        </a>
                    </div>
                </div>

                <!-- Email -->
                <div class="contacto__info-item">
                    <div class="contacto__info-icon contacto__info-icon--email">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="contacto__info-content">
                        <h4 class="contacto__info-label">Email</h4>
                        <a href="#" class="contacto__info-text contacto__info-text--link">
                            contacto@barberpro.com
                        </a>
                    </div>
                </div>

                <!-- Horario -->
                <div class="contacto__info-item">
                    <div class="contacto__info-icon contacto__info-icon--clock">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div class="contacto__info-content">
                        <h4 class="contacto__info-label">Horario de Atención</h4>
                        <p class="contacto__info-text">
                            Lunes a Viernes<br>
                            10:00 AM - 9:00 PM
                        </p>
                        <p class="contacto__info-text contacto__info-text--closed">
                            Sabado: Cerrado<br>
                            Domingo: Cerrado
                        </p>
                    </div>
                </div>

                <!-- Redes Sociales -->
                <div class="contacto__redes">
                    <h4 class="contacto__redes-title">Síguenos</h4>
                    <div class="contacto__redes-links">
                        <a href="https://facebook.com" target="_blank" class="contacto__red contacto__red--facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="https://instagram.com" target="_blank" class="contacto__red contacto__red--instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="https://twitter.com" target="_blank" class="contacto__red contacto__red--twitter">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <a href="https://tiktok.com" target="_blank" class="contacto__red contacto__red--tiktok">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulario de contacto -->
            <div class="contacto__formulario">
                <h3 class="contacto__formulario-title">Envíanos un Mensaje</h3>
                <p class="contacto__formulario-subtitle">
                    Completa el formulario y nos pondremos en contacto contigo lo antes posible
                </p>

                <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

                <form action="/contacto" method="POST" class="contacto__form">
                    <div class="contacto__form-group">
                        <label for="nombre" class="contacto__form-label">
                            <i class="fa-solid fa-user"></i>
                            Nombre Completo
                        </label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            class="contacto__form-input"
                            placeholder="Tu nombre completo"
                            value="<?php echo htmlspecialchars($datos['nombre'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="contacto__form-group">
                        <label for="email" class="contacto__form-label">
                            <i class="fa-solid fa-envelope"></i>
                            Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="contacto__form-input"
                            placeholder="tu@email.com"
                            value="<?php echo htmlspecialchars($datos['email'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="contacto__form-group">
                        <label for="telefono" class="contacto__form-label">
                            <i class="fa-solid fa-phone"></i>
                            Teléfono
                        </label>
                        <input
                            type="tel"
                            id="telefono"
                            name="telefono"
                            class="contacto__form-input"
                            placeholder="55-1234-5678"
                            value="<?php echo htmlspecialchars($datos['telefono'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="contacto__form-group">
                        <label for="asunto" class="contacto__form-label">
                            <i class="fa-solid fa-tag"></i>
                            Asunto
                        </label>
                        <select
                            id="asunto"
                            name="asunto"
                            class="contacto__form-input"
                            required>
                            <option value="" disabled <?php echo empty($datos['asunto']) ? 'selected' : ''; ?>>Selecciona un asunto</option>
                            <option value="informacion" <?php echo ($datos['asunto'] ?? '') === 'informacion' ? 'selected' : ''; ?>>Solicitar información</option>
                            <option value="sugerencia" <?php echo ($datos['asunto'] ?? '') === 'sugerencia' ? 'selected' : ''; ?>>Sugerencia</option>
                            <option value="queja" <?php echo ($datos['asunto'] ?? '') === 'queja' ? 'selected' : ''; ?>>Queja</option>
                            <option value="otro" <?php echo ($datos['asunto'] ?? '') === 'otro' ? 'selected' : ''; ?>>Otro</option>
                        </select>
                    </div>

                    <div class="contacto__form-group">
                        <label for="mensaje" class="contacto__form-label">
                            <i class="fa-solid fa-message"></i>
                            Mensaje
                        </label>
                        <textarea
                            id="mensaje"
                            name="mensaje"
                            class="contacto__form-textarea"
                            placeholder="Escribe tu mensaje aquí..."
                            rows="5"
                            required><?php echo htmlspecialchars($datos['mensaje'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" class="contacto__form-submit">
                        <i class="fa-solid fa-paper-plane"></i>
                        Enviar Mensaje
                    </button>
                </form>
            </div>
        </div>

        <!-- Mapa -->
        <div class="contacto__mapa">
            <h3 class="contacto__mapa-title">Encuéntranos</h3>
            <div class="contacto__mapa-contenedor">
                <!-- iframe de Google Maps -->
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7521.350305474832!2d-98.890927549439!3d19.512606989257268!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d1e64ac5bed935%3A0x9e9acd34b27b8bf2!2sCentro%2C%20Texcoco%20de%20Mora%2C%20M%C3%A9x.!5e0!3m2!1ses-419!2smx!4v1790821205270!5m2!1ses-419!2smx"
                    class="location__map"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="strict-origin-when-cross-origin">
                </iframe>

            </div>
        </div>

        <!-- Llamada a la acción -->
        <div class="contacto__cta">
            <div class="contacto__cta-content">
                <h3 class="contacto__cta-title">¿Listo para tu próxima cita?</h3>
                <p class="contacto__cta-text">
                    Agenda tu cita ahora y disfruta de nuestros servicios profesionales
                </p>
                <a href="/reservar" class="contacto__cta-btn">
                    <i class="fa-solid fa-calendar-check"></i>
                    Reservar Cita
                </a>
            </div>
        </div>
</section>