<?php
require __DIR__ . '/../config/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$token = $data['token'];
$newPassword = $data['password'];

$stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token=? AND reset_expires > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if ($user) {
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $update = $pdo->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE id=?");
    $update->execute([$hash, $user['id']]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid or expired token']);
}