<?php
session_start();
include('../db.php');  // Ensure db.php contains the correct PDO connection

// Function to fetch user data based on email
function fetchUserData($email, $conn) {
    try {
        // Prepare the SELECT query
        $stmt = $conn->prepare("SELECT id, email, firstname, surname, password FROM users WHERE email = :email");

        // Bind the email parameter
        $stmt->bindParam(':email', $email);
        
        // Execute the query
        $stmt->execute();

        // Fetch user data as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo "Error fetching user data: " . $e->getMessage();
        return null;
    }
}
// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = $_SESSION['email']; // Ensure we are updating the logged-in user's data
    $password = $_POST['password'];
    $birthday = $_POST['birthday'];
    $location = $_POST['location'];
    $age = $_POST['age'];
    $birthmonth = $_POST['birthmonth'];
    $mobilenumber = $_POST['mobilenumber'];

    // If password is not empty, hash it
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    } else {
        // Keep the current password if it's not changed
        $hashedPassword = $user['password'];
    }

    // Update account settings for the logged-in user, including the password if changed
    updateAccountSettings($email, $birthday, $location, $age, $birthmonth, $mobilenumber, $hashedPassword, $conn);
}

function updateAccountSettings($email, $birthday, $location, $age, $birthmonth, $mobilenumber, $conn) {
    try {
        // Prepare the UPDATE query
        $stmt = $conn->prepare("UPDATE account_settings SET birthday = :birthday, location = :location, 
                                age = :age, birthmonth = :birthmonth, mobilenumber = :mobilenumber 
                                WHERE email = :email");

        // Bind the parameters
        $stmt->bindParam(':birthday', $birthday);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':birthmonth', $birthmonth);
        $stmt->bindParam(':mobilenumber', $mobilenumber);
        $stmt->bindParam(':email', $email);

        // Execute the update query
        if ($stmt->execute()) {
            echo "<div class='success-message'>Account settings updated successfully!</div>";
        } else {
            throw new Exception("Failed to execute update query.");
        }
    } catch (Exception $e) {
        echo "<div class='error-message'>Error updating account settings: " . $e->getMessage() . "</div>";
    }
}



// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index/index.php"); // Redirect to login page
    exit();
}

$email = $_SESSION['email']; // Get the logged-in user's email

// Fetch user data from the database
$stmt = $conn->prepare("SELECT id, email, firstname, surname, password, birthday, location, age, birthmonth, mobilenumber FROM account_settings WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found!";
    exit();
}

$email = $_SESSION['email']; // Get the logged-in user's email

// Fetch user data
$user = fetchUserData($email, $conn);

if (!$user) {
    echo "<div class='error-message'>User not found.</div>";
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $birthday = $_POST['birthday'];
    $location = $_POST['location'];
    $age = $_POST['age'];
    $birthmonth = $_POST['birthmonth'];
    $mobilenumber = $_POST['mobilenumber'];

    // Update account settings for the logged-in user
    updateAccountSettings($email, $birthday, $location, $age, $birthmonth, $mobilenumber, $conn);
}
?>

<!-- Your HTML form goes here -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input[type="range"] {
            width: 100%;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #6c63ff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #5752f3;
        }

        .progress-bar {
            width: 100%;
            height: 10px;
            background-color: #ddd;
            margin-bottom: 20px;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            width: 0%;
            background-color: #6c63ff;
            transition: width 0.3s;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Account Settings</h1>
        <div class="progress-bar">
            <div class="progress-bar-fill"></div>
        </div>
        <form id="accountForm" method="POST">
    <!-- Step 1 -->
    <div class="step active">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required readonly>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter a new password (optional)">
        
        <label for="gender">Gender:</label>
        <select id="gender" name="gender">
            <option value="male" <?php echo ($user['gender'] === 'male' ? 'selected' : ''); ?>>Male</option>
            <option value="female" <?php echo ($user['gender'] === 'female' ? 'selected' : ''); ?>>Female</option>
            <option value="other" <?php echo ($user['gender'] === 'other' ? 'selected' : ''); ?>>Other</option>
        </select>
        <button type="button" onclick="nextStep()">Next</button>
    </div>

    <!-- Step 2 -->
    <div class="step">
        <label for="birthday">Birthday:</label>
        <input type="date" id="birthday" name="birthday" value="<?php echo htmlspecialchars($user['birthday']); ?>" required onchange="syncAgeWithBirthday()">

        <label for="age">Age:</label>
        <input type="range" id="age" name="age" min="0" max="120" value="25" oninput="updateAgeValue(this.value)">
        <span id="ageValue">25</span> years old

        <button type="button" onclick="prevStep()">Previous</button>
        <button type="button" onclick="nextStep()">Next</button>
    </div>

    <!-- Step 3 -->
    <div class="step">
        <label for="mobilenumber">Mobile Number:</label>
        <input type="text" id="mobilenumber" name="mobilenumber" value="<?php echo htmlspecialchars($user['mobilenumber']); ?>" required>

        <button type="button" onclick="prevStep()">Previous</button>
        <button type="submit">Submit</button>
    </div>
</form>

    </div>

    <script>
        let currentStep = 0;
        const steps = document.querySelectorAll('.step');
        const progressBarFill = document.querySelector('.progress-bar-fill');
        const ageSlider = document.getElementById('age');
        const ageValue = document.getElementById('ageValue');
        const birthdayInput = document.getElementById('birthday');

        function updateProgress() {
            const progress = ((currentStep + 1) / steps.length) * 100;
            progressBarFill.style.width = progress + '%';
        }

        function nextStep() {
            if (currentStep < steps.length - 1) {
                steps[currentStep].classList.remove('active');
                currentStep++;
                steps[currentStep].classList.add('active');
                updateProgress();
            }
        }

        function prevStep() {
            if (currentStep > 0) {
                steps[currentStep].classList.remove('active');
                currentStep--;
                steps[currentStep].classList.add('active');
                updateProgress();
            }
        }

        function updateAgeValue(value) {
            ageValue.textContent = value;
        }

        function calculateAge(birthdate) {
            const today = new Date();
            const birthDate = new Date(birthdate);
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            return age;
        }

        function syncAgeWithBirthday() {
            const birthdate = birthdayInput.value;
            if (birthdate) {
                const age = calculateAge(birthdate);
                ageSlider.value = age;
                updateAgeValue(age);
            }   
        }
        const countryCodePatterns = {
            'PH': /^\+63\d{10}$/, // Philippines: +63 followed by 10 digits
            'US': /^\+1\d{10}$/,  // USA: +1 followed by 10 digits
            'IN': /^\+91\d{10}$/  // India: +91 followed by 10 digits
        };

        document.getElementById('mobilenumber').addEventListener('input', function () {
            const mobileNumber = this.value;
            const selectedCountry = document.getElementById('country').value; // Assuming a country dropdown
            
            if (!countryCodePatterns[selectedCountry].test(mobileNumber)) {
                this.setCustomValidity(`Enter a valid mobile number for ${selectedCountry}`);
            } else {
                this.setCustomValidity('');
            }
        });


        document.getElementById('accountForm').addEventListener('submit', function (e) {
            e.preventDefault();
            alert('Account settings updated successfully!');
        });
    </script>
</body>
</html>
