<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        // Registration process
        $surname = $_POST['surname'];
        $firstname = $_POST['firstname']; 
        $password = $_POST['password'];
        $email = $_POST['email'];

        // Check if email already exists
        $checkEmail = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($checkEmail);

        if ($result->num_rows > 0) {
            echo "Email already registered. Please use a different email.";
        } else {
            $sql = "INSERT INTO users (surname, firstname, password, email) VALUES ('$surname', '$firstname', '$password', '$email')";
            if ($conn->query($sql) === TRUE) {
                echo "Registration successful!";
                header("Location: eula.html");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } elseif (isset($_POST['login'])) {
        // Login process
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "Login successful!";
            header("Location: dashboard.html");
            exit();
        } else {
            echo "Invalid email or password.";
        }
    }
}

$conn->close();
?>
