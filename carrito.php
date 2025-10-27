<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = [];
}

if (isset($_POST["agregar"])) {
    $id_producto = $_POST["id_producto"];
    $_SESSION["carrito"][] = $id_producto;
}

if (isset($_POST["finalizar"])) {
    $usuario_id = $_SESSION["usuario_id"];
    $total = $_POST["total"];
    mysqli_query($conexion, "INSERT INTO pedidos (usuario_id, total) VALUES ($usuario_id, $total)");
    $_SESSION["carrito"] = [];
    $mensaje = "✅ Pedido realizado con éxito.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Carrito - Panadería Delicias</title>
<link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header>
    <h1>Tu carrito</h1>
    <nav><a href="productos.php">Volver a productos</a></nav>
</header>

<section>
    <?php
    if (!empty($_SESSION["carrito"])) {
        $ids = implode(",", $_SESSION["carrito"]);
        $resultado = mysqli_query($conexion, "SELECT * FROM productos WHERE id IN ($ids)");
        $total = 0;
        echo "<table><tr><th>Producto</th><th>Precio</th></tr>";
        while ($row = mysqli_fetch_assoc($resultado)) {
            echo "<tr><td>{$row['nombre']}</td><td>S/ {$row['precio']}</td></tr>";
            $total += $row['precio'];
        }
        echo "</table>";
        echo "<h3>Total: S/ $total</h3>";
        echo "
        <form method='POST'>
            <input type='hidden' name='total' value='$total'>
            <button type='submit' name='finalizar'>Finalizar compra</button>
        </form>";
    } else {
        echo "<p>Tu carrito está vacío.</p>";
    }
    if (isset($mensaje)) echo "<p>$mensaje</p>";
    ?>
</section>
</body>
</html>
