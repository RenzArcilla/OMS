// Simulate real-time data updates
function simulateDataUpdate() {
    const stripBladeData = {
        count: 1200000,
        max: 1500000,
        thresholds: { good: 500000, warning: 1000000, critical: 1500000 }
    };

    const cutBladeData = {
        count: 750000,
        max: 2000000,
        thresholds: { good: 800000, warning: 1500000, critical: 2000000 }
    };

    updateBladeDisplay('strip', stripBladeData);
    updateBladeDisplay('cut', cutBladeData);
}

function updateBladeDisplay(blade, data) {
    const countEl = document.getElementById(`${blade}Count`);
    const statusEl = document.getElementById(`${blade}Status`);
    const percentEl = document.getElementById(`${blade}Percent`);
    const progressEl = document.getElementById(`${blade}Progress`);

    // Calculate percentage
    const percentage = Math.min((data.count / data.max) * 100, 100);
    
    // Update display
    countEl.textContent = data.count.toLocaleString();
    percentEl.textContent = `${percentage.toFixed(1)}%`;
    progressEl.style.width = `${percentage}%`;

    // Determine status
    let status, statusClass, progressClass;
    
    if (data.count >= data.thresholds.critical) {
        status = 'Replace Now';
        statusClass = 'critical';
        progressClass = 'critical';s
    } else if (data.count >= data.thresholds.warning) {
        status = 'Needs Attention';
        statusClass = 'warning';
        progressClass = 'warning';
    } else {
        status = 'Good Condition';
        statusClass = 'good';
        progressClass = 'good';
    }

    // Update status badge
    statusEl.textContent = status;
    statusEl.className = `status-badge ${statusClass}`;
    
    // Update progress bar
    progressEl.className = `progress-fill ${progressClass}`;
}

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    simulateDataUpdate();
    
    // Simulate periodic updates (every 30 seconds)
    setInterval(() => {
        // Add small random variations to simulate real usage
        const stripVariation = Math.floor(Math.random() * 10000) - 5000;
        const cutVariation = Math.floor(Math.random() * 8000) - 4000;
        
        const newStripData = {
            count: Math.max(0, 1200000 + stripVariation),
            max: 1500000,
            thresholds: { good: 500000, warning: 1000000, critical: 1500000 }
        };

        const newCutData = {
            count: Math.max(0, 750000 + cutVariation),
            max: 2000000,
            thresholds: { good: 800000, warning: 1500000, critical: 2000000 }
        };

        updateBladeDisplay('strip', newStripData);
        updateBladeDisplay('cut', newCutData);
    }, 30000);
});