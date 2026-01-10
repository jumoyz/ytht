<?php
// download.php
$file = $_GET['file'] ?? '';

$downloadDir = __DIR__ . '/downloads/';
$filepath = realpath($downloadDir . $file);

// Security check: ensure file exists and is inside downloads folder
if (!$filepath || strpos($filepath, realpath($downloadDir)) !== 0 || !file_exists($filepath)) {
    http_response_code(404);
    echo "File not found.";
    exit;
}

// Send headers
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
header('Content-Length: ' . filesize($filepath));
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Expires: 0');

// Stream file
readfile($filepath);

// Delete after serving
unlink($filepath);
exit;
