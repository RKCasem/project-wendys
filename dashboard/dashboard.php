<?php
session_start();
include '../db.php'; // Database connection file

// Database connection settings
$servername = "localhost";  // Typically 'localhost' for XAMPP
$username = "root";         // Default username for XAMPP MySQL
$password = "";             // Default password for XAMPP MySQL (empty by default)
$dbname = "mydatabase";     // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL query to fetch the user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);  // 's' indicates the type (string)
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user with the provided email exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the provided password matches the stored password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['userLoggedIn'] = true;
            $_SESSION['userEmail'] = $user['email'];

            // Redirect to the dashboard
            header("Location: ../dashboard/dashboard.php");
            exit();
        } else {
            // If the password doesn't match
            echo "<p style='color:red;'>Invalid email or password!</p>";
        }
    } else {
        // If no user is found with the given email
        echo "<p style='color:red;'>Invalid email or password!</p>";
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill n' Chill</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard_style.css">
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
                <li><a href="#dashboard" onclick="showSection('dashboard')">Home</a></li>
                <li><a href="#transactions" onclick="redirectToTransactionPage()">Billing Information</a></li>
                <li><a href="#account-settings" onclick="redirectToAccountSettings()">Account Settings</a></li>
                <li><a href="#logout" onclick="logout()">Log Out</a></li>
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

        function redirectToTransactionPage() {
    // Redirect to the subscription transaction page
    window.location.href = '../subscription/subs.php';
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
            if (section === 'logout') {
        // Redirect to the logout page (index.php)
        window.location.href = '../index/index.php';
    } else {
        document.getElementById(section).classList.add('active');
    }
    
        }
        function logout() {
    // Clear session data or any necessary logout process
    sessionStorage.removeItem('userLoggedIn');
    localStorage.removeItem('userLoggedIn');

    // Redirect to the login page
    window.location.href = '../index/index.php';  // Redirect to the login page
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
        function redirectToAccountSettings() {
    // Redirect to the account settings page
    window.location.href = '../account/setting.php';
}

    </script>
</body>
</html>
