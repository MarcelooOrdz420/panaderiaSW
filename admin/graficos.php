<?php
include("../conexion.php");

// Obtener datos reales de ventas agrupadas
$ventasDia = mysqli_query($conexion, "
    SELECT DATE(fecha) AS fecha, SUM(total) AS total 
    FROM pedidos 
    GROUP BY DATE(fecha)
    ORDER BY fecha DESC
    LIMIT 10
");

$ventasMes = mysqli_query($conexion, "
    SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, SUM(total) AS total 
    FROM pedidos 
    GROUP BY mes
    ORDER BY mes DESC
    LIMIT 12
");

$ventasAnio = mysqli_query($conexion, "
    SELECT YEAR(fecha) AS anio, SUM(total) AS total 
    FROM pedidos 
    GROUP BY anio
    ORDER BY anio DESC
    LIMIT 5
");

// Convertir a arrays para JS
$datosDia = [];
while ($r = mysqli_fetch_assoc($ventasDia)) {
    $datosDia[] = $r;
}

$datosMes = [];
while ($r = mysqli_fetch_assoc($ventasMes)) {
    $datosMes[] = $r;
}

$datosAnio = [];
while ($r = mysqli_fetch_assoc($ventasAnio)) {
    $datosAnio[] = $r;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reportes de Ventas</title>
<link rel="stylesheet" href="../css/graficos.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<h2>📊 Reportes de Ventas Reales</h2>

<div class="grafico-contenedor">
    <canvas id="ventasDia"></canvas>
</div>

<div class="grafico-contenedor">
    <canvas id="ventasMes"></canvas>
</div>

<div class="grafico-contenedor">
    <canvas id="ventasAnio"></canvas>
</div>

<a href="dashboard.php" class="back-btn">← Volver</a>

<script>
const ventasDia = <?= json_encode($datosDia) ?>;
const ventasMes = <?= json_encode($datosMes) ?>;
const ventasAnio = <?= json_encode($datosAnio) ?>;

// === GRAFICO POR DÍA ===
new Chart(document.getElementById('ventasDia'), {
    type: 'bar',
    data: {
        labels: ventasDia.map(v => v.fecha),
        datasets: [{
            label: 'Ventas por Día (S/)',
            data: ventasDia.map(v => v.total),
            backgroundColor: '#e1913c'
        }]
    }
});

// === GRAFICO POR MES ===
new Chart(document.getElementById('ventasMes'), {
    type: 'line',
    data: {
        labels: ventasMes.map(v => v.mes),
        datasets: [{
            label: 'Ventas por Mes (S/)',
            data: ventasMes.map(v => v.total),
            borderColor: '#d2691e',
            backgroundColor: 'rgba(225,145,60,0.3)',
            fill: true
        }]
    }
});

// === GRAFICO POR AÑO ===
new Chart(document.getElementById('ventasAnio'), {
    type: 'bar',
    data: {
        labels: ventasAnio.map(v => v.anio),
        datasets: [{
            label: 'Ventas por Año (S/)',
            data: ventasAnio.map(v => v.total),
            backgroundColor: '#f4a460'
        }]
    }
});
</script>
</body>
</html>
