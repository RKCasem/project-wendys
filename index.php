<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, black, red);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }
        .corner-decor {
            position: absolute;
            width: 150px;
            height: 150px;
            background-color: white;
            border-radius: 50%;
        }
        .corner-decor.top-left {
            top: -75px;
            left: -75px;
        }
        .corner-decor.bottom-left {
            bottom: -75px;
            left: -75px;
        }
        .corner-decor.top-right {
            top: -75px;
            right: -75px;
        }
        .corner-decor.bottom-right {
            bottom: -75px;
            right: -75px;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }
        .logo img {
            max-width: 150px;
            height: auto;
        }
        .contact-us {
            position: absolute;
            top: 20px;
            right: 75px;
            font-size: 16px;
            color: white;
            text-decoration: none;
            background-color: transparent;
            border: 2px solid white;
            padding: 10px 20px;
            cursor: pointer;
            z-index: 1000;
        }
        .contact-us:hover {
            background-color: white;
            color: red;
            border-color: red;
        }
        .card {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            max-width: 400px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            position: relative;
        }
        .form-container {
            max-width: 400px;
            width: 100%;
        }
        h2 {
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"] {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: darkred;
        }
        label {
            display: block;
            margin: 5px 0;
        }
        .hidden {
            display: none;
        }
        .button {
            background-color: transparent;
            color: white;
            text-align: center;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Corner Decor -->
    <div class="corner-decor bottom-left"></div>
    <div class="corner-decor top-right"></div>

    <!-- Logo -->
    <div class="logo">
        <img src="mobile-logo.png" alt="Bill n' Chill Logo">
    </div>

    <!-- Contact Us link -->
    <a href="contact.php" class="contact-us">Contact Us</a>
    <div class="card">
        <div class="form-container">
            <!-- Login Form -->
            <div id="login-form-container">
                <h2>Login Form</h2>
                <form id="login-form" action="login_register.php" method="post">
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
                <form action="login_register.php" method="post">
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
    <script>
        document.getElementById('show-register').addEventListener('click', function() {
            document.getElementById('login-form-container').classList.add('hidden');
            document.getElementById('register-form-container').classList.remove('hidden');
        });
        document.getElementById('back-to-login').addEventListener('click', function() {
            document.getElementById('login-form-container').classList.remove('hidden');
            document.getElementById('register-form-container').classList.add('hidden');
        });
        function validateEULA() {
            const eulaCheckbox = document.getElementById('preference');
            if (!eulaCheckbox.checked) {
                alert('You must agree to the EULA before registering.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
