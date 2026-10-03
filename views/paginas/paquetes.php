<!-- SECCION DE PRECIOS -->
<section class="section">
    <div class="section__header">
        <h2 class="section__title">Nuestros Precios</h2>
        <p class="section__subtitle">Paquetes diseñados para ti</p>
    </div>

    <div class="paquetes">
        <!-- Paquete Básico -->
        <div class="paquete__card">
            <div class="paquete__header">
                <h3 class="paquete__name">Básico</h3>
            </div>
            <div class="paquete__price">$250 <span class="paquete__currency">MXN</span></div>
            <ul class="paquete__features">
                <li class="paquete__feature">Corte de cabello</li>
                <li class="paquete__feature">Lavado con shampoo</li>
                <li class="paquete__feature">Secado y peinado</li>
                <li class="paquete__feature">Acabado con máquina</li>
            </ul>
            <button class="paquete__btn" onclick="reservar('Básico')">Reservar Ahora</button>
        </div>

        <!-- Paquete Premium (Destacado) -->
        <div class="paquete__card paquete__card--destacado">
            <span class="paquete__badge">Popular</span>
            <div class="paquete__header">
                <h3 class="paquete__name">Premium</h3>
            </div>
            <div class="paquete__price">$400 <span class="paquete__currency">MXN</span></div>
            <ul class="paquete__features">
                <li class="paquete__feature">Corte de cabello premium</li>
                <li class="paquete__feature">Arreglo de barba completo</li>
                <li class="paquete__feature">Lavado con productos premium</li>
                <li class="paquete__feature">Masaje capilar</li>
                <li class="paquete__feature">Tratamiento hidratante</li>
                <li class="paquete__feature">Toallas calientes</li>
            </ul>
            <button class="paquete__btn" onclick="reservar('Premium')">Reservar Ahora</button>
        </div>

        <!-- Paquete Deluxe -->
        <div class="paquete__card">
            <div class="paquete__header">
                <h3 class="paquete__name">Deluxe</h3>
            </div>
            <div class="paquete__price">$600 <span class="paquete__currency">MXN</span></div>
            <ul class="paquete__features">
                <li class="paquete__feature">Todo lo del Premium</li>
                <li class="paquete__feature">Afeitado tradicional navaja</li>
                <li class="paquete__feature">Exfoliación facial</li>
                <li class="paquete__feature">Mascarilla revitalizante</li>
                <li class="paquete__feature">Cera para barba premium</li>
                <li class="paquete__feature">Bebida de cortesía</li>
                <li class="paquete__feature">Estacionamiento gratis</li>
            </ul>
            <button class="paquete__btn" onclick="reservar('Deluxe')">Reservar Ahora</button>
        </div>
    </div>
</section>