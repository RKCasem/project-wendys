<?php
$servername = "localhost"; // Default for XAMPP
$username = "root";        // Default for XAMPP
$password = "";            // Default password
$dbname = "mydatabase";      // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
