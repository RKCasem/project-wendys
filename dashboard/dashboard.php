<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill n' Chill</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
    /* General body styles */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #111;
        color: #fff;
        display: flex;
        transition: margin-left 0.3s ease-in-out;
    }

    /* Sidebar */
    nav#sidebar {
        width: 250px;
        background-color: #222;
        padding: 15px;
        position: fixed;
        height: 100%;
        overflow: auto;
        left: 0; /* Sidebar is initially visible */
        top: 0;
        transition: left 0.3s ease-in-out;
    }

    /* Sidebar title */
    nav#sidebar h2 {
        color: #ff4747;
        font-size: 24px;
        text-align: center;
    }

    /* Sidebar links */
    nav#sidebar a {
        color: #ddd;
        padding: 10px 15px;
        display: block;
        text-decoration: none;
        font-size: 18px;
        cursor: pointer;
    }

    nav#sidebar a:hover {
        background-color: #444;
        color: #fff;
    }

    /* Hamburger icon (default visible for all devices with screen size smaller than 1024px) */
    #hamburger-icon {
        display: none; /* Hidden by default */
        font-size: 30px;
        cursor: pointer;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 999;
        color: white;
    }

    /* Responsive styles for small screens (below 1024px) */
    @media (max-width: 1024px) {
        /* Show the hamburger icon for smaller screens */
        #hamburger-icon {
            display: block;
        }

        /* Initially hide the sidebar on small screens */
        nav#sidebar {
            width: 250px;
            left: -250px; /* Sidebar hidden initially */
        }

        /* Adjust body layout when sidebar is hidden */
        body {
            margin-left: 0;
        }
    }

    /* Fully responsive for smaller screens (below 768px) */
    @media (max-width: 768px) {
        nav#sidebar {
            width: 100%; /* Sidebar takes up the entire screen width */
        }

        body {
            margin-left: 0;
        }
    }




        .btn-outline-light {
            margin: 5px;
            color: #fff;
            border-color: #ff4747;
        }
        .btn-outline-light:hover {
            background-color: #ff4747;
            color: #fff;
        }
        /* Article Styling */
        .content-block {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            margin: 10px 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-wrap: wrap;
        }
        .content-block h5 {
            color: #ff4747;
            font-size: 20px;
            margin-bottom: 15px;
        }
        .content-block img {
            width: 200px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }
        .content-block .content-text {
            flex: 1;
        }
        .content-block p {
            color: #ddd;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .content-block a {
            background-color: #ff4747;
            padding: 8px 15px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        /* Loader */
        .loader {
            border: 8px solid rgba(255, 255, 255, 0.3);
            border-top: 8px solid #ff4747;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        /* Pagination */
        .pagination-button {
            margin: 5px;
            padding: 8px 15px;
            background-color: #e44040;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .pagination-button.active,
        .pagination-button:hover {
            background-color: #ff4747;
        }
    </style>
</head>
<body>
<span id="hamburger-icon" onclick="toggleSidebar()">&#9776;</span>

    <nav id="sidebar">
        <h2>Bill n' Chill</h2>
        <a href="javascript:void(0);" onclick="showSection('dashboard')">Dashboard</a>
        <a href="javascript:void(0);" onclick="toggleSubmenu('billing-submenu')">Billing Information</a>
        <div id="billing-submenu" class="submenu" style="display: none;">
            <a href="javascript:void(0);" onclick="showSection('transactions')">Transactions</a>
            <a href="javascript:void(0);" onclick="showSection('subscription')">Subscription</a>
        </div>
        <a href="javascript:void(0);" onclick="showSection('account-settings')">Account Settings</a>
        <a href="javascript:void(0);" onclick="showSection('logout')">Log Out</a>
    </nav>

    <main>
        <!-- Dashboard content -->
        <section id="dashboard" class="content-section" style="display: block;">
            <header><h2>Dashboard</h2></header>
            <div id="filters" class="text-center my-3">
                <button class="btn btn-outline-light" onclick="filterNews('finance')">Finance</button>
                <button class="btn btn-outline-light" onclick="filterNews('technology')">Technology</button>
                <button class="btn btn-outline-light" onclick="filterNews('entertainment')">Entertainment</button>
                <button class="btn btn-outline-light" onclick="filterNews('sports')">Sports</button>
            </div>
            <div id="news-container">Loading articles...</div>
            <div id="pagination" class="text-center my-4"></div>
        </section>

        <!-- Transactions content (hidden by default) -->
        <section id="transactions" class="content-section" style="display: none;">
            <header><h2>Transactions</h2></header>
            <p>Here you can view all your transactions.</p>
        </section>

        <!-- Subscription content (hidden by default) -->
        <section id="subscription" class="content-section" style="display: none;">
            <header><h2>Subscription</h2></header>
            <p>Here you can manage your subscription plans.</p>
        </section>

        <!-- Account Settings content (hidden by default) -->
        <section id="account-settings" class="content-section" style="display: none;">
            <header><h2>Account Settings</h2></header>
            <p>Here you can update your account settings.</p>
        </section>

        <!-- Log Out content (hidden by default) -->
        <section id="logout" class="content-section" style="display: none;">
            <header><h2>Log Out</h2></header>
            <p>Are you sure you want to log out?</p>
            <button onclick="logout()">Confirm Log Out</button>
        </section>
    </main>

    <script>
        let articles = [];
        let currentCategory = 'finance';
        let currentPage = 1;
        const pageSize = 5;

        // Fetch articles for the selected category
        async function fetchNews(category) {
            document.getElementById('news-container').innerHTML = '<div class="loader"></div>';
            try {
                const response = await fetch(`https://newsapi.org/v2/everything?q=${category}&apiKey=2490b1f670994bcab9e3f40524b6110b`);
                const data = await response.json();
                articles = data.articles.filter(article => article.urlToImage && article.description && article.url);
                renderNews();
            } catch {
                document.getElementById('news-container').innerHTML = '<div>Error fetching news. Please try again later.</div>';
            }
        }

        // Render the articles
        function renderNews() {
            const newsContainer = document.getElementById('news-container');
            const start = (currentPage - 1) * pageSize;
            const currentArticles = articles.slice(start, start + pageSize);

            newsContainer.innerHTML = currentArticles.map(({ urlToImage, title, description, url }) => `
                <div class="content-block">
                    <img src="${urlToImage || 'https://via.placeholder.com/200x120'}" alt="${title}">
                    <div class="content-text">
                        <h5>${title}</h5>
                        <p>${description}</p>
                        <a href="${url}" target="_blank">Read More</a>
                    </div>
                </div>
            `).join('');

            renderPagination();
        }

        // Render pagination controls
        function renderPagination() {
            const paginationContainer = document.getElementById('pagination');
            const pageCount = Math.ceil(articles.length / pageSize);
            paginationContainer.innerHTML = '';

            let startPage, endPage;

            // If there are too many pages, display a range with ellipses
            if (pageCount <= 5) {
                // Show all pages if the total pages are 5 or fewer
                startPage = 1;
                endPage = pageCount;
            } else {
                // Show the first few pages, the last few pages, and an ellipsis in the middle
                startPage = Math.max(1, currentPage - 2);
                endPage = Math.min(pageCount, currentPage + 2);

                if (startPage > 1) {
                    paginationContainer.innerHTML += `<button class="pagination-button" onclick="goToPage(1)">1</button>`;
                    if (startPage > 2) {
                        paginationContainer.innerHTML += `<span class="pagination-button">...</span>`;
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    paginationContainer.innerHTML += `<button class="pagination-button ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
                }

                if (endPage < pageCount) {
                    if (endPage < pageCount - 1) {
                        paginationContainer.innerHTML += `<span class="pagination-button">...</span>`;
                    }
                    paginationContainer.innerHTML += `<button class="pagination-button" onclick="goToPage(${pageCount})">${pageCount}</button>`;
                }
            }
        }


        // Filter articles by category
        function filterNews(category) {
            currentCategory = category;
            currentPage = 1;
            fetchNews(category);
        }

        // Change page
        function goToPage(page) {
            currentPage = page;
            renderNews();
        }

        // Log out functionality
        function logout() {
            alert('You have been logged out.');
        }

        // Initial fetch for news
        fetchNews(currentCategory);
        // Toggle sidebar visibility
                function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const isSidebarVisible = sidebar.style.left === '0px';
            if (isSidebarVisible) {
                sidebar.style.left = '-250px'; // Hide sidebar
                document.body.style.marginLeft = '0'; // Adjust body margin
            } else {
                sidebar.style.left = '0px'; // Show sidebar
                document.body.style.marginLeft = '250px'; // Adjust body margin
            }
        }
    // Function to toggle the sidebar visibility
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const isSidebarVisible = sidebar.style.left === '0px';
        if (isSidebarVisible) {
            sidebar.style.left = '-250px'; // Hide sidebar
            document.body.style.marginLeft = '0'; // Reset body margin
        } else {
            sidebar.style.left = '0px'; // Show sidebar
            document.body.style.marginLeft = '250px'; // Push body content to the right
        }
    }

    // Function to show the section content
    function showSection(sectionId) {
        // Hide all sections
        document.querySelectorAll('.content-section').forEach(section => section.style.display = 'none');
        // Show the selected section
        document.getElementById(sectionId).style.display = 'block';
    }

    // Toggle submenu visibility
    function toggleSubmenu(id) {
        const submenu = document.getElementById(id);
        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
    }


    </script>
</body>
</html>
