<?php
session_start();
include("conexion.php");

// 🔐 Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// 🧺 Inicializar carrito
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// 🧩 Obtener categorías
$categorias = mysqli_query($conexion, "SELECT * FROM categorias");

// 🛒 Obtener productos si se selecciona categoría
$productos = null;
if (isset($_GET['categoria'])) {
    $categoria_id = intval($_GET['categoria']);
    $productos = mysqli_query($conexion, "SELECT * FROM productos WHERE categoria_id = $categoria_id");
}

/* ──────────────────────────────
   🛍️ AGREGAR PRODUCTO AL CARRITO
────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto']) && !isset($_POST['confirmar_pago'])) {
    $id_producto = intval($_POST['id_producto']);
    $query = mysqli_query($conexion, "SELECT * FROM productos WHERE id = $id_producto");
    $prod = mysqli_fetch_assoc($query);

    if ($prod) {
        $id = $prod['id'];
        if (!isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id] = [
                'id' => $prod['id'],
                'nombre' => $prod['nombre'],
                'precio' => $prod['precio'],
                'cantidad' => 1,
                'imagen' => $prod['imagen']
            ];
        } else {
            $_SESSION['carrito'][$id]['cantidad']++;
        }
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

/* ──────────────────────────────
   🗑️ ELIMINAR PRODUCTO DEL CARRITO
────────────────────────────── */
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    unset($_SESSION['carrito'][$id]);
    header("Location: comprar.php");
    exit;
}

/* ──────────────────────────────
   💳 CONFIRMAR Y REGISTRAR PEDIDO
────────────────────────────── */
if (isset($_POST['confirmar_pago']) && !empty($_SESSION['carrito'])) {
    $metodo = $_POST['metodo_pago'] ?? '';
    $id_usuario = intval($_SESSION['id_usuario']);
    $total = 0;

    foreach ($_SESSION['carrito'] as $item) {
        $total += floatval($item['precio']) * intval($item['cantidad']);
    }

    if (in_array($metodo, ['yape', 'transferencia'])) {
        $metodo_safe = mysqli_real_escape_string($conexion, $metodo);
        $total_safe = number_format($total, 2, '.', '');

        $sql_pedido = "INSERT INTO pedidos (id_usuario, metodo_pago, total) 
                       VALUES ('$id_usuario', '$metodo_safe', '$total_safe')";
        if (mysqli_query($conexion, $sql_pedido)) {
            $id_pedido = mysqli_insert_id($conexion);
            $insert_ok = true;

            foreach ($_SESSION['carrito'] as $item) {
                if (!isset($item['id'])) continue;

                $producto_id = intval($item['id']);
                $cantidad = intval($item['cantidad']);
                $subtotal = number_format($cantidad * floatval($item['precio']), 2, '.', '');

                $sql_det = "INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, subtotal)
                            VALUES ('$id_pedido', '$producto_id', '$cantidad', '$subtotal')";
                if (!mysqli_query($conexion, $sql_det)) {
                    $insert_ok = false;
                    break;
                }
            }

            if ($insert_ok) {
                $_SESSION['carrito'] = [];
                if ($metodo === 'yape') {
                    $mensaje = "✅ Pedido #{$id_pedido} registrado. Realiza el pago Yape al número <b>987 654 321</b>.";
                } else {
                    $mensaje = "✅ Pedido #{$id_pedido} registrado. Transfiere al banco BCP: <b>Cuenta 123-45678910</b>.";
                }
            } else {
                mysqli_query($conexion, "DELETE FROM pedidos WHERE id = " . intval($id_pedido));
                $mensaje = "❌ Error al registrar los detalles del pedido.";
            }
        } else {
            $mensaje = "❌ Error al registrar el pedido: " . mysqli_error($conexion);
        }
    } else {
        $mensaje = "❌ Selecciona un método de pago válido.";
    }
}

// 🔸 Contar productos en carrito
$carrito_cantidad = 0;
if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        $carrito_cantidad += intval($item['cantidad']);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprar - Panadería Delicias</title>
    <link rel="stylesheet" href="css/comprar.css">
</head>
<body>

<header>
    <div class="encabezado-empresa">
        <img src="img/logo.jpg" alt="Logo Panadería" class="logo">
        <div class="nombre-empresa">
            <h1>PANADERÍA - PASTELERÍA</h1>
            <h2>“DELICIAS DEL CENTRO”</h2>
        </div>
    </div>

    <div class="barra-usuario">
        <h3>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?> 👋</h3>
        <nav>
            <a href="comprar.php">Inicio</a>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </div>

    <div class="carrito-top">
        🛒 <a href="#carrito">Carrito (<?php echo $carrito_cantidad; ?>)</a>
    </div>
