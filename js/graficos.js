// === Gráfico por Día ===
if (datosDia.length > 0) {
  new Chart(document.getElementById('ventasDia'), {
    type: 'bar',
    data: {
      labels: datosDia.map(d => d.fecha),
      datasets: [{
        label: 'Ventas por Día (S/)',
        data: datosDia.map(d => d.total),
        backgroundColor: '#e1913c'
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: { display: true, text: 'Ventas Diarias', font: { size: 16 } },
        legend: { display: false }
      },
      scales: {
        y: { beginAtZero: true, title: { display: true, text: 'Monto (S/)' } }
      }
    }
  });
}

// === Gráfico por Mes ===
if (datosMes.length > 0) {
  new Chart(document.getElementById('ventasMes'), {
    type: 'line',
    data: {
      labels: datosMes.map(d => d.mes),
      datasets: [{
        label: 'Ventas por Mes (S/)',
        data: datosMes.map(d => d.total),
        borderColor: '#d2691e',
        backgroundColor: 'rgba(225,145,60,0.2)',
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: { display: true, text: 'Ventas Mensuales', font: { size: 16 } },
        legend: { display: false }
      },
      scales: {
        y: { beginAtZero: true, title: { display: true, text: 'Monto (S/)' } }
      }
    }
  });
}

// === Gráfico por Año ===
if (datosAnio.length > 0) {
  new Chart(document.getElementById('ventasAnio'), {
    type: 'bar',
    data: {
      labels: datosAnio.map(d => d.anio),
      datasets: [{
        label: 'Ventas por Año (S/)',
        data: datosAnio.map(d => d.total),
        backgroundColor: '#f0a14b'
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: { display: true, text: 'Ventas Anuales', font: { size: 16 } },
        legend: { display: false }
      },
      scales: {
        y: { beginAtZero: true, title: { display: true, text: 'Monto (S/)' } }
      }
    }
  });
}
