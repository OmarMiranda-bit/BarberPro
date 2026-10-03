// Variable que controla en qué paso del formulario estamos (1, 2 o 3)
let paso = 1;
const pasoInicial = 1; // Primer paso del formulario
const pasoFinal = 3; // Último paso del formulario

// Variables globales para Flatpickr
let instanciaFecha = null;
let instanciaHora = null;

// Objeto que almacena toda la información de la cita
const cita = {
  id: "", // ID del usuario/cliente
  nombre: "", // Nombre del cliente
  fecha: "", // Fecha de la cita
  hora: "", // Hora de la cita
  servicios: [], // Array que guardará los servicios seleccionados
  barberoId: "", // ID del barbero seleccionado
  barberoNombre: "", // Nombre del barbero seleccionado
};

// Espera a que todo el DOM esté cargado antes de ejecutar el código
document.addEventListener("DOMContentLoaded", function () {
  iniciarApp();
  iniciarEdicion();
  iniciarCancelarCita();
});

// Función principal que inicializa toda la aplicación
function iniciarApp() {
  // Verificar si estamos en la página de citas buscando un elemento único de esa página
  const contenedorCitas = document.querySelector("#servicios");
  if (!contenedorCitas) {
    // Si no existe el contenedor de servicios, no estamos en la página de citas
    // Salimos de la función para evitar errores
    return;
  }
  //si estamos en edición, esta función no debe correr
  if (document.querySelector("#barberoActual")) return;

  mostrarSeccion(); // Muestra y oculta las secciones según el paso actual
  tabs(); // Cambia la sección cuando se presionan los tabs
  botonesPaginador(); // Agrega o quita los botones del paginador
  paginaSiguiente(); // Configura el botón de página siguiente
  paginaAnterior(); // Configura el botón de página anterior

  consultarAPI(); // Consulta la API en el backend de PHP
  // Una API (Application Programming Interface) es una interfaz que permite que dos
  // aplicaciones se comuniquen entre sí. Básicamente, es un conjunto de reglas y
  // protocolos que especifican cómo interactuar con un software o servicio para
  // pedir datos o enviar comandos sin necesidad de interactuar directamente con el sistema.

  // En nuestro caso, la API que estamos creando permite comunicar el código Javascript
  // con la base de datos a través del servidor PHP ya que JS no puede comunicarse
  // de forma nativa con una base de datos.
  idCliente(); // Obtiene el ID del cliente
  nombreCliente(); // Añade el nombre del cliente al objeto de cita
  inicializarFlatpickr(); // Inicializa los calendarios de Flatpickr
  mostrarResumen(); // Muestra el resumen final de la cita
}

// Función que controla qué sección se muestra según el paso actual
function mostrarSeccion() {
  // Busca si hay una sección que ya tenga la clase "mostrar"
  const seccionAnterior = document.querySelector(".mostrar");
  if (seccionAnterior) {
    // Si existe, le quita la clase para ocultarla
    seccionAnterior.classList.remove("mostrar");
  }

  // Construye el selector usando template literals para encontrar la sección actual
  // Ejemplo: si paso = 1, el selector será "#paso-1"
  const pasoSelector = `#paso-${paso}`;
  const seccion = document.querySelector(pasoSelector);

  // Verificar si existe la sección antes de intentar manipularla
  if (!seccion) {
    return; // Sale si no existe
  }

  // Añade la clase "mostrar" a la sección actual para hacerla visible
  seccion.classList.add("mostrar");

  // Busca el tab que tenía la clase "actual" anteriormente
  const tabAnterior = document.querySelector(".actual");
  if (tabAnterior) {
    // Le quita la clase "actual" al tab anterior
    tabAnterior.classList.remove("actual");
  }

  // Busca el tab correspondiente al paso actual usando data attributes
  // Ejemplo: [data-paso="1"] busca el elemento con ese atributo
  const tab = document.querySelector(`[data-paso="${paso}"]`);
  if (tab) {
    // Resalta el tab actual añadiendo la clase "actual"
    tab.classList.add("actual");
  }
}

// Función que maneja el comportamiento de los tabs (pestañas)
function tabs() {
  // Selecciona todos los botones dentro del contenedor de tabs
  const botones = document.querySelectorAll(".tabs button");

  // *** CAMBIO AGREGADO ***
  // Verificar si existen botones antes de intentar añadirles eventos
  if (botones.length === 0) {
    return; // Sale si no hay botones
  }
  // *** FIN DEL CAMBIO ***

  // Un "addEventListener" no se puede usar directamente cuando se usa "querySelectorAll"
  // Por eso usamos forEach para iterar sobre cada botón
  botones.forEach((boton) => {
    // Añade un evento click a cada botón
    boton.addEventListener("click", function (e) {
      // Obtiene el número de paso desde el atributo data-paso del botón clickeado
      // parseInt convierte el string a número
      paso = parseInt(e.target.dataset.paso);

      // Actualiza la vista para mostrar la sección correspondiente
      mostrarSeccion();
      botonesPaginador();
    });
  });
}

