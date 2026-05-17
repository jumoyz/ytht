<?php
/**
 * Mailer configuration for YTHT Downloader
 * Sets up PHPMailer with environment variables
 * Includes error handling and secure logging for email issues
 */
require __DIR__ . '/db.php';
require __DIR__ . '/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = env('SMTP_HOST'); // e.g. smtp.gmail.com
    $mail->SMTPAuth   = true;
    $mail->Username   = env('SMTP_USER');  // SMTP username
    $mail->Password   = env('SMTP_PASS');     // SMTP password or app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS encryption
    $mail->Port       = env('SMTP_PORT'); // or 465 for SSL

    // Default sender
    $mail->setFrom(env('SMTP_FROM'), env('SMTP_NAME'));

    // You can now use $mail in forgot.php, reset.php, etc.
} catch (Exception $e) {
    error_log("Mailer setup failed: " . $mail->ErrorInfo);
}