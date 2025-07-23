<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = htmlspecialchars($_POST['titulo'], ENT_QUOTES, 'UTF-8');
    $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES, 'UTF-8');
    $id_usuario = $_SESSION['user_id'];
    $imagen = null;
    $archivo_ejercicios = null;

    // Subir imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed) && $_FILES['imagen']['size'] <= 5 * 1024 * 1024) {
            $filename = uniqid('blog_img_') . '.' . $ext;
            $destino = '../uploads/' . $filename;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                $imagen = '/uploads/' . $filename;
            }
        } else {
            $message = "Imagen no válida.";
        }
    }

    // Subir PDF de ejercicios
    if (isset($_FILES['archivo_ejercicios']) && $_FILES['archivo_ejercicios']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['archivo_ejercicios']['name'], PATHINFO_EXTENSION));
        if ($ext === 'pdf' && $_FILES['archivo_ejercicios']['size'] <= 10 * 1024 * 1024) {
            $filename = uniqid('ejercicio_') . '.pdf';
            $destino = '../uploads/' . $filename;
            if (move_uploaded_file($_FILES['archivo_ejercicios']['tmp_name'], $destino)) {
                $archivo_ejercicios = '/uploads/' . $filename;
            }
        } else {
            $message = "Solo se permiten PDFs.";
        }
    }

    if (empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO blog (id_usuario, titulo, descripcion, imagen, archivo_ejercicios) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$id_usuario, $titulo, $descripcion, $imagen, $archivo_ejercicios])) {
            $message = "✅ Publicación creada con éxito.";
        } else {
            $message = "Error al publicar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Crear Publicación - FitFlow</title>
  <style>
    body { font-family: 'Poppins', sans-serif; padding: 40px; }
    .form { max-width: 600px; margin: 0 auto; }
    input, textarea, button { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; }
    button { background: #4CAF50; color: white; border: none; cursor: pointer; }
    .error { color: #e74c3c; }
  </style>
</head>
<body>
  <div class="form">
    <h1>Crear Publicación</h1>
    <?php if ($message): ?>
      <p class="error" style="color: <?= strpos($message, 'éxito') ? 'green' : 'red' ?>"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <input type="text" name="titulo" placeholder="Título" required />
      <textarea name="descripcion" rows="5" placeholder="Describe tu avance..." required></textarea>
      <input type="file" name="imagen" accept="image/*" placeholder="Imagen (opcional)" />
      <input type="file" name="archivo_ejercicios" accept=".pdf" placeholder="Rutina en PDF (opcional)" />
      <button type="submit">Publicar</button>
    </form>
    <p><a href="index.php">← Volver al bloc</a></p>
  </div>
</body>
</html>