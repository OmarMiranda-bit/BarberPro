<section class="services section">
    <div class="section__header">
        <h3 class="section__title">Nuestros Servicios</h3>
        <p class="section__subtitle">Experimenta el arte del cuidado masculino</p>
    </div>
    
    <!-- CONTENEDOR GRID: Solo debe haber uno, el foreach va ADENTRO -->
    <div class="services__grid">
        
        <?php foreach($servicios as $servicio): ?>
            <!-- TARJETA: Esto es lo que se va a repetir -->
            <div class="services__card">
                
                <!-- OJO A LA RUTA DE LA IMAGEN -->
                <img src="/img/servicios/<?php echo $servicio->imagen; ?>.webp" 
                     alt="<?php echo $servicio->nombre; ?>" 
                     class="services_imagen">
                
                <div class="services_contenido">
                    <h3 class="services__title"><?php echo $servicio->nombre; ?></h3>
                    <p class="services__description">
                        <?php echo $servicio->descripcion; ?>
                    </p>
                    
                    <div class="services__footer">
                        <div class="services__footer_price">
                            $<?php echo $servicio->precio; ?> <span>MXN</span>
                        </div>
                        <a href="/reservar" class="services__footer_btn">Reservar</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
    </div>
</section>