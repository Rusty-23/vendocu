<?php
// Database configuration
$host = 'localhost'; // usually localhost
$db_name = 'vendocu';
$username = 'root';
$password = 'llames';

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>
