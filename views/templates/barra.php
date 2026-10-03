<div class="barra">
    <p>Hola: <?php echo $nombre ?? ' '; ?></p>
    <form action="/logout" method="POST" class="barra__boton">
        <input type="submit" value="Cerrar Sesión">
    </form>
</div>