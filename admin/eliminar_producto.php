<?php
include("../conexion.php");

$id = $_GET["id"] ?? 0;
$id = intval($id);

if ($id <= 0) {
    echo "ID no válido.";
    exit();
}

$result = mysqli_query($conexion, "SELECT imagen FROM productos WHERE id = $id");
if ($row = mysqli_fetch_assoc($result)) {
    $ruta = "../" . $row["imagen"];
    if (file_exists($ruta)) unlink($ruta);
}

mysqli_query($conexion, "DELETE FROM productos WHERE id = $id");

header("Location: productos_admin.php");
exit();