</header>

<!-- 🗂️ CATEGORÍAS -->
<section class="categorias">
    <h2>Categorías</h2>
    <div class="lista-categorias">
        <?php while ($cat = mysqli_fetch_assoc($categorias)): ?>
            <a href="comprar.php?categoria=<?php echo $cat['id']; ?>"
               class="<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == $cat['id']) ? 'activa' : ''; ?>">
                <?php echo htmlspecialchars($cat['nombre']); ?>
            </a>
        <?php endwhile; ?>
    </div>
</section>

<!-- 🍞 PRODUCTOS -->
<?php if (isset($_GET['categoria'])): ?>
<section class="productos contenedor">
    <h2>Productos disponibles</h2>
    <div class="grid">
        <?php if ($productos && mysqli_num_rows($productos) > 0): ?>
            <?php while ($prod = mysqli_fetch_assoc($productos)): ?>
                <div class="producto">
                    <div class="imagen-container">
                        <?php
                        $imgPath = !empty($prod['imagen']) ? $prod['imagen'] : 'img/no_image.jpg';
                        if (!file_exists($imgPath) && file_exists('img/' . basename($imgPath))) {
                            $imgPath = 'img/' . basename($imgPath);
                        }
                        ?>
                        <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">

                        <div class="detalles">
                            <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                            <p>S/ <?php echo number_format($prod['precio'], 2); ?></p>
                            <button class="btn-ver"
                                data-nombre="<?php echo htmlspecialchars($prod['nombre']); ?>"
                                data-desc="<?php echo htmlspecialchars($prod['descripcion']); ?>"
                                data-precio="<?php echo number_format($prod['precio'],2); ?>">
                                Ver detalles
                            </button>
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                <input type="hidden" name="id_producto" value="<?php echo $prod['id']; ?>">
                                <button type="submit">Agregar al carrito 🛒</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">No hay productos en esta categoría.</p>
        <?php endif; ?>
    </div>
</section>
<?php else: ?>
<p style="text-align:center; margin-top:30px; font-size:18px; color:#8b5a2b;">
    👆 Selecciona una categoría para ver los productos disponibles.
</p>
<?php endif; ?>

<!-- 🛒 CARRITO -->
<?php if (!empty($_SESSION['carrito'])): ?>
<div id="carrito" class="carrito">
    <h2>🛍️ Tu carrito</h2>
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
            <td><img src="<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>"></td>
            <td><?php echo htmlspecialchars($item['nombre']); ?></td>
            <td>S/ <?php echo number_format($item['precio'], 2); ?></td>
            <td><?php echo intval($item['cantidad']); ?></td>
            <td>S/ <?php echo number_format($subtotal, 2); ?></td>
            <td><a href="?eliminar=<?php echo $id; ?>"><button>Eliminar</button></a></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p class="total">Total: S/ <?php echo number_format($total, 2); ?></p>

    <div class="pago">
        <h3>Método de pago</h3>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
            <label><input type="radio" name="metodo_pago" value="yape" required> 📱 Yape</label><br>
            <label><input type="radio" name="metodo_pago" value="transferencia" required> 💳 Transferencia bancaria</label><br><br>
            <button type="submit" name="confirmar_pago">Confirmar pedido</button>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if (isset($mensaje)) echo "<div class='mensaje'>$mensaje</div>"; ?>

<!-- Script para mostrar detalles encima de la imagen -->
<script>
document.querySelectorAll('.btn-ver').forEach(btn => {
  btn.addEventListener('click', e => {
    e.preventDefault();
    const producto = btn.closest('.producto');
    if (producto.querySelector('.overlay-detalles')) {
      producto.querySelector('.overlay-detalles').remove();
      return;
    }

    const overlay = document.createElement('div');
    overlay.className = 'overlay-detalles';
    overlay.innerHTML = `
      <h3>${btn.dataset.nombre}</h3>
      <p>${btn.dataset.desc || 'Sin descripción disponible'}</p>
      <p><b>Precio:</b> S/ ${btn.dataset.precio}</p>
      <button>Cerrar</button>
    `;
    producto.appendChild(overlay);
    overlay.querySelector('button').onclick = () => overlay.remove();
  });
});
</script>

</body>
</html>
