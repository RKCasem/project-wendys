<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>End User License Agreement</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffe1e1;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            overflow: hidden;
        }
        .container {
            background: rgba(255, 255, 255, 0.4);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            overflow-y: auto;
            max-height: 80vh;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 32px;
            text-align: center;
            color: black;
        }
        h2 {
            color: black;
            font-size: 24px;
            margin-top: 20px;
            margin-bottom: 10px;    
        }
        p {
            margin-bottom: 15px;
            line-height: 1.6;
            font-size: 18px;
            color: black;
        }
        .actions {
            text-align: center;
            margin-top: 25px;
        }
        .actions input[type="checkbox"] {
            margin-right: 10px;
        }
        .actions button {
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .actions button:disabled {
            background-color: #b0c4de;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>End User License Agreement (EULA)</h1>
        <p>Welcome to Bill n' Chill! This End User License Agreement ("Agreement") is a legal agreement between you ("User") and Bill n' Chill ("Company"). By using the Bill n' Chill software ("Software"), you agree to be bound by the terms and conditions of this Agreement.</p>
        <h2>1. License Grant</h2>
        <p>The Company grants you a limited, non-exclusive, non-transferable, revocable license to use the Software solely for your personal, non-commercial purposes.</p>
        <h2>2. Restrictions</h2>
        <p>You shall not: (a) reverse engineer, decompile, or disassemble the Software; (b) modify, adapt, translate, or create derivative works based on the Software; (c) distribute, lease, rent, lend, or sublicense the Software to any third party; (d) remove or alter any proprietary notices or labels on the Software.</p>
        <h2>3. Ownership</h2>
        <p>The Software is licensed, not sold, to you. The Company retains all right, title, and interest in and to the Software, including all intellectual property rights.</p>
        <h2>4. Termination</h2>
        <p>This Agreement is effective until terminated. Your rights under this Agreement will terminate automatically without notice if you fail to comply with any of its terms. Upon termination, you must cease all use of the Software and delete all copies of the Software.</p>
        <h2>5. Disclaimer of Warranties</h2>
        <p>The Software is provided "as is" without warranty of any kind. The Company disclaims all warranties, whether express, implied, or statutory, including, but not limited to, the implied warranties of merchantability, fitness for a particular purpose, and non-infringement.</p>
        <h2>6. Limitation of Liability</h2>
        <p>In no event shall the Company be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or in connection with the use or performance of the Software.</p>
        <div class="actions">
            <input type="checkbox" id="agree" name="agree">
            <label for="agree">I agree to the EULA</label><br>
            <button id="proceed" disabled>Proceed</button>
        </div>
    </div>
    <script>
        const checkbox = document.getElementById('agree');
        const proceedButton = document.getElementById('proceed');
        checkbox.addEventListener('change', function() {
            proceedButton.disabled = !this.checked;
        });
        proceedButton.addEventListener('click', function() {
            window.location.href = 'preferences.html';
        });
    </script>
</body>
</html>
