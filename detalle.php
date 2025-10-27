<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: comprar.php');
    exit;
}
$nombre = $_POST['nombre'] ?? 'Producto';
$precio = isset($_POST['precio']) ? number_format((float)$_POST['precio'], 2) : '0.00';
$imagen = $_POST['imagen'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Detalle - <?php echo htmlspecialchars($nombre); ?></title>
  <link rel="stylesheet" href="css/comprar.css">
  <style>
    .detalle-contenedor { max-width:520px; margin:40px auto; background:#fff; padding:20px; border-radius:12px; text-align:center; box-shadow:0 6px 18px rgba(0,0,0,0.08); }
    .detalle-contenedor img{ max-width:90%; height:auto; border-radius:10px; }
    .detalle-precio{ margin-top:12px; font-size:20px; color:var(--naranja-oscuro); font-weight:700; }
    .volver{ margin-top:16px; display:inline-block; padding:8px 12px; background:var(--naranja); color:#fff; border-radius:8px; text-decoration:none; }
  </style>
</head>
<body>
  <div class="detalle-contenedor">
    <?php if ($imagen): ?>
      <img src="img/<?php echo htmlspecialchars($imagen, ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($nombre); ?>">
    <?php endif; ?>
    <h2><?php echo htmlspecialchars($nombre); ?></h2>
    <div class="detalle-precio">S/ <?php echo $precio; ?></div>
    <p style="margin-top:10px;">Aquí puedes mostrar más información del producto.</p>
    <a class="volver" href="comprar.php<?php echo isset($_SERVER['HTTP_REFERER']) ? '' : ''; ?>">Volver</a>
  </div>
</body>
</html>