let articles = []; // Store fetched articles
let currentCategory = 'finance'; // Default category
let currentPage = 1; // Pagination state
const pageSize = 5; // Articles per page

// Fetch and filter articles by category
async function fetchNews(category) {
    document.getElementById('news-container').innerHTML = '<div class="loader"></div>';
    try {
        const response = await fetch(`https://newsapi.org/v2/everything?q=${category}&apiKey=91e4cec7ae99466bb4b50dd8788291c1`);
        const data = await response.json();
        articles = data.articles;
        renderNews();
    } catch (error) {
        document.getElementById('news-container').innerHTML = '<div>Error fetching news. Please try again later.</div>';
    }
}

// Render articles and pagination
function renderNews() {
    const newsContainer = document.getElementById('news-container');
    const start = (currentPage - 1) * pageSize;
    const end = start + pageSize;
    const currentArticles = articles.slice(start, end);

    // Render articles
    newsContainer.innerHTML = currentArticles.map(article => `
        <div class="content-block">
            <h5>${article.title}</h5>
            <img src="${article.urlToImage}" alt="${article.title}">
            <p>${article.description}</p>
            <a href="${article.url}" target="_blank" class="btn btn-danger">Read More</a>
        </div>
    `).join('');

    renderPagination();
}

// Filter news by selected category
function filterNews(category) {
    currentCategory = category;
    currentPage = 1;
    fetchNews(category);
}

// Render pagination controls
function renderPagination() {
    const pageCount = Math.ceil(articles.length / pageSize);
    const paginationContainer = document.getElementById('pagination');
    paginationContainer.innerHTML = '';

    for (let i = 1; i <= pageCount; i++) {
        paginationContainer.innerHTML += `<button class="pagination-button ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
    }
}

// Go to selected page
function goToPage(page) {
    currentPage = page;
    renderNews();
}

// Initial fetch for default category
fetchNews(currentCategory);
