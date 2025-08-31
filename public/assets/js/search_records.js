let recordSearchTimeout = null;

/*
    Apply search and shift filters to the records table.
    Debounced to reduce the number of AJAX requests while typing.
*/
async function applyRecordFilters(searchQuery = '') {
    clearTimeout(recordSearchTimeout);

    recordSearchTimeout = setTimeout(async () => {
        const shift = document.getElementById('recordShift').value;

        try {
            // Fetch filtered records from controller
            const response = await fetch(`/SOMS/app/controllers/search_records.php?q=${encodeURIComponent(searchQuery)}&shift=${encodeURIComponent(shift)}`);
            const data = await response.json();

            if (!data.success) {
                console.error("Record search failed:", data.error);
                updateRecordsTable([]); // show empty table if search fails
                return;
            }

            // Update table with filtered results
            updateRecordsTable(data.data);
        } catch (err) {
            console.error("Record search failed:", err);
        }
    }, 300); // debounce delay
}

/*
    Dynamically update the records table rows.
    Clears previous rows and renders new ones.
*/
function updateRecordsTable(records) {
    const tbody = document.getElementById("recordsTableBody");
    tbody.innerHTML = "";

    if (records.length === 0) {
        tbody.innerHTML = `<tr><td colspan="12" style="text-align:center;">No results found</td></tr>`;
        return;
    }

    // Loop through each record and render table row
    records.forEach(row => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>
                <div class="actions">
                    <button class="edit-btn" onclick="openRecordEditModalSafe(this); return false;"
                        data-id="${row.record_id}"
                        data-date-inspected="${row.date_inspected}"
                        data-shift="${row.shift}"
                        data-hp1-no="${row.hp1_no ?? ''}"
                        data-app1-output="${row.app1_output ?? ''}"
                        data-hp2-no="${row.hp2_no ?? ''}"
                        data-app2-output="${row.app2_output ?? ''}"
                        data-control-no="${row.control_no ?? ''}"
                        data-machine-output="${row.machine_output ?? ''}"
                        title="Edit Record"
                    >Edit</button>

                    <form action="/SOMS/app/controllers/disable_record.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this record?');">
                        <input type="hidden" name="record_id" value="${row.record_id}">
                        <button type="submit" title="Delete Record" class="delete-btn">Delete</button>
                    </form>
                </div>
            </td>
            <td>${row.record_id}</td>
            <td>${row.date_inspected}</td>
            <td>${row.date_encoded?.split(' ')[0] || ''}</td>
            <td>${row.last_updated?.split(' ')[0] || ''}</td>
            <td>${row.shift}</td>
            <td>${row.hp1_no ?? ''}</td>
            <td>${row.app1_output ?? ''}</td>
            <td>${row.hp2_no ?? ''}</td>
            <td>${row.app2_output ?? ''}</td>
            <td>${row.control_no ?? ''}</td>
            <td>${row.machine_output ?? ''}</td>
        `;
        tbody.appendChild(tr);
    });
}
