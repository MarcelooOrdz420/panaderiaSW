<?php
// Primero intentamos obtener los valores desde variables de entorno (Render)
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: 'panaderio_db';

// Intentar conectar con MySQL
$conexion = mysqli_connect($host, $user, $pass, $db);

// Verificar conexión
if (!$conexion) {
    die("❌ Error de conexión: " . mysqli_connect_error());
} else {
    // echo "✅ Conexión exitosa"; // (solo para pruebas)
}
?>

