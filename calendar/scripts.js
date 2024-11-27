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

