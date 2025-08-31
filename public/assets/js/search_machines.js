let machineSearchTimeout = null;

/*
    Apply search and description filters to the machine table.
    Debounced to prevent too many requests.
*/
async function applyMachineFilters() {
    clearTimeout(machineSearchTimeout);

    machineSearchTimeout = setTimeout(async () => {
        const search = document.querySelector('.search-input').value.trim();
        const description = document.getElementById('machineDescription').value;

        try {
            const response = await fetch(
                `../controllers/search_machines.php?q=${encodeURIComponent(search)}&description=${encodeURIComponent(description)}`
            );
            const result = await response.json();

            if (!result.success) {
                console.error("Machine search failed:", result.error);
                updateMachineTable([]); // show empty table if failed
                return;
            }

            updateMachineTable(result.data);
        } catch (err) {
            console.error("Machine search failed:", err);
        }
    }, 300);
}

/*
    Update the table rows dynamically.
*/
function updateMachineTable(machines) {
    const tbody = document.getElementById("machine-body");
    tbody.innerHTML = "";

    if (!machines.length) {
        tbody.innerHTML = "<tr><td colspan='7'>No machines found</td></tr>";
        return;
    }

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
                        <form action="/SOMS/app/controllers/disable_machine.php" method="POST" style="display:inline;">
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
        tbody.insertAdjacentHTML("beforeend", row);
    });
}