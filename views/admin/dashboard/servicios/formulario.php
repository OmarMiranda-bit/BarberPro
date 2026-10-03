<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información del Servicio</legend>
    <div class="formulario__campo">
        <label for="nombre" class="formulario__label">Nombre</label>
        <input
            class="formulario__input"
            type="text"
            id="nombre"
            name="nombre"
            placeholder="Nombre Servicio"
            value="<?php echo $servicio->nombre ?? ''; ?>">
    </div>
    <div class="formulario__campo">
        <label for="precio" class="formulario__label">Precio</label>
        <input
            class="formulario__input"
            type="number"
            id="precio"
            name="precio"
            placeholder="Precio Servicio"
            value="<?php echo $servicio->precio ?? ''; ?>">
    </div>

    <div class="formulario__campo">
        <label for="duracion" class="formulario__label">Duración (minutos)</label>
        <input
            class="formulario__input"
            type="number"
            id="duracion"
            name="duracion"
            min="1"
            placeholder="45"
            value="<?php echo $servicio->duracion ?? ''; ?>">
    </div>

    <div class="formulario__campo">
        <label for="imagen" class="formulario__label">Imagen</label>
        <input
            class="formulario__input formulario__input--file"
            type="file"
            id="imagen"
            name="imagen">
    </div>

    <?php if (isset($servicio->imagen_actual)) { 
            ?>
        <p class="formulario__texto">Imagen Actual</p>
        <div class="formulario__imagen">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/img/servicios/' . $servicio->imagen; 
                                ?>.webp" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/img/servicios/' . $servicio->imagen; 
                                ?>.png" type="image/png">
                <img src="<?php echo $_ENV['HOST'] . '/img/servicios/' . $servicio->imagen; 
                            ?>.png" alt="Imagen Servicio">
            </picture>
        </div>
    <?php } 
    ?>

    <div class="campo">
        <label for="descripcion">Descripción</label>
        <textarea
            class="formulario__textarea"
            id="descripcion"
            name="descripcion"
            placeholder="Describe el servicio ..."
            rows="4"><?php echo $servicio->descripcion; ?></textarea>
    </div>
</fieldset>