<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$pagina_actual = basename($_SERVER['PHP_SELF'], '.php');

if (!function_exists('getStats')) {
    function getStats($pdo) {
        $stats = [];
        $stats['estudiantes'] = $pdo->query("SELECT COUNT(*) FROM estudiantes WHERE activo=1")->fetchColumn();
        $stats['profesores'] = $pdo->query("SELECT COUNT(*) FROM profesores WHERE activo=1")->fetchColumn();
        $stats['cursos'] = $pdo->query("SELECT COUNT(*) FROM cursos WHERE activo=1")->fetchColumn();
        $stats['matriculas'] = $pdo->query("SELECT COUNT(*) FROM matriculas WHERE estado='Activa'")->fetchColumn();
        return $stats;
    }
}

$stats = $pdo->query("SELECT COUNT(*) FROM estudiantes WHERE activo=1")->fetchColumn();
$nombre_usuario = $_SESSION['usuario_nombre'] ?? 'Usuario';
$rol_usuario = $_SESSION['usuario_rol'] ?? 'Usuario';
$inicial = strtoupper(substr($nombre_usuario, 0, 1));
$anio = date('Y');

// Pendiente_notificaciones
$notificaciones = $pdo->query("SELECT COUNT(*) FROM calificaciones WHERE definitiva < 3.0")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pagina_titulo ?? 'Sistema Escolar' ?> - Colegio Color de Vida</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-container">
    <!-- SIDEBAR -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-graduation-cap"></i> Color de Vida</h2>
            <p>Sistema Escolar <?= $anio ?></p>
        </div>
        <div class="sidebar-nav">
            <div class="nav-section-title">Principal</div>
            <a href="dashboard.php" class="nav-item <?= $pagina_actual == 'dashboard' ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-tachometer-alt"></i></span>
                Dashboard
            </a>

            <div class="nav-section-title">Gestión Académica</div>
            <a href="estudiantes.php" class="nav-item <?= $pagina_actual == 'estudiantes' ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-user-graduate"></i></span>
                Estudiantes
            </a>
            <a href="profesores.php" class="nav-item <?= $pagina_actual == 'profesores' ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-chalkboard-teacher"></i></span>
                Profesores
            </a>
            <a href="cursos.php" class="nav-item <?= $pagina_actual == 'cursos' ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-school"></i></span>
                Cursos
            </a>
            <a href="materias.php" class="nav-item <?= $pagina_actual == 'materias' ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-book"></i></span>
                Materias
            </a>
            <a href="matriculas.php" class="nav-item <?= $pagina_actual == 'matriculas' ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-file-signature"></i></span>
                Matrículas
            </a>

            <div class="nav-section-title">Evaluación</div>
            <a href="calificaciones.php" class="nav-item <?= $pagina_actual == 'calificaciones' ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-star"></i></span>
                Calificaciones
            </a>

            <?php if ($rol_usuario === 'admin'): ?>
            <div class="nav-section-title">Administración</div>
            <a href="usuarios.php" class="nav-item <?= $pagina_actual == 'usuarios' ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-users-cog"></i></span>
                Usuarios
            </a>
            <?php endif; ?>

            <div class="nav-section-title">Sesión</div>
            <a href="logout.php" class="nav-item">
                <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                Cerrar Sesión
            </a>
        </div>
    </nav>

    <!-- MAIN -->
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button onclick="document.getElementById('sidebar').classList.toggle('show')" style="background:none;border:none;font-size:1.3rem;cursor:pointer;margin-right:10px;">
                    <i class="fas fa-bars"></i>
                </button>
                <h1><?= $pagina_titulo ?? 'Dashboard' ?></h1>
            </div>
            <div class="topbar-right">
                <div class="topbar-user">
                    <div class="topbar-user-info">
                        <div class="name"><?= htmlspecialchars($nombre_usuario) ?></div>
                        <div class="role"><?= htmlspecialchars($rol_usuario) ?></div>
                    </div>
                    <div class="topbar-avatar"><?= $inicial ?></div>
                </div>
            </div>
        </header>
        <div class="page-content">
