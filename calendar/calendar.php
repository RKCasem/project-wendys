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
    <link rel="stylesheet" href="styless.css">
    <script>
        // Embed PHP variables into JavaScript
        let currentMonth = <?php echo json_encode($currentMonth); ?>;
        let currentYear = <?php echo json_encode($currentYear); ?>;
        let theme = "<?php echo htmlspecialchars($theme, ENT_QUOTES, 'UTF-8'); ?>";
    </script>
</head>
<body class="<?php echo htmlspecialchars($theme, ENT_QUOTES, 'UTF-8'); ?>">
<div id="calendar" class="tab-content active">
<h2>calendar</h2>
    <div class="container">
        <div class="header">
            <h1><?php echo $viewType == 'month' ? $monthName . ' ' . $currentYear : ($viewType == 'year' ? 'Year ' . $currentYear : 'Week ' . $currentWeek); ?></h1>
            <button class="dark-mode-toggle" onclick="toggleDarkMode()">Toggle Dark Mode</button>
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
                <div>Sun</div>
                <div>Mon</div>
                <div>Tue</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div>Sat</div>
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

// Toggle dark mode
function toggleDarkMode() {
    const body = document.body;
    const currentTheme = body.classList.contains('dark') ? 'dark' : 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

    // Apply the new theme
    body.classList.remove(currentTheme);
    body.classList.add(newTheme);

    // Update URL parameter to persist theme
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('theme', newTheme);
    window.history.replaceState({}, '', `${location.pathname}?${urlParams}`);

    // Save the new theme to local storage
    localStorage.setItem('theme', newTheme);
}

// Initialize the theme on page load
function initializeTheme() {
    const savedTheme = localStorage.getItem('theme') || 'dark'; // Default to light theme if no preference is saved
    document.body.classList.add(savedTheme);

    // Update the URL parameter to reflect the current theme
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('theme') !== savedTheme) {
        urlParams.set('theme', savedTheme);
        window.history.replaceState({}, '', `${location.pathname}?${urlParams}`);
    }
}

// Call initializeTheme on page load
document.addEventListener('DOMContentLoaded', initializeTheme);

</script>
    </body>
    </html>
    