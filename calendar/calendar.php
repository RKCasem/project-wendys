<?php
include '../db.php'; // Include the database connection

// Function to fetch subscriptions
function fetch_subscriptions($conn) {
    return $conn->query("SELECT * FROM subscriptions");
}

// Function to get current date details
function get_current_date($field, $default) {
    return isset($_GET[$field]) ? $_GET[$field] : date($default);
}

// Function to calculate the first and last day of the month
function calculate_days($currentYear, $currentMonth) {
    $firstDay = strtotime("$currentYear-$currentMonth-01");
    $lastDay = strtotime("$currentYear-$currentMonth-" . date('t', $firstDay));
    return [$firstDay, $lastDay];
}


// Fetch subscriptions
$subscriptions = fetch_subscriptions($conn);

// Get current month, week, and year
$currentMonth = get_current_date('month', 'm');
$currentYear = get_current_date('year', 'Y');
$currentWeek = get_current_date('week', 'W');

// Get the view type (year, month, or week)
$viewType = isset($_GET['view']) ? $_GET['view'] : 'month';  // Default view is 'month'

// Calculate the first and last day of the current month
list($firstDay, $lastDay) = calculate_days($currentYear, $currentMonth);

// Get the month name for the header
$monthName = date('F', $firstDay);

// Generate the calendar
$daysInMonth = date('t', $firstDay);
$firstDayOfWeek = date('w', $firstDay);

// Default theme
$theme = isset($_GET['theme']) ? $_GET['theme'] : 'dark';  // Default theme is 'light'
?>
<!DOCTYPE html>
<html lang="en">      
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Calendar</title>
    <style>

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: rgba(255, 255, 255, 0.944);
}


.calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr); /* 7 columns for days of the week */
    grid-gap: 10px;
    width: 100%;
    margin-top: 20px;
    color: white;

}

.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    font-weight: bold;
    background-color: #4b4b4b;
    padding: 10px 0;
    border-radius: 5px;
    margin-bottom: 10px;
}

.calendar-day,
.calendar-weekday {
    padding: 15px;
    text-align: center;
    border: 1px solid #ddd;
    cursor: pointer;
    position: relative;
    background-color: #ff4747;
    transition: all 0.3s ease;
    border-radius: 5px;
}

.calendar-day.highlight,
.calendar-weekday.highlight {
    background-color: #bebdb8;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    color: white;
}

