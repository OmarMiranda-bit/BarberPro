<?php

/** @var object $cita */ ?>

<!-- 1. Servicios — FUERA del form, igual que cita.php -->
<h2>Servicios</h2>
<p class="text-center">Actualiza o conserva tus servicios</p>
<div id="servicios" class="listado-servicios"></div>

<!-- 2. Barbero — FUERA del form, igual que cita.php -->
<h2>Barbero</h2>
<p class="text-center">Actualiza al barbero o conserva el actual</p>
<div id="barberos" class="listado-barberos"></div>

<!-- Form solo para fecha, hora y los hidden inputs -->
<form action="/api/citas/actualizar" method="POST" class="formulario">

    <!-- Hidden inputs: JS los lee para pre-poblar -->
    <input type="hidden" id="serviciosActuales" value="<?php echo $cita->serviciosIds; ?>">
    <input type="hidden" id="barberoActual" value="<?php echo $cita->empleadoId; ?>">
    <input type="hidden" id="fechaActual" value="<?php echo $cita->fecha; ?>">
    <input type="hidden" id="horaActual" value="<?php echo substr($cita->hora, 0, 5); ?>">
    <input type="hidden" id="duracionTotal" value="<?php echo $cita->duracionTotal; ?>">

    <!-- Hidden inputs: JS los actualiza al seleccionar -->
    <input type="hidden" name="id" id="hiddenId" value="<?php echo $cita->id; ?>">
    <input type="hidden" name="servicios" id="hiddenServicios" value="<?php echo $cita->serviciosIds; ?>">
    <input type="hidden" name="empleadoId" id="hiddenBarbero" value="<?php echo $cita->empleadoId; ?>">
    <input type="hidden" name="fecha" id="hiddenFecha" value="<?php echo $cita->fecha; ?>">
    <input type="hidden" name="hora" id="hiddenHora" value="<?php echo substr($cita->hora, 0, 5); ?>">

    <!-- 3. Fecha -->
    <div class="campo">
        <label for="fecha">Fecha</label>
        <input type="text" id="fecha" placeholder="Selecciona una fecha" autocomplete="off">
    </div>

    <!-- 4. Horarios -->
    <h2>Hora</h2>
    <ul id="horarios" class="listado-horarios"></ul>

    <input type="submit"
        class="formulario__submit formulario__submit--registrar"
        value="Guardar Cambios" id="guardarCambios">
</form>