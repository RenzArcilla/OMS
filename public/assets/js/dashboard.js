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
            const response = await fetch('/SOMS/app/controllers/get_outputs.php');
            const result = await response.json();
            
            if (result.success) {
                this.progressData = result.data;
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
        if (Array.isArray(this.progressData)) {
            // Multiple applicators
            this.progressData.forEach(applicator => {
                this.updateApplicatorProgress(applicator);
            });
        } else {
            // Single applicator
            this.updateApplicatorProgress(this.progressData);
        }
    }

    // Update progress bars for a specific applicator
    updateApplicatorProgress(applicator) {
        const applicatorId = applicator.applicator_id;
        const progress = applicator.progress;

        // Update each part's progress bar
        Object.keys(progress).forEach(partName => {
            const partData = progress[partName];
            this.updateProgressBar(applicatorId, partName, partData);
        });
    }

    // Update individual progress bar
    updateProgressBar(applicatorId, partName, partData) {
        // Find the progress bar element
        const progressBar = document.querySelector(
            `[data-applicator-id="${applicatorId}"][data-part="${partName}"] .progress-fill`
        );

        if (progressBar) {
            // Update width
            progressBar.style.width = `${partData.percentage}%`;
            
            // Update status color
            progressBar.className = `progress-fill status-${partData.status}`;
            
            // Update tooltip with current/limit info
            progressBar.title = `${partData.current.toLocaleString()} / ${partData.limit.toLocaleString()} (${partData.percentage}%)`;
            
            // Add warning class for high percentages
            if (partData.percentage >= 90) {
                progressBar.classList.add('warning');
            }
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

    // Update specific applicator output
    async updateOutput(applicatorId, updates) {
        try {
            const response = await fetch('/SOMS/app/controllers/update_outputs.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    applicator_id: applicatorId,
                    updates: updates
                })
            });

            const result = await response.json();
            
            if (result.success) {
                // Reload progress data to reflect changes
                await this.loadProgressData();
                return true;
            } else {
                console.error('Failed to update output:', result.message);
                return false;
            }
        } catch (error) {
            console.error('Error updating output:', error);
            return false;
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
    window.progressManager = new ProgressBarManager();
});

// Export for global use
window.ProgressBarManager = ProgressBarManager;