// Función que controla la visibilidad de los botones Anterior y Siguiente
function botonesPaginador() {
  // Selecciona ambos botones del paginador
  const paginaAnterior = document.querySelector("#anterior");
  const paginaSiguiente = document.querySelector("#siguiente");

  // *** CAMBIO AGREGADO ***
  // Verificar si existen los botones antes de manipularlos
  if (!paginaAnterior || !paginaSiguiente) {
    return; // Sale si alguno no existe
  }
  // *** FIN DEL CAMBIO ***

  // Si estamos en el paso 1 (inicio)
  if (paso === 1) {
    // Oculta el botón "Anterior" porque no hay paso previo
    paginaAnterior.classList.add("ocultar");
    // Muestra el botón "Siguiente"
    paginaSiguiente.classList.remove("ocultar");
  }
  // Si estamos en el paso 3 (final)
  else if (paso === 3) {
    // Muestra el botón "Anterior"
    paginaAnterior.classList.remove("ocultar");
    // Oculta el botón "Siguiente" porque no hay más pasos
    paginaSiguiente.classList.add("ocultar");

    // Muestra el resumen de la cita en el paso final
    mostrarResumen();
  }
  // Si estamos en el paso 2 (medio)
  else {
    // Muestra ambos botones
    paginaAnterior.classList.remove("ocultar");
    paginaSiguiente.classList.remove("ocultar");
  }
  // Actualiza la sección visible
  mostrarSeccion();
}

// Función que configura el botón "Anterior"
function paginaAnterior() {
  const paginaAnterior = document.querySelector("#anterior");

  // Verificar si existe el botón antes de añadir el evento
  if (!paginaAnterior) {
    return;
  }

  // Añade evento click al botón
  paginaAnterior.addEventListener("click", function () {
    // Si ya estamos en el primer paso, no hace nada
    if (paso <= pasoInicial) return;
    // Decrementa el paso (retrocede)
    paso--;
    // Actualiza los botones según el nuevo paso
    botonesPaginador();
  });
}

// Función que configura el botón "Siguiente"
function paginaSiguiente() {
  const paginaSiguiente = document.querySelector("#siguiente");

  // Verificar si existe el botón antes de añadir el evento
  if (!paginaSiguiente) {
    return;
  }

  // Añade evento click al botón
  paginaSiguiente.addEventListener("click", function () {
    // Si ya estamos en el último paso, no hace nada
    if (paso >= pasoFinal) return;
    // Incrementa el paso (avanza)
    paso++;
    // Actualiza los botones según el nuevo paso
    botonesPaginador();
  });
}

// Función asíncrona que consulta los servicios desde la API
async function consultarAPI() {
  try {
    //Consulta de los servicios
    // Define la URL del endpoint de la API
    const urlServicios = "/api/servicios";
    // fetch hace una petición HTTP y await espera a que se complete
    const resultadoServicios = await fetch(urlServicios);
    // Convierte la respuesta a formato JSON
    const servicios = await resultadoServicios.json();
    // Llama a la función para mostrar los servicios en el HTML
    mostrarServicios(servicios);

    // Consultar barberos
    const urlBarberos = "/api/barberos";
    const resultadoBarberos = await fetch(urlBarberos);
    const barberos = await resultadoBarberos.json();
    mostrarBarberos(barberos);
  } catch (error) {
    // Si hay un error en la petición, lo captura aquí
    // console.log(error);
  }
}

// Función que muestra todos los servicios obtenidos de la base de datos
// La API usa PHP para obtener los datos y los exporta a JSON para JavaScript
function mostrarServicios(servicios) {
  // Selecciona el contenedor donde se mostrarán los servicios
  const contenedorServicios = document.querySelector("#servicios");

  // Verificar si existe el contenedor antes de intentar añadir servicios
  if (!contenedorServicios) {
    return;
  }

  // Itera sobre cada servicio recibido de la API
  servicios.forEach((servicio) => {
    // Destructuring: extrae las propiedades del objeto servicio
    const { id, nombre, precio, imagen } = servicio;

    // Crea un DIV contenedor para cada servicio
    const servicioDiv = document.createElement("DIV");
    servicioDiv.classList.add("servicio");
    servicioDiv.dataset.idServicio = id;

    // Define qué hacer cuando se hace click en el servicio
    servicioDiv.onclick = function () {
      seleccionarServicio(servicio);
    };

    // Muestra la imagen del servicio (si existe)
    if (imagen) {
      const imagenServicio = document.createElement("IMG");
      imagenServicio.src = `/img/servicios/${imagen}.webp`;
      imagenServicio.alt = nombre;
      imagenServicio.classList.add("servicio__imagen");
      imagenServicio.loading = "lazy";
      servicioDiv.appendChild(imagenServicio);
    }

    // Contenedor para el contenido (nombre y precio)
    const contenidoDiv = document.createElement("DIV");
    contenidoDiv.classList.add("servicio__contenido");

    // Crea un párrafo para mostrar el nombre del servicio
    const nombreServicio = document.createElement("P");
    nombreServicio.classList.add("servicio__nombre");
    nombreServicio.textContent = nombre;

    // Crea un párrafo para mostrar el precio del servicio
    const precioServicio = document.createElement("P");
    precioServicio.classList.add("servicio__precio");
    precioServicio.textContent = `$${precio}`;

    // Inserta el nombre y precio dentro del contenedor de contenido
    contenidoDiv.appendChild(nombreServicio);
    contenidoDiv.appendChild(precioServicio);

    // Añade el contenido al servicio
    servicioDiv.appendChild(contenidoDiv);

    // Añade el servicio completo al contenedor principal en el HTML
    contenedorServicios.appendChild(servicioDiv);
  });
}

