<?php
session_start();
include("../conexion.php");

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$categoria_id = isset($_GET["categoria"]) ? intval($_GET["categoria"]) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - Panadería Delicias</title>
    <!-- 🔗 Asegúrate que el nombre del CSS coincida exactamente con el archivo -->
    <link rel="stylesheet" href="css/producto.css">
</head>
<body>
<header>
    <h1>Panadería Delicias</h1>
    <nav>
        <a href="carrito.php">🛒 Carrito</a>
        <a href="logout.php">Cerrar sesión</a>
    </nav>
</header>

<section class="productos">
    <h2 class="titulo">Categorías</h2>
    <div class="categorias">
        <?php
        $cat = mysqli_query($conexion, "SELECT * FROM categorias");
        while ($row = mysqli_fetch_assoc($cat)) {
            echo "<a href='?categoria={$row['id']}' class='btn'>{$row['nombre']}</a>";
        }
        ?>
    </div>

    <h2 class="titulo">Productos</h2>
    <div class="grid">
        <?php
        $query = $categoria_id > 0 ?
            "SELECT * FROM productos WHERE categoria_id=$categoria_id" :
            "SELECT * FROM productos";
        $res = mysqli_query($conexion, $query);

        while ($row = mysqli_fetch_assoc($res)) {
            $imagen = !empty($row['imagen']) ? "img/productos/{$row['imagen']}" : "img/no-image.png";
            echo "
            <div class='producto'>
                <img src='{$imagen}' alt='{$row['nombre']}'>
                <div class='overlay'>
                    <h3>{$row['nombre']}</h3>
                    <p class='precio'>S/ {$row['precio']}</p>
                    <p class='descripcion'>{$row['descripcion']}</p>
                    <div class='botones'>
                        <form method='POST' action='carrito.php'>
                            <input type='hidden' name='id_producto' value='{$row['id']}'>
                            <button type='submit' name='agregar'>Agregar</button>
                        </form>
                        <a href='detalle_producto.php?id={$row['id']}' class='ver'>Ver detalles</a>
                    </div>
                </div>
            </div>
            ";
        }
        ?>
    </div>
</section>
</body>
</html>
