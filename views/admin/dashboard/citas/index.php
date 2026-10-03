<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<!-- Filtros de búsqueda enviados mediante GET -->
<form method="GET" action="/admin/dashboard/citas" class="dashboard__filtros">
    <div class="dashboard__filtro-campo">
        <label for="filtro-estado" class="dashboard__filtro-label">Estado</label>
        <select id="filtro-estado" name="estado" class="dashboard__filtro-select" onchange="this.form.submit()">
            <option value="" <?php echo empty($estadoSeleccionado) ? 'selected' : ''; ?>>Todas</option>
            <option value="COMPLETADA" <?php echo $estadoSeleccionado === 'COMPLETADA' ? 'selected' : ''; ?>>Completada</option>
            <option value="PENDIENTE" <?php echo $estadoSeleccionado === 'PENDIENTE' ? 'selected' : ''; ?>>Pendiente</option>
            <option value="EN_PROCESO" <?php echo $estadoSeleccionado === 'EN_PROCESO' ? 'selected' : ''; ?>>En Proceso</option>
            <option value="CANCELADA" <?php echo $estadoSeleccionado === 'CANCELADA' ? 'selected' : ''; ?>>Cancelada</option>
            <option value="NO_ASISTIO" <?php echo $estadoSeleccionado === 'NO_ASISTIO' ? 'selected' : ''; ?>>No Asistió</option>
        </select>
    </div>

    <div class="dashboard__filtro-campo">
        <label for="filtro-barbero" class="dashboard__filtro-label">Barbero</label>
        <select id="filtro-barbero" name="barbero" class="dashboard__filtro-select" onchange="this.form.submit()">
            <option value="" <?php echo empty($barberoSeleccionado) ? 'selected' : ''; ?>>Todos</option>
            <?php foreach ($barberos as $barbero): ?>
                <option value="<?php echo $barbero->id; ?>" <?php echo $barberoSeleccionado == $barbero->id ? 'selected' : ''; ?>>
                    <?php echo $barbero->nombre; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</form>

<div class="dashboard__contenedor">
    <?php if (!empty($citas)): ?>
        <table class="table">
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th">Cliente</th>
                    <th scope="col" class="table__th">Servicio</th>
                    <th scope="col" class="table__th">Fecha y Hora</th>
                    <th scope="col" class="table__th">Precio</th>
                    <th scope="col" class="table__th">Barbero</th>
                    <th scope="col" class="table__th">Estado</th>
                </tr>
            </thead>
            <tbody class="table__tbody">
                <?php foreach ($citas as $cita): ?>
                    <tr class="table__tr">
                        <td class="table__td"><?php echo $cita->cliente; ?></td>
                        <td class="table__td"><?php echo $cita->servicio ?? 'Sin servicio'; ?></td>
                        <td class="table__td"><?php echo date('Y-m-d H:i', strtotime($cita->fecha . ' ' . $cita->hora)); ?></td>
                        <td class="table__td">$<?php echo number_format($cita->precio, 2); ?></td>
                        <td class="table__td"><?php echo $cita->barbero; ?></td>
                        <td class="table__td">
                            <?php
                            $claseEstado = 'estado--pendiente';
                            if ($cita->estado === 'COMPLETADA') $claseEstado = 'estado--completada';
                            if ($cita->estado === 'EN_PROCESO') $claseEstado = 'estado--proceso';
                            if (in_array($cita->estado, ['CANCELADA', 'CANCELADA_POR_BARBERO', 'NO_ASISTIO'])) $claseEstado = 'estado--cancelada';
                            ?>
                            <span class="estado <?php echo $claseEstado; ?>">
                                <?php echo ucfirst(strtolower(str_replace('_', ' ', $cita->estado))); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Enlaces de Paginación -->
        <?php echo $paginacion; ?>

    <?php else: ?>
        <p class="text-center">No se encontraron citas con los filtros seleccionados.</p>
    <?php endif; ?>
</div>