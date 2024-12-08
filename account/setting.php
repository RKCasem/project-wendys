
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <style>
        /* General styling */
        /* General styling */
        body {
            font-family: 'Homemade Apple', Arial, sans-serif;
            background: linear-gradient(135deg, #111, #333); /* Gradient background */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
            flex-direction: column;
            animation: fadeIn 1s ease-out;
            overflow: hidden;
        }

.container {
    background-color: #444;
    padding: 25px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    border-radius: 15px;
    width: 100%;
    max-width: 600px;
    opacity: 0;
    animation: slideIn 1s forwards 0.5s;
    transform: scale(0.95);
    transition: transform 0.2s ease, box-shadow 0.3s ease;
}

.container:hover {
    transform: translateY(-5px); /* Similar to the "Back to Dashboard" button */
    box-shadow: 0 8px 30px rgba(231, 76, 60, 0.5); /* Shadow similar to button */
}
    


h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #ff4747;
    font-size: 30px;
    font-weight: bold;
    animation: slideInFromLeft 1s ease-out;
    text-transform: uppercase;
    letter-spacing: 1px;
}

label {
    font-size: 14px;
    margin-bottom: 5px;
    display: block;
    color: #e74c3c;
    font-weight: bold;
}

input, select {
    width: 100%;
    padding: 12px;
    margin: 12px 0;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #444;
    color: #fff;
    font-size: 16px;
    box-sizing: border-box;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

input:focus, select:focus {
    background-color: #555;
    outline: none;
    transform: translateY(-2px);
    box-shadow: 0 0 8px rgba(231, 76, 60, 0.7);
}

input[type="range"] {
    width: 100%;
    margin: 20px 0;
    height: 6px;
    border-radius: 10px;
    background-color: #555;
}

input[type="range"]::-webkit-slider-thumb {
    background-color: #e74c3c;
    border: 2px solid #fff;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    transition: background-color 0.3s ease;
}

input[type="range"]:hover::-webkit-slider-thumb {
    background-color: #c0392b;
}

button {
    width: 100%;
    padding: 15px;
    background-color: #e74c3c;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 20px;
    opacity: 0;
    animation: fadeInButton 1s forwards;
    transform: translateY(20px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    transition: transform 0.2s ease, box-shadow 0.3s ease;
}


button:active {
    transform: translateY(2px);
}


.progress-bar {
    width: 100%;
    height: 10px;
    background-color: #555;
    margin-bottom: 20px;
    border-radius: 5px;
    overflow: hidden;
}

.progress-bar-fill {
    height: 100%;
    width: 0%;
    background-color: #e74c3c;
    transition: width 0.3s ease;
    border-radius: 5px;
}

.step {
    display: none;
    opacity: 0;
    animation: fadeInStep 1s forwards;
}

.step.active {
    display: block;
    animation: fadeInStep 1s forwards;
}

.back-button {
    padding: 12px;
    background-color: #555;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    width: 100%;
    margin-top: 20px;
    opacity: 0;
    animation: fadeInButton 1s forwards 1.5s;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.back-button:hover {
    background-color: #444;
    transform: translateY(-5px);
}

.back-button:active {
    transform: translateY(2px);
}

/* Back to Dashboard Button Styling */
.back-to-dashboard {
    margin-top: 25px;
    text-align: center;
}

.back-to-dashboard button {
    padding: 12px 25px;
    font-size: 18px;
    background-color: #ff4747;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    animation: slideIn 1s forwards 0.8s;
    transition: background-color 0.5s, transform 0.5s ease, box-shadow 0.5s ease;
    font-weight: bold;
}

.back-to-dashboard button:hover {
    background-color: #c0392b;
    transform: scale(1.05); /* Slight scale effect on hover */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* Shadow effect */
}


/* Animations */
@keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

@keyframes fadeInText {
    0% { opacity: 0; transform: translateY(-20px); }
    100% { opacity: 1; transform: translateY(0); }
}

@keyframes slideIn {
    0% { transform: translateY(50px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
}

@keyframes fadeInButton {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInStep {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}

@keyframes shake {
    0% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    50% { transform: translateX(10px); }
    75% { transform: translateX(-10px); }
    100% { transform: translateX(0); }
}

input:invalid {
    border: 2px solid #e74c3c;
    animation: shake 0.4s ease-in-out;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Update Account Settings</h1>
        <div class="progress-bar">
            <div class="progress-bar-fill"></div>
        </div>

        <form id="accountForm">
            <!-- Step 1 -->
            <div class="step active">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter a new password (optional)">
                
                <label for="gender">Gender:</label>
                <select id="gender" name="gender">
                    <option value="" disabled selected>Select your gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <button type="button" onclick="nextStep()">Next</button>
            </div>

            <!-- Step 2 -->
            <div class="step">
                <label for="birthday">Birthday:</label>
                <input type="date" id="birthday" name="birthday" required onchange="syncAgeWithBirthday()">

                <label for="age">Age:</label>
                <input type="range" id="age" name="age" min="0" max="120" value="25" oninput="updateAgeValue(this.value)">
                <span id="ageValue">25</span> years old

                <button type="button" onclick="prevStep()">Previous</button>
                <button type="button" onclick="nextStep()">Next</button>
            </div>

            <!-- Step 3 -->
            <div class="step">
                <label for="mobilenumber">Mobile Number:</label>
                <label for="country">Country:</label>
                <select id="country" name="country">
                    <option value="PH">Philippines (+63)</option>
                    <option value="US">United States (+1)</option>
                    <option value="IN">India (+91)</option>
                </select>

                <input type="text" id="mobilenumber" name="mobilenumber" placeholder="Enter your mobile number" required>

                <label for="backupemail">Backup Email:</label>
                <input type="email" id="backupemail" name="backupemail" placeholder="Enter a backup email">

                <button type="button" onclick="prevStep()">Previous</button>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>

    <!-- Add a Back button to go to the dashboard, positioned below the container -->
    <div class="back-to-dashboard">
        <button onclick="window.location.href='../dashboard/dashboard.php'">Back to Dashboard</button>
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

        function syncAgeWithBirthday() {
            const birthday = new Date(birthdayInput.value);
            const age = new Date().getFullYear() - birthday.getFullYear();
            ageSlider.value = age;
            updateAgeValue(age);
        }
    </script>
</body>
</html>
