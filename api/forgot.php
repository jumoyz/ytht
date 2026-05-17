<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/mailer.php'; // PHPMailer or similar

header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);

// Generate token
$token = bin2hex(random_bytes(32));
$expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

// Save token
$stmt = $pdo->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE email=?");
$stmt->execute([$token, $expires, $email]);

// Send email
$link = "https://yourdomain.com/reset.php?token=$token";
$mail->addAddress($email);
$mail->Subject = "Password Reset";
$mail->Body    = "Click here to reset your password: $link";
$mail->send();

echo json_encode(['success' => true, 'message' => 'Reset link sent']);