<?php
$host = "localhost";
$dbname = "postgres";
$user = "postgres";
$password = "Prachu@13";

$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Database connection failed: " . pg_last_error());
}
?>
