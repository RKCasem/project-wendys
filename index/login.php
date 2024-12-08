<?php
session_start();
include '../db.php'; // Your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL query to get the user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['userLoggedIn'] = true;
            $_SESSION['userEmail'] = $user['email'];
            header("Location: ../dashboard/dashboard.php");
            exit();
        } else {
            echo "<p style='color:red;'>Invalid email or password!</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid email or password!</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
