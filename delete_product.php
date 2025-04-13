<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM products WHERE id = $id";
    pg_query($conn, $query);
}

echo "<script>alert('Product deleted successfully!'); window.location.href='admin_dashboard.php';</script>";
?>
