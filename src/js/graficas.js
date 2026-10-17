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
      String(item.estado).trim().toUpperCase().replace(/_/g, " ")
    );
    const dataEstados = dashboardData.estados.map((item) => parseInt(item.cantidad));

    // Asignar colores según el estado
    const bgColorsEstados = dashboardData.estados.map((item) => {
      switch (String(item.estado).trim().toUpperCase()) {
        case "COMPLETADA":
          return colors.success;
        case "PENDIENTE":
          return colors.warning;
        case "EN_PROCESO":
          return colors.info;
        case "CANCELADA":
          return colors.danger;
        case "CANCELADA_POR_BARBERO":
          return colors.purple;
        case "NO_ASISTIO":
          return colors.info;
        default:
          return colors.primary;
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