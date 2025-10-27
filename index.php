<?php 
include("conexion.php"); 
session_start(); // 🔹 Necesario para verificar si hay sesión activa
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panadería Delicias</title>
    <link rel="stylesheet" href="css/estilos.css">
    <script src="js/funciones.js" defer></script>
</head>
<body>
<div class="contenedor">
    <header>
        <div style="display:flex; align-items:center; gap:15px;">
            <img src="img/logo.jpg" alt="Logo Panadería" 
                 style="width:90px; height:auto; border-radius:8px; 
                        box-shadow:0 2px 6px rgba(0,0,0,0.3); margin:8px;
                        transition:transform 0.3s ease, box-shadow 0.3s ease;"
                 onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.4)'"
                 onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 6px rgba(0,0,0,0.3)'">
            
            <h1>Panadería - Pastelería "Delicias del Centro"</h1>
        </div>

        <nav>
            <?php if (!isset($_SESSION['usuario'])): ?>
                <a href="login.php" style="color:white; text-decoration:none; font-weight:bold;">Iniciar Sesión</a>
            <?php else: ?>
                <span>Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong></span>
                <a href="logout.php" style="color:white; text-decoration:none; font-weight:bold; margin-left:15px;">Cerrar Sesión</a>
            <?php endif; ?>
        </nav>
    </header>

    <!-- SLIDER -->
    <section class="slider">
        <button class="prev">&#10094;</button>
        <div class="slides">
            <div class="slide"><img src="img/kekon.jpg" alt=""></div>
            <div class="slide"><img src="img/chancay.png" alt=""></div>
            <div class="slide"><img src="img/prepizza.png" alt=""></div>
            <div class="slide"><img src="img/panchalaco .png" alt=""></div>
            <div class="slide"><img src="img/pionono.jpg" alt=""></div>
            <div class="slide"><img src="img/churros.png" alt=""></div>
        </div>
        <button class="next">&#10095;</button>
    </section>

    <?php if (isset($_SESSION['usuario'])): ?>
    <!-- PRODUCTOS -->
    <section class="productos">
        <div class="grid">
        <?php
        $query = mysqli_query($conexion, "SELECT * FROM productos LIMIT 6");
        while ($row = mysqli_fetch_assoc($query)) {
            $imagenRuta = '';
            if (!empty($row['imagen']) && file_exists($row['imagen'])) {
                $imagenRuta = $row['imagen'];
            } elseif (!empty($row['imagen']) && file_exists('img/' . basename($row['imagen']))) {
                $imagenRuta = 'img/' . basename($row['imagen']);
            } else {
                $imagenRuta = 'img/no-image.png';
            }

            echo "
            <div class='producto'>
                <img src='{$imagenRuta}' alt='{$row['nombre']}'>
                <h3>{$row['nombre']}</h3>
                <p>S/ {$row['precio']}</p>
            </div>
            ";
        }
        ?>
        </div>
    </section>
    <?php endif; ?>

    <footer>
        <div class="contact-links">
            <a href='https://maps.app.goo.gl/PBfuwyYAqXvmbmSS8' target='_blank' class='icon-link maps'>
                <img src='https://cdn-icons-png.flaticon.com/512/2991/2991231.png' alt='Ubicación'>
            </a>
            <a href='https://wa.me/51964527852' target='_blank' class='icon-link whatsapp'>
                <img src='https://cdn-icons-png.flaticon.com/512/733/733585.png' alt='WhatsApp'>
            </a>
            <a href='https://web.facebook.com/deliciashuancayoperu?locale=es_LA' target='_blank' class='icon-link facebook'>
                <img src='https://cdn-icons-png.flaticon.com/512/733/733547.png' alt='Facebook'>
            </a>
        </div>

        <style>
            .contact-links {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 15px;
                flex-wrap: wrap;
                margin: 15px 0;
            }
            .icon-link img {
                width: 24px;
                height: 24px;
                border-radius: 50%;
                background-color: white;
                padding: 4px;
                box-shadow: 0 1px 4px rgba(0, 0, 0, 0.25);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .icon-link img:hover {
                transform: scale(1.1);
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            }
            .icon-link.maps img { background-color: #e53935; }
            .icon-link.whatsapp img { background-color: #25d366; }
            .icon-link.facebook img { background-color: #1877f2; }
            @media (max-width: 768px) {
                .icon-link img { width: 20px; height: 20px; padding: 3px; }
                .contact-links { gap: 12px; }
            }
            @media (max-width: 480px) {
                .icon-link img { width: 18px; height: 18px; padding: 2px; }
                .contact-links { gap: 10px; }
            }
        </style>
    </footer>
</div>
</body>
</html>
