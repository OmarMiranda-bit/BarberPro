<h2 class="dashboard__heading" ><?php echo $titulo;?></h2>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/admin/dashboard/barberos">
        <i class="fa-duotone fa-solid fa-circle-chevron-left"></i>
        Volver
    </a>
</div>
<!-- preguntar a gemini o pedir ayuda para que cuando se añada un nuevo barbero desde el dashboard se mande un correo para confirmar el email y que en cuanto se se valide que ya esta confirmada la cuenta se agregue automaticamente su rol a 2 (empleado) pero solo cuando sea añadido desde el dashboard  -->
<div class="dashboard__formulario">
    <?php
    include_once __DIR__ .'/../../../templates/alertas.php';
    ?>

    <form action="/admin/dashboard/barberos/crear" method="POST" enctype="multipart/form-data" class="formulario">
         <?php
         include_once __DIR__ .'/formulario.php';
         ?>
        <input class="formulario__submit formulario__submit--registrar" type="submit" value="Registrar Barbero">
    </form>
</div>