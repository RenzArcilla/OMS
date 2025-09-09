// Timer for debouncing search input to reduce number of fetch requests
let machineSearchTimeout = null;

/*
    Apply search and description filters to the machine table.
    Debounced to prevent too many requests.
*/
async function applyMachineFilters(searchValue = '', page = 1, limit = 20) {
    // Clear any pending timeout to debounce
    clearTimeout(machineSearchTimeout);

    // Set a new debounce timeout
    machineSearchTimeout = setTimeout(async () => {
        // Get search query from input field if not passed directly
        const search = searchValue.trim() || document.getElementById('machineSearchInput').value.trim();

        // Get selected machine description filter
        const description = document.getElementById('machineDescription').value;

        const tbody = document.getElementById("machine-body");

        // Show loading spinner while fetching data
        tbody.innerHTML = `
            <tr>
                <td colspan="12" style="text-align:center;">
                    <div class="loading-spinner"></div>
                </td>
            </tr>
        `;

        try {
            // Fetch filtered machine data from the controller
            const response = await fetch(
                `../controllers/search_machines.php?q=${encodeURIComponent(search)}&description=${encodeURIComponent(description)}&page=${page}&limit=${limit}`
            );
            const result = await response.json();

            // If fetch failed or controller returned error
            if (!result.success) {
                console.error("Machine search failed:", result.error);
                updateMachineTable([], false); // Render empty table
                return;
            }

            // Update table with fetched data and emptyDb info
            updateMachineTable(result.data, result.empty_db);
        } catch (err) {
            // Log network or unexpected errors
            console.error("Machine search failed:", err);
            updateMachineTable([], false);
        }
    }, 300); // Wait 300ms before triggering fetch
}

/*
    Update the table rows dynamically.
*/
function updateMachineTable(machines, emptyDb = false) {
    const tbody = document.getElementById("machine-body");
    tbody.innerHTML = ""; // Clear existing rows

    // Handle empty table
    if (!machines.length) {
        tbody.innerHTML = emptyDb
            ? `<tr><td colspan="12" style="text-align:center;">No machines available yet</td></tr>`
            : `<tr><td colspan="12" style="text-align:center;">No results found</td></tr>`;
        return;
    }

    // Loop through each machine and create table rows
    machines.forEach(machine => {
        const row = `
            <tr>
                <td>
                    <div class="actions">
                        <button class="edit-btn" type="button"
                            onclick="openEditModal(this)"
                            data-id="${machine.machine_id}"
                            data-control="${machine.control_no}"
                            data-description="${machine.description}"
                            data-model="${machine.model}"
                            data-maker="${machine.maker}"
                            data-serial="${machine.serial_no}"
                            data-invoice="${machine.invoice_no}"
                        >Edit</button>
                        <form action="/OMS/app/controllers/disable_machine.php" method="POST" style="display:inline;">
                            <input type="hidden" name="machine_id" value="${machine.machine_id}">
                            <button class="delete-btn" type="button" onclick="openMachineDeleteModal(this)">Delete</button>
                        </form>
                    </div>
                </td>
                <td>${machine.control_no}</td>
                <td>${machine.description}</td>
                <td>${machine.model}</td>
                <td>${machine.maker}</td>
                <td>${machine.serial_no}</td>
                <td>${machine.invoice_no}</td>
            </tr>
        `;
        tbody.insertAdjacentHTML("beforeend", row); // Append new row
    });
}

// Load initial machine data on page load
document.addEventListener("DOMContentLoaded", () => {
    applyMachineFilters(); // Initial fetch without filters
});