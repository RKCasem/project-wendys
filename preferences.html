<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill n' Chill Preferences</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4, #fad0c4, #ffd1ff);
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
            padding: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            overflow-y: auto;
            max-height: 80vh;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 32px;
            color: #ffffff;
        }
        .preference {
            display: inline-block;
            padding: 10px 20px;
            background: #ffffff;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 5px;
            cursor: pointer;
            user-select: none;
        }
        .preference.selected {
            background: #007bff;
            color: #fff;
        }
        .add-preference {
            display: inline-block;
            padding: 10px 20px;
            background: #ff9a9e;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 5px;
            cursor: pointer;
            user-select: none;
        }
        .actions {
            margin-top: 20px;
        }
        .actions button {
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 5px;
        }
        .actions button:disabled {
            background-color: #b0c4de;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Select Your Preferences</h1>
        <div id="preferences">
            <div class="preference" data-preference="Electricity">Electricity</div>
            <div class="preference" data-preference="Water">Water</div>
            <div class="preference" data-preference="Entertainment">Entertainment</div>
            <div class="preference" data-preference="Games">Games</div>
            <div class="preference" data-preference="Internet">Internet</div>
        </div>
        <div class="add-preference" id="addPreference">+ Add Preference</div>
        <div class="actions">
            <button id="skip">SKIP</button>
            <button id="submit">SUBMIT</button>
        </div>
    </div>
    <script>
        const preferences = document.querySelectorAll('.preference');
        const addPreference = document.getElementById('addPreference');
        const skipButton = document.getElementById('skip');
        const submitButton = document.getElementById('submit');

        preferences.forEach(pref => {
            pref.addEventListener('click', () => {
                pref.classList.toggle('selected');
            });
        });

        addPreference.addEventListener('click', () => {
            const newPref = prompt("Enter new preference:");
            if (newPref) {
                const prefDiv = document.createElement('div');
                prefDiv.className = 'preference';
                prefDiv.textContent = newPref;
                prefDiv.setAttribute('data-preference', newPref);
                prefDiv.addEventListener('click', () => {
                    prefDiv.classList.toggle('selected');
                });
                document.getElementById('preferences').appendChild(prefDiv);
            }
        });

        submitButton.addEventListener('click', () => {
            const selectedPreferences = [];
            document.querySelectorAll('.preference.selected').forEach(pref => {
                selectedPreferences.push(pref.getAttribute('data-preference'));
            });
            // AJAX call to save preferences
            console.log('Selected Preferences:', selectedPreferences);
            // Redirect after saving preferences
            window.location.href = 'dashboard.html';
        });

        skipButton.addEventListener('click', () => {
            window.location.href = 'dashboard.html';
        });
    </script>
</body>
</html>
