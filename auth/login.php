<?php
session_start();
require_once '../config/database.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo inválido.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['pass'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_role'] = $user['tipo_usuario'];
            header('Location: ../perfil/index.php');
            exit;
        } else {
            $error = "Correo o contraseña incorrectos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Login - FitFlow</title>
  <style>
    body { font-family: 'Poppins', sans-serif; text-align: center; padding: 50px; }
    .form { max-width: 400px; margin: 0 auto; }
    input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; }
    .btn { background: #4CAF50; color: white; border: none; padding: 12px; width: 100%; border-radius: 6px; cursor: pointer; }
    .error { color: #e74c3c; margin: 10px 0; }
  </style>
</head>
<body>
  <div class="form">
    <h2>Área de Miembros</h2>
    <?php if ($error): ?>
      <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="email" name="correo" placeholder="Tu correo" required />
      <input type="password" name="password" placeholder="Contraseña" required />
      <button type="submit" class="btn">INICIAR SESIÓN</button>
    </form>
    <p><a href="register.php">Crear cuenta</a> | <a href="#">¿Olvidaste tu contraseña?</a></p>
  </div>
</body>
</html>