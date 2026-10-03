<h1 class="servicios__heading"><?php echo $titulo;?></h1>
<p class="servicios__descripcion">Administración de Servicios</p>

<?php 
    include_once __DIR__ . '/../templates/barra.php';
?>

<ul class="servicios">
    <?php foreach($servicios as $servicio): ?>
        <li>
            <p>Nombre: <span><?php echo $servicio->nombre?></span></p>
            <p>Precio: <span><?php echo '$' . $servicio->precio?></span></p>
            <div class="acciones">
                <a href="/servicios/actualizar?id=<?php echo $servicio->id; ?>" class="botonCH">Actualizar</a>
                <form method="POST" action="/servicios/eliminar" class="w-100">
                    <input type="hidden" name="id" value="<?php echo $servicio->id; ?>">
                    <input type="submit" class="botonCH-eliminar" value="Borrar">
                </form>
        </li>
    <?php endforeach; ?>
</ul>