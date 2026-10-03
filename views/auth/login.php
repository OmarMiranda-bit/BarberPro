<h1 class="login__heading"><?php echo $titulo; ?></h1>
<p class="login__descripcion">Inicia sesión ingresando tus datos</p>

<?php
include_once __DIR__ . '/../templates/alertas.php';
?>

<form action="/login" class="formulario" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input 
              type="email"
              id="email"
              placeholder="Tu Email"
              name="email"
        >
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input 
        type="password" 
        name="password" 
        id="password"
        placeholder="Tu password"
        >
    </div>
    <input type="submit" value="Iniciar Sesión" class="boton">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una.</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>