// Función que maneja la selección/deselección de servicios
function seleccionarServicio(servicio) {
  // Extrae el ID del servicio clickeado
  const { id } = servicio;
  // Extrae el array de servicios del objeto cita
  const { servicios } = cita;

  // Busca el div del servicio usando su data-id-servicio
  const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

  // Comprueba si el servicio ya fue agregado anteriormente
  // some() retorna true si encuentra al menos un elemento que cumpla la condición
  if (servicios.some((agregado) => agregado.id === id)) {
    // Si ya está agregado, lo elimina del array
    // filter() crea un nuevo array excluyendo el servicio con ese ID
    cita.servicios = servicios.filter((agregado) => agregado.id !== id);
    // Quita la clase visual que indica que está seleccionado
    divServicio.classList.remove("seleccionado");
  } else {
    // Si no está agregado, lo añade al array
    // El spread operator (...) toma una copia de los servicios existentes
    // y le agrega el nuevo servicio
    cita.servicios = [...servicios, servicio];
    // Añade la clase visual que indica que está seleccionado
    divServicio.classList.add("seleccionado");
  }

  //Cargar horarios disponibles cuando hay servicios y barbero seleccionado
  if (cita.servicios.length > 0 && cita.barberoId) {
    cargarHorarios();
  }

  // Actualizar hidden input en página de edición
  const hiddenServicios = document.querySelector("#hiddenServicios");
  if (hiddenServicios) {
    hiddenServicios.value = cita.servicios.map((s) => s.id).join(",");
  }
}

// Función que obtiene el ID del cliente y lo guarda en el objeto cita
function idCliente() {
  const idElement = document.querySelector("#id");
  if (idElement) {
    cita.id = idElement.value;
  }
}

// Función que obtiene el nombre del cliente y lo guarda en el objeto cita
function nombreCliente() {
  const nombreElement = document.querySelector("#nombre");
  if (nombreElement) {
    cita.nombre = nombreElement.value;
  }
}

// Función que muestra los barberos en tarjetas
function mostrarBarberos(barberos) {
  const contenedorBarberos = document.querySelector("#barberos");

  // Verificar si existe el contenedor antes de intentar añadir barberos
  if (!contenedorBarberos) {
    return;
  }

  // Itera sobre cada barbero recibido de la API
  barberos.forEach((barbero) => {
    // Destructuring: extrae las propiedades del objeto barbero
    const { id, nombre, apellido, imagen } = barbero;
    const nombreCompleto = `${nombre} ${apellido}`;

    // Crea un DIV contenedor para cada barbero
    const barberoDiv = document.createElement("DIV");
    barberoDiv.classList.add("barbero");
    // Dataset permite asociar datos personalizados al elemento HTML
    // En este caso, asocia el ID del barbero al div
    barberoDiv.dataset.idBarbero = id;
    // Define qué hacer cuando se hace click en el barbero
    barberoDiv.onclick = function () {
      seleccionarBarbero(barbero);
    };

    // Muestra la imagen del barbero (si existe)
    if (imagen) {
      const imagenBarbero = document.createElement("IMG");
      imagenBarbero.src = `/img/barberos/${imagen}.webp`;
      imagenBarbero.alt = nombreCompleto;
      imagenBarbero.classList.add("barbero__imagen");
      imagenBarbero.loading = "lazy";

      // Si la imagen no carga, mostrar iniciales como fallback
      imagenBarbero.onerror = function () {
        this.style.display = "none";
        const iniciales = document.createElement("DIV");
        iniciales.classList.add("barbero__iniciales");
        iniciales.textContent = `${nombre.charAt(0)}${apellido.charAt(0)}`;
        barberoDiv.insertBefore(iniciales, barberoDiv.firstChild);
      };

      barberoDiv.appendChild(imagenBarbero);
    }

    // Crea un párrafo para mostrar el nombre del barbero
    const nombreBarbero = document.createElement("P");
    nombreBarbero.classList.add("barbero__nombre");
    nombreBarbero.textContent = nombreCompleto;

    // Inserta el nombre dentro del div del barbero
    barberoDiv.appendChild(nombreBarbero);

    // Añade el barbero completo al contenedor principal en el HTML
    contenedorBarberos.appendChild(barberoDiv);
  });
}

