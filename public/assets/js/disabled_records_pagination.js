// Global variables for pagination state
currentPageRecords = 1;
itemsPerPageRecords = 10;
totalPagesRecords = 1;
totalRecordsCount = 0;
currentSearchRecords = '';
currentShiftRecords = 'ALL';

disabledRecordSearchTimeout = null;

// Initialize pagination on page load
document.addEventListener("DOMContentLoaded", () => {
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    currentPageRecords = parseInt(urlParams.get('page')) || 1;
    itemsPerPageRecords = parseInt(urlParams.get('items_per_page')) || 10;
    
    // Get filter parameters from URL
    currentSearchRecords = urlParams.get('search') || '';
    currentShiftRecords = urlParams.get('shift') || 'ALL';
    
    // Set the items per page selector
    const itemsPerPageSelect = document.getElementById('items-per-page-records');
    if (itemsPerPageSelect) {
        itemsPerPageSelect.value = itemsPerPageRecords;
    }
    
    // Set filter values in the form
    const searchInput = document.querySelector('.data-section .search-input');
    if (searchInput) {
        searchInput.value = currentSearchRecords;
    }
    
    const shiftSelect = document.getElementById('recordShiftDisabled');
    if (shiftSelect) {
        shiftSelect.value = currentShiftRecords;
    }
    
    // Load initial data
    loadDisabledRecords();
});

/*
    Apply search and filter parameters to the disabled records table.
    Debounced to prevent too many requests.
*/
async function applyDisabledRecordFilters(searchValue = '') {
    clearTimeout(disabledRecordSearchTimeout);

    disabledRecordSearchTimeout = setTimeout(async () => {
        const searchInput = document.querySelector('.data-section .search-input');
        const search = searchValue.trim() || (searchInput ? searchInput.value.trim() : '');
        const shift = document.getElementById('recordShiftDisabled').value;

        // Update current filters
        currentSearchRecords = search;
        currentShiftRecords = shift;
        currentPageRecords = 1; // Reset to first page when filtering

        // Update URL without reloading
        updateURLRecords();
        
        // Load data with new filters
        await loadDisabledRecords();
    }, 300);
}

/*
    Load disabled records with current pagination and filter settings
*/
async function loadDisabledRecords() {
    const tbody = document.getElementById('deletedRecordsMetricsBody');
    const paginationContainer = document.getElementById('disabled-records-pagination');

    // Show loading spinner
    tbody.innerHTML = `
        <tr>
            <td colspan="12" style="text-align:center;">
                <div class="loading-spinner"></div>
            </td>
        </tr>
    `;

    try {
        const params = new URLSearchParams({
            page: currentPageRecords,
            items_per_page: itemsPerPageRecords,
            search: currentSearchRecords,
            shift: currentShiftRecords
        });

        const response = await fetch(`/OMS/app/controllers/get_disabled_records.php?${params}`);
        const result = await response.json();

        if (!result.success) {
            tbody.innerHTML = `<tr><td colspan="12" style="text-align:center;">Failed to load records</td></tr>`;
            return;
        }

        // Update pagination state
        totalPagesRecords = result.pagination.total_pages;
        totalRecordsCount = result.pagination.total_records;

        // Update table
        updateDisabledRecordsTable(result.data, result.empty_db);
        
        // Update pagination controls
        updatePaginationControlsRecords(result.pagination);
        
        // Show/hide pagination container
        if (totalPagesRecords > 1) {
            paginationContainer.style.display = 'block';
        } else {
            paginationContainer.style.display = 'none';
        }

    } catch (err) {
        console.error('Disabled records pagination failed:', err);
        tbody.innerHTML = `<tr><td colspan="12" style="text-align:center;">Failed to load records</td></tr>`;
    }
}

