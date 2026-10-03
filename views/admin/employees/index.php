<?php

/** @var string $titulo */
/** @var int $totalCitasHoy */
/** @var object[] $citas */
/** @var string $fecha */
?>

<div class="contenedor-empleados">
    <h2 class="admin__heading"><?php echo $titulo; ?></h2>
    <p class="admin__descripcion">Filtra por fecha y gestiona el progreso de tus servicios.</p>

    <?php include_once __DIR__ . '/../../templates/barra.php'; ?>

    <div class="barbero-stats">
        <div class="stat-card">
            <p class="stat-label">Citas Hoy</p>
            <p class="stat-numero"><?php echo $totalCitasHoy; ?></p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Próximo Cliente</p>
            <p class="stat-numero" style="font-size: 1.8rem;">
                <?php echo $citas[0]->hora ?? '--:--'; ?>
            </p>
        </div>
        <div class="stat-card stat-card--accion">
            <p class="stat-label">Horario Laboral</p>
            <a href="/admin/employees/disponibilidad" class="btn-administrar">
             <i class="fa-solid fa-cog"></i> Administrar
            </a>
        </div>
    </div>



    <div class="busqueda">
        <form action="" class="formulario">
            <div class="campo">
                <label for="fecha">Buscar Fecha:</label>
                <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
            </div>
        </form>
    </div>

    <?php if (count($citas) === 0) {
        echo "<h2 class='admin__heading'>No hay citas programadas</h2>";
    } ?>

    <div id="citas-admin">
        <ul class="citas">
            <?php
            $idCita = 0;
            foreach ($citas as $key => $cita) {
                if ($idCita !== $cita->id) {
                    $total = 0;
                    $totalDuracion = 0; //Inicializamos el acumulador de duración
            ?>
                    <li class="cita-card">
                        <p>Hora: <span><?php echo $cita->hora; ?></span></p>
                        <p>Cliente: <a href="/historial?id=<?php echo $cita->clienteId; ?>" class="link-historial"><?php echo $cita->cliente; ?></a></p>
                        <p>Teléfono: <span><?php echo $cita->telefono; ?></span></p>

                        <h3>Servicios Solicitados</h3>
                    <?php $idCita = $cita->id;
                }
                $total += $cita->precio;
                $totalDuracion += $cita->duracion; // sumamos la duración de cada servicio
                    ?>

                    <p class="cita-card__servicio"><?php echo $cita->servicio; ?> - <span>$<?php echo $cita->precio; ?></span></p>

                    <?php
                    $actual = $cita->id;
                    $proximo = $citas[$key + 1]->id ?? 0;

                    if (esUltimo($actual, $proximo)) { ?>
                        <p class="total">Total a Cobrar: <span>$<?php echo $total; ?></span></p>

                        <!-- data-attributes con la info del tiempo de la cita -->
                        <div class="acciones-barbero"
                            data-id="<?php echo $cita->id; ?>"
                            data-hora="<?php echo $cita->hora; ?>"
                            data-duracion="<?php echo $totalDuracion; ?>">

                            <?php if (trim($cita->estado) === 'PENDIENTE'): ?>
                                <?php
                                // Validamos si la fecha que se está viendo es el día de hoy real en el servidor
                                $esHoy = ($fecha === date('Y-m-d'));

                                // Calculamos los tiempos para activar el botón de iniciar servicio solo si es hoy y estamos dentro del rango de tiempo permitido
                                $horaActual = date('H:i:s');
                                $tiempoMinimoParaIniciar = strtotime($cita->hora) - (15 * 60); // Restamos 15 minutos a la hora de la cita
                                $horaValida = strtotime($horaActual) >= $tiempoMinimoParaIniciar;

                                // Calculamos el tiempo de tolerancia para activar el "No Asistió" (15 minutos después)
                                $tiempoMinimoParaNoAsistio = strtotime($cita->hora) + (15 * 60); // 15 minutos después de la cita
                                $noAsistioValido = strtotime($horaActual) >= $tiempoMinimoParaNoAsistio;

                                // Si es hoy y ya estamos en el rango de tiempo, mostramos el botón activo
                                if ($esHoy && $horaValida):
                                ?>
                                    <button class="btn-iniciar">Iniciar Servicio</button>
                                <?php else: ?>
                                    <!-- Si no cumple, el botón se muestra gris y deshabilitado -->
                                    <button class="btn-iniciar btn-iniciar--deshabilitado" disabled
                                        title="Solo disponible el día de la cita (hasta 15 min antes)">
                                        Iniciar Servicio
                                    </button>
                                <?php endif; ?>

                                <!-- Boton para marcar que no asistio el cliente -->
                                <form action="/api/no-asistio" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $cita->id; ?>">

                                    <?php if ($esHoy && $noAsistioValido): ?>
                                        <!-- Se activa solo si es hoy o el dia de la cita o si ya pasaron los 15 minutos de tolerancia -->
                                        <input type="submit" class="btn-cancelar" value="No Asistió">
                                    <?php else: ?>
                                        <!-- Botón deshabilitado visualmente -->
                                        <input type="submit" class="btn-cancelar btn-cancelar--deshabilitado" value="No Asistió" disabled title="Disponible 15 minutos después de la hora programada">
                                    <?php endif; ?>
                                </form>
                                    <?php endif; ?>

                            <?php if (trim($cita->estado) === 'EN_PROCESO'): ?>
                                <div class="cronometro">
                                    Tiempo restante: <span class="cronometro__tiempo" id="timer-<?php echo $cita->id; ?>">--:--</span>
                                </div>
                                <button class="btn-finalizar">Finalizar Servicio</button>
                            <?php endif; ?>

                        </div>
                <?php }
                } ?>
        </ul>
    </div>
</div>