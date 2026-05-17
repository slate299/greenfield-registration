<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$password = '1234567890';  // ⚠️ REPLACE THIS with your actual MySQL password
$database = 'greenfield_db';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");
?>