.calendar-day .subscription-info,
.calendar-weekday .subscription-info {
    position: absolute;
    bottom: 5px;
    left: 5px;
    font-size: 10px;
    color: #333;
    background-color: rgba(255, 255, 255, 0.7);
    padding: 3px;
    border-radius: 3px;
    display: none;
    }
    
    .calendar-nav {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        font-size: 16px;
        
    }
    
    .calendar-nav a {
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        background-color: #656565;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    
    .calendar-nav a:hover {
        background-color: #ff4747;
    }
    
    .view-picker {
        margin-bottom: 20px;
    }
    
    .view-picker select {
        padding: 5px;
        border-radius: 5px;
        border: 1px solid #ff4747;
        background-color: #535353;
        color: white;
    }
    
    /* Year View */
    .calendar-year {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-gap: 20px;
        margin-top: 20px;
    }
    
    .calendar-month {
        padding: 20px;
        background-color: #48494b;
        border: 1px solid #ff4747;
        border-radius: 5px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .calendar-month:hover {
        background-color: #bebdb8;
    }
    
    /* Week View */
    .calendar-week {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        grid-gap: 10px;
        width: 100%;
    }
    /* Sidebar styling */
     
        .sidebar { 
            position: fixed; 
            right: 0; 
            top: 0; 
            width: 300px; 
            height: 100%;
            background-color: #4b4b4b; 
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
            padding: 20px; 
            overflow-y: auto; 
            z-index: 1000; 
            transform: translateX(100%);
            transition: transform 0.3s ease; 

        }
            
        .sidebar.show {
            display: block;
            transform: translateX(0);
        }

        .sidebar h3 {
            margin-top: 0;
        }

        .close-btn { 
            background-color: #ff4747;
            color: rgb(237, 236, 236); 
            border: 1px;
            padding: 10px 20px; 
            cursor: pointer; 
            display: block; 
            width: 100%; 
            margin-top: 20px; 
            text-align: center; 
        
        }

        .close-btn:hover {
            background-color: #666;
        }

        .subscription-info { 
            margin: 10px 0; 
            padding: 10px; 
            border: 1px solid #ddd; 
            background-color: #48494b; 
            border-radius: 5px
        }
        .highlight { 
            background-color: blue; /* Highlight color */ }
        /* Smooth transition for theme change */

        body {
            transition: background-color 0.5s ease, color 0.5s ease-in-out;
        }

        /* Example dark mode styles */
        body.dark {
            background-color: #333;
            color: #f1f1f1;
        }

        /* General styles for the calendar days */
        /* Calendar Days */

        #menu {
            position: fixed; /* Keep the menu fixed */
            top: 50%; /* Center vertically within the viewport */
            left: 20px; /* Align slightly from the left */
            transform: translateY(30%); /* Adjust position for accurate centering */
            z-index: 2;
        }

        #menu-bar {
            width: 50px;
            height: 40px;
            margin-left: 25px;
            margin-top: 10px; /* Remove extra margin to prevent offset */
            cursor: pointer;
        }
        .menu-bg {
            position: fixed;
            top: 50%; /* Match the menu's vertical alignment */
            left: 50px;
            transform: translateY(-30%);
            width: 0; /* Initial size */
            height: 0; /* Initial size */
            background: radial-gradient(circle, #DC052D, #DC052D);
            border-radius: 100%;
            z-index: 1;
            transition: 0.3s ease;
        }

        .menu-bg.change-bg {
            width: 800px; /* Increased size */
            height: 550px; /* Increased size */
            transform: translate(-70%, -12%); /* Adjust centering as needed */
        }


        .menu-bg, #menu {
            top: 0;
            left: 0;
            position: absolute;
        }        
        .bar {
            height: 5px;
            width: 100%;
            background-color: #DC052D;
            display: block;
            border-radius: 5px;
            transition: 0.3s ease;
        }

        #bar1 {
            transform: translateY(-4px);
        }

        #bar3 {
            transform: translateY(4px);
        }

        .nav {
            transition: 0.3s ease;
            display: none;
        }

        .nav ul {
            padding: 0 22px;
        }

        .nav li {
            list-style: none;
            padding: 12px 0;
        }

        .nav li a {
            color: white;
            font-size: 20px;
            text-decoration: none;
        }

        .nav li a:hover {
            font-weight: bold;
        }

        .change {
            display: block;
        }

        .change .bar {
            background-color: white;
        }

        .change #bar1 {
            transform: translateY(4px) rotateZ(-45deg);
        }

        .change #bar2 {
            opacity: 0;
        }

        .change #bar3 {
            transform: translateY(-6px) rotateZ(45deg);
        }

        .change-bg {
            width: 520px;
            height: 460px;
            transform: translate(-60%,-30%);
        }


        /* Consistent Section Styling */
        .content-section {
            display: none;
            padding: 20px;
            background-color: #333;
            border-radius: 10px;
            margin-top: 50px;
        }

        .content-section.active {
            display: block;
        }
.calendar-day {
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 15px;
    background-color: #48494b;
    border: 1px solid #ff4747   ;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

/* Highlight the day if it has a subscription */
.calendar-day.highlight {
    background-color: calc(25,10,25); /* Highlight color */
    transform: scale(1); /* Slightly enlarge the day */
    box-shadow: 0 0 10px rgba(255, 71, 71, 0.6); /* Add a subtle glow effect */
}

/* Calendar Days Hover Effect */
.calendar-day:hover {
    background-color: #bebdb8;
    transform: scale(1.05);
    box-shadow: 0 0 5px rgba(255, 71, 71, 0.6);
}

/* Show subscription info on hover or click */
.calendar-day.highlight:hover .subscription-info,
.calendar-day.highlight:focus .subscription-info {
    display: inline-block;
    animation: fadeIn 0.3s ease-in-out;
}
.container {
    background-color: #444;
    padding: 30px;
    border-radius: 20px; /* Larger rounded corners for a softer look */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2); /* Deeper shadow */
    width: 80%;
    margin: auto;
    color: #fff;
    font-family: 'Arial', sans-serif;
    transition: box-shadow 0.4s ease, transform 0.4s ease, background-color 0.4s ease;
    position: relative;
    overflow: hidden;
    animation: fadeIn 1s ease-in-out; /* Fade in animation for the container */
    margin-top: 10px;
}

