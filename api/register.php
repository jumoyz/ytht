<?php
require __DIR__ . '/../config/db.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

$firstName = htmlspecialchars($data['firstName']);
$lastName  = htmlspecialchars($data['lastName']);
$email     = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$password  = $data['password'];

if (strlen($password) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password too short']);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
try {
    $stmt->execute([$firstName, $lastName, $email, $hash]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Email already exists']);
}