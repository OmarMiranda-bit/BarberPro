<h1 class="nombre-pagina">Actualizar Servicio</h1>
<p class="descripcion-pagina">LLena los campos para actualizar el servicio</p>

<?php 
    include_once __DIR__ . '/../templates/barra.php';
    include_once __DIR__ . '/../templates/alertas.php';
?>

<form class="formulario" method="POST"">
    <?php include_once __DIR__ . '/formulario.php'; ?>

    <input type="submit" class="boton" value="Actualizar Servicio">
</form>