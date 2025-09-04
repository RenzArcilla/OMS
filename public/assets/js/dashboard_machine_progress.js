// Machine Dashboard Progress Bar Management
class MachineProgressBarManager {
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
            console.log('Loading machine progress data...');
            const response = await fetch('/SOMS/app/controllers/get_machine_outputs.php');
            const result = await response.json();
            
            console.log('Machine progress data response:', result);
            
            if (result.success) {
                this.progressData = result.data;
                console.log('Machine progress data loaded:', this.progressData);
                this.updateAllProgressBars();
            } else {
                console.error('Failed to load machine progress data:', result.message);
            }
        } catch (error) {
            console.error('Error loading machine progress data:', error);
        }
    }

    // Update all progress bars on the page
    updateAllProgressBars() {
        console.log('Updating all machine progress bars...');
        console.log('Progress data type:', Array.isArray(this.progressData) ? 'array' : 'object');
        
        if (Array.isArray(this.progressData)) {
            // Multiple machines
            console.log(`Updating ${this.progressData.length} machines`);
            this.progressData.forEach(machine => {
                this.updateMachineProgress(machine);
            });
        } else {
            // Single machine
            console.log('Updating single machine');
            this.updateMachineProgress(this.progressData);
        }
    }

    // Update progress bars for a specific machine
    updateMachineProgress(machine) {
        const machineId = machine.machine_id;
        const progress = machine.progress;

        console.log(`Updating machine ${machineId}:`, machine);
        console.log(`Progress parts:`, Object.keys(progress));

        // Update each part's progress bar
        Object.keys(progress).forEach(partName => {
            const partData = progress[partName];
            this.updateProgressBar(machineId, partName, partData);
        });
    }

    // Update individual progress bar
    updateProgressBar(machineId, partName, partData) {
        // Find the progress bar container (td element with both data attributes)
        const container = document.querySelector(`td[data-machine-id="${machineId}"][data-part="${partName}"]`);
        const progressBar = container?.querySelector('.progress-fill');
        const textDisplay = container?.querySelector('div:first-child');

        console.log(`Looking for machine progress bar: td[data-machine-id="${machineId}"][data-part="${partName}"]`);
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
            const limitText = (partData.limit / 1000000) + 'M';
            textDisplay.innerHTML = `<strong>${partData.current.toLocaleString()}</strong> / ${limitText}`;
            
            console.log(`Updated machine progress bar for ${partName}: ${partData.percentage}%`);
            console.log(`Updated text display: ${partData.current.toLocaleString()} / ${limitText}`);
        } else {
            console.warn(`Machine progress bar or text display not found for machine ${machineId}, part ${partName}`);
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

    // Update specific machine output
    async updateOutput(machineId, updates) {
        try {
            const response = await fetch('/SOMS/app/controllers/update_machine_outputs.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    machine_id: machineId,
                    updates: updates
                })
            });

            const result = await response.json();
            
            if (result.success) {
                // Reload progress data to reflect changes
                await this.loadProgressData();
                return true;
            } else {
                console.error('Failed to update machine output:', result.message);
                return false;
            }
        } catch (error) {
            console.error('Error updating machine output:', error);
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
    console.log('Dashboard machine progress.js: DOM loaded, initializing MachineProgressBarManager...');
    window.machineProgressBarManager = new MachineProgressBarManager();
    console.log('Dashboard machine progress.js: MachineProgressBarManager initialized:', window.machineProgressBarManager);
});

// Export for global use
window.MachineProgressBarManager = MachineProgressBarManager;

// Manual refresh function for testing
window.refreshMachineProgressBars = function() {
    console.log('Manual machine refresh called');
    if (window.machineProgressBarManager) {
        window.machineProgressBarManager.loadProgressData();
    } else {
        console.error('MachineProgressBarManager not initialized');
    }
};