.container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.05);
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.container:hover {
    box-shadow: 0 10px 30px rgba(255, 0, 0, 0.7);
    transform: translateY(-15px);
    background-color: #555;
}

.container:hover::before {
    opacity: 0; /* Fade out overlay on hover */
}

/* Fade-in animation for subscription info */
@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: translateY(10px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}
@media (max-width: 480px) {
    .calendar {
        grid-template-columns: repeat(4, 1fr); /* 4 columns on very small screens */
    }

    .calendar-header {
        font-size: 12px;
    }

    .calendar-day {
        padding: 8px;
    }
}
/* Responsive Calendar */
@media (max-width: 768px) {
    .calendar {
        grid-template-columns: repeat(5, 1fr); /* 5 columns on smaller screens */
    }

    .calendar-header {
        font-size: 14px;
    }

    .calendar-day {
        padding: 10px;
    }
}

    .tab-content {
        margin-top: 10px;
        margin-left: 80px;
        margin-right: 17px;
    }

    </style>
    <script>
        // Embed PHP variables into JavaScript
        let currentMonth = <?php echo json_encode($currentMonth); ?>;
        let currentYear = <?php echo json_encode($currentYear); ?>;
        let theme = "<?php echo htmlspecialchars($theme, ENT_QUOTES, 'UTF-8'); ?>";
        // function goToSubscriptions() {
        //     window.location.href = "../subscription/subscription_transaction.php"; // Update the URL if necessary
        // }


        function showSection(section) {
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(s => s.classList.remove('active'));
            document.getElementById(section).classList.add('active');
        }

        function menuOnClick() {
            const menu = document.getElementById("nav");
            const menuBar = document.getElementById("menu-bar");
            const menuBg = document.getElementById("menu-bg");

            menu.classList.toggle("change");
            menuBar.classList.toggle("change");
            menuBg.classList.toggle("change-bg");
            menu.style.display = menu.classList.contains("change") ? "block" : "none";
                document.addEventListener('DOMContentLoaded', () => {
                // Reapply event listeners if dynamic content is loaded
                document.getElementById('menu-bar').addEventListener('click', menuOnClick);
                });
        }
    </script>
</head>
<body class="<?php echo htmlspecialchars($theme, ENT_QUOTES, 'UTF-8'); ?>">
<!-- <button class="subscription-button" id="calendar" onclick="goToSubscriptions()">Go to Subscriptions</button> -->

<div id="menu">
        <div id="menu-bar" onclick="menuOnClick()">
            <div id="bar1" class="bar"></div>
            <div id="bar2" class="bar"></div>
            <div id="bar3" class="bar"></div>
        </div>
        <nav class="nav" id="nav">
            <ul>
                <li><a href="../dashboard/dashboard.php" onclick="showSection('dashboard')">Home</a></li>
                <li><a href="../subscription/subscription_transaction.php" onclick="showSection('transactions')">Billing Information</a></li>
                <li><a href="../account/setting.php" onclick="showSection('account-settings')">Account Settings</a></li>
                <li><a href="../index/index.php" onclick="showSection('logout')">Log Out</a></li>
            </ul>
        </nav> 
    </div>
    <div class="menu-bg" id="menu-bg"></div>

