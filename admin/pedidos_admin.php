<?php
include("../conexion.php");

// Consultar pedidos con el nombre del usuario
$query = "
    SELECT 
        p.id, 
        COALESCE(u.nombre, '(sin usuario)') AS cliente, 
        p.fecha, 
        p.total, 
        p.estado
    FROM pedidos p
    LEFT JOIN usuarios u ON p.usuario_id = u.id
    ORDER BY p.fecha DESC
";
$pedidos = mysqli_query($conexion, $query);

if ($pedidos === false) {
    die("Error en la consulta de pedidos: " . mysqli_error($conexion));
}
$no_pedidos = (mysqli_num_rows($pedidos) === 0);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pedidos</title>
<link rel="stylesheet" href="../css/pedidos_admin.css">
<style>
table {
    width: 90%;
    border-collapse: collapse;
    margin: 20px auto;
}
th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}
th {
    background-color: #007bff;
    color: white;
}
select {
    padding: 4px;
    border-radius: 4px;
}
button {
    padding: 4px 8px;
    border: none;
    border-radius: 4px;
    background: #28a745;
    color: white;
    cursor: pointer;
}
button:hover {
    background: #218838;
}
.back-btn {
    display: inline-block;
    margin: 20px;
    padding: 10px 15px;
    background: #555;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}
</style>
</head>
<body>
<h2 style="text-align:center;">Pedidos de Clientes</h2>

<?php if ($no_pedidos): ?>
    <p style="text-align:center;">No se encontraron pedidos.</p>
<?php else: ?>
<table>
<tr>
    <th>ID</th>
    <th>Cliente</th>
    <th>Fecha</th>
    <th>Total</th>
    <th>Estado</th>
    <th>Acción</th>
</tr>
<?php while($p = mysqli_fetch_assoc($pedidos)): ?>
<tr>
<td><?= htmlspecialchars($p['id']) ?></td>
<td><?= htmlspecialchars($p['cliente']) ?></td>
<td><?= htmlspecialchars($p['fecha']) ?></td>
<td>S/ <?= htmlspecialchars($p['total']) ?></td>
<td>
    <form action="cambiar_estado.php" method="POST">
        <input type="hidden" name="id" value="<?= $p['id'] ?>">
        <select name="estado">
            <option value="pendiente" <?= ($p['estado'] == 'pendiente' ? 'selected' : '') ?>>Pendiente</option>
            <option value="en proceso" <?= ($p['estado'] == 'en proceso' ? 'selected' : '') ?>>En proceso</option>
            <option value="entregado" <?= ($p['estado'] == 'entregado' ? 'selected' : '') ?>>Entregado</option>
        </select>
</td>
<td>
        <button type="submit">Guardar</button>
    </form>
</td>
</tr>
<?php endwhile; ?>
</table>
<?php endif; ?>

<a href="dashboard.php" class="back-btn">← Volver</a>
</body>
</html>
