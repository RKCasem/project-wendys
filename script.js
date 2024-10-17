// script.js
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.getElementById('menuToggle');
    const mainContent = document.querySelector('main');
    const footer = document.querySelector('footer');
    const dashboardLink = document.querySelector('#sidebar a[href="#dashboard"]');

    menuToggle.addEventListener('click', function() {
        toggleSidebar();
    });

    dashboardLink.addEventListener('click', function(event) {
        event.preventDefault();
        toggleSidebar();
        const targetId = this.getAttribute('href').substring(1);
        const targetSection = document.getElementById(targetId);

        window.scrollTo({
            top: targetSection.offsetTop,
            behavior: 'smooth'
        });
    });

    function toggleSidebar() {
        if (sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
            sidebar.style.left = '-220px';
            mainContent.style.marginLeft = '0';
            footer.style.left = '0';
        } else {
            sidebar.classList.add('open');
            sidebar.style.left = '0px';
            mainContent.style.marginLeft = '220px';
            footer.style.left = '220px';
        }
    }

    document.getElementById('upgradeBtn').addEventListener('click', function() {
        alert('Upgrade your subscription to enjoy more features!');
    });
});
