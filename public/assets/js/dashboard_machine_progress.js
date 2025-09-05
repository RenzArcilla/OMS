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
            // Add cache-busting parameter to force fresh data
            const timestamp = new Date().getTime();
            const response = await fetch(`/SOMS/app/controllers/get_machine_outputs.php?t=${timestamp}`);
            const result = await response.json();
            
            console.log('Machine progress data response:', result);
            
            if (result.success) {
                this.progressData = result.data;
                console.log('Progress data loaded:', this.progressData);
                
                // Log each machine's data for debugging
                if (Array.isArray(this.progressData)) {
                    this.progressData.forEach((machine, index) => {
                        console.log(`Machine ${index + 1}:`, machine);
                        if (machine.progress) {
                            console.log(`Machine ${machine.machine_id} progress:`, machine.progress);
                        }
                    });
                } else {
                    console.log('Single machine data:', this.progressData);
                }
                
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
        console.log(`=== UPDATING PROGRESS BAR ===`);
        console.log(`Machine ID: ${machineId}`);
        console.log(`Part Name: ${partName}`);
        console.log(`Part Data:`, partData);
        
        // Find the progress bar container (td element with both data attributes)
        const escapedPartName = (window.CSS && CSS.escape) ? CSS.escape(partName) : partName;
        const container = document.querySelector(`td[data-machine-id="${machineId}"][data-part="${escapedPartName}"]`);
        const progressBar = container?.querySelector('.progress-fill');
        const textDisplay = container?.querySelector('div:first-child');

        console.log(`Container selector: td[data-machine-id="${machineId}"][data-part="${escapedPartName}"]`);
        console.log(`Found container:`, container);
        console.log(`Found progress bar:`, progressBar);
        console.log(`Found text display:`, textDisplay);

        if (progressBar && textDisplay) {
            // Log current state before update
            console.log(`Before update - Progress bar width: ${progressBar.style.width}`);
            console.log(`Before update - Text display: ${textDisplay.innerHTML}`);
            
            // Force clear any existing width and set to 0 first if percentage is 0
            if (partData.percentage === 0) {
                progressBar.style.width = '0px';
                progressBar.style.minWidth = '0px';
            }
            
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
            
            // Log after update
            console.log(`After update - Progress bar width: ${progressBar.style.width}`);
            console.log(`After update - Text display: ${textDisplay.innerHTML}`);
            console.log(`Successfully updated machine progress bar for ${partName}: ${partData.percentage}%`);
        } else {
            console.error(`❌ Machine progress bar or text display not found for machine ${machineId}, part ${partName}`);
            console.error(`Container selector: td[data-machine-id="${machineId}"][data-part="${partName}"]`);
            console.error(`All td elements with data-machine-id:`, document.querySelectorAll(`td[data-machine-id]`));
            console.error(`All td elements with data-part:`, document.querySelectorAll(`td[data-part]`));
            
            // Try alternative selectors
            const allContainers = document.querySelectorAll('td[data-machine-id]');
            console.error(`All containers with data-machine-id:`, allContainers);
            
            const allParts = document.querySelectorAll('td[data-part]');
            console.error(`All containers with data-part:`, allParts);
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

// Force refresh function to clear cached progress bars
window.forceRefreshMachineProgressBars = function() {
    console.log('🔄 Force refreshing machine progress bars...');
    if (window.machineProgressBarManager) {
        // Force clear all progress bars first
        const allProgressBars = document.querySelectorAll('.progress-fill');
        allProgressBars.forEach(bar => {
            if (bar.style.width === '0%' || bar.style.width === '0px') {
                bar.style.width = '0px';
                bar.style.minWidth = '0px';
            }
        });
        // Then reload data
        window.machineProgressBarManager.loadProgressData();
    } else {
        console.error('Machine progress bar manager not found');
    }
};
