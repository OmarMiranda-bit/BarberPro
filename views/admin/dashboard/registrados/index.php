<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contenedor">
    <?php if(!empty($registrados)): ?>
        <table class="table">
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th">Cliente</th>
                    <th scope="col" class="table__th">Email</th>
                    <th scope="col" class="table__th">Teléfono</th>
                    <th scope="col" class="table__th">Citas Históricas</th>
                    <th scope="col" class="table__th">Cuenta</th>
                </tr>
            </thead>
            <tbody class="table__tbody">
                <?php foreach($registrados as $usuario): ?>
                    <tr class="table__tr">
                        <td class="table__td"><?php echo $usuario->nombre . ' ' . $usuario->apellido; ?></td>
                        <td class="table__td"><?php echo $usuario->email; ?></td>
                        <td class="table__td"><?php echo !empty($usuario->telefono) ? $usuario->telefono : 'Sin teléfono'; ?></td>
                        <td class="table__td"><?php echo $usuario->total_citas; ?> cita(s)</td>
                        <td class="table__td">
                            <?php if($usuario->confirmado == 1): ?>
                                <span class="estado estado--completada">Confirmada</span>
                            <?php else: ?>
                                <span class="estado estado--cancelada">Sin Confirmar</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Renderizar la paginación de PHP -->
        <?php echo $paginacion; ?>

    <?php else: ?>
        <p class="text-center">No hay clientes registrados en la plataforma.</p>
    <?php endif; ?>
</div>