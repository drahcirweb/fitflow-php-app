<?php
require_once '../config/database.php';
$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $apellido = filter_var($_POST['apellido'], FILTER_SANITIZE_STRING);
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $sexo = in_array($_POST['sexo'], ['Masculino','Femenino','Otro']) ? $_POST['sexo'] : null;
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo inválido.";
    } elseif (strlen($password) < 6) {
        $error = "Contraseña mínima de 6 caracteres.";
    } elseif ($password !== $confirm) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
        $apellido = htmlspecialchars($apellido, ENT_QUOTES, 'UTF-8');

        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        if ($stmt->rowCount() > 0) {
            $error = "Este correo ya está registrado.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, sexo, correo, pass) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$nombre, $apellido, $sexo, $correo, $hashed])) {
                $success = "✅ Registro exitoso. <a href='login.php'>Inicia sesión</a>";
            } else {
                $error = "Error al registrar.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Registro - FitFlow</title>
  <style>
    body { font-family: 'Poppins', sans-serif; text-align: center; padding: 50px; }
    .form { max-width: 400px; margin: 0 auto; }
    input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; }
    .btn { background: #4CAF50; color: white; border: none; padding: 12px; width: 100%; border-radius: 6px; cursor: pointer; }
    .error { color: #e74c3c; margin: 10px 0; }
    .success { color: #27ae60; margin: 10px 0; }
  </style>
</head>
<body>
  <div class="form">
    <h2>Crear Cuenta</h2>
    <?php if ($error): ?>
      <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
      <p class="success"><?= $success ?></p>
    <?php else: ?>
      <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required />
        <input type="text" name="apellido" placeholder="Apellido" required />
        <select name="sexo" required>
          <option value="">Sexo</option>
          <option value="Masculino">Masculino</option>
          <option value="Femenino">Femenino</option>
          <option value="Otro">Otro</option>
        </select>
        <input type="email" name="correo" placeholder="Correo" required />
        <input type="password" name="password" placeholder="Contraseña" required />
        <input type="password" name="confirm_password" placeholder="Repetir" required />
        <button type="submit" class="btn">REGISTRARSE</button>
      </form>
      <p><a href="login.php">¿Ya tienes cuenta? Inicia sesión</a></p>
    <?php endif; ?>
  </div>
</body>
</html>