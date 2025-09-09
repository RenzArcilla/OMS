// Global variables for pagination state
let currentPage = 1;
let itemsPerPage = 10;
let totalPages = 1;
let totalRecords = 0;
let currentSearch = '';
let currentDescription = 'ALL';
let currentType = 'ALL';

let disabledApplicatorSearchTimeout = null;

// Initialize pagination on page load
document.addEventListener("DOMContentLoaded", () => {
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    currentPage = parseInt(urlParams.get('page')) || 1;
    itemsPerPage = parseInt(urlParams.get('items_per_page')) || 10;
    
    // Get filter parameters from URL
    currentSearch = urlParams.get('search') || '';
    currentDescription = urlParams.get('description') || 'ALL';
    currentType = urlParams.get('type') || 'ALL';
    
    // Set the items per page selector
    const itemsPerPageSelect = document.getElementById('items-per-page-disabled');
    if (itemsPerPageSelect) {
        itemsPerPageSelect.value = itemsPerPage;
    }
    
    // Set filter values in the form
    const searchInput = document.querySelector('#disabled-applicators-section .search-input');
    if (searchInput) {
        searchInput.value = currentSearch;
    }
    
    const descriptionSelect = document.getElementById('applicatorDescription');
    if (descriptionSelect) {
        descriptionSelect.value = currentDescription;
    }
    
    const typeSelect = document.getElementById('applicatorWireType');
    if (typeSelect) {
        typeSelect.value = currentType;
    }
    
    // Load initial data
    loadDisabledApplicators();
});

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

        // Update current filters
        currentSearch = search;
        currentDescription = description;
        currentType = type;
        currentPage = 1; // Reset to first page when filtering

        // Update URL without reloading
        updateURL();
        
        // Load data with new filters
        await loadDisabledApplicators();
    }, 300);
}

/*
    Load disabled applicators with current pagination and filter settings
*/
async function loadDisabledApplicators() {
    const tbody = document.querySelector('#disabled-applicators-section .data-table tbody');
    const paginationContainer = document.getElementById('disabled-applicators-pagination');

    // Show loading spinner
    tbody.innerHTML = `
        <tr>
            <td colspan="6" style="text-align:center;">
                <div class="loading-spinner"></div>
            </td>
        </tr>
    `;

    try {
        const params = new URLSearchParams({
            page: currentPage,
            items_per_page: itemsPerPage,
            search: currentSearch,
            description: currentDescription,
            type: currentType
        });

        const response = await fetch(`/OMS/app/controllers/get_disabled_applicators.php?${params}`);
        const result = await response.json();

        if (!result.success) {
            tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;">Failed to load applicators</td></tr>`;
            return;
        }

        // Update pagination state
        totalPages = result.pagination.total_pages;
        totalRecords = result.pagination.total_records;

        // Update table
        updateDisabledApplicatorsTable(result.data, result.empty_db);
        
        // Update pagination controls
        updatePaginationControls(result.pagination);
        
        // Show/hide pagination container
        if (totalPages > 1) {
            paginationContainer.style.display = 'block';
        } else {
            paginationContainer.style.display = 'none';
        }

    } catch (err) {
        console.error('Disabled applicator pagination failed:', err);
        tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;">Failed to load applicators</td></tr>`;
    }
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

/*
    Update pagination controls with current state
*/
function updatePaginationControls(pagination) {
    const { current_page, total_pages, total_records, showing_from, showing_to } = pagination;
    
    // Update pagination info text
    const infoText = document.getElementById('pagination-info-text');
    infoText.textContent = `Showing ${showing_from} to ${showing_to} of ${total_records.toLocaleString()} results`;
    
    // Update previous button
    const prevBtn = document.getElementById('pagination-prev');
    if (current_page > 1) {
        prevBtn.href = '#';
        prevBtn.onclick = () => goToPage(current_page - 1);
        prevBtn.classList.remove('disabled');
    } else {
        prevBtn.href = '#';
        prevBtn.onclick = null;
        prevBtn.classList.add('disabled');
    }
    
    // Update next button
    const nextBtn = document.getElementById('pagination-next');
    if (current_page < total_pages) {
        nextBtn.href = '#';
        nextBtn.onclick = () => goToPage(current_page + 1);
        nextBtn.classList.remove('disabled');
    } else {
        nextBtn.href = '#';
        nextBtn.onclick = null;
        nextBtn.classList.add('disabled');
    }
    
    // Update page numbers
    updatePageNumbers(current_page, total_pages);
}

/*
    Update page number links
*/
function updatePageNumbers(currentPage, totalPages) {
    const numbersContainer = document.getElementById('pagination-numbers');
    numbersContainer.innerHTML = '';
    
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);
    
    // Show first page if not in range
    if (startPage > 1) {
        const firstLink = document.createElement('a');
        firstLink.href = '#';
        firstLink.className = 'pagination-btn';
        firstLink.textContent = '1';
        firstLink.onclick = () => goToPage(1);
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
            link.onclick = () => goToPage(i);
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
        lastLink.onclick = () => goToPage(totalPages);
        numbersContainer.appendChild(lastLink);
    }
}

/*
    Navigate to a specific page
*/
async function goToPage(page) {
    currentPage = page;
    updateURL();
    await loadDisabledApplicators();
}

/*
    Change items per page
*/
async function changeItemsPerPageDisabled(newItemsPerPage) {
    itemsPerPage = parseInt(newItemsPerPage);
    currentPage = 1; // Reset to first page
    updateURL();
    await loadDisabledApplicators();
}

/*
    Update URL parameters without reloading the page
*/
function updateURL() {
    const url = new URL(window.location);
    
    // Update or add parameters
    url.searchParams.set('page', currentPage);
    url.searchParams.set('items_per_page', itemsPerPage);
    
    if (currentSearch) {
        url.searchParams.set('search', currentSearch);
    } else {
        url.searchParams.delete('search');
    }
    
    if (currentDescription !== 'ALL') {
        url.searchParams.set('description', currentDescription);
    } else {
        url.searchParams.delete('description');
    }
    
    if (currentType !== 'ALL') {
        url.searchParams.set('type', currentType);
    } else {
        url.searchParams.delete('type');
    }
    
    // Update URL without reloading
    window.history.pushState({}, '', url);
}