// Función que maneja la selección de barbero (solo uno a la vez)
function seleccionarBarbero(barbero) {
  // Extrae el ID del barbero clickeado
  const { id, nombre, apellido } = barbero;

  // Busca todos los barberos disponibles
  const todosLosBarberos = document.querySelectorAll(".barbero");

  // Remueve la clase 'seleccionado' de todos los barberos
  // para dejar solo uno seleccionado (comportamiento de radio button)
  todosLosBarberos.forEach((b) => b.classList.remove("seleccionado"));

  // Busca el div del barbero usando su data-id-barbero
  const divBarbero = document.querySelector(`[data-id-barbero="${id}"]`);

  // Añade la clase visual que indica que está seleccionado
  divBarbero.classList.add("seleccionado");

  // Guarda el ID del barbero en el objeto cita
  cita.barberoId = id;
  cita.barberoNombre = `${nombre} ${apellido}`;

  // También actualiza el input oculto para el formulario
  const inputBarbero = document.querySelector("#barberoId");
  if (inputBarbero) {
    inputBarbero.value = id;
  }

  // Cargar horarios cuando se selecciona barbero y hay servicios
  if (cita.servicios.length > 0) {
    cargarHorarios();
  }

  // Actualizar hidden input en página de edición
  const hiddenBarbero = document.querySelector("#hiddenBarbero");
  if (hiddenBarbero) {
    hiddenBarbero.value = id;
  }
}

// Función que inicializa Flatpickr para la fecha
function inicializarFlatpickr() {
  const inputFecha = document.querySelector("#fecha");

  // Verificar si existen los inputs
  if (!inputFecha) {
    return;
  }

  // Destruir instancias anteriores si existen
  if (instanciaFecha) instanciaFecha.destroy();

  // Configurar Flatpickr para la FECHA
  instanciaFecha = flatpickr(inputFecha, {
    minDate: "today", // No permite fechas pasadas
    // dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "F j, Y",
    dateFormat: "Y-m-d", // Formato:
    locale: "es", // Idioma español
    disable: [
      function (date) {
        // Deshabilitar sábados (6) y domingos (0)
        return date.getDay() === 0 || date.getDay() === 6;
      },
    ],

    onChange: function (selectedDates) {
      if (selectedDates.length > 0) {
        cita.fecha = inputFecha.value;
        // recargar horarios si ya hay barbero y servicios elegidos
        if (cita.barberoId && cita.servicios.length > 0) {
          cargarHorarios();
        }
      }
    },
  });

  // Prevenir envío de formulario
  const formulario = inputFecha.closest(".formulario");
  if (formulario) {
    formulario.addEventListener("submit", (e) => {
      e.preventDefault();
    });
  }
}

// Función que carga y muestra los horarios disponibles
// async function cargarHorarios() {
//   // Obtener el ID del servicio (el primero seleccionado por ahora)
//   const barberoId = cita.barberoId;
//   const fecha = cita.fecha;
//   const contenedor = document.querySelector("#horarios");

//   // Necesitamos los 3 para consultar
//   if (!barberoId || cita.servicios.length === 0 || !fecha) {
//     if (contenedor) {
//       contenedor.innerHTML =
//         "<p class='sin-horarios'>Selecciona barbero, servicio y fecha para ver horarios</p>";
//     }
//     return;
//   }

//   // Sumar duración de TODOS los servicios seleccionados
//   const duracionTotal = cita.servicios.reduce((total, servicio) => {
//     return total + (parseInt(servicio.duracion) || 0);
//   }, 0);

//   // Loading
//   if (contenedor) {
//     contenedor.innerHTML =
//       "<p class='sin-horarios'>Buscando horarios disponibles...</p>";
//   }

//   try {
//     // Llamar al API para obtener horarios
//     const url = `/api/horarios?barberoId=${barberoId}&duracion=${duracionTotal}&fecha=${fecha}`;
//     const respuesta = await fetch(url);
//     const horarios = await respuesta.json();

//     if (horarios.error) {
//       if (contenedor)
//         contenedor.innerHTML = `<p class='sin-horarios'>${horarios.error}</p>`;
//       return;
//     }

//     mostrarHorarios(horarios);
//   } catch (error) {
//     console.error("Error al cargar horarios:", error);
//   }
// }

