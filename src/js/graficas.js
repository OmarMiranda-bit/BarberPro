// document.addEventListener("DOMContentLoaded", function () {
//   "use strict";

//   // Si la librería Chart no existe en esta página, salimos de la función inmediatamente
//   if (typeof Chart === "undefined") {
//     return;
//   }

//   // Colores del tema
//   const colors = {
//     primary: "#C19A6B",
//     success: "#10B981",
//     warning: "#F59E0B",
//     danger: "#EF4444",
//     info: "rgb(59, 130, 246)",
//     purple: "#8B5CF6",
//     text: "#1a1b15",
//     secondary: "#8b8b8b",
//   };

//   // Configuración global de Chart.js
//   Chart.defaults.color = colors.text;
//   Chart.defaults.borderColor = "rgba(193, 154, 107, 0.1)";
//   Chart.defaults.font.family = "'Open Sans', sans-serif";

//   // Extraer datos inyectados desde PHP
//   const dashboardData = window.dashboardData || { estados: [], servicios: [] };

//   // Gráfica de Tendencias (Línea)
//   const ctxTendencias = document.getElementById("chartTendencias");

//   if (ctxTendencias) {
//     // Obtenemos el contexto 2d para crear el degradado
//     const ctx = ctxTendencias.getContext("2d");

//     // Creamos el degradado (x0, y0, x1, y1)
//     // El '400' representa la altura aproximada de la gráfica; se ajusta solo
//     const gradient = ctx.createLinearGradient(0, 0, 0, 400);
//     gradient.addColorStop(0, "rgba(59, 130, 246, 0.4)"); // Color info con 40% opacidad
//     gradient.addColorStop(1, "rgba(193, 154, 107, 0)"); // Transparente total en la base

//     new Chart(ctxTendencias, {
//       type: "line",
//       data: {
//         labels: ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
//         datasets: [
//           {
//             label: "Citas",
//             data: [32, 38, 35, 42, 45, 48, 41],
//             borderColor: colors.info, // La línea sólida
//             tension: 0.4, // Curvatura elegante
//             borderWidth: 3,
//             backgroundColor: gradient, // <-- Aplicamos el degradado aquí
//             fill: true, // Habilitamos el relleno
//             pointRadius: 3, // Escondemos puntos por defecto para limpieza
//             pointHoverRadius: 6, // Aparecen al pasar el mouse
//             pointBackgroundColor: colors.info,
//             pointBorderColor: "#fff",
//             pointBorderWidth: 2,
//           },
//         ],
//       },
//       options: {
//         responsive: true,
//         maintainAspectRatio: true,
//         plugins: {
//           legend: {
//             display: false,
//           },
//           tooltip: {
//             backgroundColor: "rgba(28, 28, 28, 0.95)",
//             padding: 12,
//             cornerRadius: 8,
//             titleFont: { size: 14 },
//             bodyFont: { size: 13 },
//           },
//         },
//         scales: {
//           y: {
//             border: { display: false }, // Quitamos la línea del eje Y
//             beginAtZero: true,
//             grid: {
//               color: "rgba(193, 154, 107, 0.1)",
//               drawBorder: false,
//             },
//           },
//           x: {
//             grid: {
//               border: { display: false }, // Quitamos la línea del eje x
//               display: false,
//             },
//           },
//         },
//       },
//     });
//   }

//   // Gráfica de Servicios (Barras)
//   const ctxServicios = document.getElementById("chartServicios");
//   if (ctxServicios) {
//     new Chart(ctxServicios, {
//       type: "bar",
//       data: {
//         labels: [
//           "Corte + Barba",
//           "Corte Clásico",
//           "Diseño Degradado",
//           "Tinte Barba",
//           "Afeitado",
//           "Tratamiento",
//         ],
//         datasets: [
//           {
//             label: "Cantidad",
//             data: [89, 76, 68, 45, 38, 31],
//             backgroundColor: [
//               `${colors.primary}80`,
//               `${colors.info}80`,
//               `${colors.success}80`,
//               `${colors.warning}80`,
//               `${colors.purple}80`,
//               `${colors.danger}80`,
//             ],
//             borderColor: [
//               colors.primary,
//               colors.info,
//               colors.success,
//               colors.warning,
//               colors.purple,
//               colors.danger,
//             ],
//             borderWidth: 2,
//             borderRadius: 8,
//           },
//         ],
//       },
//       options: {
//         responsive: true,
//         maintainAspectRatio: true,
//         plugins: {
//           legend: {
//             display: false,
//           },
//           tooltip: {
//             backgroundColor: "rgba(28, 28, 28, 0.95)",
//             padding: 12,
//             cornerRadius: 8,
//           },
//         },
//         scales: {
//           y: {
//             beginAtZero: true,
//             grid: {
//               color: "rgba(193, 154, 107, 0.1)",
//             },
//           },
//           x: {
//             grid: {
//               display: false,
//             },
//           },
//         },
//       },
//     });
//   }

