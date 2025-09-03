let disabledRecordSearchTimeout = null;

/*
    Apply search and shift filters to the disabled records table.
    Debounced to prevent excessive requests.
*/
async function applyDisabledRecordFilters(searchValue = '') {
    clearTimeout(disabledRecordSearchTimeout);

    disabledRecordSearchTimeout = setTimeout(async () => {
        const searchInput = document.querySelector('.data-section .search-input');
        const search = searchValue.trim() || (searchInput ? searchInput.value.trim() : '');
        const shift = document.getElementById('recordShiftDisabled').value;

        try {
            const response = await fetch(
                `/SOMS/app/controllers/search_disabled_records.php?q=${encodeURIComponent(search)}&shift=${encodeURIComponent(shift)}`
            );
            const result = await response.json();

            if (!result.success) {
                console.error("Disabled record search failed:", result.error);
                updateDisabledRecordsTable([]);
                return;
            }

            updateDisabledRecordsTable(result.data);
        } catch (err) {
            console.error("Disabled record search failed:", err);
        }
    }, 300);
}

/*
    Update the disabled records table dynamically.
*/
function updateDisabledRecordsTable(records, emptyDb = false) {
    const tbody = document.getElementById('deletedRecordsMetricsBody');
    tbody.innerHTML = '';

    if (!records.length) {
        tbody.innerHTML = emptyDb
            ? `<tr><td colspan="12" style="text-align:center;">No disabled records yet</td></tr>`
            : `<tr><td colspan="12" style="text-align:center;">No results found</td></tr>`;
        return;
    }

    records.forEach(record => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <button class="restore-btn restore-output-btn" data-record-id="${record.record_id}">Restore</button>
            </td>
            <td>${record.record_id}</td>
            <td>${record.date_inspected || ''}</td>
            <td>${record.date_encoded ? record.date_encoded.split(' ')[0] : ''}</td>
            <td>${record.last_updated ? record.last_updated.split(' ')[0] : ''}</td>
            <td>${record.shift}</td>
            <td>${record.hp1_no || ''}</td>
            <td>${record.app1_output || ''}</td>
            <td>${record.hp2_no || ''}</td>
            <td>${record.app2_output || ''}</td>
            <td>${record.control_no || ''}</td>
            <td>${record.machine_output || ''}</td>
        `;
        tbody.appendChild(tr);
    });
}

// Load initial disabled records on page load
document.addEventListener("DOMContentLoaded", () => {
    applyDisabledRecordFilters();
});