<?php 
session_start();
include("../conexion.php");

// 🔒 Seguridad: solo admin puede acceder
if (!isset($_SESSION["id_usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Mensaje de resultado
$mensaje = "";

// 📦 Registrar nuevo producto
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = mysqli_real_escape_string($conexion, $_POST["nombre"]);
    $descripcion = mysqli_real_escape_string($conexion, $_POST["descripcion"]);
    $precio = floatval($_POST["precio"]);
    $categoria_id = intval($_POST["categoria_id"]);
    $imagen_db = "";

    // 📸 Guardar imagen en /img/
    if (!empty($_FILES["imagen"]["name"])) {
        $uploads_dir = "../img";
        if (!is_dir($uploads_dir)) mkdir($uploads_dir, 0755, true);

        $tmp_name = $_FILES["imagen"]["tmp_name"];
        $nombre_original = basename($_FILES["imagen"]["name"]);
        $ext = pathinfo($nombre_original, PATHINFO_EXTENSION);
        $nuevo_nombre = uniqid("prod_") . "." . $ext;
        $ruta_guardada = $uploads_dir . "/" . $nuevo_nombre;

        if (move_uploaded_file($tmp_name, $ruta_guardada)) {
            $imagen_db = "img/" . $nuevo_nombre;
        }
    }

    if ($nombre && $precio > 0) {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen, categoria_id)
                VALUES ('$nombre', '$descripcion', $precio, '$imagen_db', $categoria_id)";
        if (mysqli_query($conexion, $sql)) {
            $mensaje = "✔ Producto agregado correctamente.";
        } else {
            $mensaje = "✖ Error: " . mysqli_error($conexion);
        }
    }
}

// 📂 Consultas
$categorias = mysqli_query($conexion, "SELECT * FROM categorias ORDER BY nombre ASC");
$productos = mysqli_query($conexion, "
    SELECT p.*, c.nombre AS categoria 
    FROM productos p 
    LEFT JOIN categorias c ON p.categoria_id = c.id 
    ORDER BY p.id DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Gestión de Productos - Panel Administrativo</title>
<link rel="stylesheet" href="../css/productos_admin.css">
<style>
body { font-family: Arial; margin: 0; padding: 0; background: #fafafa; }
.top-bar { background: #f5b041; color: #fff; padding: 15px; display: flex; justify-content: space-between; align-items: center; }
.top-bar a { color: #fff; text-decoration: none; font-weight: bold; }
.top-bar span { font-weight: bold; }
.main-area { padding: 20px; }
form { display: flex; flex-direction: column; gap: 10px; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,.1); }
input, select, textarea { padding: 8px; border: 1px solid #ccc; border-radius: 5px; }
button { background: #f5b041; color: #fff; padding: 10px; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
button:hover { background: #e67e22; }
table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 20px; }
table th, table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
table th { background: #f5b041; color: white; }
img { border-radius: 8px; }
.msg { background: #eafaf1; padding: 10px; border-left: 4px solid #27ae60; margin-bottom: 15px; }
</style>
</head>
<body>
<div class="top-bar">
  <span>👤 <?php echo htmlspecialchars($_SESSION['nombre']); ?> (Administrador)</span>
  <div>
    <a href="dashboard.php">🏠 Inicio</a> |
    <a href="../logout.php">🚪 Cerrar sesión</a>
  </div>
</div>

<div class="main-area">
  <h2>Gestión de Productos</h2>

  <?php if ($mensaje): ?>
      <div class="msg"><?= htmlspecialchars($mensaje) ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Precio (S/):</label>
    <input type="number" name="precio" step="0.01" required>

    <label>Descripción:</label>
    <textarea name="descripcion"></textarea>

    <label>Categoría:</label>
    <select name="categoria_id" required>
      <option value="">Seleccione</option>
      <?php while ($c = mysqli_fetch_assoc($categorias)): ?>
        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
      <?php endwhile; ?>
    </select>

    <label>Imagen:</label>
    <input type="file" name="imagen" accept="image/*">

    <button type="submit">Agregar Producto</button>
  </form>

  <table>
    <thead>
      <tr>
        <th>Imagen</th>
        <th>Nombre</th>
        <th>Categoría</th>
        <th>Precio</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($p = mysqli_fetch_assoc($productos)): ?>
      <tr>
        <td>
          <?php if (!empty($p['imagen']) && file_exists("../" . $p['imagen'])): ?>
            <img src="../<?= htmlspecialchars($p['imagen']) ?>" width="70">
          <?php else: ?>
            <span>No disponible</span>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($p['nombre']) ?></td>
        <td><?= htmlspecialchars($p['categoria'] ?? 'Sin categoría') ?></td>
        <td>S/ <?= number_format($p['precio'], 2) ?></td>
        <td>
          <a href="editar_producto.php?id=<?= $p['id'] ?>">✏️ Editar</a> |
          <a href="eliminar_producto.php?id=<?= $p['id'] ?>" onclick="return confirm('¿Eliminar producto?')">🗑 Eliminar</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
