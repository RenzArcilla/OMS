let disabledMachineSearchTimeout = null;

/*
    Apply search and description filters to the disabled machines table.
    Debounced to prevent too many requests.
*/
async function applyDisabledMachineFilters() {
    clearTimeout(disabledMachineSearchTimeout);

    disabledMachineSearchTimeout = setTimeout(async () => {
        // Select the search input and description filter specific to this section
        const search = document.querySelector('#disabled-machines-section .search-input').value.trim();
        const descriptionSelect = document.getElementById('disabledMachineDescription');
        const description = descriptionSelect ? descriptionSelect.value : 'ALL';
        const section = document.getElementById('disabled-machines-section');
        const tbody = section.querySelector('.data-table tbody');

        // Show loading spinner row while fetching
        tbody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align:center;">
                    <div class="loading-spinner"></div>
                </td>
            </tr>
        `;

        try {
            const res = await fetch(
                `/OMS/app/controllers/search_disabled_machines.php?q=${encodeURIComponent(search)}&description=${encodeURIComponent(description)}`
            );
            const result = await res.json();

            // prefer the controller's empty_db flag; fallback to error === 'emptyDb'
            const emptyDb = !!result.empty_db || result.error === 'emptyDb';

            if (!result.success) {
                // still update the table so the user sees the proper empty state
                updateDisabledMachinesTable([], emptyDb);
                return;
            }

            updateDisabledMachinesTable(result.data, emptyDb);
        } catch (err) {
            console.error('Disabled machine search failed:', err);
            // show a generic failure row
            tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;">Failed to load machines</td></tr>`;
        }
    }, 300);
}

/*
    Update the disabled machines table rows dynamically.
*/
function updateDisabledMachinesTable(machines, emptyDb = false) {
    const tbody = document.querySelector('#disabled-machines-section .data-table tbody');
    tbody.innerHTML = '';

    // If no machines found, display placeholder
    if (!Array.isArray(machines) || machines.length === 0) {
        tbody.innerHTML = emptyDb
            ? `<tr><td colspan="5" style="text-align:center;">No machines available yet</td></tr>`
            : `<tr><td colspan="5" style="text-align:center;">No results found</td></tr>`;
        return;
    }

    // Populate table rows
    machines.forEach(machine => {
        const row = `
            <tr>
                <td>
                    <div class="actions">
                        <button class="restore-btn restore-machine-btn"
                                data-machine-id="${machine.machine_id}">
                            Restore
                        </button>
                    </div>
                </td>
                <td>${machine.control_no}</td>
                <td>${machine.model}</td>
                <td>${machine.maker}</td>
                <td>${machine.last_encoded || ''}</td>
            </tr>
        `;
        tbody.insertAdjacentHTML("beforeend", row);
    });
}

// Load initial disabled machine data on page load
document.addEventListener("DOMContentLoaded", () => {
    applyDisabledMachineFilters(); // Initial fetch without filters
});