/*
    Update the disabled records table rows dynamically.
*/
function updateDisabledRecordsTable(records, emptyDb = false) {
    const tbody = document.getElementById('deletedRecordsMetricsBody');
    tbody.innerHTML = '';

    // If no records found, display placeholder
    if (!Array.isArray(records) || records.length === 0) {
        tbody.innerHTML = emptyDb
            ? `<tr><td colspan="12" style="text-align:center;">No disabled records yet</td></tr>`
            : `<tr><td colspan="12" style="text-align:center;">No results found</td></tr>`;
        return;
    }

    // Populate table rows
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

/*
    Update pagination controls with current state
*/
function updatePaginationControlsRecords(pagination) {
    const { current_page, total_pages, total_records, showing_from, showing_to } = pagination;
    
    // Update pagination info text
    const infoText = document.getElementById('pagination-info-text-records');
    infoText.textContent = `Showing ${showing_from} to ${showing_to} of ${total_records.toLocaleString()} results`;
    
    // Update previous button
    const prevBtn = document.getElementById('pagination-prev-records');
    if (current_page > 1) {
        prevBtn.href = '#';
        prevBtn.onclick = () => goToPageRecords(current_page - 1);
        prevBtn.classList.remove('disabled');
    } else {
        prevBtn.href = '#';
        prevBtn.onclick = null;
        prevBtn.classList.add('disabled');
    }
    
    // Update next button
    const nextBtn = document.getElementById('pagination-next-records');
    if (current_page < total_pages) {
        nextBtn.href = '#';
        nextBtn.onclick = () => goToPageRecords(current_page + 1);
        nextBtn.classList.remove('disabled');
    } else {
        nextBtn.href = '#';
        nextBtn.onclick = null;
        nextBtn.classList.add('disabled');
    }
    
    // Update page numbers
    updatePageNumbersRecords(current_page, total_pages);
}

/*
    Update page number links
*/
function updatePageNumbersRecords(currentPage, totalPages) {
    const numbersContainer = document.getElementById('pagination-numbers-records');
    numbersContainer.innerHTML = '';
    
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);
    
    // Show first page if not in range
    if (startPage > 1) {
        const firstLink = document.createElement('a');
        firstLink.href = '#';
        firstLink.className = 'pagination-btn';
        firstLink.textContent = '1';
        firstLink.onclick = () => goToPageRecords(1);
        numbersContainer.appendChild(firstLink);
        
        if (startPage > 2) {
            const ellipsis = document.createElement('span');
            ellipsis.className = 'pagination-ellipsis';
            ellipsis.textContent = '...';
            numbersContainer.appendChild(ellipsis);
        }
    }
    
    // Show page numbers in range
    for (let i = startPage; i <= endPage; i++) {
        if (i === currentPage) {
            const currentSpan = document.createElement('span');
            currentSpan.className = 'pagination-btn pagination-current';
            currentSpan.textContent = i;
            numbersContainer.appendChild(currentSpan);
        } else {
            const link = document.createElement('a');
            link.href = '#';
            link.className = 'pagination-btn';
            link.textContent = i;
            link.onclick = () => goToPageRecords(i);
            numbersContainer.appendChild(link);
        }
    }
    
    // Show last page if not in range
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            const ellipsis = document.createElement('span');
            ellipsis.className = 'pagination-ellipsis';
            ellipsis.textContent = '...';
            numbersContainer.appendChild(ellipsis);
        }
        
        const lastLink = document.createElement('a');
        lastLink.href = '#';
        lastLink.className = 'pagination-btn';
        lastLink.textContent = totalPages;
        lastLink.onclick = () => goToPageRecords(totalPages);
        numbersContainer.appendChild(lastLink);
    }
}

/*
    Navigate to a specific page
*/
async function goToPageRecords(page) {
    currentPageRecords = page;
    updateURLRecords();
    await loadDisabledRecords();
}

/*
    Change items per page
*/
async function changeItemsPerPageRecords(newItemsPerPage) {
    itemsPerPageRecords = parseInt(newItemsPerPage);
    currentPageRecords = 1; // Reset to first page
    updateURLRecords();
    await loadDisabledRecords();
}

/*
    Update URL parameters without reloading the page
*/
function updateURLRecords() {
    const url = new URL(window.location);
    
    // Update or add parameters
    url.searchParams.set('page', currentPageRecords);
    url.searchParams.set('items_per_page', itemsPerPageRecords);
    
    if (currentSearchRecords) {
        url.searchParams.set('search', currentSearchRecords);
    } else {
        url.searchParams.delete('search');
    }
    
    if (currentShiftRecords !== 'ALL') {
        url.searchParams.set('shift', currentShiftRecords);
    } else {
        url.searchParams.delete('shift');
    }
    
    // Update URL without reloading
    window.history.pushState({}, '', url);
}
