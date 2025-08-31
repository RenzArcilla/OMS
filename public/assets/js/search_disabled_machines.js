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

        try {
            const response = await fetch(
                `../controllers/search_disabled_machines.php?q=${encodeURIComponent(search)}&description=${encodeURIComponent(description)}`
            );
            const result = await response.json();

            if (!result.success) {
                console.error("Disabled machine search failed:", result.error);
                updateDisabledMachinesTable([]); // show empty table if failed
                return;
            }

            updateDisabledMachinesTable(result.data);
        } catch (err) {
            console.error("Disabled machine search failed:", err);
        }
    }, 300);
}

/*
    Update the disabled machines table rows dynamically.
*/
function updateDisabledMachinesTable(machines) {
    const tbody = document.querySelector("#disabled-machines-section .data-table tbody");
    tbody.innerHTML = "";

    // If no machines found, display placeholder
    if (!Array.isArray(machines) || !machines.length) {
        tbody.innerHTML = "<tr><td colspan='5' style='text-align:center;'>No machines found</td></tr>";
        return;
    }

    // Populate table rows
    machines.forEach(machine => {
        const row = `
            <tr>
                <td>
                    <div class="actions">
                        <button class="restore-btn" type="button"
                            data-machine-id="${machine.machine_id}"
                            onclick="restoreMachine(this)">
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
