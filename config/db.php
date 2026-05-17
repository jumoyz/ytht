<?php
/**
 * Database configuration for YTHT Downloader
 * Sets up PDO connection with environment variables
 * Includes error handling and secure logging for connection issues
 * Uses utf8mb4 charset for full Unicode support
 */
// db.php
// Centralized PDO connection file
require __DIR__ . '/config.php';

$host = env('DB_HOST', 'localhost');
$db   = env('DB_NAME', 'tms_ytht_db');
$user = env('DB_USER', 'root');
$pass = env('DB_PASS', '');
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Log error securely (never expose raw DB credentials)
    error_log("Database connection failed: " . $e->getMessage());
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Internal server error']));
}