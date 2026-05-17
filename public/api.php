<?php
declare(strict_types=1);
/**
 * API entry point
 * All API requests are routed through this file
 * 
 */
ini_set('display_errors', 1);
ini_set('html_errors', '0');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
//header('Content-Type: application/json');

//require_once __DIR__ . '/../app/bootstrap/loader.php';

// Simple routes by query string
// Read endpoint and allow lowercase letters, digits, underscores and hyphens
$endpoint = $_GET['endpoint'] ?? null;

if (!preg_match('/^[a-z0-9_-]+$/', (string)$endpoint)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid endpoint']);
    exit;
}

switch ($endpoint) {
    // Include logic from /api/login.php
    case 'login':
        require __DIR__ . '/../api/login.php';
        break;

    // Include logic from /api/register.php
    case 'register':
        require __DIR__ . '/../api/register.php';
        break;


    /*
    default:
        echo json_encode([
            'error' => 'Unkown endpoint',
            'endpoint' => $endpoint
        ]);
    */
    /*
    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Unknown endpoint']);
        exit;
    */
    default:
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'API endpoint not found'
        ]);
        exit;

}
?>
