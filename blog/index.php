<?php
session_start();
require_once '../config/database.php';

// Obtener publicaciones con datos del usuario
$stmt = $pdo->prepare("
    SELECT b.*, u.nombre, u.apellido, u.foto 
    FROM blog b 
    JOIN usuarios u ON b.id_usuario = u.id 
    WHERE b.estado = 'publicado'
    ORDER BY b.fecha DESC
");
$stmt->execute();
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Bloc de Miembros - FitFlow</title>
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
      max-width: 800px; margin: 40px auto; padding: 0 20px;
    }
    .post {
      background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    .post-header {
      display: flex; align-items: center; margin-bottom: 15px;
    }
    .post-pic {
      width: 50px; height: 50px; border-radius: 50%; background: #4CAF50; color: white; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-weight: bold;
    }
    .post-content img {
      max-width: 100%; border-radius: 8px; margin: 10px 0;
    }
    .btn {
      background: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 6px; text-decoration: none; display: inline-block; margin: 10px 0;
    }
    footer {
      text-align: center; padding: 30px 20px; font-size: 0.9em; color: #777; background: #f0f2f5; margin-top: 50px;
    }
  </style>
</head>
<body>
  <nav>
    <div class="container">
      <a href="../index.php" style="font-weight: bold; font-size: 1.2em;">FitFlow</a>
      <div>
        <a href="crear.php">+ Publicar</a>
        <a href="../auth/logout.php">Cerrar sesión</a>
      </div>
    </div>
  </nav>

  <div class="container">
    <h1>💬 Bloc de Miembros</h1>
    <p>Comparte tu progreso y motiva a otros.</p>

    <?php if (empty($posts)): ?>
      <p>No hay publicaciones aún. ¡Sé el primero en compartir!</p>
    <?php else: ?>
      <?php foreach ($posts as $p): ?>
        <div class="post">
          <div class="post-header">
            <div class="post-pic">
              <?= strtoupper(substr($p['nombre'], 0, 1)) ?>
            </div>
            <div>
              <strong><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?></strong><br>
              <small><?= $p['fecha'] ?></small>
            </div>
          </div>
          <h3><?= htmlspecialchars($p['titulo']) ?></h3>
          <div class="post-content">
            <p><?= nl2br(htmlspecialchars($p['descripcion'])) ?></p>
            <?php if ($p['imagen']): ?>
              <img src="<?= htmlspecialchars($p['imagen']) ?>" alt="Imagen">
            <?php endif; ?>
            <?php if ($p['archivo_ejercicios']): ?>
              <p><a href="<?= htmlspecialchars($p['archivo_ejercicios']) ?>" target="_blank">Ver rutina (PDF)</a></p>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <a href="crear.php" class="btn">Crear nueva publicación</a>
  </div>

  <footer>
    &copy; 2025 FitFlow. Todos los derechos reservados.
  </footer>
</body>
</html>