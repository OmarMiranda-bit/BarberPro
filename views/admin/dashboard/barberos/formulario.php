<?php
  /** @var \Model\Usuario $usuario */
?>

<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Personal</legend>
    <div class="formulario__campo">
        <label for="nombre" class="formulario__label">Nombre</label>
        <input
            class="formulario__input"
            type="text"
            id="nombre"
            name="nombre"
            placeholder="Nombre Barbero"
            value="<?php echo $usuario->nombre ?? ''; ?>">
    </div>
    <div class="formulario__campo">
        <label for="apellido" class="formulario__label">Apellido</label>
        <input
            class="formulario__input"
            type="text"
            id="apellido"
            name="apellido"
            placeholder="Apellido Barbero"
            value="<?php echo $usuario->apellido ?? ''; ?>">
    </div>
    <div class="formulario__campo">
        <label for="telefono" class="formulario__label">Telefono</label>
        <input
            class="formulario__input"
            type="tel"
            id="telefono"
            name="telefono"
            placeholder="Telefono Barbero"
            value="<?php echo $usuario->telefono ?? ''; ?>">
    </div>
    <?php if(is_null($usuario->id)) { ?>
        <div class="formulario__campo">
            <label for="email" class="formulario__label">Email</label>
            <input
                class="formulario__input"
                type="email"
                id="email"
                name="email"
                placeholder="Email Barbero"
                value="<?php echo $usuario->email ?? ''; ?>">
        </div>
    <?php } ?>
    <div class="formulario__campo">
        <label for="imagen" class="formulario__label">Imagen</label>
        <input
            class="formulario__input formulario__input--file"
            type="file"
            id="imagen"
            name="imagen">
    </div>
    
    <?php if (isset($empleado->imagen_actual)) { ?>
        <p class="formulario__texto">Imagen Actual</p>
        <div class="formulario__imagen">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/img/barberos/' . $empleado->imagen; ?>.webp" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/img/barberos/' . $empleado->imagen; ?>.png" type="image/png">
                <img src="<?php echo $_ENV['HOST'] . '/img/barberos/' . $empleado->imagen; ?>.png" alt="Imagen Barbero">
            </picture>
        </div>
    <?php } ?>

    <?php if(is_null($usuario->id)) { ?>
        <div class="formulario__campo">
            <label for="password" class="formulario__label">Password</label>
            <input
                class="formulario__input"
                type="password"
                id="password"
                name="password"
                placeholder="Password Barbero">
        </div>
    <?php } ?>
</fieldset>


<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Redes Sociales</legend>

    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-instagram"></i>
            </div>
            <input
                class="formulario__input--sociales"
                type="text"
                name="redes[instagram]"
                placeholder="Instagram"
                value="<?php echo $redes->instagram ?? ''; ?>">
        </div>
    </div>
    
    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-facebook"></i>
            </div>
            <input
                class="formulario__input--sociales"
                type="text"
                name="redes[facebook]"
                placeholder="Facebook"
                value="<?php echo $redes->facebook ?? ''; ?>">
        </div>
    </div>

    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-tiktok"></i>
            </div>
            <input
                class="formulario__input--sociales"
                type="text"
                name="redes[tiktok]"
                placeholder="TikTok"
                value="<?php echo $redes->tiktok ?? ''; ?>">
        </div>
    </div>

    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-x-twitter"></i>
            </div>
            <input
                class="formulario__input--sociales"
                type="text"
                name="redes[twitter]"
                placeholder="X (Twitter)"
                value="<?php echo $redes->twitter ?? ''; ?>">
        </div>
    </div>
    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-youtube"></i>
            </div>
            <input
                class="formulario__input--sociales"
                type="text"
                name="redes[youtube]"
                placeholder="Youtube"
                value="<?php echo $redes->youtube ?? ''; ?>">
        </div>
    </div>

</fieldset>