//<<<<<<-------------------HEADER--------------------->>>>>>

// FUNCIÓN AUXILIAR para mostrar/ocultar botón 
function toggleNavbarBtn(show) {
  const navbarBtn = document.querySelector('.navbar__item--cta');
  if (navbarBtn) {
    navbarBtn.style.display = show ? 'block' : 'none';
  }
}

// ASIGNACION DE VARIABLES - Seleccionar elementos de la página
const navbar = document.getElementById("navbar");
const navbarToggle = document.getElementById("navbarToggle");
const navbarMenu = document.getElementById("navbarMenu");
// const toggleIcon = navbarToggle.querySelector("i");
const toggleIcon = navbarToggle ? navbarToggle.querySelector("i") : null;
const navLinks = document.querySelectorAll(".navbar__link");

// GUARD CLAUSE: Si no existe el navbar en esta página, no ejecutamos nada del header
if (navbar) {

// INICIALIZAR: Verificar al cargar la página
document.addEventListener('DOMContentLoaded', () => {
  // Si al cargar la página NO has scrolleado > 600px, ocultar botón
  if (window.pageYOffset <= 600) {
    toggleNavbarBtn(false);  // ← Ocultar al inicio
  }
});

// STICKY NAVBAR
// Verificar si hay usuario logueado
const header = document.querySelector('.header');
const usuarioLogueado = header ? header.getAttribute('data-usuario-logueado') === 'true' : false;

// Si hay usuario logueado, aplicar navbar--sticky desde el inicio
if (usuarioLogueado) {
  navbar.classList.add("navbar--sticky");
  toggleNavbarBtn(true);
}

// Al dar scroll se agrega/quita la clase y se ejecuta el codigo scss del archivo _header.scss
window.addEventListener("scroll", () => {
  if (!navbar) return; // Si no hay navbar en esta página, no ejecutar nada
  // Si hay usuario logueado, el navbar--sticky siempre está activo
  if (usuarioLogueado) {
    navbar.classList.add("navbar--sticky");
    toggleNavbarBtn(true);
  } else {
    // Si no hay usuario logueado, usar la lógica original (600px de scroll)
    if (window.pageYOffset > 600) { 
      navbar.classList.add("navbar--sticky"); //Añade la clase al navbar
      toggleNavbarBtn(true);   // ← Mostrar botón
    } else {
      // si no
      navbar.classList.remove("navbar--sticky"); // la elimina
      toggleNavbarBtn(false);  // ← Ocultar botón
    }
  }
});

// Toggle menu móvil - Cambia el icono dinámicamente
if (navbarToggle && navbarMenu && toggleIcon) {
navbarToggle.addEventListener("click", () => {
  // con .toggle se evita usar comprobaciones como if/else 
  navbarToggle.classList.toggle("navbar__toggle--active"); //si no tiene la clase "--active" el elemento 'navbarToggle', la agrega. o viceversa
  navbarMenu.classList.toggle("navbar__menu--active");
  document.body.classList.toggle("menu-open");

  // Luego verificar si está activo DESPUÉS del toggle
  const isOpen = navbarToggle.classList.contains("navbar__toggle--active");//funciona como un sensor para detectar que si se haya asignado la clase 

  // Cambiar icono basado en el estado actual
  if (isOpen) {
    // Si está abierto, elimina las barras pero añade la X
    toggleIcon.classList.remove("fa-bars");
    toggleIcon.classList.add("fa-x");
  } else { //si no 
    // elimina la x y agrega las barras 
    toggleIcon.classList.remove("fa-x");
    toggleIcon.classList.add("fa-bars");
  }
});

}

// Cerrar menu al hacer click en cualquier enlace dentro del menu movil 
//recorre todos links que se pudo haber dado un click 
navLinks.forEach((link) => { link.addEventListener("click", () => { //callback
//elimina todas las clases que se agregan al estar activo el menu movil 
    navbarToggle.classList.remove("navbar__toggle--active");
    navbarMenu.classList.remove("navbar__menu--active");
    document.body.classList.remove("menu-open");

    // Resetear icono cuando se cierra
    toggleIcon.classList.remove("fa-x");
    toggleIcon.classList.add("fa-bars");
  });
});

// Cerrar menu al hacer click fuera
document.addEventListener("click", (e) => {
  if (!navbar.contains(e.target)) {//si el click no se dio dentro del navbar, entonces 
    //remueve todas las clases de --active.
    navbarToggle.classList.remove("navbar__toggle--active");
    navbarMenu.classList.remove("navbar__menu--active");
    document.body.classList.remove("menu-open");

    // Resetear icono cuando se cierra
    toggleIcon.classList.remove("fa-x");
    toggleIcon.classList.add("fa-bars");
  }
});
}