<?php
session_start();
include 'db.php';

// Ensure only admins can add products
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied! Only admins can add products.'); window.location.href='login.html';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    // Ensure the uploads folder exists
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Generate a unique image name
    $image_name = time() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    // Move uploaded file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Store correct relative path in the database
        $query = "INSERT INTO products (name, category, price, description, image) 
                  VALUES ('$name', '$category', '$price', '$description', '$target_file')";
        $result = pg_query($conn, $query);

        if ($result) {
            echo "<script>alert('Product added successfully!'); window.location.href='admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error adding product! Try again.'); window.location.href='admin_dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Error uploading image! Check file permissions.'); window.location.href='admin_dashboard.php';</script>";
    }
}
?>
