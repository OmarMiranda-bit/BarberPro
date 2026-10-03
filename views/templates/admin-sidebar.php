<aside class="dashboard__sidebar">
    <nav class="dashboard__menu">
        <a href="/admin/dashboard" class="dashboard__enlace <?php echo pagina_actual('/dashboard') ? 'dashboard__enlace--actual' : ''; ?> ">
            <i class="fa-duotone fa-solid fa-house dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Inicio
            </span>
        </a>
        <a href="/admin/dashboard/citas" class="dashboard__enlace <?php echo pagina_actual('/citas') ? 'dashboard__enlace--actual' : ''; ?>">
            <i class="fa-solid fa-calendar dashboard__icono dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Citas
            </span>
        </a>
        <a href="/admin/dashboard/barberos" class="dashboard__enlace <?php echo pagina_actual('/barberos') ? 'dashboard__enlace--actual' : ''; ?>">
            <i class="fa-solid fa-address-book dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Barberos
            </span>
        </a>
        <a href="/admin/dashboard/servicios" class="dashboard__enlace <?php echo pagina_actual('/servicios') ? 'dashboard__enlace--actual' : ''; ?>">
            <i class="fa-solid fa-scissors dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Servicios
            </span>
        </a>
        <a href="/admin/dashboard/registrados" class="dashboard__enlace <?php echo pagina_actual('/registrados') ? 'dashboard__enlace--actual' : ''; ?>">
            <i class="fa-solid fa-users dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Registrados
            </span>
        </a>
    </nav>
</aside>