<?php
session_start();
include 'db.php';

// Si no está logueado, redirigir
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pasarela de Pago</title>
<link rel="stylesheet" href="comprar.css">
<style>
.pasarela {
  max-width: 800px;
  margin: 40px auto;
  background: #fff;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  text-align: center;
}
.opciones-pago {
  display: flex;
  justify-content: center;
  gap: 30px;
  margin-top: 20px;
  flex-wrap: wrap;
}
.opcion {
  border: 2px solid #f0d7b2;
  border-radius: 12px;
  padding: 15px;
  width: 250px;
  background: #fffaf3;
  transition: transform 0.3s ease;
}
.opcion:hover {
  transform: scale(1.03);
  box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}
.opcion img {
  width: 100px;
  margin-bottom: 10px;
}
button {
  background: linear-gradient(90deg, var(--naranja), var(--naranja-oscuro));
  border: none;
  color: #fff;
  padding: 10px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  transition: 0.3s;
}
button:hover {
  background: linear-gradient(90deg, var(--naranja-oscuro), #a85414);
}
</style>
</head>
<body>

<div class="pasarela">
  <h2>💳 Selecciona tu método de pago</h2>
  <div class="opciones-pago">
    <div class="opcion">
      <img src="img/yape.png" alt="Yape">
      <h3>Yape</h3>
      <p>Escanea el código QR con tu app Yape.</p>
      <img src="img/qr-yape.png" alt="QR Yape" style="width:140px;border-radius:10px;">
      <p><strong>Celular:</strong> 987 654 321</p>
    </div>

    <div class="opcion">
      <img src="img/banco.png" alt="Transferencia">
      <h3>Transferencia Bancaria</h3>
      <p><strong>Banco:</strong> BCP</p>
      <p><strong>N° Cuenta:</strong> 123-45678900-0-99</p>
      <p><strong>Titular:</strong> Panadería Delicias</p>
    </div>
  </div>

  <form action="confirmar_pago.php" method="POST" style="margin-top:30px;">
    <button type="submit">Confirmar Pago</button>
  </form>
</div>

</body>
</html>
