<?php
/** @var string $titulo */
/** @var object $cita */
?>

<h1 class="cita__heading"><?php echo $titulo; ?></h1>
<p class="cita__descripcion">Modifica los detalles de tu cita.</p>
<p class="cita__descripcion">
    Folio: <strong><?php echo $cita->folio; ?></strong>
</p>

<div id="app">
    <div class="seccion mostrar">
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <!-- <form action="/api/citas/actualizar" method="POST" class="formulario"> -->

            <?php include_once __DIR__ . '/formulario.php'; ?>

            <!-- <button type="submit" class="formulario__submit formulario__submit--registrar">
                Guardar Cambios
            </button> -->
        <!-- </form> -->
    </div>
</div>