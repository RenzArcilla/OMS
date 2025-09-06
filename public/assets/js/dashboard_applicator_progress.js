// Dashboard Progress Bar Management
class ProgressBarManager {
    constructor() {
        this.progressData = {};
        this.updateInterval = null;
        this.init();
    }

    init() {
        this.loadProgressData();
        this.setupAutoRefresh();
        this.setupEventListeners();
    }

    // Load progress data from API
    async loadProgressData() {
        try {
            console.log('Loading progress data...');
            const response = await fetch('/SOMS/app/controllers/get_dashboard_outputs.php');
            const result = await response.json();
            
            console.log('Progress data response:', result);
            
            if (result.success) {
                this.progressData = result.data;
                console.log('Progress data loaded:', this.progressData);
                this.updateAllProgressBars();
            } else {
                console.error('Failed to load progress data:', result.message);
            }
        } catch (error) {
            console.error('Error loading progress data:', error);
        }
    }

    // Update all progress bars on the page
    updateAllProgressBars() {
        console.log('Updating all progress bars...');
        console.log('Progress data type:', Array.isArray(this.progressData) ? 'array' : 'object');
        
        if (Array.isArray(this.progressData)) {
            // Multiple applicators
            console.log(`Updating ${this.progressData.length} applicators`);
            this.progressData.forEach(applicator => {
                this.updateApplicatorProgress(applicator);
            });
        } else {
            // Single applicator
            console.log('Updating single applicator');
            this.updateApplicatorProgress(this.progressData);
        }
    }

    // Update progress bars for a specific applicator
    updateApplicatorProgress(applicator) {
        const applicatorId = applicator.applicator_id;
        const progress = applicator.progress;

        console.log(`Updating applicator ${applicatorId}:`, applicator);
        console.log(`Progress parts:`, Object.keys(progress));

        // Update each part's progress bar
        Object.keys(progress).forEach(partName => {
            const partData = progress[partName];
            this.updateProgressBar(applicatorId, partName, partData);
        });
    }

    // Update individual progress bar
    updateProgressBar(applicatorId, partName, partData) {
        // Find the progress bar container (td element with both data attributes)
        const container = document.querySelector(`td[data-applicator-id="${applicatorId}"][data-part="${partName}"]`);
        const progressBar = container?.querySelector('.progress-fill');
        const textDisplay = container?.querySelector('div:first-child');

        console.log(`Looking for progress bar: td[data-applicator-id="${applicatorId}"][data-part="${partName}"]`);
        console.log(`Found container:`, container);
        console.log(`Found progress bar:`, progressBar);
        console.log(`Found text display:`, textDisplay);
        console.log(`Part data:`, partData);

        if (progressBar && textDisplay) {
            // Update progress bar width
            progressBar.style.width = `${partData.percentage}%`;
            
            // Update status color
            progressBar.className = `progress-fill status-${partData.status}`;
            
            // Update tooltip with current/limit info
            progressBar.title = `${partData.current.toLocaleString()} / ${partData.limit.toLocaleString()} (${partData.percentage}%)`;
            
            // Add warning class for high percentages
            if (partData.percentage >= 90) {
                progressBar.classList.add('warning');
            }
            
            // Update the text display
            const limitText = (partData.limit / 1000) + 'K';
            textDisplay.innerHTML = `<strong>${partData.current.toLocaleString()}</strong> / ${limitText}`;
            
            console.log(`Updated progress bar for ${partName}: ${partData.percentage}%`);
            console.log(`Updated text display: ${partData.current.toLocaleString()} / ${limitText}`);
        } else {
            console.warn(`Progress bar or text display not found for applicator ${applicatorId}, part ${partName}`);
        }
    }

    // Setup auto-refresh every 30 seconds
    setupAutoRefresh() {
        this.updateInterval = setInterval(() => {
            this.loadProgressData();
        }, 30000); // 30 seconds
    }

    // Setup event listeners
    setupEventListeners() {
        // Manual refresh button
        const refreshBtn = document.querySelector('[onclick="refreshPage()"]');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.loadProgressData();
            });
        }

        // Progress bar hover effects
        document.addEventListener('mouseover', (e) => {
            if (e.target.classList.contains('progress-fill')) {
                this.showProgressTooltip(e.target);
            }
        });

        document.addEventListener('mouseout', (e) => {
            if (e.target.classList.contains('progress-fill')) {
                this.hideProgressTooltip();
            }
        });
    }

    // Show detailed tooltip
    showProgressTooltip(progressBar) {
        const tooltip = document.createElement('div');
        tooltip.className = 'progress-tooltip';
        tooltip.textContent = progressBar.title;
        
        document.body.appendChild(tooltip);
        
        // Position tooltip
        const rect = progressBar.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
    }

    // Hide tooltip
    hideProgressTooltip() {
        const tooltip = document.querySelector('.progress-tooltip');
        if (tooltip) {
            tooltip.remove();
        }
    }

    // Cleanup
    destroy() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    console.log('Dashboard.js: DOM loaded, initializing ProgressBarManager...');
    window.progressBarManager = new ProgressBarManager();
    console.log('Dashboard.js: ProgressBarManager initialized:', window.progressBarManager);
});

// Export for global use
window.ProgressBarManager = ProgressBarManager;

// Manual refresh function for testing
window.refreshProgressBars = function() {
    console.log('Manual refresh called');
    if (window.progressBarManager) {
        window.progressBarManager.loadProgressData();
    } else {
        console.error('ProgressBarManager not initialized');
    }
};
