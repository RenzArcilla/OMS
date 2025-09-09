let recordSearchTimeout = null;
let currentPage = 1;
let itemsPerPage = 10;

/*
    Apply search and shift filters to the records table.
    Debounced to reduce the number of AJAX requests while typing.
*/
async function applyRecordFilters(searchQuery = '', page = 1, limit = 20) {
    clearTimeout(recordSearchTimeout);

    recordSearchTimeout = setTimeout(async () => {
        const shift = document.getElementById('recordShift').value;

        // Show loading spinner row while fetching
        const tbody = document.getElementById("recordsTableBody");
        tbody.innerHTML = `
            <tr>
                <td colspan="12" style="text-align:center;">
                    <div class="loading-spinner"></div>
                </td>
            </tr>
        `;

        try {
            const response = await fetch(
                `/SOMS/app/controllers/search_records.php?q=${encodeURIComponent(searchQuery)}&shift=${encodeURIComponent(shift)}&page=${page}&limit=${limit}`
            );
            const data = await response.json();

            if (!data.success) {
                console.error("Record search failed:", data.error);
                updateRecordsTable([], false);
                return;
            }

            // Pass empty_db from PHP to JS
            updateRecordsTable(data.data, data.empty_db);
            
            // Update pagination info and controls
            if (data.total !== undefined) {
                updatePaginationInfo(data.total, data.page, data.limit, data.data.length);
                renderRecordsPagination(data);
            }

        } catch (err) {
            console.error("Record search failed:", err);
            updateRecordsTable([], false);
        }
    }, 300);
}


/*
    Dynamically update the records table rows.
    Clears previous rows and renders new ones.
*/
function updateRecordsTable(records, emptyDb) {
    const tbody = document.getElementById("recordsTableBody");
    tbody.innerHTML = "";

    if (records.length === 0) {
        tbody.innerHTML = emptyDb 
            ? `<tr><td colspan="12" style="text-align:center;">No records available yet</td></tr>`
            : `<tr><td colspan="12" style="text-align:center;">No results found</td></tr>`;
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


// Update pagination info
function updatePaginationInfo(total, page, limit, currentCount) {
    const paginationText = document.querySelector('.pagination-text');
    if (paginationText) {
        const start = (page - 1) * limit + 1;
        const end = Math.min(start + currentCount - 1, total);
        paginationText.textContent = `Showing ${start} to ${end} of ${total.toLocaleString()} results`;
    }
}

// Render pagination controls for records table
function renderRecordsPagination(data) {
    const containerId = 'recordsPaginationContainer';
    let container = document.getElementById(containerId);
    if (!container) {
        container = document.createElement('div');
        container.id = containerId;
        container.className = 'pagination-container';
        const table = document.getElementById('data-table');
        table.parentNode.appendChild(container);
    }

    const { page, total_pages, total, limit } = data;
    currentPage = page;
    itemsPerPage = limit;

    const makeBtn = (label, targetPage, disabled = false, current = false) => {
        const cls = ['pagination-btn'];
        if (disabled) cls.push('disabled');
        if (current) cls.push('pagination-current');
        return `<button class="${cls.join(' ')}" ${disabled ? 'disabled' : ''} data-page="${targetPage}">${label}</button>`;
    };

    const prevDisabled = page <= 1;
    const nextDisabled = page >= total_pages;
    const start = Math.max(1, page - 2);
    const end = Math.min(total_pages, page + 2);

    let numbers = '';
    if (start > 1) {
        numbers += makeBtn('1', 1, false, page === 1);
        if (start > 2) numbers += `<span class="pagination-ellipsis">...</span>`;
    }
    for (let i = start; i <= end; i++) {
        numbers += makeBtn(String(i), i, false, i === page);
    }
    if (end < total_pages) {
        if (end < total_pages - 1) numbers += `<span class="pagination-ellipsis">...</span>`;
        numbers += makeBtn(String(total_pages), total_pages, false, page === total_pages);
    }

    container.innerHTML = `
        <div class="pagination-info">
            <span class="pagination-text">Page ${page} of ${total_pages} • ${total.toLocaleString()} records</span>
        </div>
        <div class="pagination-controls">
            ${makeBtn('← Previous', page - 1, prevDisabled)}
            <div class="pagination-numbers">${numbers}</div>
            ${makeBtn('Next →', page + 1, nextDisabled)}
        </div>
        <div class="pagination-items-per-page" style="display: flex; align-items: center; gap: 1.5em;">
            <div>
                <label for="items-per-page">Show:</label>
                <select id="items-per-page" onchange="changeItemsPerPage(this.value)">
                    <option value="5" ${limit == 5 ? 'selected' : ''}>5</option>
                    <option value="10" ${limit == 10 ? 'selected' : ''}>10</option>
                    <option value="20" ${limit == 20 ? 'selected' : ''}>20</option>
                    <option value="50" ${limit == 50 ? 'selected' : ''}>50</option>
                </select>
                <span>per page</span>
            </div>
            
            <!-- Go to Page -->
            <form onsubmit="event.preventDefault(); applyRecordFilters('', this.page.value, itemsPerPage)">
                <label for="page-search" style="margin-bottom:0;">Go to page:</label>
                <input type="number" min="1" max="${total_pages}" id="page-search" name="page" value="${page}" style="width: 60px; padding: 2px 6px;">
                <button type="submit" class="pagination-btn" style="padding: 2px 10px;">Go</button>
            </form>
        </div>
    `;

    container.querySelectorAll('button[data-page]')
        .forEach(btn => btn.addEventListener('click', () => {
            const target = parseInt(btn.getAttribute('data-page'), 10);
            applyRecordFilters(document.getElementById('recordSearchInput').value, target, limit);
        }));

    // Handle items per page change
    const itemsPerPageSelect = container.querySelector('#items-per-page');
    if (itemsPerPageSelect) {
        itemsPerPageSelect.addEventListener('change', function() {
            applyRecordFilters(document.getElementById('recordSearchInput').value, 1, parseInt(this.value));
        });
    }

    // Handle go to page form
    const goToPageForm = container.querySelector('form');
    if (goToPageForm) {
        goToPageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const targetPage = parseInt(document.getElementById('page-search').value, 10);
            if (targetPage >= 1 && targetPage <= total_pages) {
                applyRecordFilters(document.getElementById('recordSearchInput').value, targetPage, limit);
            }
        });
    }
}

// Function to change items per page
function changeItemsPerPage(itemsPerPage) {
    applyRecordFilters(document.getElementById('recordSearchInput').value, 1, parseInt(itemsPerPage));
}

// Load initial data
document.addEventListener("DOMContentLoaded", () => {
    applyRecordFilters();
});