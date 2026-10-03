<?php

/** @var string $titulo */
/** @var string $nombre */
/** @var int $id */
/** @var int $barberoId */
?>
<h1 class="cita__heading"><?php echo $titulo; ?></h1>
<p class="cita__descripcion">Elije tus servicios y coloca tus datos.</p>
<?php
include_once __DIR__ . '/../templates/barra.php';
?>
<div id="app">
    <nav class="tabs">
        <button class="actual" type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Información Cita</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>
    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Elige tus servicios a continuación</p>
        <div id="servicios" class="listado-servicios"></div>
    </div>
    <div id="paso-2" class="seccion">
        <h2>Tus Datos y Cita</h2>
        <p class="text-center">Elije al barbero de tu preferencia y fecha de tu cita</p>
        <form action="" class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input
                    disabled
                    type="text"
                    id="nombre"
                    placeholder="Tu Nombre"
                    value="<?php echo $nombre; ?>">
            </div>

            <div class="campo">
                <label>Selecciona tu Barbero</label>
                <div id="barberos" class="listado-barberos"></div>
            </div>

            <div class="campo">
                <label for="fecha">Fecha</label>
                <input
                    type="text"
                    id="fecha"
                    placeholder="Selecciona una fecha">
            </div>
            
            <div class="campo">
            <label for="hora">Selecciona una Hora</label>
            <ul id="horarios" class="listado-horarios"></ul>
            
            <input type="hidden" id="hora" name="hora"> <!-- Almacena la hora seleccionada -->
            </div>

            <input type="hidden" id="id" value="<?php echo $id; ?>">
            <input type="hidden" id="barberoId" value="<?php echo $barberoId; ?>">
        </form>
    </div>
    <div id="paso-3" class="seccion contenido-resumen">
        <h2>Resumen</h2>
        <p class="text-center">Verifica que la información sea correcta</p>
    </div>
    <div class="paginacion">
        <button id="anterior" class="boton">&laquo; Anterior</button>
        <button id="siguiente" class="boton">Siguiente &raquo;</button>
    </div>

</div>