// Función que carga y muestra los horarios disponibles
async function cargarHorarios() {
  const barberoId = cita.barberoId;
  const fecha = cita.fecha;
  const contenedor = document.querySelector("#horarios");

  if (!barberoId || cita.servicios.length === 0 || !fecha) {
    if (contenedor) {
      contenedor.innerHTML =
        "<p class='sin-horarios'>Selecciona barbero, servicio y fecha para ver horarios</p>";
    }
    return;
  }

  // Sumar duración de TODOS los servicios seleccionados
  const duracionTotal = cita.servicios.reduce((total, servicio) => {
    return total + (parseInt(servicio.duracion) || 0);
  }, 0);

  if (contenedor) {
    contenedor.innerHTML =
      "<p class='sin-horarios'>Buscando horarios disponibles...</p>";
  }

  try {
    // === 1. CAPTURAR EL FOLIO DE LA URL ===
    const urlParams = new URLSearchParams(window.location.search);
    const excluirFolio = urlParams.get("folio") || ""; // Si no hay, queda vacío (como al crear una cita nueva)

    // === 2. ENVIAR EL PARAMETRO "excluirFolio" A LA API ===
    const url = `/api/horarios?barberoId=${barberoId}&duracion=${duracionTotal}&fecha=${fecha}&excluirFolio=${excluirFolio}`;
    const respuesta = await fetch(url);
    const horarios = await respuesta.json();

    if (horarios.error) {
      if (contenedor)
        contenedor.innerHTML = `<p class='sin-horarios'>${horarios.error}</p>`;
      return;
    }

    mostrarHorarios(horarios);
  } catch (error) {
    console.error("Error al cargar horarios:", error);
  }
}

// Función que renderiza los horarios en un grid
// function mostrarHorarios(horarios) {
//   const contenedorHorarios = document.querySelector("#horarios");

//   if (!contenedorHorarios) {
//     return;
//   }

//   // Limpiar horarios anteriores
//   contenedorHorarios.innerHTML = "";

//   // Si no hay horarios, mostrar mensaje
//   if (horarios.length === 0) {
//     contenedorHorarios.innerHTML =
//       "<p class='sin-horarios'>No hay horarios disponibles</p>";
//     return;
//   }

//   // Crear un botón para cada horario
//   horarios.forEach((slot) => {
//     const botonHorario = document.createElement("BUTTON");
//     botonHorario.type = "button";
//     botonHorario.textContent = slot.hora;

//     if (slot.disponible) {
//       botonHorario.classList.add("horario-btn");
//       botonHorario.onclick = function () {
//         seleccionarHorario(slot.hora, botonHorario);
//       };
//     } else {
//       botonHorario.classList.add("horario-btn", "horario-btn--ocupado");
//       botonHorario.disabled = true;
//     }

//     contenedorHorarios.appendChild(botonHorario);
//   });
// }
function mostrarHorarios(horarios) {
  const contenedorHorarios = document.querySelector("#horarios");
  if (!contenedorHorarios) return;

  // Limpiar el grid antes de mostrar los nuevos horarios
  contenedorHorarios.innerHTML = "";

  if (horarios.length === 0) {
    contenedorHorarios.innerHTML =
      "<p class='sin-horarios'>No hay horarios disponibles</p>";
    return;
  }

  // Calcular duración total para mostrar hora de fin
  const duracionTotal = cita.servicios.reduce((total, servicio) => {
    return total + (parseInt(servicio.duracion) || 0);
  }, 0);

  horarios.forEach((slot) => {
    // slot = {hora: "10:00", disponible: true}

    const botonHorario = document.createElement("LI");

    // Mostrar "10:00 - 11:30" (inicio - fin según duración)
    const horaFin = calcularHoraFin(slot.hora, duracionTotal);
    botonHorario.textContent = `${slot.hora} - ${horaFin}`;

    if (slot.disponible) {
      // Slot libre -> azul, clickeable
      botonHorario.classList.add("horario-btn");

      //pre-seleccionar hora actual en la pagina de edicion de cita
      if (cita.hora && slot.hora === cita.hora) {
        botonHorario.classList.add("horario-btn--seleccionado");
      }

      botonHorario.addEventListener("click", function () {
        seleccionarHorario(slot.hora, botonHorario);
      });
    } else {
      // Slot ocupado -> gris, deshabilitado
      botonHorario.classList.add("horario-btn", "horario-btn--ocupado");
      botonHorario.disabled = true;
    }

    contenedorHorarios.appendChild(botonHorario);
  });
}

// Suma minutos a "HH:MM" y devuelve "HH:MM" calcula el timpo total sumando la hora de inicio más la duración total de los servicios para mostrar el rango completo del horario
function calcularHoraFin(horaInicio, duracionMinutos) {
  const [horas, minutos] = horaInicio.split(":").map(Number);
  const totalMin = horas * 60 + minutos + duracionMinutos;
  const horaFinal = Math.floor(totalMin / 60);
  const minFinal = totalMin % 60;
  return `${String(horaFinal).padStart(2, "0")}:${String(minFinal).padStart(2, "0")}`;
}

