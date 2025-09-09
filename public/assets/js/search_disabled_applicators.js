let disabledApplicatorSearchTimeout = null;

/*
    Apply search and filter parameters to the disabled applicators table.
    Debounced to prevent too many requests.
*/
async function applyDisabledApplicatorFilters() {
    clearTimeout(disabledApplicatorSearchTimeout);

    disabledApplicatorSearchTimeout = setTimeout(async () => {
        // Select search and filter inputs specific to this section
        const section = document.getElementById('disabled-applicators-section');
        const search = section.querySelector('.search-input').value.trim();

        const descriptionSelect = document.getElementById('applicatorDescription');
        const description = descriptionSelect ? descriptionSelect.value : 'ALL';

        const typeSelect = document.getElementById('applicatorWireType');
        const type = typeSelect ? typeSelect.value : 'ALL';

        const tbody = section.querySelector('.data-table tbody');

        // Show loading spinner row while fetching
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align:center;">
                    <div class="loading-spinner"></div>
                </td>
            </tr>
        `;

        try {
            const res = await fetch(
                `/OMS/app/controllers/search_disabled_applicators.php?q=${encodeURIComponent(search)}&description=${encodeURIComponent(description)}&type=${encodeURIComponent(type)}`
            );
            const result = await res.json();

            // prefer the controller's empty_db flag; fallback to error === 'emptyDb'
            const emptyDb = !!result.empty_db || result.error === 'emptyDb';

            if (!result.success) {
                // still update the table so the user sees the proper empty state
                updateDisabledApplicatorsTable([], emptyDb);
                return;
            }

            updateDisabledApplicatorsTable(result.data, emptyDb);
        } catch (err) {
            console.error('Disabled applicator search failed:', err);
            // show a generic failure row
            tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;">Failed to load applicators</td></tr>`;
        }
    }, 300);
}

/*
    Update the disabled applicators table rows dynamically.
*/
function updateDisabledApplicatorsTable(applicators, emptyDb = false) {
    const tbody = document.querySelector('#disabled-applicators-section .data-table tbody');
    tbody.innerHTML = '';

    // If no applicators found, display placeholder
    if (!Array.isArray(applicators) || applicators.length === 0) {
        tbody.innerHTML = emptyDb
            ? `<tr><td colspan="6" style="text-align:center;">No applicators available yet</td></tr>`
            : `<tr><td colspan="6" style="text-align:center;">No results found</td></tr>`;
        return;
    }

    // Populate table rows
    applicators.forEach(applicator => {
        const row = `
            <tr>
                <td>
                    <div class="actions">
                        <button class="restore-btn restore-applicator-btn"
                                data-applicator-id="${applicator.applicator_id}">
                            Restore
                        </button>
                    </div>
                </td>
                <td>${applicator.hp_no}</td>
                <td>${applicator.description}</td>
                <td>${applicator.terminal_maker}</td>
                <td>${applicator.applicator_maker}</td>
                <td>${applicator.last_encoded || ''}</td>
            </tr>
        `;
        tbody.insertAdjacentHTML("beforeend", row);
    });
}

// Load initial disabled applicator data on page load
document.addEventListener("DOMContentLoaded", () => {
    applyDisabledApplicatorFilters(); // Initial fetch without filters
});
