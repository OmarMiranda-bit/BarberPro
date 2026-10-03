<h1 class="servicios__heading"><?php echo $titulo; ?></h1>
<p class="servicios__descripcion">Llena los campos para añadir un nuevo servicio</p>

<?php 
    include_once __DIR__ . '/../templates/barra.php';
    include_once __DIR__ . '/../templates/alertas.php';
?>

<form class="formulario" method="POST" action="/servicios/crear">
    <?php include_once __DIR__ . '/formulario.php'; ?>

    <input type="submit" class="boton" value="Guardar Servicio">
</form>