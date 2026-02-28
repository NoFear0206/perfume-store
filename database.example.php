<?php
// Database configuration template
// Copy this file to database.php and fill in your local credentials

$host = "localhost";
$dbname = "perfume_store_db";
$username = "your_db_username";  // replace with your MySQL username
$password = "your_db_password";  // replace with your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>