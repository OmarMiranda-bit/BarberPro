// Espera a que todo el DOM esté cargado antes de ejecutar el código
document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

// Función principal que inicializa la aplicación
function iniciarApp() {
    buscarPorFecha();
    inicializarAccionesBarbero();
    inicializarCronometros();
}

// Función que permite buscar citas por fecha
function buscarPorFecha(){
    // Selecciona el input de fecha del HTML
    const fechaInput = document.querySelector('#fecha');
    // Verificar si el elemento existe antes de añadir el evento
    // Esto previene errores cuando estamos en páginas que no tienen este input
    if (!fechaInput) {
        return; // Sale de la función si no existe el elemento
    }
    // Verificar si estamos en la página de admin (/admin)
    // Solo hacer la redirección si estamos en admin, no en /cita
    const esAdminPage = window.location.pathname === '/admin/employees';
    if (!esAdminPage) {
        return; // Salir si no estamos en admin
    }
    
    // Agrega un evento que escucha cuando el usuario cambia la fecha
    fechaInput.addEventListener('input', function(e){
        // Obtiene el valor de la fecha seleccionada
        const fechaSeleccionada = e.target.value;
        // Redirige a la misma página pero con un parámetro de fecha en la URL
        // Esto permite filtrar las citas por la fecha en el servidor
        window.location = `?fecha=${fechaSeleccionada}`;
    });
}

// Maneja los clics de los botones Iniciar y Finalizar Servicio
function inicializarAccionesBarbero() {
    const contenedorCitas = document.querySelector('#citas-admin');
    if (!contenedorCitas) return;

    contenedorCitas.addEventListener('click', async function(e) {
        // BOTÓN INICIAR SERVICIO
        if (e.target.classList.contains('btn-iniciar')) {
            const id = e.target.parentElement.dataset.id;
            await cambiarEstadoCita(id, 'EN_PROCESO', '/api/citas/actualizar-estado');
        }

        // BOTÓN FINALIZAR SERVICIO (Manual con SweetAlert2)
        if (e.target.classList.contains('btn-finalizar')) {
            const id = e.target.parentElement.dataset.id;

            Swal.fire({
                title: "¿Estás seguro de finalizar el servicio?",
                text: "Esta acción marcará la cita como completada exitosamente.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#10b981", // Verde éxito
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Sí, finalizar",
                cancelButtonText: "Cancelar",
                allowOutsideClick: false,
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await cambiarEstadoCita(id, 'COMPLETADA', '/api/citas/actualizar-estado');
                }
            });
        }
    });
}

// Función auxiliar para enviar las peticiones Fetch al servidor
async function cambiarEstadoCita(id, nuevoEstado, url) {
    const datos = new FormData();
    datos.append('id', id);
    datos.append('estado', nuevoEstado);

    try {
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();
        
        if (resultado.resultado) {
            window.location.reload(); // Recarga para mover la tarjeta a su sección correspondiente
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al actualizar el estado del servicio.'
        });
    }
}

// Inicializa y gestiona las cuentas regresivas
function inicializarCronometros() {
    const bloquesAcciones = document.querySelectorAll('.acciones-barbero');
    const fechaInput = document.querySelector('#fecha');
    if (!bloquesAcciones.length || !fechaInput) return;

    bloquesAcciones.forEach(bloque => {
        const timerSpan = bloque.querySelector('.tiempo-cuenta');
        if (!timerSpan) return; // Si no tiene el span del tiempo, no está EN_PROCESO

        const id = bloque.dataset.id;
        const horaCita = bloque.dataset.hora; // Ej: "12:00:00"
        const duracionMinutos = parseInt(bloque.dataset.duracion); // Ej: 45
        const fechaActualStr = fechaInput.value; // Ej: "2026-07-27"

        // Calculamos la hora exacta de finalización combinando fecha y hora programada
        const tiempoInicio = new Date(`${fechaActualStr}T${horaCita}`);
        const tiempoFin = new Date(tiempoInicio.getTime() + duracionMinutos * 60 * 1000);

        const intervalo = setInterval(async () => {
            const ahora = new Date().getTime();
            const distancia = tiempoFin - ahora;

            // Si el tiempo llegó a cero se activa la finalización automatica
            if (distancia <= 0) {
                clearInterval(intervalo);
                timerSpan.innerHTML = "00:00";
                // Llama al backend de forma automática por si el barbero lo olvidó
                await cambiarEstadoCita(id, 'COMPLETADA', '/api/citas/actualizar-estado');
                return;
            }

            // Cálculo matemático para renderizar minutos y segundos en pantalla
            const minutos = Math.floor((distancia % (1000 * 60 * 60)) / (1000 * 60));
            const segundos = Math.floor((distancia % (1000 * 60)) / 1000);

            // Formateo con ceros a la izquierda (Ej: 05:09)
            const minFormateados = minutos < 10 ? `0${minutos}` : minutos;
            const segFormateados = segundos < 10 ? `0${segundos}` : segundos;

            timerSpan.innerHTML = `${minFormateados}:${segFormateados}`;
        }, 1000);
    });
}