//   // Gráfica de Estados (Pie/Dona)
//   const ctxEstados = document.getElementById("chartEstados");
//   if (ctxEstados) {
//     new Chart(ctxEstados, {
//       type: "doughnut",
//       data: {
//         labels: ["Completadas", "Pendientes", "Canceladas"],
//         datasets: [
//           {
//             data: [215, 15, 32],
//             backgroundColor: [colors.success, colors.warning, colors.danger],
//             // borderColor: "#1C1C1C",
//             // borderWidth: 3,
//           },
//         ],
//       },
//       options: {
//         responsive: true,
//         maintainAspectRatio: true,
//         plugins: {
//           legend: {
//             position: "bottom",
//             labels: {
//               padding: 20,
//               font: { size: 13 },
//               usePointStyle: true,
//               pointStyle: "circle",
//             },
//           },
//           tooltip: {
//             backgroundColor: "rgba(28, 28, 28, 0.95)",
//             padding: 12,
//             cornerRadius: 8,
//             callbacks: {
//               label: function (context) {
//                 const total = context.dataset.data.reduce((a, b) => a + b, 0);
//                 const percentage = ((context.parsed / total) * 100).toFixed(1);
//                 return `${context.label}: ${context.parsed} (${percentage}%)`;
//               },
//             },
//           },
//         },
//       },
//     });
//   }

//   // Gráfica de Ingresos (Área)
//   const ctxIngresos = document.getElementById("chartIngresos");
//   if (ctxIngresos) {
//     new Chart(ctxIngresos, {
//       type: "line",
//       data: {
//         labels: ["Sem 1", "Sem 2", "Sem 3", "Sem 4"],
//         datasets: [
//           {
//             label: "Mes Actual",
//             data: [15200, 18400, 16800, 18050],
//             borderColor: colors.primary,
//             backgroundColor: `${colors.primary}40`,
//             tension: 0.4,
//             fill: true,
//             pointBackgroundColor: colors.primary,
//             pointBorderColor: "#fff",
//             pointBorderWidth: 2,
//             pointRadius: 5,
//           },
//           {
//             label: "Mes Anterior",
//             data: [13500, 15800, 14200, 14500],
//             borderColor: colors.secondary,
//             backgroundColor: `${colors.secondary}20`,
//             tension: 0.4,
//             fill: true,
//             pointBackgroundColor: colors.secondary,
//             pointBorderColor: "#fff",
//             pointBorderWidth: 2,
//             pointRadius: 5,
//           },
//         ],
//       },
//       options: {
//         responsive: true,
//         maintainAspectRatio: true,
//         plugins: {
//           legend: {
//             display: true,
//             position: "top",
//             labels: {
//               padding: 15,
//               usePointStyle: true,
//             },
//           },
//           tooltip: {
//             backgroundColor: "rgba(28, 28, 28, 0.95)",
//             padding: 12,
//             cornerRadius: 8,
//             callbacks: {
//               label: function (context) {
//                 return `${context.dataset.label}: $${context.parsed.y.toLocaleString()}`;
//               },
//             },
//           },
//         },
//         scales: {
//           y: {
//             beginAtZero: true,
//             grid: {
//               color: "rgba(193, 154, 107, 0.1)",
//             },
//             ticks: {
//               callback: function (value) {
//                 return "$" + value.toLocaleString();
//               },
//             },
//           },
//           x: {
//             grid: {
//               display: false,
//             },
//           },
//         },
//       },
//     });
//   }
// });


