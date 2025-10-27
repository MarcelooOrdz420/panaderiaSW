<?php
include("conexion.php");

$mensaje = "";
$tipo = ""; // 'exito' o 'error'

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar y limpiar
    $nombre = mysqli_real_escape_string($conexion, trim($_POST['nombre'] ?? ''));
    $correo = mysqli_real_escape_string($conexion, trim($_POST['correo'] ?? ''));
    $password_raw = $_POST['password'] ?? '';

    // Validación básica
    if ($nombre === '' || $correo === '' || $password_raw === '') {
        $mensaje = "Por favor completa todos los campos.";
        $tipo = "error";
    } else {
        // Hashear la contraseña correctamente
        $hash = password_hash($password_raw, PASSWORD_BCRYPT);

        // Verificar si ya existe el correo o nombre
        $verificar = mysqli_query($conexion, "SELECT id FROM usuarios WHERE correo = '$correo' OR nombre = '$nombre' LIMIT 1");
        if ($verificar && mysqli_num_rows($verificar) > 0) {
            $mensaje = "El usuario o correo ya existe.";
            $tipo = "error";
        } else {
            // Definir rol automáticamente (si contiene "admin" en el correo)
            $rol = (strpos(strtolower($correo), 'admin') !== false) ? 'admin' : 'cliente';

            // Insertar en la tabla con la contraseña hasheada
            $sql = "INSERT INTO usuarios (nombre, correo, password, rol) 
                    VALUES ('$nombre', '$correo', '$hash', '$rol')";

            if (mysqli_query($conexion, $sql)) {
                $mensaje = "✅ Registro exitoso. Ahora puede <a href='login.php'>iniciar sesión</a>.";
                $tipo = "exito";
            } else {
                $mensaje = "❌ Error al registrar: " . mysqli_error($conexion);
                $tipo = "error";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse - Panadería Delicias</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="login-container">
        <!-- LOGO DE LA PANADERÍA -->
        <img src="img/logo.jpg" alt="Logo Panadería" class="logo">

        <h2>Registrarse</h2>

        <?php if ($mensaje !== ""): ?>
            <p class="<?= $tipo === 'exito' ? 'exito' : 'error' ?>"><?= $mensaje ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="nombre" placeholder="Nombre de usuario" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Registrarse</button>
        </form>

        <p class="register-text">
            ¿Ya tienes cuenta?
            <a href="login.php">Inicia sesión aquí</a>
        </p>

        <a href="index.php" class="back-btn">← Volver</a>
    </div>
</body>
</html>