// Función que maneja la selección de un horario
function seleccionarHorario(horario, elemento) {
  // Quitar seleccionado de todos
  document
    .querySelectorAll(".horario-btn")
    .forEach((li) => li.classList.remove("horario-btn--seleccionado"));

  // Marcar el actual
  elemento.classList.add("horario-btn--seleccionado");

  // Guardar en el objeto cita
  cita.hora = horario;
  //para gurdar la hora seleccionada
  // document.querySelector("#hora").value = horario;
  const inputHora =
    document.querySelector("#hora") || document.querySelector("#hiddenHora");
  if (inputHora) inputHora.value = horario;
}

// Función que muestra alertas personalizadas al usuario
function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
  // Previene que se genere más de 1 alerta a la vez
  const alertaPrevia = document.querySelector(".alerta");
  if (alertaPrevia) {
    // Si ya existe una alerta, la elimina
    alertaPrevia.remove();
  }

  // Crea un nuevo div para la alerta
  const alerta = document.createElement("DIV");
  alerta.textContent = mensaje;
  alerta.classList.add("alerta");
  alerta.classList.add(tipo); // Añade la clase del tipo (error, exito, etc.)

  // Selecciona el elemento de referencia donde se mostrará la alerta
  const referencia = document.querySelector(elemento);

  // Verificar si existe el elemento de referencia
  if (!referencia) {
    return;
  }

  // Inserta la alerta en el DOM
  referencia.appendChild(alerta);

  // Si desaparece es true, programa la eliminación automática
  if (desaparece) {
    // setTimeout ejecuta una función después de X milisegundos
    setTimeout(() => {
      alerta.remove();
    }, 3000); // 3000ms = 3 segundos
  }
}

// Función que genera y muestra el resumen final de la cita
function mostrarResumen() {
  const resumen = document.querySelector(".contenido-resumen");

  // Verificar si existe el contenedor del resumen
  if (!resumen) {
    return;
  }

  // Limpia el contenido previo del resumen
  // firstChild obtiene el primer hijo del elemento
  // Este while elimina todos los elementos hijos uno por uno
  while (resumen.firstChild) {
    resumen.removeChild(resumen.firstChild);
  }

  // Object.values() obtiene todos los valores del objeto cita
  // includes("") verifica si algún valor está vacío
  // Valida que todos los campos estén llenos y que haya al menos un servicio
  if (Object.values(cita).includes("") || cita.servicios.length === 0) {
    mostrarAlerta(
      "Faltan datos de servicios, fecha u hora",
      "error",
      ".contenido-resumen",
      false, // Esta alerta no desaparece automáticamente
    );
    return; // Sale de la función si faltan datos
  }

  // Destructuring: extrae las propiedades del objeto cita
  const { nombre, fecha, hora, servicios, barberoNombre } = cita;

  // Crea el encabezado para la sección de servicios
  const headingServicios = document.createElement("H3");
  headingServicios.textContent = "Resumen De Servicios";
  resumen.appendChild(headingServicios);

  // Itera sobre cada servicio seleccionado para mostrarlo en el resumen
  servicios.forEach((servicio) => {
    const { id, precio, nombre } = servicio;

    // Crea un contenedor para cada servicio
    const contenedorServicio = document.createElement("DIV");
    contenedorServicio.classList.add("contenedor-servicio");

    // Crea el párrafo con el nombre del servicio
    const textoServicio = document.createElement("P");
    textoServicio.textContent = nombre;

    // Crea el párrafo con el precio del servicio
    const precioServicio = document.createElement("P");
    // innerHTML permite insertar HTML dentro del elemento
    precioServicio.innerHTML = `<span>Precio</span> $${precio}`;

    // Añade nombre y precio al contenedor del servicio
    contenedorServicio.appendChild(textoServicio);
    contenedorServicio.appendChild(precioServicio);

    // Añade el servicio completo al resumen
    resumen.appendChild(contenedorServicio);
  });

  // Crea el encabezado para la sección de información de la cita
  const headingCita = document.createElement("H3");
  headingCita.textContent = "Resumen De Cita";
  resumen.appendChild(headingCita);

  // Muestra el nombre del cliente
  const nombreCliente = document.createElement("P");
  nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

  // Formatear la fecha en español
  // new Date() crea un objeto Date desde el string de fecha
  const fechaObj = new Date(fecha);
  const mes = fechaObj.getMonth(); // Obtiene el mes (0-11)
  const dia = fechaObj.getDate() + 2; // Obtiene el día y suma 2 para ajuste
  const year = fechaObj.getFullYear(); // Obtiene el año

  // Date.UTC crea una fecha en tiempo universal coordinado
  const fechaUTC = new Date(Date.UTC(year, mes, dia));

  // opciones define cómo se formateará la fecha
  const opciones = {
    weekday: "long", // Día de la semana completo (ej: "Lunes")
    year: "numeric", // Año en números (ej: "2024")
    month: "long", // Mes completo (ej: "Enero")
    day: "numeric", // Día en número (ej: "15")
  };
  // toLocaleDateString formatea la fecha según el idioma especificado
  const fechaFormateada = fechaUTC.toLocaleDateString("es-MX", opciones);

  // Muestra la fecha formateada
  const fechaCita = document.createElement("P");
  fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

  // Muestra la hora de la cita
  const horaCita = document.createElement("P");
  horaCita.innerHTML = `<span>Hora:</span> ${hora} Horas`;

  const nombreBarbero = document.createElement("P");
  nombreBarbero.innerHTML = `<span>Barbero:</span> ${barberoNombre}`;

  // Crea el botón para confirmar la reserva
  const botonReservar = document.createElement("BUTTON");
  botonReservar.classList.add("boton");
  botonReservar.textContent = "Reservar Cita";
  // onclick define qué función ejecutar cuando se hace click
  botonReservar.onclick = reservarCita;

  // Añade todos los elementos al resumen
  resumen.appendChild(nombreCliente);
  resumen.appendChild(fechaCita);
  resumen.appendChild(horaCita);
  resumen.appendChild(nombreBarbero);
  resumen.appendChild(botonReservar);
}

