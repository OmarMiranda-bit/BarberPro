<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/admin/dashboard/servicios/crear">
        <i class="fa-duotone fa-solid fa-user-plus"></i>
        Añadir Servicio
    </a>
</div>


<div class="dashboard__contenedor">
        <?php if(!empty($servicios)){ ?>
        <table class="table">
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th">Servicio</th>
                    <th scope="col" class="table__th">Precio</th>
                    <th scope="col" class="table__th">Duración</th>
                    <!-- <th scope="col" class="table__th">Estado</th>  -->
                    <th scope="col" class="table__th">Acciones</th>
                </tr>
            </thead>
            <tbody class="table__tbody">
                <?php foreach($servicios as $servicio){ ?>
                <tr class="table__tr">
                    <td class="table__td">
                        <?php echo $servicio->nombre; ?>
                    </td>
                    <td class="table__td">
                        $<?php echo number_format($servicio->precio, 2); ?>
                    </td> 
                    <td class="table__td">
                        <?php echo $servicio->duracion ?? 0; ?> min
                    </td>
                    <!-- <td class="table__td">
                        <span class="estado estado--completada"><?php //echo $servicio->obtenerEstado(); ?></span>
                    </td> -->
                    <td class="table__td--acciones">
                        <a class="table__accion table__accion--editar" href="/admin/dashboard/servicios/editar?id=<?php echo $servicio->id ?? 'aun no hay datos'; ?>">
                            <i class="fa-solid fa-pencil"></i>
                            Editar
                        </a>
                        <form action="/admin/dashboard/servicios/eliminar" class="table__formulario" method="POST">
                            <input type="hidden" name="id" value="<?php echo $servicio->id; ?>">
                            <button class="table__accion table__accion--eliminar" type="submit">
                                <i class="fa-solid fa-circle-xmark"></i>
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                <?php }?>
            </tbody>
    
        </table>
    
        <?php } else{ ?>
    
        <p class="text-center">No hay servicios registrados Aún.</p>
    
        <?php } ?>
</div>