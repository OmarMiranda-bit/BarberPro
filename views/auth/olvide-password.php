<h1 class="olvide__heading"><?php echo $titulo; ?></h1>
<p class="olvide__descripcion">Reestablecer tu contraseña escribiendo tu email a continuacion</p>
<?php
include_once __DIR__ . '/../templates/alertas.php';
?>

<form action="/olvide" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input 
        type="email" 
        name="email" 
        id="email"
        placeholder="Tu Email"
        >
    </div>

    <input type="submit" value="Enviar Instrucciones" class="boton">

</form>

<div class="acciones">
    <a href="/login">¿Ya tienes una cuenta? Inicia Sesión.</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una.</a>
</div>