// Función asíncrona que envía la cita al servidor para guardarla
async function reservarCita() {
  // Extrae todos los datos de la cita
  const { nombre, fecha, hora, servicios, id } = cita;

  // map() transforma el array de servicios en un array solo con los IDs
  // Esto es necesario porque el servidor solo necesita los IDs, no los objetos completos
  const idServicios = servicios.map((servicio) => servicio.id);

  // FormData es un objeto especial para enviar datos en formato formulario
  const datos = new FormData();
  datos.append("fecha", fecha);
  datos.append("hora", hora);
  datos.append("usuarioId", id);
  datos.append("servicios", idServicios);
  datos.append("empleadoId", cita.barberoId);

  try {
    // Define la URL del endpoint para guardar citas
    const url = "/api/citas";

    // fetch hace la petición HTTP POST al servidor
    const respuesta = await fetch(url, {
      method: "POST", // Tipo de petición
      body: datos, // Datos a enviar
    });

    // Convierte la respuesta a JSON
    const resultado = await respuesta.json();

    // console.log(resultado.resultado);

    // Si la cita se guardó exitosamente
    // if (resultado.resultado) {
    //   // SweetAlert2 muestra una alerta bonita y personalizada
    //   Swal.fire({
    //     icon: "success",
    //     title: "Cita Creada",
    //     text: "Tu cita fue creada correctamente!",
    //     button: "Ok",
    //   }).then(() => {
    //     // Después de 3 segundos, recarga la página para limpiar el formulario
    //     setTimeout(() => {
    //       window.location.reload();
    //     }, 3000);
    //   });
    // }
    if (resultado.resultado) {
      Swal.fire({
        icon: "success",
        title: "Cita Creada",
        // Mostramos el folio también en el texto para que el usuario lo vea de una vez
        text: `Tu cita fue creada correctamente. Folio: ${resultado.folio}`,
        confirmButtonText: "Ver detalle",
      }).then((result) => {
        if (result.isConfirmed) {
          // Redirige usando el folio como parámetro en la URL
          window.location.href = `/reservar/cita/detalle?folio=${resultado.folio}`;
        }
      });
    }
  } catch (error) {
    // Si hay un error al guardar, muestra una alerta de error
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Hubo un error al guardar la cita, intente nuevamente!",
    });
  }
}

// Función asincrona que actualiza una cita existente en el servidor
async function actualizarCita() {
  // 1. Sacamos los datos del objeto global 'cita'
  const { fecha, hora, servicios } = cita;
  const idServicios = servicios.map((servicio) => servicio.id);

  // 2. Obtenemos el ID de la cita que estamos editando (desde el input del HTML)
  const idCita = document.querySelector('input[name="id"]')?.value;

  // 3. Empaquetamos todos los datos en un FormData para enviarlos al servidor
  const datos = new FormData();
  datos.append("id", idCita);
  datos.append("empleadoId", cita.barberoId);
  datos.append("fecha", fecha);
  datos.append("hora", hora);
  datos.append("servicios", idServicios);

  try {
    const url = "/api/citas/actualizar";
    const respuesta = await fetch(url, {
      method: "POST",
      body: datos,
    });

    const resultado = await respuesta.json();

    if (resultado.resultado) {
      // 4. Mostramos la alerta hermosa de SweetAlert2
      Swal.fire({
        icon: "success",
        title: "Cita Actualizada",
        text: "¡Los cambios se han guardado correctamente!",
        confirmButtonText: "Aceptar",
        allowOutsideClick: false,
      }).then((result) => {
        if (result.isConfirmed) {
          // 5. Redirección manual después de dar click en aceptar
          window.location.href = "/reservar";
        }
      });
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Hubo un error al actualizar la cita, intente nuevamente!",
    });
  }
}

