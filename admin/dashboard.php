<?php
session_start();
require_once '../config/database.php';

// Verificar si es admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Obtener usuarios
$stmt = $pdo->query("
    SELECT id, nombre, apellido, correo, sexo, fecha_registro, 
           fecha_suscripcion, fecha_termino_suscripcion, tipo_usuario 
    FROM usuarios 
    ORDER BY fecha_registro DESC
");
$usuarios = $stmt->fetchAll();

// Obtener últimos pagos
$stmt = $pdo->query("
    SELECT p.*, u.nombre, u.apellido 
    FROM pagos p 
    JOIN usuarios u ON p.id_usuario = u.id 
    ORDER BY p.fecha_pago DESC 
    LIMIT 10
");
$pagos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Admin - FitFlow</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { background: #f0f2f5; color: #333; }
    nav {
      background: #2c3e50; color: white; padding: 15px 0; display: flex; justify-content: space-between; align-items: center;
    }
    nav .container {
      max-width: 1200px; margin: 0 auto; padding: 0 20px;
    }
    nav a {
      color: white; text-decoration: none; margin-left: 20px; font-weight: 500;
    }
    .container {
      max-width: 1200px; margin: 40px auto; padding: 0 20px;
    }
    h1, h2 { color: #2c3e50; margin-bottom: 20px; }
    table {
      width: 100%; background: white; border-collapse: collapse; box-shadow: 0 3px 10px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden; margin: 20px 0;
    }
    th, td {
      padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee;
    }
    th {
      background: #2c3e50; color: white; font-weight: 600;
    }
    tr:hover { background: #f8f9fa; }
    .btn {
      padding: 6px 10px; border: none; border-radius: 5px; font-size: 0.9em; cursor: pointer;
    }
    .edit { background: #4CAF50; color: white; }
    .delete { background: #e74c3c; color: white; }
    footer {
      text-align: center; padding: 30px 20px; font-size: 0.9em; color: #777; background: #f0f2f5; margin-top: 50px;
    }
  </style>
</head>
<body>
  <nav>
    <div class="container">
      <a href="../index.php" style="font-weight: bold; font-size: 1.2em;">FitFlow Admin</a>
      <div>
        <span style="color: #4CAF50;">Hola, <?= htmlspecialchars($_SESSION['user_name']) ?> (Admin)</span> |
        <a href="../auth/logout.php">Cerrar sesión</a>
      </div>
    </div>
  </nav>

  <div class="container">
    <h1>Dashboard de Administrador</h1>

    <!-- Usuarios -->
    <h2>👥 Usuarios Registrados</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Sexo</th>
          <th>Registro</th>
          <th>Suscripción</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($usuarios as $u): ?>
          <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['nombre'] . ' ' . $u['apellido']) ?></td>
            <td><?= htmlspecialchars($u['correo']) ?></td>
            <td><?= $u['sexo'] ?></td>
            <td><?= $u['fecha_registro'] ?></td>
            <td><?= $u['fecha_suscripcion'] ?: 'No' ?></td>
            <td><?= $u['fecha_termino_suscripcion'] && date('Y-m-d') <= $u['fecha_termino_suscripcion'] ? 'Activo' : 'Inactivo' ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Pagos Recientes -->
    <h2>💳 Últimos Pagos</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Usuario</th>
          <th>Plan</th>
          <th>Valor</th>
          <th>Fecha</th>
          <th>Método</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pagos as $p): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?></td>
            <td><?= $p['plan'] ?></td>
            <td>$<?= number_format($p['valor'], 2) ?></td>
            <td><?= $p['fecha_pago'] ?></td>
            <td><?= $p['medio_pago'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <footer>
    &copy; 2025 FitFlow. Todos los derechos reservados.
  </footer>
</body>
</html>