// Dashboard Progress Bar Management (DOM -> PHP controller -> UI)
class ProgressBarManager {
    constructor(options = {}) {
        this.scanDelay = options.scanDelay ?? 50; // Step 2: wait 50ms
        this.controllerUrl = options.controllerUrl ?? '/SOMS/app/controllers/get_applicator_output_progress.php'; // Step 3: PHP controller
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
            const payload = this.buildPayloadFromDOM();     // Step 2: grab per-row data
            const result = await this.fetchComputedProgress(payload); // Step 3: compute in PHP
            if (result?.success && Array.isArray(result.data)) {
                this.progressData = result.data;
                this.applyProgressData(this.progressData); // Step 4: display progress
            } else {
                console.error('Controller returned an unexpected response:', result);
            }
        } catch (err) {
            console.error('Failed to compute/apply progress:', err);
        }

    }

    // Step 2: Build payload from the existing table
    buildPayloadFromDOM() {
        const applicators = [];
        const rows = document.querySelectorAll('#metricsBody tr');

        rows.forEach(tr => {
            const firstOutputCell = tr.querySelector('td[data-applicator-id]');
            if (!firstOutputCell) return;

            const applicatorId = firstOutputCell.getAttribute('data-applicator-id');
            const parts = {};

            tr.querySelectorAll('td[data-applicator-id][data-part]').forEach(td => {
                const partName = td.getAttribute('data-part');

                // First line looks like: "<strong>123,456</strong> / 400K" or "123,456 / 600K"
                const primaryTextDiv = td.querySelector('div');
                const rawText = (primaryTextDiv?.textContent || '').trim();

                // Extract current number before "/"
                let current = 0;
                const beforeSlash = rawText.split('/')[0] || '';
                const currentMatch = beforeSlash.match(/[\d,]+/);
                if (currentMatch) current = parseInt(currentMatch[0].replace(/,/g, ''), 10);

                // Extract limit from " / xxxK "
                let limit = 0;
                const limitMatch = rawText.match(/\/\s*([\d,.]+)\s*K/i);
                if (limitMatch) {
                    const limitNumber = parseFloat(limitMatch[1].replace(/,/g, ''));
                    if (!isNaN(limitNumber)) limit = Math.round(limitNumber * 1000);
                } else {
                    // Fallback if not present in text (rare)
                    limit = this.getDefaultLimitForPart(partName);
                }

                parts[partName] = { current, limit };
            });

            applicators.push({
                applicator_id: applicatorId,
                parts
            });
        });

        // Payload shape expected by PHP controller
        return { applicators };
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
    applyProgressData(applicators) {
        applicators.forEach(applicator => {
            const { applicator_id, progress } = applicator;
            if (!progress) return;
            Object.keys(progress).forEach(partName => {
                this.updateProgressBar(applicator_id, partName, progress[partName]);
            });
        });
    }

    updateProgressBar(applicatorId, partName, partData) {
        const container = document.querySelector(`td[data-applicator-id="${applicatorId}"][data-part="${partName}"]`);
        if (!container) return;

        const progressBar = container.querySelector('.progress-fill');
        const textDisplay = container.querySelector('div:first-child');
        if (!progressBar || !textDisplay) return;

        const pct = Number.isFinite(partData.percentage) ? partData.percentage : 0;

        progressBar.style.width = `${pct}%`;
        progressBar.className = `progress-fill status-${partData.status || 'green'}`;
        progressBar.title = `${(partData.current ?? 0).toLocaleString()} / ${(partData.limit ?? 0).toLocaleString()} (${pct}%)`;

        if (pct >= 90) progressBar.classList.add('warning');

        const limitText = partData.limit ? (partData.limit / 1000) + 'K' : '';
        textDisplay.innerHTML = `<strong>${(partData.current ?? 0).toLocaleString()}</strong>${limitText ? ` / ${limitText}` : ''}`;
    }

    // Fallback limit mapping (used only if limit isn't parsable from cell text)
    getDefaultLimitForPart(part) {
        // Standardized: all applicator parts fallback to 500k
        return 500000;
    }
}

// Initialize with a 50ms scan delay and set the PHP compute endpoint
window.progressBarManager = new ProgressBarManager({
    scanDelay: 50,
    controllerUrl: '/SOMS/app/controllers/get_applicator_output_progress.php'
});