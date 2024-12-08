<?php
session_start();
include '../db.php';
// Database connection settings
$servername = "localhost";  // Typically 'localhost' for XAMPP
$username = "root";         // Default username for XAMPP MySQL
$password = "";             // Default password for XAMPP MySQL (empty by default)
$dbname = "mydatabase";     // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL query to fetch the user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);  // 's' indicates the type (string)
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user with the provided email exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the provided password matches the stored password (assuming plain text for now)
        // It's better to use hashed passwords in production
        if ($password == $user['password']) {
            // Set session variables
            $_SESSION['userLoggedIn'] = true;
            $_SESSION['userEmail'] = $user['email'];

            // Redirect to the dashboard
            header("Location: ../dashboard/dashboard.php");
            exit();
        } else {
            // If the password doesn't match
            echo "Invalid email or password!";
        }
    } else {
        // If no user is found with the given email
        echo "Invalid email or password!";
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}
?>
