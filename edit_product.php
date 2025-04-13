<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM products WHERE id = $id";
    $result = pg_query($conn, $query);
    $product = pg_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    $query = "UPDATE products SET name='$name', category='$category', price='$price', description='$description' WHERE id=$id";
    pg_query($conn, $query);
    
    echo "<script>alert('Product updated successfully!'); window.location.href='admin_dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Product</title>
</head>
<body>
    <form action="edit_product.php" method="post">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
        <input type="text" name="category" value="<?php echo $product['category']; ?>" required>
        <input type="number" name="price" value="<?php echo $product['price']; ?>" required>
        <input type="text" name="description" value="<?php echo $product['description']; ?>" required>
        <button type="submit">Update Product</button>
    </form>
</body>
</html>
