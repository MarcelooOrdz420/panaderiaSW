<?php
include("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);

    $query = "UPDATE pedidos SET estado = '$estado' WHERE id = $id";

    if (mysqli_query($conexion, $query)) {
        header("Location: pedidos_admin.php");
        exit();
    } else {
        echo "Error al actualizar el estado: " . mysqli_error($conexion);
    }
} else {
    echo "Acceso no permitido.";
}
?>
