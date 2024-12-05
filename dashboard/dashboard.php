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
            color: #fff;
            display: flex;
            flex-direction: column;
            margin-left: 0;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }
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

        .btn-outline-light {
            margin: 5px;
            color: #fff;
            border-color: #ff4747;
        }

        .btn-outline-light:hover {
            background-color: #ff4747;
            color: #fff;
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

        /* Consistent article block layout */
        .content-block {
            display: flex;
            margin-bottom: 15px;
            background-color: #444;
            border-radius: 8px;
            padding: 15px;
            align-items: center;
        }

        .content-block img {
            width: 120px;
            height: 130px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }

        .content-text {
            flex-grow: 1;
        }

        .content-text h5 {
            margin: 0 0 10px;
        }

        .content-text p {
            margin: 0 0 10px;
        }

        .content-text a {
            color: #ff4747;
        }
    </style>
</head>
<body>
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
                <li><a href="../account-settings/settings.php" onclick="showSection('account-settings')">Account Settings</a></li>
                <li><a href="#logout" onclick="showSection('logout')">Log Out</a></li>
            </ul>
        </nav> 
    </div>
    <main>
        <section id="dashboard" class="content-section active">
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

        <!-- Other sections -->

    </main>
    <div class="menu-bg" id="menu-bg"></div>

    <script>
        let articles = [];
        let currentCategory = 'finance';
        let currentPage = 1;
        const pageSize = 5;

        async function fetchNews(category) {
        document.getElementById('news-container').innerHTML = '<div class="loader"></div>';
        try {
            const response = await fetch(`https://newsapi.org/v2/everything?q=${category}&apiKey=2490b1f670994bcab9e3f40524b6110b`);
            const data = await response.json();
            articles = data.articles || []; // Ensure articles is an empty array if no articles found

            // Filter out invalid articles directly here
            articles = articles.filter(article => article.title && article.description && article.url && article.urlToImage);

            // If no valid articles are found, replace them with a custom empty article
            if (articles.length === 0) {
                console.warn('No valid articles found.');
                articles = [{ title: 'No articles available', description: 'Sorry, no articles were found in this category.', url: '#', urlToImage: 'https://via.placeholder.com/120' }];
            }

            displayNews();
            setupPagination();
        } catch (error) {
            console.error('Error fetching articles:', error);

            // In case of an error, handle it gracefully by not showing fallback articles
            articles = [{ title: 'Error loading articles', description: 'Sorry, there was an issue fetching the news.', url: '#', urlToImage: 'https://via.placeholder.com/120' }];
            
            displayNews();
            setupPagination();
        }
    }

 
        function displayNews() {

            const startIndex = (currentPage - 1) * pageSize;
            const endIndex = startIndex + pageSize;
            let articlesToDisplay = articles.slice(startIndex, endIndex);

            // Replace missing or invalid articles with the next valid one
            articlesToDisplay = articlesToDisplay.map(article => {
                if (!article.title || !article.description || !article.url || !article.urlToImage) {
                    // Replace with the next valid article if the current one is missing critical data
                    return getNextValidArticle();
                }
                return article;
            });

            const container = document.getElementById('news-container');
            container.innerHTML = ''; // Clear previous articles

            articlesToDisplay.forEach(article => {
                const articleDiv = document.createElement('div');
                articleDiv.classList.add('content-block');
                
                const imageUrl = article.urlToImage || 'https://via.placeholder.com/120'; // Placeholder image if no image found
                const articleTitle = article.title;
                const articleDescription = article.description;

                articleDiv.innerHTML = `
                    <img src="${imageUrl}" alt="Article Image" onerror="this.onerror=null;this.src='https://via.placeholder.com/120';">
                    <div class="content-text">
                        <h5>${articleTitle}</h5>
                        <p>${articleDescription}</p>
                        <a href="${article.url}" target="_blank">Read more</a>
                    </div>
                `;
                container.appendChild(articleDiv);
            });
        }

        function getNextValidArticle() {
            // Get the next valid article from the articles array
            for (let i = 0; i < articles.length; i++) {
                const article = articles[i];
                if (article.title && article.description && article.url && article.urlToImage) {
                    return article;
                }
            }
            // If no valid article is found, return an empty article (or some default behavior)
            return { title: 'No valid article available', description: 'Sorry, there was an issue loading the article.', url: '#', urlToImage: 'https://via.placeholder.com/120' };
        }



        function setupPagination() {

            const totalPages = Math.ceil(articles.length / pageSize);
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = ''; // Clear previous pagination buttons

            const maxPagesToShow = 7; // Maximum number of page buttons to show before ellipsis

            // Always show the first page and the last page
            let startPage = 1;
            let endPage = totalPages;

            // Check if the total pages exceed the maximum pages to show
            if (totalPages > maxPagesToShow) {
                // If the current page is near the beginning or end, adjust the range to show
                if (currentPage <= maxPagesToShow - 3) {
                    endPage = maxPagesToShow - 1; // Show first 7 pages
                } else if (currentPage >= totalPages - 3) {
                    startPage = totalPages - maxPagesToShow + 2; // Show last 7 pages
                } else {
                    startPage = currentPage - 3; // Show pages around the current page
                    endPage = currentPage + 3;
                }
            }

            // Create the page buttons (1 to 7, with ellipsis if needed)
            for (let i = startPage; i <= endPage; i++) {
                const button = document.createElement('button');
                button.classList.add('pagination-button');
                button.textContent = i;
                button.onclick = () => goToPage(i);
                if (i === currentPage) button.classList.add('active');
                pagination.appendChild(button);
            }

            // Add the first page and ellipsis if necessary
            if (startPage > 1) {
                const ellipsis = document.createElement('span');
                ellipsis.textContent = '...';
                pagination.insertBefore(ellipsis, pagination.firstChild);
            }

            // Add the last page and ellipsis if necessary
            if (endPage < totalPages) {
                const ellipsis = document.createElement('span');
                ellipsis.textContent = '...';
                pagination.appendChild(ellipsis);
            }

            // Add the last page button
            if (endPage < totalPages) {
                const lastButton = document.createElement('button');
                lastButton.classList.add('pagination-button');
                lastButton.textContent = totalPages;
                lastButton.onclick = () => goToPage(totalPages);
                pagination.appendChild(lastButton);
            }
        }



        function goToPage(page) {
            currentPage = page;
            displayNews();
            setupPagination();
        }

        function filterNews(category) {
            currentCategory = category;
            currentPage = 1;
            fetchNews(category);
        }

        // Initialize with default category
        fetchNews(currentCategory);
        
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
        }
    </script>
</body>
</html>