// EDITAR CITA — funciones exclusivas de editar.php para la cita

async function iniciarEdicion() {
  // Verificar que estamos en la página de edición
  const inputBarberoActual = document.querySelector("#barberoActual");
  if (!inputBarberoActual) return;

  // Leer valores actuales desde los hidden inputs que mandó PHP
  const serviciosActuales = document
    .querySelector("#serviciosActuales")
    .value.split(",")
    .map((id) => id.trim());
  const barberoActual = document.querySelector("#barberoActual").value;
  const fechaActual = document.querySelector("#fechaActual").value;
  const horaActual = document.querySelector("#horaActual").value;

  // Inicializar el objeto cita con los valores actuales
  // para que cargarHorarios() y mostrarHorarios() funcionen correctamente
  cita.fecha = fechaActual;
  cita.hora = horaActual; // mostrarHorarios() lo usa para pre-seleccionar

  // 1. Servicios — usa mostrarServicios() existente (mismas clases BEM)
  const respServicios = await fetch("/api/servicios");
  const servicios = await respServicios.json();
  mostrarServicios(servicios);

  // Pre-seleccionar los servicios actuales de la cita
  servicios.forEach((servicio) => {
    if (serviciosActuales.includes(String(servicio.id))) {
      seleccionarServicio(servicio); // ← función existente, no llama cargarHorarios (sin barberoId aún)
    }
  });

  // 2. Barberos — usa mostrarBarberos() existente (mismas clases BEM)
  const respBarberos = await fetch("/api/barberos");
  const barberos = await respBarberos.json();
  mostrarBarberos(barberos);

  // Pre-seleccionar el barbero actual
  // seleccionarBarbero() llama cargarHorarios() automáticamente
  // y cargarHorarios() llama mostrarHorarios() que pre-selecciona cita.hora
  const barbero = barberos.find((b) => String(b.id) === String(barberoActual));
  if (barbero) {
    seleccionarBarbero(barbero);
  }

  // 3. Flatpickr con fecha actual pre-seleccionada
  flatpickr("#fecha", {
    locale: "es",
    minDate: "today",
    altInput: true,
    altFormat: "F j, Y",
    dateFormat: "Y-m-d",
    defaultDate: fechaActual,
    disableMobile: true,
    disable: [
      function (date) {
        return date.getDay() === 0 || date.getDay() === 6;
      },
    ],
    onChange: function (selectedDates, dateStr) {
      cita.fecha = dateStr;
      cita.hora = ""; // limpiar hora al cambiar fecha
      document.querySelector("#hiddenFecha").value = dateStr;
      if (cita.barberoId && cita.servicios.length > 0) {
        cargarHorarios();
      }
    },
  });

  const botonGuardar = document.querySelector("#guardarCambios");
  if (botonGuardar) {
    botonGuardar.addEventListener("click", function (e) {
      e.preventDefault(); // Detiene el envío tradicional
      actualizarCita(); // Dispara el fetch con SweetAlert2
    });
  }
}

function iniciarCancelarCita() {
  const btnCancelar = document.querySelector("#btnCancelarCita");
  if (!btnCancelar) return; // Si no está el botón en la página, salimos para evitar errores

  btnCancelar.addEventListener("click", function (e) {
    e.preventDefault(); // Detiene la redirección inmediata

    const id = this.dataset.id; // Obtenemos el ID de la cita desde el HTML

    // Lanzamos la alerta de confirmación con SweetAlert2
    Swal.fire({
      title: "¿Estás seguro de cancelar tu cita?",
      text: "Esta acción no eliminará tu historial, pero liberará tu horario.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#ef4444", // Rojo para cancelar
      cancelButtonColor: "#6b7280", // Gris para mantener cita
      confirmButtonText: "Sí, cancelar cita",
      cancelButtonText: "No, mantener cita",
      allowOutsideClick: false,
    }).then(async (result) => {
      if (result.isConfirmed) {
        // Si el usuario confirma, preparamos el paquete de datos
        const datos = new FormData();
        datos.append("id", id);

        try {
          const url = "/api/citas/cancelar";
          const respuesta = await fetch(url, {
            method: "POST",
            body: datos,
          });

          const resultado = await respuesta.json();

          if (resultado.resultado) {
            Swal.fire({
              icon: "success",
              title: "Cita Cancelada",
              text: "Tu cita ha sido cancelada de forma exitosa.",
              confirmButtonText: "Aceptar",
              allowOutsideClick: false,
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = "/reservar"; // Redirige a la página principal de reservas
              }
            });
          }
        } catch (error) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un problema al procesar la cancelación. Inténtalo de nuevo.",
          });
        }
      }
    });
  });
}
