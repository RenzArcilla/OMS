let disabledApplicatorSearchTimeout = null;

/*
    Apply search and filter parameters to the disabled applicators table.
    Debounced to prevent excessive requests while typing or changing filters.
*/
async function applyDisabledApplicatorFilters() {
    clearTimeout(disabledApplicatorSearchTimeout);

    disabledApplicatorSearchTimeout = setTimeout(async () => {
        const section = document.getElementById('disabled-applicators-section');
        const searchInput = section.querySelector('.search-input');
        const search = searchInput ? searchInput.value.trim() : '';

        const descriptionSelect = document.getElementById('applicatorDescription');
        const description = descriptionSelect ? descriptionSelect.value : 'ALL';

        const typeSelect = document.getElementById('applicatorWireType');
        const type = typeSelect ? typeSelect.value : 'ALL';

        try {
            const response = await fetch(
                `../controllers/search_disabled_applicators.php?q=${encodeURIComponent(search)}&description=${encodeURIComponent(description)}&type=${encodeURIComponent(type)}`
            );
            const result = await response.json();

            if (!result.success) {
                console.error("Disabled applicator search failed:", result.error);
                updateDisabledApplicatorsTable([]); // show empty table if failed
                return;
            }

            updateDisabledApplicatorsTable(result.data);
        } catch (err) {
            console.error("Disabled applicator search failed:", err);
        }
    }, 300);
}

/*
    Update the disabled applicators table rows dynamically.
*/
function updateDisabledApplicatorsTable(applicators) {
    const section = document.getElementById('disabled-applicators-section');
    const tbody = section.querySelector(".data-table tbody");
    tbody.innerHTML = "";

    if (!applicators.length) {
        tbody.innerHTML = "<tr><td colspan='6' style='text-align:center;'>No applicators found</td></tr>";
        return;
    }

    applicators.forEach(applicator => {
        const row = `
            <tr>
                <td>
                    <div class="actions">
                        <button class="restore-btn" type="button"
                            data-applicator-id="${applicator.applicator_id}"
                            onclick="restoreApplicator(this)">
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

