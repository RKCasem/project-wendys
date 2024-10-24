<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill n' Chill</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #111;
            margin: 0;
            padding: 0;
            color: #fff;
        }

        /* Sidebar */
        aside {
            width: 240px;
            height: 100vh;
            background-color: #1c1c1c;
            padding: 20px;
            position: fixed;
            top: 0;
            left: -240px; /* Hidden by default */
            transition: left 0.3s ease; /* Animation for sliding in/out */
            z-index: 999;
        }

        .logo {
            cursor: pointer;
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu a {
            text-decoration: none;
            color: #fff;
            display: block;
            padding: 10px 0;
            font-size: 18px;
        }

        .sidebar-menu a:hover {
            color: #ff4747;
        }

        .subscription-btn {
            background-color: #ff4747;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 8px;
            color: #fff;
            text-align: center;
            cursor: pointer;
        }

        .subscription-btn:hover {
            background-color: #e44040;
        }

        /* Header */
        header {
            background-color: #0d0d0d;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }

        /* Arrow for toggle */
        .arrow {
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-top: 10px solid white; /* Arrow pointing up */
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        /* Rotate arrow when sidebar is open */
        .sidebar-open .arrow {
            transform: rotate(90deg); /* Arrow pointing to the left */
        }

        /* Header Right (stretches to fill the available space) */
        .header-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-grow: 1;
            padding-right: 20px;
        }

        .header-right input[type="text"] {
            padding: 8px 15px;
            border-radius: 20px;
            border: none;
            margin-right: 20px;
            background-color: #333;
            color: #fff;
        }

        .header-right .icon {
            color: white;
            font-size: 20px;
            margin-right: 20px;
            cursor: pointer;
        }

        .header-right img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
        }

        /* Main Content */
        main {
            padding-top: 80px; /* To prevent content from being hidden behind the header */
            background-color: #2c2c2c;
            min-height: 100vh;
            transition: margin-left 0.3s ease; /* Smooth transition */
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 20px;
        }

        .content-block {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
        }

        .content-block.red {
            background-color: #ff4747;
        }

        /* When the sidebar is open */
        .sidebar-open aside {
            left: 0; /* Sidebar slides into view */
        }

        .sidebar-open main {
            margin-left: 240px; /* Main content shifts to the right */
        }

        .sidebar-open header {
            padding-left: 270px; /* Header shifts to accommodate sidebar */
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside id="sidebar">
        <div class="logo">
            <img src="mobile-logo.png" alt="Bill n' Chill Logo" style="width: 50px; display: inline-block; vertical-align: middle;">
            <h2 style="display: inline-block; margin-left: 10px; vertical-align: middle; font-size: 20px;">Bill n' Chill</h2>
        </div>
        
        <div class="sidebar-menu">
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Billing</a></li>
                <li><a href="#">Transaction</a></li>
                <li><a href="#">Account</a></li>
                <li><a href="#">Settings</a></li>
            </ul>
        </div>
        <button class="subscription-btn">Upgrade your SUBSCRIPTION! CLICK ME!!</button>
    </aside>

    <!-- Header -->
    <header>
        <!-- Header Logo (acts as toggle for sidebar) -->
        <div class="logo" id="headerLogoToggle">
            <div class="arrow"></div> <!-- Arrow Icon for the header -->
        </div>

        <!-- Right Side: Search, Notification, User Avatar -->
        <div class="header-right">
            <input type="text" placeholder="Search here">
            <span class="icon">&#128276;</span> <!-- Notifications bell icon -->
            <img src="user-avatar.png" alt="User Avatar">
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="dashboard-grid" id="dashboard">
            <!-- Cards will be dynamically generated here from the API -->
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const headerLogoToggle = document.getElementById('headerLogoToggle');
            const body = document.body;

            // Toggle the sidebar when the logo in the header is clicked
            headerLogoToggle.addEventListener('click', function () {
                body.classList.toggle('sidebar-open');
            });

         // Fetch finance-related news from NewsAPI
         fetch('https://newsapi.org/v2/everything?q=finance&apiKey=91e4cec7ae99466bb4b50dd8788291c1')
            .then(response => response.json())
            .then(data => {
                let articles = data.articles.slice(0, 25); // Fetch 5 articles
                let content = '';

                // Loop through articles to generate cards dynamically
                articles.forEach(article => {
                    content += `
                        <div class="content-block card">
                            ${article.urlToImage ? `<img src="${article.urlToImage}" class="card-img-top" alt="${article.title}" style="border-radius: 8px 8px 0 0; height: 180px; object-fit: cover;">` : ''}
                            <div class="card-body" style="padding: 15px;">
                                <h4 class="card-title" style="font-size: 20px; font-weight: bold; color: #ff4747;">${article.title}</h4>
                                <p class="card-text" style="color: #ccc; font-size: 14px;">${article.description}</p>
                                <a href="${article.url}" target="_blank" class="btn btn-read-more" style="background-color: #ff4747; color: #fff; padding: 10px 15px; border-radius: 20px; text-decoration: none;">Read more</a>
                            </div>
                        </div>
                    `;
                });

                dashboard.innerHTML = content;
            })
            .catch(error => console.error('Error fetching news:', error));
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>