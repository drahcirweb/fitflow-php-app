<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES, 'UTF-8');
    $id_usuario = $_SESSION['user_id'];
    $archivo = null;

    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $message = "Solo se permiten imágenes.";
        } elseif ($_FILES['archivo']['size'] > 5 * 1024 * 1024) {
            $message = "Máx. 5MB.";
        } else {
            $filename = uniqid('progreso_') . '.' . $ext;
            $destino = '../uploads/' . $filename;
            if (move_uploaded_file($_FILES['archivo']['tmp_name'], $destino)) {
                $archivo = '/uploads/' . $filename;
            }
        }
    }

    if (empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO progreso (id_usuario, archivo, descripcion) VALUES (?, ?, ?)");
        if ($stmt->execute([$id_usuario, $archivo, $descripcion])) {
            $message = "✅ Progreso guardado.";
        } else {
            $message = "Error al guardar.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Progreso - FitFlow</title>
  <style>
    body { font-family: 'Poppins', sans-serif; padding: 40px; }
    .form { max-width: 600px; margin: 0 auto; }
    input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; }
    .btn { background: #4CAF50; color: white; border: none; padding: 12px; width: 100%; border-radius: 6px; }
  </style>
</head>
<body>
  <div class="form">
    <h1>Registrar Progreso</h1>
    <?php if ($message): ?>
      <p style="color: <?= strpos($message, 'Error') === false ? 'green' : 'red' ?>"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <textarea name="descripcion" rows="4" placeholder="Describe tu progreso..." required></textarea>
      <input type="file" name="archivo" accept="image/*" />
      <button type="submit" class="btn">Guardar Progreso</button>
    </form>
    <p><a href="../index.php">← Volver al inicio</a></p>
  </div>
</body>
</html>