<div id="calendar" class="tab-content active">
<h2>Calendar</h2>
    <div class="container">
        <div class="header">
            <h1><?php echo $viewType == 'month' ? $monthName . ' ' . $currentYear : ($viewType == 'year' ? 'Year ' . $currentYear : 'Week ' . $currentWeek); ?></h1>
          
        </div>
        
        <div class="calendar-nav">
            <!-- Previous link -->
            <?php
            if ($viewType == 'month') {
                $prevMonth = ($currentMonth - 1 < 1) ? 12 : $currentMonth - 1;
                $prevYear = ($currentMonth - 1 < 1) ? $currentYear - 1 : $currentYear;
                echo '<a href="calendar.php?view=month&month=' . $prevMonth . '&year=' . $prevYear . '&theme=' . $theme . '">Previous</a>';
            } elseif ($viewType == 'year') {
                $prevYear = $currentYear - 1;
                echo '<a href="calendar.php?view=year&year=' . $prevYear . '&theme=' . $theme . '">Previous</a>';
            } else {
                $prevWeek = $currentWeek - 1;
                $prevYear = ($prevWeek < 1) ? $currentYear - 1 : $currentYear;
                $prevWeek = ($prevWeek < 1) ? 52 : $prevWeek;
                echo '<a href="calendar.php?view=week&week=' . $prevWeek . '&year=' . $prevYear . '&theme=' . $theme . '">Previous</a>';
            }
            ?>
            
            <!-- Next link -->
            <?php
            if ($viewType == 'month') {
                $nextMonth = ($currentMonth + 1 > 12) ? 1 : $currentMonth + 1;
                $nextYear = ($currentMonth + 1 > 12) ? $currentYear + 1 : $currentYear;
                echo '<a href="calendar.php?view=month&month=' . $nextMonth . '&year=' . $nextYear . '&theme=' . $theme . '">Next</a>';
            } elseif ($viewType == 'year') {
                $nextYear = $currentYear + 1;
                echo '<a href="calendar.php?view=year&year=' . $nextYear . '&theme=' . $theme . '">Next</a>';
            } else {
                $nextWeek = $currentWeek + 1;
                $nextYear = ($nextWeek > 52) ? $currentYear + 1 : $currentYear;
                $nextWeek = ($nextWeek > 52) ? 1 : $nextWeek;
                echo '<a href="calendar.php?view=week&week=' . $nextWeek . '&year=' . $nextYear . '&theme=' . $theme . '">Next</a>';
            }
            ?>
            
        </div>

        <!-- View Selector -->
        <div class="view-picker">
            <label for="view">View: </label>
            <select id="view" onchange="changeView(this.value)">
                <option value="month" <?php if ($viewType == 'month') echo 'selected'; ?>>Month</option>
                <option value="year" <?php if ($viewType == 'year') echo 'selected'; ?>>Year</option>
                <option value="week" <?php if ($viewType == 'week') echo 'selected'; ?>>Week</option>
            </select>
        </div>

        <!-- Calendar Header (Day names for month and week views) -->
        <?php if ($viewType == 'month' || $viewType == 'week') : ?>
            <div class="calendar-header">
                <div>Sunday</div>
                <div>Monday</div>
                <div>Tuesday</div>
                <div>Wednesday</div>
                <div>Thursday</div>
                <div>Friday</div>
                <div>Saturday</div>
            </div>
        <?php endif; ?>

        <!-- Calendar Days for month view -->
        <?php if ($viewType == 'month') : ?>
            <div class="calendar">
                <?php
                // Print empty days before the first day of the month
                for ($i = 0; $i < $firstDayOfWeek; $i++) {
                    echo '<div class="calendar-day"></div>';
                }

                // Print the days of the month
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $currentDate = "$currentYear-$currentMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT);

                    // Check if the current day has a subscription
                    $highlight = '';
                    $subscriptionInfo = [];
                    $subscriptions->data_seek(0);  // Reset result pointer
                    while ($row = $subscriptions->fetch_assoc()) {
                        $startDate = $row['start_date'];
                        $endDate = $row['end_date'];

                        // Convert dates to timestamps for easier comparison
                        $startTimestamp = strtotime($startDate);
                        $endTimestamp = strtotime($endDate);
                        $currentTimestamp = strtotime($currentDate);

                        // Check if the current day is within the subscription range
                        
                            if ($currentTimestamp >= $startTimestamp && $currentTimestamp <= $endTimestamp) {
                                $highlight = 'highlight';
                                $subscriptionInfo[] = $row['name'] . ' - ' . $row['amount'];
                            }
                        }
    
                        // Display the day
                        echo '<div class="calendar-day ' . $highlight . '" onclick="showDetails(\'' . $currentDate . '\')">
                                <span>' . $day . '</span>
                                <div class="subscription-info">' . implode('<br>', $subscriptionInfo) . '</div>
                              </div>';
                    }
                    ?>
                </div>
            <?php elseif ($viewType == 'year') : ?>
                <!-- Year View -->
                <div class="calendar-year">
                    <?php
                    for ($month = 1; $month <= 12; $month++) {
                        $firstDayOfMonth = strtotime("$currentYear-$month-01");
                        $monthName = date('F', $firstDayOfMonth);
                        echo '<div class="calendar-month" onclick="changeMonth(' . $month . ')">' . $monthName . '</div>';
                    }
                    ?>
                </div>
            <?php elseif ($viewType == 'week') : ?>
                <!-- Week View -->
                <?php
