// Variables globales
const lat = 19.5126072;
const lng = -98.8863355;

// Función para abrir Google Maps o waze con direcciones
function getDirections(app) {
  let url = "";

  if (app === "google") {
    url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
  } else if (app === "waze") {
    url = `https://waze.com/ul?ll=${lat},${lng}&navigate=yes`;
  } else {
    console.warn("App no soportada");
    return;
  }

  window.open(url, "_blank");
}

// Inicializar listeners de botones
function iniciarAcciones() {
  document.querySelectorAll(".location__btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const action = btn.dataset.action;
      if (!action) return;

      e.preventDefault();

      switch (action) {
        case "llamar":
          window.location.href = "tel:5512345678";
          break;
        case "whatsapp":
          window.open("https://wa.me/5598765432", "_blank");
          break;
        case "google-maps":
          getDirections("google");
          break;
        case "waze":
          getDirections("waze");
          break;
        default:
          console.warn("Acción no reconocida:", action);
      }
    });
  });
}
// Ejecutar cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", iniciarAcciones);

// ============================================
// FUNCIONES GENERALES
// ============================================
// function reservar(paquete) {
//   alert(
//     `Reservando paquete: ${paquete}\n\nAquí conectarás con tu sistema de reservas.`
//   );
//   // window.location.href = `/reservar?paquete=${encodeURIComponent(paquete)}`;
// }
