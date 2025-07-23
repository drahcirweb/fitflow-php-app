<?php
session_start();
require_once '../config/database.php';

// Verificar login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Obtener datos del usuario
$stmt = $pdo->prepare("SELECT nombre, apellido, correo, foto, fecha_suscripcion, fecha_termino_suscripcion FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    die("Usuario no encontrado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Mi Perfil - FitFlow</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { background: #f0f2f5; color: #333; }
    .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
    .profile-card {
      background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      display: flex; align-items: center; gap: 30px;
    }
    .profile-pic {
      width: 100px; height: 100px; border-radius: 50%; background: #4CAF50; color: white; font-size: 2.5em; font-weight: bold;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .profile-info h2 { color: #2c3e50; margin-bottom: 10px; }
    .profile-info p { margin: 5px 0; color: #555; }
    .btn {
      display: inline-block; background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; margin-top: 15px; font-size: 0.9em;
    }
    .btn.red { background: #e74c3c; }
  </style>
</head>
<body>
  <?php include '../includes/header.php'; ?>
  <div class="container">
    <div class="profile-card">
      <div class="profile-pic">
        <?= strtoupper(substr($user['nombre'], 0, 1)) ?>
      </div>
      <div class="profile-info">
        <h2>Hola, <?= htmlspecialchars($user['nombre']) ?>!</h2>
        <p><strong>Correo:</strong> <?= htmlspecialchars($user['correo']) ?></p>
        <p><strong>Suscripción:</strong> 
          <?= $user['fecha_suscripcion'] ? $user['fecha_suscripcion'] . ' → ' . $user['fecha_termino_suscripcion'] : 'No activa' ?>
        </p>
        <p>
          <a href="progreso.php" class="btn">Registrar Progreso</a>
          <a href="../auth/logout.php" class="btn red">Cerrar sesión</a>
        </p>
      </div>
    </div>
  </div>
  <?php include '../includes/footer.php'; ?>
</body>
</html>