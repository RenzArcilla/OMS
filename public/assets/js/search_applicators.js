let applicatorSearchTimeout = null;

/*
    Apply search and filters to the applicator table.
    Debounced to reduce the number of AJAX requests on typing.
*/
async function applyApplicatorFilters(searchQuery = '') {
    clearTimeout(applicatorSearchTimeout); // cancel any previous scheduled fetch

    applicatorSearchTimeout = setTimeout(async () => {
        const description = document.getElementById('applicatorDescription').value;
        const type = document.getElementById('applicatorWireType').value;

        const tbody = document.getElementById("applicator-body");
        
        // Show loading spinner
        tbody.innerHTML = `
            <tr>
                <td colspan="9" style="text-align:center;">
                    <div class="loading-spinner"></div>
                </td>
            </tr>
        `;

        try {
            // Fetch filtered applicators from controller
            const response = await fetch(
                `/SOMS/app/controllers/search_applicators.php?q=${encodeURIComponent(searchQuery)}&description=${encodeURIComponent(description)}&type=${encodeURIComponent(type)}`
            );
            const data = await response.json();

            if (!data.success) {
                console.error("Applicator search failed:", data.error);
                updateApplicatorTable([]); // Show empty table if search fails
                return;
            }

            // Update table with filtered results
            updateApplicatorTable(data.data);
        } catch (err) {
            console.error("Applicator search failed:", err);
        }
    }, 300); // debounce delay in milliseconds
}

/*
    Dynamically update the applicator table rows.
    Clears previous rows and renders new ones.
*/
function updateApplicatorTable(applicators, emptyDb = false) {
    const tbody = document.getElementById("applicator-body");
    tbody.innerHTML = "";

    if (applicators.length === 0) {
        tbody.innerHTML = emptyDb
            ? `<tr><td colspan="9" style="text-align:center;">No applicators available yet</td></tr>`
            : `<tr><td colspan="9" style="text-align:center;">No results found</td></tr>`;
        return;
    }

    // Loop through each applicator and render table row
    applicators.forEach(row => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>
                <div class="actions">
                    <button class="edit-btn"
                        type="button"
                        onclick="openApplicatorEditModal(this)"
                        data-id="${row.applicator_id}"
                        data-control="${row.hp_no}"
                        data-terminal="${row.terminal_no}"
                        data-description="${row.description}"
                        data-wire="${row.wire}"
                        data-terminal-maker="${row.terminal_maker}"
                        data-applicator-maker="${row.applicator_maker}"
                        data-serial="${row.serial_no}"
                        data-invoice="${row.invoice_no}"
                    >Edit</button>

                    <form action="/SOMS/app/controllers/disable_applicator.php" method="POST" style="display:inline;">
                        <input type="hidden" name="applicator_id" value="${row.applicator_id}">
                        <button 
                            type="button"
                            class="delete-btn"
                            onclick="openApplicatorDeleteModal(this)"
                        >Delete</button>
                    </form>
                </div>
            </td>
            <td>${row.hp_no}</td>
            <td>${row.terminal_no}</td>
            <td>${row.description}</td>
            <td>${row.wire}</td>
            <td>${row.terminal_maker}</td>
            <td>${row.applicator_maker}</td>
            <td>${row.serial_no}</td>
            <td>${row.invoice_no}</td>
        `;
        tbody.appendChild(tr);
    });
}

// Load initial applicator data on page load
document.addEventListener("DOMContentLoaded", () => {
    applyApplicatorFilters(); // Initial fetch without filters
});
