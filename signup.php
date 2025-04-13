<?php
session_start();
include 'db.php'; // Ensure your database connection file is correctly set up

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['username']) && 
        isset($_POST['email']) && 
        isset($_POST['phone']) && 
        isset($_POST['password']) && 
        isset($_POST['confirmPassword']) && 
        isset($_POST['login_as'])
    ) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirmPassword']);
        $role = $_POST['login_as']; // "user" or "admin"

        // Validate passwords match
        if ($password !== $confirmPassword) {
            echo "<script>alert('Passwords do not match!'); window.location.href='Sign Up.html';</script>";
            exit();
        }

        // Hash the password before storing
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Check if email already exists
        $checkQuery = "SELECT * FROM users WHERE email = '$email'";
        $checkResult = pg_query($conn, $checkQuery);

        if (pg_num_rows($checkResult) > 0) {
            echo "<script>alert('Email already exists!'); window.location.href='Sign Up.html';</script>";
            exit();
        }

        // Insert user data into PostgreSQL
        $query = "INSERT INTO users (username, email, phone, password, role) 
                  VALUES ('$username', '$email', '$phone', '$hashedPassword', '$role')";
        $result = pg_query($conn, $query);

        if ($result) {
            echo "<script>alert('Signup successful! Please login.'); window.location.href='login.html';</script>";
        } else {
            echo "<script>alert('Signup failed. Please try again.'); window.location.href='Sign Up.html';</script>";
        }
    } else {
        echo "<script>alert('Please fill all fields.'); window.location.href='Sign Up.html';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='Sign Up.html';</script>";
}
?>
