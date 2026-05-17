<?php
// Set error handling to catch all errors
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // Don't suppress errors, but catch them
    return false;
});

// Register shutdown function to catch fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'Fatal error: ' . $error['message'] . ' in ' . $error['file'] . ':' . $error['line']
        ]);
        exit;
    }
});

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Buffer output so any PHP warnings don't corrupt the JSON response
ob_start();

// Include necessary files
require_once __DIR__ . '/../src/Config.php';
require_once __DIR__ . '/../src/Validator.php';
require_once __DIR__ . '/../src/LyricsGenerator.php';
require_once __DIR__ . '/../src/Downloader.php';

// Clean output buffer to remove any stray output
ob_clean();

// Set JSON header BEFORE any output
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

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
    $generateLyrics = $input['generate_lyrics'] ?? false;
    
    if (!in_array($format, ['mp4', 'mp3'])) {
        throw new Exception('Invalid format');
    }
    
    if ($generateLyrics && $format !== 'mp3') {
        throw new Exception('Lyrics generation requires MP3 format');
    }
    
    $downloader = new Downloader($url, $format);
    $result = $downloader->download();
    
    // Generate lyrics if requested and MP3 downloaded
    if ($generateLyrics && $result['success'] && $format === 'mp3' && isset($result['filepath'])) {
        $lyricsGen = new LyricsGenerator();
        try {
            $lyricsFile = $lyricsGen->generate((string)$result['filepath']);
            $result['lyrics'] = basename($lyricsFile);
        } catch (Exception $e) {
            $result['lyrics_error'] = $e->getMessage();
        }
    }
    
    echo json_encode($result);
    exit;
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
    exit;
}