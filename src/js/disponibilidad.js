document.addEventListener('DOMContentLoaded', function () {
    iniciarApp();
});

function iniciarApp() {
    toggleHorarios();
    guardarHorariosSemanal();
    bloquearDiaImprevisto();
}

// Activar o Desactivar inputs de hora dinámicamente según el checkbox del día
function toggleHorarios() {
    const checkboxes = document.querySelectorAll('.checkbox-dia');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function (e) {
            const diaNum = e.target.id.replace('dia-', '');
            const horasContainer = document.querySelector(`#horas-container-${diaNum}`);
            const inputsTime = horasContainer.querySelectorAll('input[type="time"]');

            if (e.target.checked) {
                horasContainer.classList.remove('deshabilitado');
                inputsTime.forEach(input => input.disabled = false);
            } else {
                horasContainer.classList.add('deshabilitado');
                inputsTime.forEach(input => input.disabled = true);
            }
        });
    });
}

// Guardar Horario Semanal Habitual mediante Fetch API
function guardarHorariosSemanal() {
    const formHorarios = document.querySelector('#form-horarios');
    if (!formHorarios) return;

    formHorarios.addEventListener('submit', async function (e) {
        e.preventDefault();

        const datos = new FormData(formHorarios);

        try {
            const respuesta = await fetch('/api/disponibilidad/guardar', {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();

            if (resultado.tipo === 'exito') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Guardado!',
                    text: resultado.mensaje,
                    confirmButtonColor: '#3085d6'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resultado.mensaje
                });
            }
        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al guardar los horarios.'
            });
        }
    });
}

// Bloquear Día con Intercepción de Conflictos y Confirmación de SweetAlert2
function bloquearDiaImprevisto() {
    const formBloqueo = document.querySelector('#form-bloqueo');
    if (!formBloqueo) return;

    formBloqueo.addEventListener('submit', async function (e) {
        e.preventDefault();

        const fechaInput = document.querySelector('#fecha_bloqueo').value;
        if (!fechaInput) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Por favor, selecciona una fecha válida.'
            });
            return;
        }

        const datos = new FormData(formBloqueo);
        await enviarPeticionBloqueo(datos);
    });
}

async function enviarPeticionBloqueo(datos) {
    try {
        const respuesta = await fetch('/api/disponibilidad/bloquear', {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();

        // Manejo del conflicto: Existen citas programadas en esa fecha
        if (resultado.tipo === 'conflicto') {
            const confirmacion = await Swal.fire({
                title: 'Citas Agendadas Detectadas',
                text: `Tienes ${resultado.total_citas} cita(s) programada(s) para este día. Al continuar, las citas se cancelarán y se notificará por correo a los clientes. ¿Deseas proceder?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, cancelar citas y bloquear',
                cancelButtonText: 'Volver'
            });

            // Si el barbero confirma, reenviamos agregando la bandera 'confirmado'
            if (confirmacion.isConfirmed) {
                datos.append('confirmado', 'true');
                await enviarPeticionBloqueo(datos);
            }
        } else if (resultado.tipo === 'exito') {
            Swal.fire({
                icon: 'success',
                title: 'Día Bloqueado',
                text: resultado.mensaje
            });
            document.querySelector('#form-bloqueo').reset();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: resultado.mensaje
            });
        }
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al procesar la solicitud.'
        });
    }
}