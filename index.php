<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Colegio Color de Vida</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-box">
            <div class="login-logo">
                <div style="width:80px;height:80px;background:linear-gradient(135deg,#2c6e49,#4c956c);border-radius:50%;margin:0 auto;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-graduation-cap" style="font-size:2rem;color:#ffc93c;"></i>
                </div>
                <h1>Colegio Color de Vida</h1>
                <p>Sistema de Gestión Escolar</p>
            </div>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> Usuario o contraseña incorrectos.
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['logout'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Sesión cerrada correctamente.
                </div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="usuario"><i class="fas fa-user"></i> Usuario</label>
                    <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Ingrese su usuario" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Ingrese su contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </button>
            </form>
        </div>
    </div>
</body>
</html>
