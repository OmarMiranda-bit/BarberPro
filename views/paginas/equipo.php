<!-- SECCION DEL EQUIPO -->
<section class="section">
    <div class="section__header">
        <h2 class="section__title">Nuestro Equipo</h2>
        <p class="section__subtitle">Conoce a los maestros barberos</p>
    </div>

    <div class="team">
        <?php foreach ($empleados as $empleado):
            // Decodificar el string JSON de la base de datos a un objeto PHP
            // $redes = json_decode($empleado->redes);
            //array asociativo para las redes sociales
            $redes = (array) json_decode($empleado->redes ?? '{}', true);

            //mapa de redes sociales con sus clases de Font Awesome
            $iconosRedes = [
                'instagram' => 'fa-brands fa-instagram',
                'facebook'  => 'fa-brands fa-facebook',
                'tiktok'    => 'fa-brands fa-tiktok',
                'twitter'   => 'fa-brands fa-x-twitter',
                'youtube'   => 'fa-brands fa-youtube'
            ];

            // Extraer solo el año de la fecha_ingreso
            $anioIngreso = date('Y', strtotime($empleado->fecha_ingreso));
        ?>

            <div class="team__card">

                <img src="/img/barberos/<?php echo $empleado->imagen; ?>.webp"
                    alt="<?php echo $empleado->usuario->nombre; ?>"
                    class="team__image">

                <div class="team__info">
                    <h3 class="team__name">
                        <?php echo $empleado->usuario->nombre . " " . $empleado->usuario->apellido; ?>
                    </h3>

                    <p class="team__role"><?php echo $empleado->puesto; ?></p>
                    <p class="team__experience">En BarberPRO desde <?php echo $anioIngreso; ?></p>

                    <!-- Redes sociales dinamicas con iconos -->
                    <div class="team__socials">
                        <?php foreach ($iconosRedes as $red => $iconoClass): ?>
                            <?php if (isset($redes[$red]) && !empty($redes[$red])): ?>
                                <a href="<?php echo htmlspecialchars($redes[$red]); ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="team__social-link team__social-link--<?php echo $red; ?>"
                                    title="<?php echo ucfirst($red); ?>">
                                    <i class="<?php echo $iconoClass; ?>"></i>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>