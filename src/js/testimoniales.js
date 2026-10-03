// ------------ CARRUSEL DE LOS TESTIMONIALES --------
(() => {
  let testimonialActual = 0;
  const wrapper = document.getElementById("testimonialsWrapper");
  const testimonialesTotal = wrapper?.children.length ?? 0;
  const contenedorPuntos = document.getElementById("testimonialDots");

  // Early return si faltan elementos
  if (!wrapper || !contenedorPuntos) return;

  // Crear los puntos (dots) para la navegación de los testimoniales 
  for (let i = 0; i < testimonialesTotal; i++) {
    const dot = document.createElement("span");
    dot.classList.add("testimonials__dot");
    
    if (i === 0) dot.classList.add("testimonials__dot--active");
    
    dot.addEventListener("click", () => irAlTestimonial(i));
    contenedorPuntos.appendChild(dot);
  }

  // Actualizar la posición del carrusel y el estado de los puntos
  function ActualizarTestimoniales() {
    wrapper.style.transform = `translateX(-${testimonialActual * 100}%)`;

    document.querySelectorAll(".testimonials__dot").forEach((dot, index) => {
      dot.classList.toggle("testimonials__dot--active", index === testimonialActual);
    });
  }

  // Para navegar al siguiente testimonial
  function testimonialSiguiente() {
    testimonialActual = (testimonialActual + 1) % testimonialesTotal;
    ActualizarTestimoniales();
  }

  // Para navegar al testimonial anterior
  function testimonialAnterior() {
    testimonialActual = (testimonialActual - 1 + testimonialesTotal) % testimonialesTotal;
    ActualizarTestimoniales();
  }

  // Navegar a un testimonial específico
  function irAlTestimonial(index) {
    testimonialActual = index;
    ActualizarTestimoniales();
  }

  // Agregar eventos a los botones
  document.getElementById("nextBtn")?.addEventListener("click", testimonialSiguiente);
  document.getElementById("prevBtn")?.addEventListener("click", testimonialAnterior);

  // Auto-slide cada 5 segundos
  setInterval(testimonialSiguiente, 5000);
})();