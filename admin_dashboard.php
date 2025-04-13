<?php
session_start();
include 'db.php';

// Ensure only admins can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #f4f4f4; padding: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }
        
        .container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }
        input, select { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #ff7f50; color: white; padding: 10px; border: none; cursor: pointer; width: 100%; }
        button:hover { background: #ff4500; }
        
        .product-list {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }
        .product-list th, .product-list td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .product-list th {
            background: #ff7f50;
            color: white;
        }
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
        .edit-btn, .delete-btn {
            padding: 5px 10px;
            margin: 5px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .edit-btn { background: #007bff; color: white; }
        .delete-btn { background: #dc3545; color: white; }
        .edit-btn:hover { background: #0056b3; }
        .delete-btn:hover { background: #c82333; }
    </style>
</head>
<body>

<h2>Admin Dashboard - Manage Products</h2>

<div class="container">
    <form action="add_product.php" method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="text" name="category" placeholder="Category (e.g., Cement, Bricks)" required>
        <input type="number" name="price" placeholder="Price" required>
        <input type="text" name="description" placeholder="Short Description" required>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">Add Product</button>
    </form>
</div>

<h2>Existing Products</h2>

<div class="container">
    <table class="product-list">
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>

        <?php
        $query = "SELECT * FROM products";
        $result = pg_query($conn, $query);
        while ($row = pg_fetch_assoc($result)) {
            echo "<tr>
                    <td><img class='product-img' src='{$row['image']}' alt='{$row['name']}'></td>
                    <td>{$row['name']}</td>
                    <td>{$row['category']}</td>
                    <td>₹{$row['price']}</td>
                    <td>
                        <a href='edit_product.php?id={$row['id']}' class='edit-btn'>Edit</a>
                        <a href='delete_product.php?id={$row['id']}' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this product?\")'>Delete</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
