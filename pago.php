<?php
include("conexion.php");
session_start();

// Simulación de usuario logueado (si ya manejas sesión, puedes quitar esto)
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['usuario_id'] = 3; // ID del usuario en tu tabla `usuarios`
}

// Cálculo del total (puedes adaptarlo según tu carrito real)
$total = 0;
if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
    foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }
}

// Si se envió el formulario, insertar pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodo_pago = $_POST['metodo_pago'];
    $usuario_id = $_SESSION['usuario_id'];
    $estado = 'pendiente';
    $fecha = date('Y-m-d H:i:s');

    $query = "INSERT INTO pedidos (usuario_id, metodo_pago, total, estado, fecha)
              VALUES ('$usuario_id', '$metodo_pago', '$total', '$estado', '$fecha')";
    
    if (mysqli_query($conexion, $query)) {
        echo "<script>alert('Pedido registrado correctamente.'); window.location.href='pedidos.php';</script>";
    } else {
        echo "Error al registrar el pedido: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Finalizar Compra</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f8f8f8;
    text-align: center;
    padding: 30px;
}

.container {
    width: 450px;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

select, button {
    padding: 8px;
    font-size: 16px;
    margin-top: 10px;
    width: 100%;
    border-radius: 6px;
    border: 1px solid #ccc;
}

button {
    background-color: #28a745;
    color: white;
    border: none;
    cursor: pointer;
}
button:hover {
    background-color: #218838;
}

.payment-info {
    display: none;
    margin-top: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 15px;
    background: #fafafa;
}

.payment-info img {
    max-width: 180px;
    border-radius: 8px;
    margin-top: 10px;
}
</style>
</head>
<body>

<div class="container">
    <h2>Finalizar compra</h2>
    <p><strong>Total a pagar:</strong> S/ <?= number_format($total, 2) ?></p>

    <form method="POST">
        <label><b>Método de Pago:</b></label>
        <select name="metodo_pago" id="metodo_pago" required>
            <option value="">-- Selecciona --</option>
            <option value="yape">Yape</option>
            <option value="transferencia">Transferencia Bancaria</option>
        </select>

        <!-- Información dinámica -->
        <div id="info_yape" class="payment-info">
            <h3>Paga con Yape</h3>
            <p>Escanea este código QR o envía al número <b>987 654 321</b></p>
            <img src="img/qr_yape.png" alt="QR Yape">
        </div>

        <div id="info_transferencia" class="payment-info">
            <h3>Transferencia Bancaria</h3>
            <p>Titular: <b>Panadería Delicias SAC</b></p>
            <p>Cuenta BCP: <b>123-4567890-12</b></p>
            <img src="img/transferencia_ref.png" alt="Referencia Transferencia">
        </div>

        <button type="submit">Confirmar Pedido</button>
    </form>
</div>

<script>
document.getElementById("metodo_pago").addEventListener("change", function() {
    const metodo = this.value;
    document.getElementById("info_yape").style.display = (metodo === "yape") ? "block" : "none";
    document.getElementById("info_transferencia").style.display = (metodo === "transferencia") ? "block" : "none";
});
</script>

</body>
</html>
