<?php
$host = "localhost";
$user = "root";
$pass = ""; // o la contraseña de tu MySQL si tiene una
$db = "panaderio_db"; // 👈 debe coincidir exactamente con el nombre de tu BD

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
} else {
    // echo "✅ Conexión exitosa"; // (solo para probar)
}
?>
