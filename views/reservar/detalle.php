<?php
/** @var object $cita */
// Esta página se mostraría después de verificar la cita
// $cita contendría los datos de la cita desde la base de datos
// Para limpiar los espacios y evitar errores, podemos usar trim() en el estado de la cita
$cita->estado = trim($cita->estado ?? '');
// Mapeo rápido para la interfaz visual basado en el estado de la DB
$estadoClase = strtolower($cita->estado); // Convierte PENDIENTE a 'pendiente', NO_ASISTIO a 'no_asistio', etc.
$estadoTexto = str_replace('_', ' ', $cita->estado); // Para que NO_ASISTIO se lea "NO ASISTIO"

// Asignación dinámica de iconos
$estadoIcono = 'fa-circle-dot';
if ($cita->estado === 'PENDIENTE') $estadoIcono = 'fa-clock';
if ($cita->estado === 'COMPLETADA') $estadoIcono = 'fa-circle-check';
if ($cita->estado === 'CANCELADA') $estadoIcono = 'fa-circle-xmark';
if ($cita->estado === 'NO_ASISTIO') $estadoIcono = 'fa-user-slash';
?>

<section class="cita-detalle section">
    <div class="cita-detalle__container">
        <div class="cita-detalle__header">
            <!-- La clase se genera dinámicamente: cita-detalle__status--pendiente, cita-detalle__status--cancelada, etc. -->
            <div class="cita-detalle__status cita-detalle__status--<?php echo $estadoClase; ?>">
                <i class="fa-solid <?php echo $estadoIcono; ?>"></i>
                <span><?php echo $estadoTexto; ?></span>
            </div>
            <h2 class="section__title">Detalles de tu Cita</h2>
            <p class="cita-detalle__codigo">
                Código de confirmación: <strong><?php echo $cita->folio; ?></strong>
            </p>
        </div>

        <div class="cita-detalle__grid">
            <div class="cita-detalle__card cita-detalle__card--destacada">
                <div class="cita-detalle__card-icon">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="cita-detalle__card-content">
                    <h3 class="cita-detalle__card-title">Cliente</h3>
                    <p class="cita-detalle__card-text">
                        <?php echo $cita->cliente; ?>
                    </p>
                    <p class="cita-detalle__card-subtext">
                        <i class="fa-solid fa-envelope"></i>
                        <?php echo $cita->email; ?>
                    </p>
                </div>
            </div>

            <div class="cita-detalle__card">
                <div class="cita-detalle__card-icon">
                    <i class="fa-solid fa-scissors"></i>
                </div>
                <div class="cita-detalle__card-content">
                    <h3 class="cita-detalle__card-title">Servicios</h3>
                    <p class="cita-detalle__card-text">
                    <ul class="cita-detalle__lista-servicios">
                        <?php
                        $listaServicios = explode('||', $cita->servicios);
                        foreach ($listaServicios as $servicio):
                            list($nombre, $precio) = explode('|', $servicio);
                        ?>
                            <li>
                                <span class="cita-detalle__lista-servicios--nombre"> <?php echo $nombre; ?></span>
                                <span class="cita-detalle__lista-servicios--precio">$<?php echo number_format($precio, 0); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    </p>
                </div>
            </div>

            <div class="cita-detalle__card">
                <div class="cita-detalle__card-icon">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
                <div class="cita-detalle__card-content">
                    <h3 class="cita-detalle__card-title">Total</h3>
                    <p class="cita-detalle__card-text">
                        Precio Total
                    </p>
                    <p class="cita-detalle__card-price">
                        $<?php echo $cita->precioTotal; ?>
                    </p>
                </div>
            </div>

            <div class="cita-detalle__card">
                <div class="cita-detalle__card-icon">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
                <div class="cita-detalle__card-content">
                    <h3 class="cita-detalle__card-title">Fecha y Hora</h3>
                    <p class="cita-detalle__card-date">
                        <?php echo formatearFecha($cita->fecha); ?>
                    </p>
                    <p class="cita-detalle__card-time">
                        <i class="fa-solid fa-clock"></i>
                        <?php echo formatearHora($cita->hora); ?>
                    </p>
                </div>
            </div>

            <div class="cita-detalle__card">
                <div class="cita-detalle__card-icon">
                    <?php if ($cita->barberoImagen): ?>
                        <img
                            src="/img/barberos/<?php echo $cita->barberoImagen; ?>.webp"
                            alt="<?php echo $cita->barberoNombre; ?>"
                            class="cita-detalle__barbero-foto"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                        <i class="fa-solid fa-user-tie" style="display:none"></i>
                    <?php else: ?>
                        <i class="fa-solid fa-user-tie"></i>
                    <?php endif; ?>
                </div>
                <div class="cita-detalle__card-content">
                    <h3 class="cita-detalle__card-title">Barbero</h3>
                    <p class="cita-detalle__card-text">
                        <?php echo $cita->barberoNombre; ?>
                    </p>
                    <p class="cita-detalle__card-subtext">
                        <i class="fa-solid fa-scissors"></i>
                        Tu barbero asignado
                    </p>
                </div>
            </div>


        </div>

        <!-- Recordatorio -->
       <?php if ($cita->estado === 'PENDIENTE'): ?>
            <!-- Recordatorio: Solo visible si está PENDIENTE -->
            <div class="cita-detalle__recordatorio">
                <div class="cita-detalle__recordatorio-icon">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div class="cita-detalle__recordatorio-content">
                    <h3 class="cita-detalle__recordatorio-title">Recordatorio</h3>
                    <p class="cita-detalle__recordatorio-text">
                        Te hemos enviado un recordatorio a tu email. Te recomendamos llegar 5 minutos antes de tu cita.
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Ubicación -->
        <div class="cita-detalle__ubicacion">
            <div class="cita-detalle__ubicacion-header">
                <i class="fa-solid fa-location-dot"></i>
                <h3>Nuestra Ubicación</h3>
            </div>
            <p class="cita-detalle__ubicacion-direccion">
                Centro, Texcoco de Mora<br>
                Estado de México, México
            </p>
            <a href="https://www.google.com/maps/search/?api=1&query=Centro%2C+Texcoco+de+Mora%2C+M%C3%A9xico" target="_blank" rel="noopener noreferrer" class="cita-detalle__ubicacion-btn">
                <i class="fa-solid fa-map-location-dot"></i>
                Ver en Google Maps
            </a>
        </div>

        <!-- Acciones Dinamicas -->
        <div class="cita-detalle__acciones">
            <?php if ($cita->estado === 'PENDIENTE'): ?>
                <!-- Si está PENDIENTE: Puede modificar o cancelar -->
                <a href="/reservar/cita/editar?id=<?php echo $cita->id; ?>" class="cita-detalle__btn cita-detalle__btn--modificar">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Modificar Cita
                </a>
                <a href="#" id="btnCancelarCita" data-id="<?php echo $cita->id; ?>" class="cita-detalle__btn cita-detalle__btn--cancelar">
                    <i class="fa-solid fa-circle-xmark"></i>
                    Cancelar Cita
                </a>
            <?php else: ?>
                <!-- Si NO está pendiente (Completada, Cancelada, No Asistió): Botón para nueva cita -->
                <a href="/reservar" class="cita-detalle__btn cita-detalle__btn--modificar">
                    <i class="fa-solid fa-calendar-plus"></i>
                    Agendar Nueva Cita
                </a>
            <?php endif; ?>
            
            <form action="/logout" method="POST" style="display: inline;">
                <button type="submit" class="cita-detalle__btn cita-detalle__btn--cancelar" style="border: none; cursor: pointer;">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Cerrar Sesión
                </button>
            </form>

            <a href="/" class="cita-detalle__btn cita-detalle__btn--volver">
                <i class="fa-solid fa-house"></i>
                Volver al Inicio
            </a>
        </div>

        <?php if ($cita->estado === 'PENDIENTE'): ?>
            <!-- Tips: Solo visibles si está PENDIENTE -->
            <div class="cita-detalle__tips">
                <h3 class="cita-detalle__tips-title">
                    <i class="fa-solid fa-lightbulb"></i>
                    Tips para tu visita
                </h3>
                <ul class="cita-detalle__tips-lista">
                    <li>Llega 5 minutos antes de tu cita</li>
                    <li>Si tienes alguna preferencia especial, comunícasela a tu barbero</li>
                    <li>Trae una foto de referencia si tienes un estilo específico en mente</li>
                    <li>Si necesitas cancelar, hazlo con al menos 24 horas de anticipación</li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</section>