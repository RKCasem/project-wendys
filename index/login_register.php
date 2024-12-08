<?php
include '../db.php';

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
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (surname, firstname, password, email) VALUES ('$surname', '$firstname', '$hashedPassword', '$email')";
            if ($conn->query($sql) === TRUE) {
                echo "Registration successful!";
                header("Location: ../dashboard/dashboard.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } elseif (isset($_POST['login'])) {
        // Login process
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Debugging: check the input email and password
        echo "Email: $email <br>";
        echo "Password: $password <br>";

        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Debugging: check if the password hash is correct
            echo "Hashed password from DB: " . $user['password'] . "<br>";

            // Verify the password using password_verify
            if (password_verify($password, $user['password'])) {
                echo "Login successful!";
                header("Location: ../dashboard/dashboard.php");
                exit();
            } else {
                echo "Invalid email or password.";
            }
        } else {
            echo "Invalid email or password.";
        }
    }
}

$conn->close();
?>
