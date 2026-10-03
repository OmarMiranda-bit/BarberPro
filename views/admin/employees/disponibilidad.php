<div class="contenedor-disponibilidad">
    <h2 class="admin__heading"><?php echo $titulo; ?></h2>
    <p class="admin__descripcion">Define tus días laborables, horarios de atención o bloquea un día por imprevisto.</p>

    <a href="/admin/employees" class="contenedor-disponibilidad__btn-regresar">← Volver al Panel</a>

    <!-- FORMULARIO 1: RUTINA SEMANAL DE HORARIOS -->
    <form action="/api/disponibilidad/guardar" method="POST" class="formulario-disponibilidad" id="form-horarios">
        <input type="hidden" name="empleado_id" value="<?php echo $empleadoId; ?>">

        <h3 class="formulario-disponibilidad__subtitulo">Horario Semanal Habitual</h3>

        <div class="tabla-horarios">
            <?php foreach ($diasSemana as $numDia => $nombreDia): 
                $horarioExistente = $horarios[$numDia] ?? null;
                $activo = $horarioExistente ? (bool)$horarioExistente->activo : ($numDia !== 0); // Domingo inactivo por defecto
                $horaInicio = $horarioExistente->hora_inicio ?? '10:00';
                $horaFin = $horarioExistente->hora_fin ?? '20:00';
            ?>
                <div class="fila-dia">
                    <div class="dia-control">
                        <input type="checkbox" 
                               name="horarios[<?php echo $numDia; ?>][activo]" 
                               id="dia-<?php echo $numDia; ?>" 
                               value="1" 
                               class="checkbox-dia"
                               <?php echo $activo ? 'checked' : ''; ?>>
                        <label for="dia-<?php echo $numDia; ?>"><?php echo $nombreDia; ?></label>
                    </div>

                    <div class="horas-control <?php echo !$activo ? 'deshabilitado' : ''; ?>" id="horas-container-<?php echo $numDia; ?>">
                        <div class="campo-hora">
                            <label>Entrada:</label>
                            <input type="time" 
                                   name="horarios[<?php echo $numDia; ?>][hora_inicio]" 
                                   value="<?php echo substr($horaInicio, 0, 5); ?>"
                                   <?php echo !$activo ? 'disabled' : ''; ?>>
                        </div>

                        <div class="campo-hora">
                            <label>Salida:</label>
                            <input type="time" 
                                   name="horarios[<?php echo $numDia; ?>][hora_fin]" 
                                   value="<?php echo substr($horaFin, 0, 5); ?>"
                                   <?php echo !$activo ? 'disabled' : ''; ?>>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="campo-submit">
            <input type="submit" class="btn-guardar" value="Guardar Configuración">
        </div>
    </form>

    <!-- FORMULARIO 2: BLOQUEO PUNTUAL POR COMPROMISO O EMERGENCIA -->
    <form action="/api/disponibilidad/bloquear" method="POST" class="formulario-disponibilidad" id="form-bloqueo" style="margin-top: 3rem;">
        <input type="hidden" name="empleado_id" value="<?php echo $empleadoId; ?>">
        
        <h3 class="formulario-disponibilidad__subtitulo">Bloquear Día por Imprevisto / Emergencia</h3>
        <p class="admin__descripcion" style="text-align: left; margin-bottom: 2rem;">
            Si necesitas tomar un día libre extraordinario, selecciónalo aquí. Si ya existen citas ese día, el sistema te solicitará confirmación antes de cancelarlas y notificar a los clientes.
        </p>

        <div class="fila-dia" style="border: none;">
            <div class="campo-hora" style="width: 100%;">
                <label for="fecha_bloqueo">Seleccionar Fecha a Bloquear:</label>
                <input type="date" id="fecha_bloqueo" name="fecha_bloqueo" min="<?php echo date('Y-m-d'); ?>" required>
            </div>
        </div>

        <div class="campo-submit">
            <input type="submit" class="btn-cancelar" value="Bloquear Día" style="width: 100%;">
        </div>
    </form>
</div>