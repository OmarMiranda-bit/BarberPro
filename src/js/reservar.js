/* Módulo para manejar los modales de Login y Verificar Cita */

document.addEventListener('DOMContentLoaded', () => {
    inicializarModales();
});

/* Inicializa ambos modales y sus eventos*/
function inicializarModales() {
    // Configuración del Modal Login
    configurarModal({
        modalId: 'modalLogin',
        btnAbrirId: 'btnLogin',
        btnCerrarId: 'closeLogin',
        formId: 'formLogin',
        handlerSubmit: manejarLogin
    });

    // Configuración del Modal Verificar
    configurarModal({
        modalId: 'modalVerificar',
        btnAbrirId: 'btnVerificar',
        btnCerrarId: 'closeVerificar',
        formId: 'formVerificar',
        handlerSubmit: manejarVerificarCita
    });

    // Cerrar modales con tecla ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            cerrarTodosLosModales();
        }
    });
}

/* Configura un modal con sus eventos*/
function configurarModal(config) {
    const { modalId, btnAbrirId, btnCerrarId, formId, handlerSubmit } = config;
    
    const modal = document.getElementById(modalId);
    const btnAbrir = document.getElementById(btnAbrirId);
    const btnCerrar = document.getElementById(btnCerrarId);
    const form = document.getElementById(formId);

    // Abrir modal
    if (btnAbrir && modal) {
        btnAbrir.addEventListener('click', (e) => {
            e.preventDefault();
            abrirModal(modal);
        });
    }

    // Cerrar modal con botón X
    if (btnCerrar && modal) {
        btnCerrar.addEventListener('click', (e) => {
            e.preventDefault();
            cerrarModal(modal);
        });
    }

    // Cerrar modal al hacer click en el fondo
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                cerrarModal(modal);
            }
        });
    }

    // Manejar submit del formulario
    if (form && handlerSubmit) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            await handlerSubmit(form);
        });
    }
}

/* Abre un modal específico*/
function abrirModal(modal) {
    if (modal) {
        modal.classList.add('reservar__modal--active');
        limpiarAlertasModal(modal);
    }
}

/* Cierra un modal específico*/
function cerrarModal(modal) {
    if (modal) {
        modal.classList.remove('reservar__modal--active');
        limpiarAlertasModal(modal);
    }
}

/*Cierra todos los modales activos*/
function cerrarTodosLosModales() {
    const modalesActivos = document.querySelectorAll('.reservar__modal--active');
    modalesActivos.forEach(modal => cerrarModal(modal));
}

/* Muestra alertas en un modal específico*/
function mostrarAlertasModal(modal, alertas) {
    if (!modal) return;

    // Limpiar alertas previas
    limpiarAlertasModal(modal);

    const modalContent = modal.querySelector('.reservar__modal-content');
    if (!modalContent) return;

    // Encontrar o crear el contenedor de alertas
    let alertasContainer = modalContent.querySelector('.alertas');
    if (!alertasContainer) {
        alertasContainer = document.createElement('div');
        alertasContainer.className = 'alertas';
        
        // Insertar después del header del modal
        const header = modalContent.querySelector('.reservar__modal-header');
        if (header) {
            header.insertAdjacentElement('afterend', alertasContainer);
        } else {
            modalContent.insertAdjacentElement('afterbegin', alertasContainer);
        }
    }

    // Iterar sobre los tipos de alertas (error, exito, etc.)
    Object.entries(alertas).forEach(([tipo, mensajes]) => {
        mensajes.forEach((mensaje) => {
            const alerta = document.createElement('div');
            alerta.className = `alerta ${tipo}`;
            alerta.textContent = mensaje;
            alertasContainer.appendChild(alerta);
        });
    });

    // Limpiar alertas automáticamente después de 3 segundos
    setTimeout(() => {
        limpiarAlertasModal(modal);
    }, 3000);
}

/* Muestra una alerta de error simple*/
function mostrarAlertaErrorModal(modal, mensaje) {
    mostrarAlertasModal(modal, {
        error: [mensaje]
    });
}

/* Muestra una alerta de éxito simple*/
function mostrarAlertaExitoModal(modal, mensaje) {
    mostrarAlertasModal(modal, {
        exito: [mensaje]
    });
}

/*Limpia todas las alertas de un modal*/
function limpiarAlertasModal(modal) {
    if (!modal) return;
    
    const alertasContainer = modal.querySelector('.alertas');
    if (alertasContainer) {
        alertasContainer.innerHTML = '';
    }
}

/*Maneja el login mediante fetch */
async function manejarLogin(formulario) {
    const modal = document.getElementById('modalLogin');
    const formData = new FormData(formulario);

    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            // Mostrar mensaje de éxito
            mostrarAlertaExitoModal(modal, data.mensaje || 'Sesión iniciada correctamente');
            
            // Redirigir después de 1 segundo
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            // Mostrar alertas de error
            mostrarAlertasModal(modal, data.alertas);

            if (data.redirect) {
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 3000);
            }
        }
    } catch (error) {
        console.error('Error en login:', error);
        mostrarAlertaErrorModal(modal, 'Error de conexión. Por favor, inténtalo de nuevo.');
    }
}

/* Maneja la verificación de cita mediante fetch*/
async function manejarVerificarCita(formulario) {
    const modal = document.getElementById('modalVerificar');
    const formData = new FormData(formulario);

    try {
        const response = await fetch('/api/verificar-cita', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            // Mostrar mensaje de éxito
            mostrarAlertaExitoModal(modal, data.mensaje || 'Cita encontrada');
            
            // Redirigir a la página de detalles después de 1 segundo
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            // Mostrar alertas de error
            if (data.alertas) {
                mostrarAlertasModal(modal, data.alertas);
            } else {
                mostrarAlertaErrorModal(modal, 'Error al verificar la cita');
            }
        }
    } catch (error) {
        console.error('Error al verificar cita:', error);
        mostrarAlertaErrorModal(modal, 'Error de conexión. Por favor, inténtalo de nuevo.');
    }
}