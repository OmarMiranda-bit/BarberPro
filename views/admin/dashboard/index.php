<?php
$titulo = $titulo ?? 'Dashboard Administrativo';
$ingresos = $ingresos ?? 0;
$citasMes = $citasMes ?? 0;
$completadas = $completadas ?? 0;
$canceladas = $canceladas ?? 0;
$citasRecientes = $citasRecientes ?? [];
$topBarberos = $topBarberos ?? [];
$estadosData = $estadosData ?? '[]';
$serviciosData = $serviciosData ?? '[]';
$tendenciaData = $tendenciaData ?? '[]';
$ingresosSemanaData = $ingresosSemanaData ?? '[]';
?>
<!-- Dashboard Administrativo -->
<section class="dashboard">
    <h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
    <div class="dashboard__container">

        <!-- KPIs Cards -->
        <div class="dashboard__kpis">
            <!-- KPI 1: Ingresos -->
            <div class="dashboard__kpi dashboard__kpi--purple">
                <div class="dashboard__kpi-icon">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
                <div class="dashboard__kpi-content">
                    <h3 class="dashboard__kpi-label">Ingresos</h3>
                    <p class="dashboard__kpi-value">$<?php echo number_format($ingresos, 2); ?></p>
                    <!-- aun falta agregar la comparativa con el mes anterior, pero por ahora se muestra el total de ingresos del mes actual. -->
                    <!-- <div class="dashboard__kpi-trend dashboard__kpi-trend--up">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                        <span>18% vs mes anterior</span>
                    </div> -->
                </div>
            </div>
            <!-- KPI 2: Citas del Mes -->
            <div class="dashboard__kpi dashboard__kpi--blue">
                <div class="dashboard__kpi-icon">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div class="dashboard__kpi-content">
                    <h3 class="dashboard__kpi-label">Citas del Mes</h3>
                    <p class="dashboard__kpi-value"><?php echo $citasMes; ?></p>
                    <!-- aun falta agregar la comparativa con el mes anterior, pero por ahora se muestra el total de citas del mes actual. -->
                    <!-- <div class="dashboard__kpi-trend dashboard__kpi-trend--up">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                        <span>12% vs mes anterior</span>
                    </div> -->
                </div>
            </div>

            <!-- KPI 3: Completadas -->
            <div class="dashboard__kpi dashboard__kpi--green">
                <div class="dashboard__kpi-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="dashboard__kpi-content">
                    <h3 class="dashboard__kpi-label">Completadas</h3>
                    <p class="dashboard__kpi-value"><?php echo $completadas; ?></p>
                    <!-- aun falta agregar la comparativa con el mes anterior, pero por ahora se muestra el total de citas completadas del mes actual. -->
                    <!-- <div class="dashboard__kpi-trend dashboard__kpi-trend--up">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                        <span>87% tasa de éxito</span>
                    </div> -->
                </div>
            </div>

            <!-- KPI 4: Cancelaciones -->
            <div class="dashboard__kpi dashboard__kpi--red">
                <div class="dashboard__kpi-icon">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
                <div class="dashboard__kpi-content">
                    <h3 class="dashboard__kpi-label">Cancelaciones</h3>
                    <p class="dashboard__kpi-value"><?php echo $canceladas; ?></p>
                    <!-- aun falta agregar la comparativa con el mes anterior, pero por ahora se muestra el total de citas canceladas del mes actual. -->
                    <!-- <div class="dashboard__kpi-trend dashboard__kpi-trend--down">
                        <i class="fa-solid fa-arrow-trend-down"></i>
                        <span>13% del total</span>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- Gráficas Principales -->
        <div class="dashboard__charts">
            <!-- Gráfica de Tendencias -->
            <div class="dashboard__chart-card">
                <div class="dashboard__chart-header">
                    <h3 class="dashboard__chart-title">
                        <i class="fa-solid fa-chart-line"></i>
                        Tendencia de Citas
                    </h3>
                    <p class="dashboard__chart-subtitle">Últimos 7 días</p>
                </div>
                <div class="dashboard__chart-body">
                    <canvas id="chartTendencias"></canvas>
                </div>
            </div>

            <!-- Gráfica de Servicios -->
            <div class="dashboard__chart-card">
                <div class="dashboard__chart-header">
                    <h3 class="dashboard__chart-title">
                        <i class="fa-solid fa-chart-bar"></i>
                        Servicios Más Demandados
                    </h3>
                    <p class="dashboard__chart-subtitle">Top 6 del mes</p>
                </div>
                <div class="dashboard__chart-body">
                    <canvas id="chartServicios"></canvas>
                </div>
            </div>

            <!-- Gráfica de Estados -->
            <div class="dashboard__chart-card">
                <div class="dashboard__chart-header">
                    <h3 class="dashboard__chart-title">
                        <i class="fa-solid fa-chart-pie"></i>
                        Estado de Citas
                    </h3>
                    <p class="dashboard__chart-subtitle">Distribución mensual</p>
                </div>
                <div class="dashboard__chart-body">
                    <canvas id="chartEstados"></canvas>
                </div>
            </div>

            <!-- Gráfica de Ingresos -->
            <div class="dashboard__chart-card">
                <div class="dashboard__chart-header">
                    <h3 class="dashboard__chart-title">
                        <i class="fa-solid fa-chart-area"></i>
                        Comparativa de Ingresos
                    </h3>
                    <p class="dashboard__chart-subtitle">Mes actual vs anterior</p>
                </div>
                <div class="dashboard__chart-body">
                    <canvas id="chartIngresos"></canvas>
                </div>
            </div>
        </div>

        <!-- Tablas y Rankings -->
        <div class="dashboard__tables">
            <!-- Citas Recientes -->
            <div class="dashboard__table-card dashboard__table-card--full">
                <div class="dashboard__table-header">
                    <h3 class="dashboard__table-title">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        Citas Recientes
                    </h3>
                    <a href="/admin/dashboard/citas" class="dashboard__table-link">Ver todas</a>
                </div>
                <div class="dashboard__table-body">
                    <table class="dashboard__table">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Barbero</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($citasRecientes as $cita): ?>
                                <tr>
                                    <td><?php echo $cita->cliente; ?></td>
                                    <td><?php echo $cita->servicio /*?? 'Sin servicio'*/; ?></td>
                                    <td><?php echo $cita->barbero; ?></td>
                                    <td><?php echo date('d M', strtotime($cita->fecha)) . ', ' . date('h:i A', strtotime($cita->hora)); ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = 'dashboard__badge--warning';
                                        if ($cita->estado === 'COMPLETADA') $badgeClass = 'dashboard__badge--success';
                                        if (in_array($cita->estado, ['CANCELADA', 'CANCELADA_POR_BARBERO', 'NO_ASISTIO'])) $badgeClass = 'dashboard__badge--danger';
                                        ?>
                                        <span class="dashboard__badge <?php echo $badgeClass; ?>"><?php echo str_replace('_', ' ', $cita->estado); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Barberos Top -->
            <div class="dashboard__table-card ">
                <div class="dashboard__table-header">
                    <h3 class="dashboard__table-title"><i class="fa-solid fa-trophy"></i> Top Barberos</h3>
                </div>
                <div class="dashboard__table-body">
                    <div class="dashboard__ranking">
                        <?php
                        $posiciones = ['gold', 'silver', 'bronze'];
                        $iconos = ['fa-crown', 'fa-medal', 'fa-award'];
                        foreach ($topBarberos as $i => $barbero):
                        ?>
                            <div class="dashboard__ranking-item">
                                <div class="dashboard__ranking-position dashboard__ranking-position--<?php echo $posiciones[$i] ?? 'bronze'; ?>">
                                    <i class="fa-solid <?php echo $iconos[$i] ?? 'fa-award'; ?>"></i> <?php echo $i + 1; ?>
                                </div>
                                <div class="dashboard__ranking-info">
                                    <h4><?php echo $barbero->nombre; ?></h4>
                                    <p><?php echo $barbero->total_citas; ?> citas completadas</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Inyección de datos para JavaScript -->
<script>
    window.dashboardData = {
        estados: <?php echo $estadosData; ?>,
        servicios: <?php echo $serviciosData; ?>,
        tendencia: <?php echo $tendenciaData; ?>,
        ingresosSemana: <?php echo $ingresosSemanaData; ?>
    };
</script>