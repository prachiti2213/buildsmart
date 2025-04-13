<?php
require 'db.php';
require 'vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("No account found with this email.");
    }

    $user = $result->fetch_assoc();
    $token = bin2hex(random_bytes(50)); // Generate a random token
    $token_hash = password_hash($token, PASSWORD_BCRYPT); // Hash the token for security
    $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // Store the hashed token in the database
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
    $stmt->bind_param("sss", $token_hash, $expiry, $email);
    $stmt->execute();

    // Create reset link
    $reset_link = "http://yourwebsite.com/reset-password.php?token=$token&email=" . urlencode($email);

    // Send email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.yourmailserver.com'; // SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@example.com'; // SMTP username
        $mail->Password = 'your-email-password'; // SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('no-reply@yourwebsite.com', 'BuildSmart Support');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Password Reset Request";
        $mail->Body = "Click the link below to reset your password:<br><a href='$reset_link'>$reset_link</a>";

        $mail->send();
        echo "A password reset link has been sent to your email.";
    } catch (Exception $e) {
        echo "Failed to send email: {$mail->ErrorInfo}";
    }
}
?>
