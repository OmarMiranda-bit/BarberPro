<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberPRO - <?php echo $titulo; ?></title>
    <!-- Fuente de google -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <!-- CDN de Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- CDN de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Estilos principales  -->
    <link rel="stylesheet" href="/build/css/app.css">
</head>
<body<?php echo hayUsuarioLogueado() ? ' class="usuario-logueado"' : ''; ?>>
    
    <?php include_once __DIR__ . '/templates/header.php'; ?>
    
    <div class="<?php echo $contenedor_clase ?? 'contenedor-paginas'; ?>">
        <?php echo $contenido; ?>
    </div>

    <?php include_once __DIR__ . '/templates/footer.php'; ?>

    <!-- <?php
    //echo $script ?? '';
    ?> -->
    <!-- Script principal -->
    <script src="/build/js/bundle.min.js" defer></script>
    <!-- CDN de Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- CDN de SweetAlert2 -->
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

            
</body>
</html>