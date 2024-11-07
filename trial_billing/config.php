<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = ""; // replace with your MySQL password
$dbname = "mydatabase";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
