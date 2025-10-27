<?php
session_start();
include("../conexion.php");

// 🔒 Seguridad
if (!isset($_SESSION["id_usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

// Detectar la página actual
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Administrativo - Panadería</title>
<link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<header>
    <h1>Panel Administrativo</h1>
    <nav>
        <?php if ($pagina_actual !== 'dashboard.php'): ?>
            <a href="dashboard.php">🏠 Inicio</a>
        <?php endif; ?>
        <a href="categorias.php">Categorías</a>
        <a href="productos_admin.php">Productos</a>
        <a href="pedidos_admin.php">Pedidos</a>
        <a href="graficos.php">Reportes</a>
        <a href="../logout.php" class="logout">Salir</a>
    </nav>
</header>

<main class="contenido">
    <h2>Bienvenido al panel administrativo</h2>
    <p>Desde aquí podrás gestionar productos, pedidos y ver las ventas del negocio.</p>

    <h3>👋 Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h3>
</main>

<footer>
    <p>© 2025 Panadería Delicias</p>
</footer>
</body>
</html>
