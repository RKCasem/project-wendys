<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration Form</title>
    <link rel="stylesheet" href="index_style.css">
</head>
<body>
    <!-- Corner Decor -->
    <div class="corner-decor bottom-left"></div>
    <div class="corner-decor top-right"></div>

    <!-- Logo -->
    <div class="logo">
    <img src="../images/mobile-logo.png" alt="Bill n' Chill Logo" >
    </div>

    <!-- Contact Us link -->
    <a href="contact.php" class="contact-us">Contact Us</a>
    <div class="card">
        <div class="form-container">
            <!-- Login Form -->
            <div id="login-form-container">
                <h2>Login Form</h2>
                <form id="login-form" action="../dashboard/dashboard.php" method="post">
                    <label for="login-email">Email:</label>
                    <input type="email" id="login-email" name="email" required><br>
                    <label for="login-password">Password:</label>
                    <input type="password" id="login-password" name="password" required><br>
                    <input type="submit" name="login" value="Login">
                </form>
                <button class="button" id="show-register">Not registered yet? Register</button>
            </div>
            <!-- Registration Form -->
            <div id="register-form-container" class="hidden">
                <h2>Registration Form</h2>
                <form action="../dashboard/dashboard.php" method="post">
                    <label for="register-surname">Surname:</label>
                    <input type="text" id="register-surname" name="surname" pattern="[A-Za-z]+" title="Special characters not allowed." required><br>
                    <label for="register-firstname">First Name:</label>
                    <input type="text" id="register-firstname" name="firstname" pattern="[A-Za-z]+" title="Special characters not allowed." required><br>
                    <label for="register-password">Password:</label>
                    <input type="password" id="register-password" name="password" required><br>
                    <label for="register-email">Email:</label>
                    <input type="email" id="register-email" name="email" required><br>
                    <input type="submit" name="register" value="Register" onclick="return validateEULA();">
                </form>
                <button class="button" id="back-to-login">Already Registered? Login</button>
            </div>
        </div>
    </div>
    <script src="index_script.js"></script>
</body>
</html>