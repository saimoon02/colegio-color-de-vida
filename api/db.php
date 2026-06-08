<?php
/**
 * Database configuration for Vercel
 * Uses environment variables for production, falls back to defaults for local
 */

function getDB() {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    // Try environment variables first (for Vercel/production)
    $host = getenv('DB_HOST') ?: 'localhost';
    $dbname = getenv('DB_NAME') ?: 'color_de_vida';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS') ?: '';
    $port = getenv('DB_PORT') ?: '3306';

    try {
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        die("Error de conexión a la base de datos. Por favor, verifica la configuración.");
    }
}
