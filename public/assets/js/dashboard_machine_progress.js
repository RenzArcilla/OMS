// Dashboard Progress Bar Management for Machines (DOM -> PHP controller -> UI)
class MachineProgressBarManager {
    constructor(options = {}) {
        this.scanDelay = options.scanDelay ?? 50; // Step 2: wait 50ms
        this.controllerUrl = options.controllerUrl ?? '/SOMS/app/controllers/get_dashboard_machine_outputs.php'; // Step 3: PHP controller
        this.progressData = [];
        this.init();
    }

    init() {
        const start = () => setTimeout(() => this.processFromDOM(), this.scanDelay);
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', start, { once: true });
        } else {
            start();
        }
    }

    async processFromDOM() {
        try {
            const payload = this.buildPayloadFromDOM();      // Step 2: grab per-row data
            const result = await this.fetchComputedProgress(payload); // Step 3: compute in PHP
            if (result?.success && Array.isArray(result.data)) {
                this.progressData = result.data;
                this.applyProgressData(this.progressData);   // Step 4: display progress
            } else {
                console.error('Controller returned an unexpected response:', result);
            }
        } catch (err) {
            console.error('Failed to compute/apply machine progress:', err);
        }

    }

    // Step 2: Build payload from the existing machine table
    buildPayloadFromDOM() {
        const machines = [];
        const rows = document.querySelectorAll('#metricsBody tr');

        rows.forEach(tr => {
            const firstOutputCell = tr.querySelector('td[data-machine-id]');
            if (!firstOutputCell) return;

            const machineId = firstOutputCell.getAttribute('data-machine-id');
            const parts = {};

            tr.querySelectorAll('td[data-machine-id][data-part]').forEach(td => {
                const partName = td.getAttribute('data-part');

                // First line looks like: "<strong>123,456</strong> / 1.5M"
                const primaryTextDiv = td.querySelector('div');
                const rawText = (primaryTextDiv?.textContent || '').trim();

                // Extract current number before "/"
                let current = 0;
                const beforeSlash = rawText.split('/')[0] || '';
                const currentMatch = beforeSlash.match(/[\d,]+/);
                if (currentMatch) current = parseInt(currentMatch[0].replace(/,/g, ''), 10);

                // Extract limit from " / 2M" or " / 1.5M"
                let limit = 0;
                const limitMatch = rawText.match(/\/\s*([\d,.]+)\s*M/i);
                if (limitMatch) {
                    const limitNumber = parseFloat(limitMatch[1].replace(/,/g, ''));
                    if (!isNaN(limitNumber)) limit = Math.round(limitNumber * 1000000);
                } else {
                    // Fallback if not present in text
                    limit = this.getDefaultLimitForPart(partName);
                }

                parts[partName] = { current, limit };
            });

            machines.push({
                machine_id: machineId,
                parts
            });
        });

        return { machines }; // Payload shape expected by PHP controller
    }

    // Step 3: Send DOM values to PHP to compute percentages/status
    async fetchComputedProgress(payload) {
        const res = await fetch(this.controllerUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        return res.json();
    }

    // Step 4: Apply computed progress to the UI
    applyProgressData(machines) {
        machines.forEach(machine => {
            const { machine_id, progress } = machine;
            if (!progress) return;
            Object.keys(progress).forEach(partName => {
                this.updateProgressBar(machine_id, partName, progress[partName]);
            });
        });
    }

    updateProgressBar(machineId, partName, partData) {
        const container = document.querySelector(`td[data-machine-id="${machineId}"][data-part="${partName}"]`);
        if (!container) return;

        const progressBar = container.querySelector('.progress-fill');
        const textDisplay = container.querySelector('div:first-child');
        if (!progressBar || !textDisplay) return;

        const pct = Number.isFinite(partData.percentage) ? partData.percentage : 0;

        progressBar.style.width = `${pct}%`;
        progressBar.className = `progress-fill status-${partData.status || 'green'}`;
        progressBar.title = `${(partData.current ?? 0).toLocaleString()} / ${(partData.limit ?? 0).toLocaleString()} (${pct}%)`;

        if (pct >= 90) progressBar.classList.add('warning');

        const limitText = partData.limit ? (partData.limit / 1000000) + 'M' : '';
        textDisplay.innerHTML = `<strong>${(partData.current ?? 0).toLocaleString()}</strong>${limitText ? ` / ${limitText}` : ''}`;
    }

    // Default limits (fallback if parsing fails)
    getDefaultLimitForPart(part) {
        return 1500000;
    }
}

// Initialize with a 50ms scan delay and set the PHP compute endpoint
window.machineProgressBarManager = new MachineProgressBarManager({
    scanDelay: 50,
    controllerUrl: '/SOMS/app/controllers/get_dashboard_machine_outputs.php'
});