document.addEventListener("DOMContentLoaded", function () {
  "use strict";

  if (typeof Chart === "undefined") {
    return;
  }

  // Colores del tema
  const colors = {
    primary: "#C19A6B",
    success: "#10B981",
    warning: "#F59E0B",
    danger: "#EF4444",
    info: "#3B82F6",
    purple: "#8B5CF6",
    text: "#1a1b15",
    secondary: "#8b8b8b",
  };

  Chart.defaults.color = colors.text;
  Chart.defaults.borderColor = "rgba(193, 154, 107, 0.1)";
  Chart.defaults.font.family = "'Open Sans', sans-serif";

  // Extraer datos inyectados desde PHP
  const dashboardData = window.dashboardData || { estados: [], servicios: [] };

  // 1. GRÁFICA DE ESTADOS (Dona / Pie)
  const ctxEstados = document.getElementById("chartEstados");
  if (ctxEstados && dashboardData.estados.length > 0) {
    const labelsEstados = dashboardData.estados.map((item) =>
      item.estado.replace(/_/g, " ")
    );
    const dataEstados = dashboardData.estados.map((item) => parseInt(item.cantidad));

    // Asignar colores según el estado
    const bgColorsEstados = dashboardData.estados.map((item) => {
      switch (item.estado) {
        case "COMPLETADA":
          return colors.success;
        case "PENDIENTE":
          return colors.warning;
        case "EN_PROCESO":
          return colors.info;
        case "CANCELADA":
        case "CANCELADA_POR_BARBERO":
        case "NO_ASISTIO":
          return colors.danger;
        default:
          return colors.secondary;
      }
    });

    new Chart(ctxEstados, {
      type: "doughnut",
      data: {
        labels: labelsEstados,
        datasets: [
          {
            data: dataEstados,
            backgroundColor: bgColorsEstados,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              padding: 20,
              font: { size: 13 },
              usePointStyle: true,
              pointStyle: "circle",
            },
          },
          tooltip: {
            backgroundColor: "rgba(28, 28, 28, 0.95)",
            padding: 12,
            cornerRadius: 8,
            callbacks: {
              label: function (context) {
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = ((context.parsed / total) * 100).toFixed(1);
                return `${context.label}: ${context.parsed} (${percentage}%)`;
              },
            },
          },
        },
      },
    });
  }

  // 2. GRÁFICA DE SERVICIOS MÁS DEMANDADOS (Barras)
  const ctxServicios = document.getElementById("chartServicios");
  if (ctxServicios && dashboardData.servicios.length > 0) {
    const labelsServicios = dashboardData.servicios.map((item) => item.nombre);
    const dataServicios = dashboardData.servicios.map((item) => parseInt(item.total));

    const palette = [
      colors.primary,
      colors.info,
      colors.success,
      colors.warning,
      colors.purple,
      colors.danger,
    ];

    new Chart(ctxServicios, {
      type: "bar",
      data: {
        labels: labelsServicios,
        datasets: [
          {
            label: "Cantidad",
            data: dataServicios,
            backgroundColor: palette
              .slice(0, labelsServicios.length)
              .map((c) => `${c}80`),
            borderColor: palette.slice(0, labelsServicios.length),
            borderWidth: 2,
            borderRadius: 8,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: "rgba(28, 28, 28, 0.95)",
            padding: 12,
            cornerRadius: 8,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { precision: 0 },
            grid: { color: "rgba(193, 154, 107, 0.1)" },
          },
          x: { grid: { display: false } },
        },
      },
    });
  }
  // 3. GRÁFICA DE TENDENCIA DE CITAS (Línea)
  const ctxTendencias = document.getElementById("chartTendencias");
  if (ctxTendencias && dashboardData.tendencia && dashboardData.tendencia.length > 0) {
    new Chart(ctxTendencias, {
      type: "line",
      data: {
        labels: dashboardData.tendencia.map(item => item.dia),
        datasets: [{
          label: "Citas",
          data: dashboardData.tendencia.map(item => parseInt(item.total)),
          borderColor: colors.info,
          tension: 0.4,
          fill: true,
          backgroundColor: "rgba(59, 130, 246, 0.2)",
          pointBackgroundColor: colors.info,
          pointBorderColor: "#fff"
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, ticks: { precision: 0 } },
          x: { grid: { display: false } }
        }
      }
    });
  }

  // 4. GRÁFICA DE COMPARATIVA DE INGRESOS (Área)
  const ctxIngresos = document.getElementById("chartIngresos");
  if (ctxIngresos && dashboardData.ingresosSemana && dashboardData.ingresosSemana.length > 0) {
    new Chart(ctxIngresos, {
      type: "line",
      data: {
        labels: dashboardData.ingresosSemana.map(item => item.semana),
        datasets: [{
          label: "Ingresos ($)",
          data: dashboardData.ingresosSemana.map(item => parseFloat(item.total)),
          borderColor: colors.primary,
          backgroundColor: `${colors.primary}40`,
          tension: 0.4,
          fill: true,
          pointBackgroundColor: colors.primary,
          pointBorderColor: "#fff"
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: true, position: "top" } },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) { return "$" + value.toLocaleString(); }
            }
          },
          x: { grid: { display: false } }
        }
      }
    });
  }
});