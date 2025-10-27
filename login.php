<?php
session_start();
include("conexion.php");

// Aseguramos que no haya salida antes del header
ob_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);

    // Buscar usuario
    $query = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo = '$correo' LIMIT 1");

    if ($query && mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);

        // ✅ Verificar contraseña con hash
        if (password_verify($password, $user['password'])) {
            // Guardar sesión
            $_SESSION['id_usuario'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['rol'] = $user['rol'];

            // ✅ Redirección según rol
            if ($user['rol'] === 'admin') {
                header("Location: /panaderia/admin/dashboard.php");
                exit();
            } else {
                header("Location: /panaderia/comprar.php");
                exit();
            }
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Correo no encontrado.";
    }
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Panadería Delicias</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <!-- 🔹 Logo -->
        <img src="img/logo.jpg" alt="Logo Panadería" class="logo">


        <h2>Iniciar Sesión</h2>

        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST" action="">
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>

        <p class="register-text">
            ¿No tienes cuenta?
            <a href="register.php">Regístrate aquí</a>
        </p>

        <a href="index.php" class="back-btn">← Volver</a>
    </div>
</body>
</html>
