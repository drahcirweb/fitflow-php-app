<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FitFlow</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    nav {
      background: #2c3e50; color: white; padding: 15px 0; margin-bottom: 40px;
    }
    nav .container {
      max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center;
    }
    nav a {
      color: white; text-decoration: none; margin-left: 20px; font-weight: 500;
    }
    nav a:hover { color: #4CAF50; }
  </style>
</head>
<body>
  <nav>
    <div class="container">
      <a href="../index.php" style="font-weight: bold; font-size: 1.2em;">FitFlow</a>
      <div>
        <a href="progreso.php">Progreso</a>
        <a href="index.php">Perfil</a>
        <a href="../auth/logout.php">Cerrar sesión</a>
      </div>
    </div>
  </nav>