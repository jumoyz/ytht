<?php
require __DIR__ . '/../config/db.php';

$id_token = $_POST['credential']; // from Google
$client = new Google_Client(['client_id' => 'YOUR_GOOGLE_CLIENT_ID']);
$payload = $client->verifyIdToken($id_token);

if ($payload) {
    $email = $payload['email'];
    $firstName = $payload['given_name'];
    $lastName = $payload['family_name'];

    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, '')");
        $stmt->execute([$firstName, $lastName, $email]);
        $userId = $pdo->lastInsertId();
    } else {
        $userId = $user['id'];
    }

    session_start();
    $_SESSION['user_id'] = $userId;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid Google token']);
}