<?php
session_start();
include 'db.php'; // Ensure your database connection works

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['login_as'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $login_as = $_POST['login_as']; // "user" or "admin"

        // Validate input to prevent SQL Injection
        $email = pg_escape_string($conn, $email);
        $password = pg_escape_string($conn, $password);

        // Query to check credentials based on user type
        $query = "SELECT * FROM users WHERE email = '$email' AND role = '$login_as'";
        $result = pg_query($conn, $query);

        if ($result && pg_num_rows($result) > 0) {
            $user = pg_fetch_assoc($result);

            // Verify password (assuming passwords are hashed)
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] == 'admin') {
                    header("Location: admin_dashboard.php"); // Redirect admins to the dashboard
                } else {
                    header("Location: main1.html"); // Redirect normal users to main1
                }
                exit();
            } else {
                echo "<script>alert('Invalid credentials'); window.location.href='login.html';</script>";
            }
        } else {
            echo "<script>alert('Invalid credentials'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Please enter email, password, and select a role.'); window.location.href='login.html';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='login.html';</script>";
}
?>
