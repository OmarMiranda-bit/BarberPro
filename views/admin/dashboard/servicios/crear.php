<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/admin/dashboard/servicios">
        <i class="fa-duotone fa-solid fa-circle-chevron-left"></i>
        Volver
    </a>
</div>
<div class="dashboard__formulario">
    <?php
    include_once __DIR__ .'/../../../templates/alertas.php';
    ?>

    <form action="/admin/dashboard/servicios/crear" method="POST" enctype="multipart/form-data" class="formulario">
         <?php
         include_once __DIR__ .'/formulario.php';
         ?>
        <input class="formulario__submit formulario__submit--registrar" type="submit" value="Guardar Servicio">
    </form>
</div>