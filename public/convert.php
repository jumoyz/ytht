<?php
session_start();
require_once __DIR__ .'/../src/Config.php';
require_once __DIR__ .'/../src/Validator.php';
require_once __DIR__ .'/../src/Downloader.php';

header('Content-Type: application/json');

// Rate limiting
$rateLimitKey = 'rate_limit_' . $_SERVER['REMOTE_ADDR'];
$rateLimit = $_SESSION[$rateLimitKey] ?? 0;

if (time() - $rateLimit < 30) { // 30 seconds between requests
    echo json_encode(['success' => false, 'error' => 'Please wait before making another request']);
    exit;
}

$_SESSION[$rateLimitKey] = time();

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['url']) || !isset($input['format'])) {
        throw new Exception('Missing required parameters');
    }
    
    $url = trim($input['url']);
    $format = $input['format'];
    
    if (!in_array($format, ['mp4', 'mp3'])) {
        throw new Exception('Invalid format');
    }
    
    $downloader = new Downloader($url, $format);
    $result = $downloader->download();
    
    echo json_encode($result);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>