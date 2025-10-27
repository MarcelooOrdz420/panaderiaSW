<?php
include("../conexion.php");

// --- AGREGAR o ACTUALIZAR CATEGORÍA ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombre"])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST["nombre"]);
    $descripcion = mysqli_real_escape_string($conexion, $_POST["descripcion"]);

    // Si existe un ID, se actualiza
    if (!empty($_POST["id"])) {
        $id = intval($_POST["id"]);
        $sql = "UPDATE categorias SET nombre='$nombre', descripcion='$descripcion' WHERE id=$id";
    } else {
        // Si no, se inserta nueva
        $sql = "INSERT INTO categorias (nombre, descripcion) VALUES ('$nombre', '$descripcion')";
    }

    mysqli_query($conexion, $sql);
    header("Location: categorias.php");
    exit;
}

// --- ELIMINAR CATEGORÍA ---
if (isset($_GET["eliminar"])) {
    $id = intval($_GET["eliminar"]);
    mysqli_query($conexion, "DELETE FROM categorias WHERE id=$id");
    header("Location: categorias.php");
    exit;
}

// --- EDITAR CATEGORÍA (CARGAR DATOS EN FORMULARIO) ---
$categoria_editar = null;
if (isset($_GET["editar"])) {
    $id = intval($_GET["editar"]);
    $resultado = mysqli_query($conexion, "SELECT * FROM categorias WHERE id=$id");
    $categoria_editar = mysqli_fetch_assoc($resultado);
}

// --- OBTENER TODAS LAS CATEGORÍAS ---
$categorias = mysqli_query($conexion, "SELECT * FROM categorias");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestión de Categorías</title>
<link rel="stylesheet" href="../css/categorias.css">
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="btn-volver">← Volver al Panel</a>

        <h2><?= $categoria_editar ? "Editar Categoría" : "Gestión de Categorías" ?></h2>

        <form method="POST">
            <?php if ($categoria_editar): ?>
                <input type="hidden" name="id" value="<?= $categoria_editar['id'] ?>">
            <?php endif; ?>

            <input type="text" name="nombre" placeholder="Nombre de la categoría" 
                   value="<?= $categoria_editar ? htmlspecialchars($categoria_editar['nombre']) : '' ?>" required>

            <textarea name="descripcion" placeholder="Descripción"><?= $categoria_editar ? htmlspecialchars($categoria_editar['descripcion']) : '' ?></textarea>

            <button type="submit"><?= $categoria_editar ? "Actualizar Categoría" : "Agregar Categoría" ?></button>

            <?php if ($categoria_editar): ?>
                <a href="categorias.php" class="btn-cancelar">Cancelar</a>
            <?php endif; ?>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($c = mysqli_fetch_assoc($categorias)): ?>
                <tr>
                    <td><?= $c["id"] ?></td>
                    <td><?= htmlspecialchars($c["nombre"]) ?></td>
                    <td><?= htmlspecialchars($c["descripcion"]) ?></td>
                    <td>
                        <a href="?editar=<?= $c["id"] ?>" class="btn-editar">Editar</a>
                        <a href="?eliminar=<?= $c["id"] ?>" onclick="return confirm('¿Eliminar esta categoría?')" class="btn-eliminar">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
