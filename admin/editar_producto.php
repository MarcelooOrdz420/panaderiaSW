<?php
include("../conexion.php");
$id = $_GET["id"] ?? 0;
$id = intval($id);

if ($id <= 0) {
    echo "ID no válido.";
    exit();
}

$producto = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT * FROM productos WHERE id = $id"));

if (!$producto) {
    echo "Producto no encontrado.";
    exit();
}

$mensaje = "";

// Actualizar producto
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = mysqli_real_escape_string($conexion, $_POST["nombre"]);
    $descripcion = mysqli_real_escape_string($conexion, $_POST["descripcion"]);
    $precio = floatval($_POST["precio"]);
    $categoria_id = intval($_POST["categoria_id"]);
    $imagen = $producto["imagen"];

    if (!empty($_FILES["imagen"]["name"])) {
        $tmp_name = $_FILES["imagen"]["tmp_name"];
        $ext = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
        $nuevo_nombre = uniqid("prod_") . "." . $ext;
        $ruta_destino = "../img/" . $nuevo_nombre;

        if (move_uploaded_file($tmp_name, $ruta_destino)) {
            $imagen = "img/" . $nuevo_nombre;
        }
    }

    $sql = "UPDATE productos SET 
            nombre='$nombre',
            descripcion='$descripcion',
            precio=$precio,
            categoria_id=$categoria_id,
            imagen='$imagen'
            WHERE id=$id";

    if (mysqli_query($conexion, $sql)) {
        $mensaje = "✔ Producto actualizado correctamente.";
        $producto = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT * FROM productos WHERE id = $id"));
    } else {
        $mensaje = "✖ Error al actualizar: " . mysqli_error($conexion);
    }
}

$categorias = mysqli_query($conexion, "SELECT * FROM categorias ORDER BY nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Editar Producto</title>
<style>
body { font-family: Arial; margin: 0; padding: 0; background: #fafafa; }
.container { padding: 20px; max-width: 600px; margin: auto; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,.1); }
img { border-radius: 10px; margin-bottom: 10px; }
label { font-weight: bold; }
input, select, textarea { width: 100%; margin-bottom: 10px; padding: 8px; border: 1px solid #ccc; border-radius: 5px; }
button { background: #f5b041; color: #fff; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
.msg { background: #eafaf1; padding: 10px; border-left: 4px solid #27ae60; margin-bottom: 15px; }
a { text-decoration: none; color: #f5b041; font-weight: bold; }
</style>
</head>
<body>
<div class="container">
  <a href="productos_admin.php">← Volver</a>
  <h2>Editar Producto</h2>
  <?php if ($mensaje): ?><div class="msg"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>

    <label>Descripción:</label>
    <textarea name="descripcion"><?= htmlspecialchars($producto['descripcion']) ?></textarea>

    <label>Precio (S/):</label>
    <input type="number" name="precio" value="<?= htmlspecialchars($producto['precio']) ?>" step="0.01" required>

    <label>Categoría:</label>
    <select name="categoria_id" required>
      <?php while ($c = mysqli_fetch_assoc($categorias)): ?>
        <option value="<?= $c['id'] ?>" <?= $c['id'] == $producto['categoria_id'] ? "selected" : "" ?>>
          <?= htmlspecialchars($c['nombre']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <label>Imagen actual:</label><br>
    <?php if (!empty($producto["imagen"]) && file_exists("../" . $producto["imagen"])): ?>
      <img src="../<?= htmlspecialchars($producto['imagen']) ?>" width="150">
    <?php else: ?>
      <p>No hay imagen</p>
    <?php endif; ?>

    <label>Cambiar imagen:</label>
    <input type="file" name="imagen" accept="image/*">

    <button type="submit">Actualizar Producto</button>
  </form>
</div>
</body>
</html>
