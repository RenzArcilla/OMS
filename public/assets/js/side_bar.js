// Initialize Lucide icons
        lucide.createIcons();

        // Global state
        let side_barOpen = true;
        let activeTab = 'dashboard';
/*
        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebarOpen = !sidebarOpen;
            
            if (sidebarOpen) {
                sidebar.classList.remove('collapsed');
            } else {
                sidebar.classList.add('collapsed');
            }
        }
*/
        // Tab switching
        function setActiveTab(tabName) {
            // Hide all tabs
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => tab.classList.add('hidden'));
            
            // Show selected tab
            const selectedTab = document.getElementById(tabName + '-tab');
            if (selectedTab) {
                selectedTab.classList.remove('hidden');
            }
            
            // Update navigation
            const navButtons = document.querySelectorAll('.nav-item button');
            navButtons.forEach(btn => btn.classList.remove('active'));
            
            const activeButton = document.querySelector(`[onclick="setActiveTab('${tabName}')"]`);
            if (activeButton) {
                activeButton.classList.add('active');
            }
            
            activeTab = tabName;
        }

        // Initialize the interface
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize icons
            lucide.createIcons();
            
            // Set initial state
            setActiveTab('dashboard');
            
            // Simulate real-time updates
            setInterval(updateStats, 30000); // Update every 30 seconds
        });