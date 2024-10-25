<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill n' Chill Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #111;
            color: #fff;
        }

        /* Card Styles */
        .card {
            background-color: #333;
            border: none;
            border-radius: 10px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .card-img-top {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            height: 180px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #ff4747;
        }

        .card-text {
            font-size: 14px;
            color: #ccc;
            height: 60px;
            overflow: hidden;
        }

        .btn-read-more {
            background-color: #ff4747;
            border: none;
            color: #fff;
            border-radius: 20px;
            padding: 8px 15px;
            text-align: center;
            transition: background-color 0.2s ease;
        }

        .btn-read-more:hover {
            background-color: #e44040;
            color: #fff;
        }

        /* Responsive Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        /* Main Content */
        main {
            padding: 40px;
            background-color: #2c2c2c;
            min-height: 100vh;
        }
    </style>
</head>

<body>
    <!-- Main Content -->
    <main>
        <div class="dashboard-grid" id="newsContainer">
            <!-- Cards will be dynamically generated here -->
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sample API URL (replace with your actual API endpoint)
            const apiURL = 'https://your-api-endpoint.com/news';

            // Fetch data from API
            fetch(apiURL)
                .then(response => response.json())
                .then(data => {
                    const newsContainer = document.getElementById('newsContainer');

                    // Iterate over API data and generate cards
                    data.articles.forEach(article => {
                        const cardHTML = `
                            <div class="card">
                                <img src="${article.image || 'default-image.jpg'}" class="card-img-top" alt="${article.title}">
                                <div class="card-body">
                                    <h5 class="card-title">${article.title}</h5>
                                    <p class="card-text">${article.description}</p>
                                    <a href="${article.url}" target="_blank" class="btn btn-read-more">Read More</a>
                                </div>
                            </div>
                        `;
                        newsContainer.innerHTML += cardHTML;
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
