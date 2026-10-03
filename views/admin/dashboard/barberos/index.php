<?php
  /** @var string $titulo */
  /** @var string $paginacion */
?>
<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/admin/dashboard/barberos/crear">
        <i class="fa-duotone fa-solid fa-user-plus"></i>
        Añadir Barbero
    </a>
</div>


<div class="dashboard__contenedor">
     <?php if(!empty($barberos)){ ?>
    <table class="table">
        <thead class="table__thead">
            <tr>
                <th scope="col" class="table__th">Barbero</th>
                <th scope="col" class="table__th">Antiguedad</th>
                <th scope="col" class="table__th">Citas Total</th>
                <th scope="col" class="table__th">Ingresos Totales</th>
                <th scope="col" class="table__th">Estado</th> 
                <th scope="col" class="table__th">Acciones</th>
            </tr>
        </thead>
        <tbody class="table__tbody">
            <?php foreach($barberos as $barbero){ ?>
            <tr class="table__tr">
                <td class="table__td">
                    <?php echo $barbero->usuario->nombre . " " . $barbero->usuario->apellido ; ?>
                </td>
                <td class="table__td">
                    <?php echo $barbero->calcularAntiguedad(); ?> 
                </td> <!--obtener la antiguedad del barbero a partir de su fecha de ingreso-->
                <td class="table__td"> 15
                    <?php //echo $barbero->citas_total; ?>
                </td>
                <td class="table__td">$150.00
                    <?php //echo $barbero->ingreso_total; ?>
                </td>
                <td class="table__td">
                    <span class="estado estado--completada"><?php echo $barbero->obtenerEstado(); ?></span>
                </td>
                <td class="table__td--acciones">
                    <a class="table__accion table__accion--editar" href="/admin/dashboard/barberos/editar?id=<?php echo $barbero->id ?? 'aun no hay datos'; ?>">
                        <i class="fa-solid fa-pencil"></i>
                        Editar
                    </a>
                    <form action="/admin/dashboard/barberos/eliminar" class="table__formulario" method="POST">
                        <input type="hidden" name="id" value="<?php echo $barbero->id; ?>">
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

    <?php }else{ ?>
        <p class="text-center">No Hay Barberos Aún.</p>
        <?php } ?>
</div>

<?php
    // echo $paginacion;
?>
