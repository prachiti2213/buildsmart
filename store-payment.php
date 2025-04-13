<?php
$conn = new PDO("pgsql:host=localhost;dbname=postgres", "postgres", "Prachu@13");

if(isset($_GET['payment_id']) && isset($_GET['amount'])) {
    $payment_id = $_GET['payment_id'];
    $amount = $_GET['amount'];
    
    $stmt = $conn->prepare("INSERT INTO payments (payment_id, amount, status) VALUES (?, ?, 'Success')");
    $stmt->execute([$payment_id, $amount]);

    echo "<h2>Payment Successful! Transaction ID: $payment_id</h2>";
} else {
    echo "<h2>Payment Failed!</h2>";
}
?>
