<?php
/**
 * Colegio Color de Vida - Vercel Router
 * Routes all requests to the appropriate PHP file via PHP built-in server
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error handling for production
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', '0');

// Get the request URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

// Route mapping: URL path => file to include
$routes = [
    ''          => '../index.php',
    '/index'    => '../index.php',
    '/index.php'=> '../index.php',
    '/login'    => '../login.php',
    '/login.php'=> '../login.php',
    '/logout'   => '../logout.php',
    '/logout.php'=> '../logout.php',
    '/dashboard'   => '../dashboard.php',
    '/dashboard.php'=> '../dashboard.php',
    '/estudiantes' => '../estudiantes.php',
    '/estudiantes.php'=> '../estudiantes.php',
    '/profesores'  => '../profesores.php',
    '/profesores.php'=> '../profesores.php',
    '/cursos'      => '../cursos.php',
    '/cursos.php'  => '../cursos.php',
    '/materias'    => '../materias.php',
    '/materias.php'=> '../materias.php',
    '/calificaciones' => '../calificaciones.php',
    '/calificaciones.php'=> '../calificaciones.php',
    '/matriculas'  => '../matriculas.php',
    '/matriculas.php'=> '../matriculas.php',
    '/usuarios'    => '../usuarios.php',
    '/usuarios.php'=> '../usuarios.php',
];

// Find the matching route
$file = $routes[$uri] ?? null;

if ($file && file_exists(__DIR__ . '/' . $file)) {
    // Set working directory to project root for includes to work
    chdir(__DIR__ . '/..');
    include __DIR__ . '/' . $file;
} else {
    // 404
    http_response_code(404);
    echo '<h1>404 - Página no encontrada</h1>';
    echo '<p>La página solicitada no existe.</p>';
    echo '<a href="/">Volver al inicio</a>';
}
