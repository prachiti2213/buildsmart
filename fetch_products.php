<?php
include 'db.php';

$query = "SELECT * FROM products";
$result = pg_query($conn, $query);

$products = array();
while ($row = pg_fetch_assoc($result)) {
    // Ensure correct relative path
    if (!empty($row['image'])) {
        $row['image'] = $row['image']; // Keep stored path
    } else {
        $row['image'] = "placeholder.jpg"; // Fallback image
    }
    $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);
?>
