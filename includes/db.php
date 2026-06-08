<?php
/**
 * Database configuration
 * Auto-detects Vercel environment or uses local settings
 */

// If running on Vercel (api/ directory exists one level up)
if (file_exists(__DIR__ . '/../api/db.php') && getenv('VERCEL')) {
    require_once __DIR__ . '/../api/db.php';
    $pdo = getDB();
} else {
    // Local development
    $host = 'localhost';
    $dbname = 'color_de_vida';
    $username = 'root';
    $password = '';

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}
