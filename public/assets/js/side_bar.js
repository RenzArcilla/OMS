// Get DOM elements
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const toggleBtn = document.getElementById('toggleBtn');
const mainContent = document.getElementById('content-area');

// Sidebar toggle functionality
function toggleSidebar() {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');

    if (sidebar.classList.contains('active')) {
        toggleBtn.innerHTML = '☰';
    } else {
        toggleBtn.innerHTML = '✕';
    }
}

// Add event listeners for sidebar toggle
if (toggleBtn) {
    toggleBtn.addEventListener('click', toggleSidebar);
}
if (overlay) {
    overlay.addEventListener('click', toggleSidebar);
}

// Initialize Lucide icons (if available)
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}

// Navigation functionality for AJAX content loading
function initializeNavigation() {
    const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
    
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove active class from all links
            sidebarLinks.forEach(a => a.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');

            // Get the page URL
            const pageUrl = this.getAttribute('href');
            
            if (pageUrl && pageUrl !== '#' && mainContent) {
                // Show loading state
                mainContent.innerHTML = '<div class="loading">Loading...</div>';
                
                // Fetch page content
                fetch(pageUrl)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(data => {
                        // Extract only the content, not the entire HTML page
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(data, 'text/html');
                        
                        // Look for specific content containers
                        let content = doc.querySelector('.page-content') || 
                                    doc.querySelector('.content-area') || 
                                    doc.querySelector('.main-content') ||
                                    doc.querySelector('main') ||
                                    doc.querySelector('body');
                        
                        if (content) {
                            // Extract only the inner content
                            mainContent.innerHTML = content.innerHTML;
                        } else {
                            // Fallback: show the data as-is
                            mainContent.innerHTML = data;
                        }
                        
                        // Re-initialize any page-specific JavaScript
                        if (typeof initializePageScripts === 'function') {
                            initializePageScripts();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading page:', error);
                        if (mainContent) {
                            mainContent.innerHTML = `
                                <div class="error-message">
                                    <h2>Error Loading Page</h2>
                                    <p>Could not load the requested page.</p>
                                    <button onclick="location.reload()">Retry</button>
                                </div>
                            `;
                        }
                    });
            } else if (pageUrl === '#') {
                // Handle dashboard or default content
                if (mainContent) {
                    mainContent.innerHTML = `
                        <div class="welcome-message">
                            <h1>Welcome to SOMS</h1>
                            <p>Select a menu item from the sidebar to get started.</p>
                        </div>
                    `;
                }
            }
        });
    });
}

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize navigation if mainContent exists (AJAX mode)
    if (mainContent) {
        initializeNavigation();
        
        // Load default content
        const dashboardLink = document.querySelector('.sidebar-menu a[data-page="dashboard"]');
        if (dashboardLink) {
            dashboardLink.click();
        }
    }
    
    // Force hide overlay on page load
    if (overlay) {
        overlay.classList.remove('active');
    }
    if (sidebar) {
        sidebar.classList.remove('active');
    }
});

// Ensure overlay is hidden when page loads
window.addEventListener('load', function() {
    if (overlay) {
        overlay.classList.remove('active');
        overlay.style.display = 'none';
        overlay.style.pointerEvents = 'none';
    }
});