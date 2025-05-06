<?php
// db_connect.php

$host = 'localhost';  // Replace with your database host
$dbname = 'sibantu_db';  // Replace with your database name
$username = 'root';  // Replace with your database username
$password = '';  // Replace with your database password

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If there's an error with the database connection, display the error
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