// Week View (continued)
echo '<div class="calendar-week">';
$weekStart = strtotime("$currentYear-W$currentWeek-1"); // Get the start of the week
for ($i = 0; $i < 7; $i++) {
    $currentDate = date('Y-m-d', strtotime("+$i days", $weekStart));
    $highlight = '';
    $subscriptionInfo = [];
    $subscriptions->data_seek(0);  // Reset result pointer
    
    // Loop through subscriptions and check if the current date is within the subscription range
    while ($row = $subscriptions->fetch_assoc()) {
        $startDate = $row['start_date'];
        $endDate = $row['end_date'];
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);
        $currentTimestamp = strtotime($currentDate);

        if ($currentTimestamp >= $startTimestamp && $currentTimestamp <= $endTimestamp) {
            $highlight = 'highlight';
            $subscriptionInfo[] = $row['name'] . ' - ' . $row['amount'];
        }
    }

    // Display the day with highlighted subscription
    echo '<div class="calendar-day ' . $highlight . '" onclick="showDetails(\'' . $currentDate . '\')">
            <span>' . date('j', strtotime($currentDate)) . '</span>
            <div class="subscription-info">' . implode('<br>', $subscriptionInfo) . '</div>
          </div>';
}
echo '</div>';
?>

                </div>
            <?php endif; ?>
    
<!-- Sidebar for details -->
<div id="sidebar" class="sidebar">
    <h3>Subscription Details</h3>
    <ul id="subscription-list"></ul>
    <button class="close-btn" onclick="closeSidebar()">Close</button>
</div>


<script>
    let outsideClickListenerAdded = false;

    function showDetails(date) {
        const sidebar = document.getElementById('sidebar');
        const subscriptionList = document.getElementById('subscription-list');
        
        // Fetch subscription details from PHP or database
        fetch('fetch_subscriptions.php?date=' + date)
            .then(response => response.json())
            .then(data => {
                subscriptionList.innerHTML = ''; // Clear previous details
                if (data.length > 0) {
                    data.forEach(item => {
                        const li = document.createElement('li');
                        li.innerHTML = `<div class="subscription-info">
                                        <strong>${item.name}</strong><br>
                                        Amount: ${item.amount}<br>
                                        Start: ${item.start_date}<br>
                                        End: ${item.end_date}
                                        </div>`;
                        subscriptionList.appendChild(li);
                    });
                } else {
                    const li = document.createElement('li');
                    li.textContent = 'No subscriptions found for this date.';
                    subscriptionList.appendChild(li);
                }
            });

        sidebar.classList.add('show');
        sidebar.classList.remove('hide');
        
        // Add outside click listener only once
        if (!outsideClickListenerAdded) {
            document.addEventListener('click', outsideClickListener);
            outsideClickListenerAdded = true;
        }
    }

    function outsideClickListener(event) {
        const sidebar = document.getElementById('sidebar');
        if (!sidebar.contains(event.target) && !event.target.matches('.calendar-day, .calendar-weekday')) {
            closeSidebar();
        }
    }

    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.add('hide');
        sidebar.classList.remove('show');

        // Remove outside click listener after closing the sidebar
        document.removeEventListener('click', outsideClickListener);
        outsideClickListenerAdded = false;
    }

    // Change view (year, month, week)
    function changeView(view) {
        window.location.href = 'calendar.php?view=' + view + '&month=' + currentMonth + '&year=' + currentYear + '&theme=' + theme;
    }

    // Change month in year view
    function changeMonth(month) {
        window.location.href = 'calendar.php?view=month&month=' + month + '&year=' + currentYear + '&theme=' + theme;
    }


    // Initialize the theme on page load


    // Call initializeTheme on page load
    document.addEventListener('DOMContentLoaded', initializeTheme);

</script>
    </body>
    </html>
    
