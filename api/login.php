<?php
// Start the session if not already started
session_start();
require __DIR__ . '/../config/db.php'; // PDO connection

header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$password = $data['password'];

$stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
}