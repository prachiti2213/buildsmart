<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $token = $_POST['token'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    // Verify the token
    $stmt = $conn->prepare("SELECT reset_token FROM users WHERE email = ? AND reset_token_expiry > NOW()");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Invalid or expired token.");
    }

    $user = $result->fetch_assoc();

    if (!password_verify($token, $user['reset_token'])) {
        die("Invalid token.");
    }

    // Update password and remove token
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE email = ?");
    $stmt->bind_param("ss", $new_password, $email);
    $stmt->execute();

    echo "Your password has been successfully reset!";
}
?>
