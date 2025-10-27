<?php
session_start();
include("conexion.php");

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar producto al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
    $id_producto = intval($_POST['id_producto']);
    $query = "SELECT * FROM productos WHERE id = '$id_producto'";
    $res = mysqli_query($conexion, $query);

    if ($res && mysqli_num_rows($res) > 0) {
        $producto = mysqli_fetch_assoc($res);
        $id = $producto['id'];

        if (!isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id] = [
                'nombre' => $producto['nombre'],
                'precio' => floatval($producto['precio']),
                'cantidad' => 1,
                'imagen' => $producto['imagen']
            ];
        } else {
            $_SESSION['carrito'][$id]['cantidad']++;
        }
    }

    header("Location: agregar_carrito.php");
    exit;
}

// Eliminar producto del carrito
if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);
    if (isset($_SESSION['carrito'][$id_eliminar])) {
        unset($_SESSION['carrito'][$id_eliminar]);
    }
    header("Location: agregar_carrito.php");
    exit;
}

// Confirmar compra
if (isset($_POST['confirmar_pago']) && !empty($_SESSION['carrito'])) {
    $metodo = $_POST['metodo_pago'] ?? '';
    $id_usuario = $_SESSION['usuario_id'];
    $total = 0;

    foreach ($_SESSION['carrito'] as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }

    if (in_array($metodo, ['yape', 'transferencia'])) {
        // Registrar pedido
        $sql_pedido = "INSERT INTO pedidos (id_usuario, metodo_pago, total) 
                       VALUES ('$id_usuario', '$metodo', '$total')";

        if (mysqli_query($conexion, $sql_pedido)) {
            $id_pedido = mysqli_insert_id($conexion);

            // Registrar detalles del pedido
            foreach ($_SESSION['carrito'] as $item) {
                $producto = mysqli_real_escape_string($conexion, $item['nombre']);
                $cantidad = intval($item['cantidad']);
                $precio = floatval($item['precio']);
                $subtotal = $cantidad * $precio;

                $sql_detalle = "INSERT INTO detalle_pedidos (id_pedido, producto, cantidad, precio, subtotal)
                                VALUES ('$id_pedido', '$producto', '$cantidad', '$precio', '$subtotal')";
                mysqli_query($conexion, $sql_detalle);
            }

            // Vaciar carrito
            $_SESSION['carrito'] = [];

            // Mensaje de confirmación
            if ($metodo === 'yape') {
                $mensaje = "✅ Pedido registrado correctamente. Realiza el pago por <b>Yape al número 987 654 321</b>.";
            } else {
                $mensaje = "✅ Pedido registrado correctamente. Realiza la transferencia al <b>BCP: 123-45678910</b>.";
            }
        } else {
            $mensaje = "❌ Error al registrar el pedido: " . mysqli_error($conexion);
        }
    } else {
        $mensaje = "❌ Selecciona un método de pago válido.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Tu Carrito - Panadería Delicias</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #fff8f3;
    margin: 0;
    padding: 0;
}
header {
    background: #f7c08a;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 3px 8px rgba(0,0,0,0.2);
}
header h1 { margin: 0; }
a {
    text-decoration: none;
    color: #333;
    font-weight: bold;
}
table {
    width: 90%;
    margin: 30px auto;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
}
th, td {
    padding: 15px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}
img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.total {
    text-align: right;
    margin-right: 60px;
    font-size: 18px;
    font-weight: bold;
}
button {
    background: #f7a23c;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    transition: 0.3s;
}
button:hover {
    background: #e58a1f;
}
.pago {
    background: #fff;
    width: 60%;
    margin: 30px auto;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    text-align: center;
}
.success {
    background: #d4edda;
    color: #155724;
    padding: 15px;
    border-radius: 10px;
    margin: 20px auto;
    width: 70%;
    text-align: center;
    box-shadow: 0 3px 8px rgba(0,0,0,0.2);
}
</style>
</head>
<body>
<header>
    <h1>🛒 Tu Carrito</h1>
    <nav><a href="productos.php">Volver a productos</a></nav>
</header>

<?php if (isset($mensaje)) echo "<div class='success'>$mensaje</div>"; ?>

<?php if (empty($_SESSION['carrito'])): ?>
    <h2 style="text-align:center; margin-top:40px;">Tu carrito está vacío</h2>
<?php else: ?>
<table>
    <tr>
        <th>Imagen</th>
        <th>Producto</th>
        <th>Precio</th>
        <th>Cantidad</th>
        <th>Subtotal</th>
        <th>Acción</th>
    </tr>
    <?php
    $total = 0;
    foreach ($_SESSION['carrito'] as $id => $item):
        $subtotal = $item['precio'] * $item['cantidad'];
        $total += $subtotal;
    ?>
    <tr>
        <td><img src="img/productos/<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>"></td>
        <td><?php echo htmlspecialchars($item['nombre']); ?></td>
        <td>S/ <?php echo number_format($item['precio'], 2); ?></td>
        <td><?php echo intval($item['cantidad']); ?></td>
        <td>S/ <?php echo number_format($subtotal, 2); ?></td>
        <td><a href="?eliminar=<?php echo $id; ?>"><button>Eliminar</button></a></td>
    </tr>
    <?php endforeach; ?>
</table>

<p class="total">Total a pagar: S/ <?php echo number_format($total, 2); ?></p>

<div class="pago">
    <h3>Selecciona método de pago</h3>
    <form method="POST">
        <label>
            <input type="radio" name="metodo_pago" value="yape" required> 📱 Pagar con Yape
        </label><br><br>
        <label>
            <input type="radio" name="metodo_pago" value="transferencia" required> 💳 Transferencia bancaria
        </label><br><br>
        <button type="submit" name="confirmar_pago">Confirmar pedido</button>
    </form>
</div>
<?php endif; ?>
</body>
</html>
