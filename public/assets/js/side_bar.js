// Get DOM elements
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const toggleBtn = document.getElementById('toggleBtn');

function toggleSidebar() {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');

        if (sidebar.classList.contains('active')) {
            toggleBtn.innerHTML = '✕';
        } else {
            toggleBtn.innerHTML = '☰';
        }
}

// Add event listeners
toggleBtn.addEventListener('click', toggleSidebar);
overlay.addEventListener('click', toggleSidebar);

// Initialize Lucide icons
lucide.createIcons();

