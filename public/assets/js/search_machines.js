// Timer for debouncing search input to reduce number of fetch requests
let machineSearchTimeout = null;

// Pagination state
let machineCurrentPage = 1;
let machineTotalPages = 1;
let machineTotalCount = 0;
let machineLimit = 20;

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
            const url = `../controllers/search_machines.php?q=${encodeURIComponent(search)}&description=${encodeURIComponent(description)}&page=${page}&limit=${limit}`;
            const response = await fetch(url);
            const result = await response.json();
            
            // If fetch failed or controller returned error
            if (!result.success) {
                console.error("Machine search failed:", result.error);
                updateMachineTable([], false); // Render empty table
                return;
            }

            // Update table with fetched data and emptyDb info
            updateMachineTable(result.data, result.empty_db);
            
            // Update pagination if available
            if (result.pagination) {
                updateMachinePagination(result.pagination);
            }
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

// Update pagination UI
function updateMachinePagination(pagination) {
    machineCurrentPage = pagination.current_page;
    machineTotalPages = pagination.total_pages;
    machineTotalCount = pagination.total_count;
    machineLimit = pagination.limit;
    
    const paginationContainer = document.getElementById('machine-pagination');
    const paginationInfo = document.getElementById('machine-pagination-info');
    const prevBtn = document.getElementById('machine-prev-btn');
    const nextBtn = document.getElementById('machine-next-btn');
    const paginationNumbers = document.getElementById('machine-pagination-numbers');
    
    if (!paginationContainer) return;
    
    // Show pagination if there are multiple pages
    if (machineTotalPages > 1) {
        paginationContainer.style.display = 'block';
    } else {
        paginationContainer.style.display = 'none';
        return;
    }
    
    // Update pagination info
    const startItem = pagination.offset + 1;
    const endItem = Math.min(pagination.offset + pagination.limit, pagination.total_count);
    paginationInfo.textContent = `Showing ${startItem} to ${endItem} of ${pagination.total_count} results`;
    
    // Update prev/next buttons
    prevBtn.disabled = machineCurrentPage <= 1;
    prevBtn.classList.toggle('disabled', machineCurrentPage <= 1);
    
    nextBtn.disabled = machineCurrentPage >= machineTotalPages;
    nextBtn.classList.toggle('disabled', machineCurrentPage >= machineTotalPages);
    
    // Generate page numbers
    generateMachinePageNumbers(paginationNumbers, machineCurrentPage, machineTotalPages);
}

// Generate page number buttons
function generateMachinePageNumbers(container, currentPage, totalPages) {
    container.innerHTML = '';
    
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    // Adjust start page if we're near the end
    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    // Add first page and ellipsis if needed
    if (startPage > 1) {
        addPageButton(container, 1, currentPage);
        if (startPage > 2) {
            addEllipsis(container);
        }
    }
    
    // Add page numbers
    for (let i = startPage; i <= endPage; i++) {
        addPageButton(container, i, currentPage);
    }
    
    // Add ellipsis and last page if needed
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            addEllipsis(container);
        }
        addPageButton(container, totalPages, currentPage);
    }
}

// Add a page button
function addPageButton(container, pageNum, currentPage) {
    const button = document.createElement('button');
    button.className = `pagination-btn ${pageNum === currentPage ? 'pagination-current' : ''}`;
    button.textContent = pageNum;
    button.onclick = () => goToMachinePage(pageNum);
    container.appendChild(button);
}

// Add ellipsis
function addEllipsis(container) {
    const ellipsis = document.createElement('span');
    ellipsis.className = 'pagination-ellipsis';
    ellipsis.textContent = '...';
    container.appendChild(ellipsis);
}

// Go to specific page
function goToMachinePage(page) {
    if (page >= 1 && page <= machineTotalPages && page !== machineCurrentPage) {
        machineCurrentPage = page;
        applyMachineFilters('', page, machineLimit);
    }
}

// Go to previous page
function goToMachinePrevPage() {
    if (machineCurrentPage > 1) {
        goToMachinePage(machineCurrentPage - 1);
    }
}

// Go to next page
function goToMachineNextPage() {
    if (machineCurrentPage < machineTotalPages) {
        goToMachinePage(machineCurrentPage + 1);
    }
}

// Change items per page
function changeMachineItemsPerPage() {
    const select = document.getElementById('machine-items-per-page');
    if (select) {
        machineLimit = parseInt(select.value);
        machineCurrentPage = 1; // Reset to first page
        applyMachineFilters('', 1, machineLimit);
    }
}

// Load initial machine data on page load
document.addEventListener("DOMContentLoaded", () => {
    applyMachineFilters(); // Initial fetch without filters
    
    // Add event listeners for pagination
    const prevBtn = document.getElementById('machine-prev-btn');
    const nextBtn = document.getElementById('machine-next-btn');
    const itemsPerPageSelect = document.getElementById('machine-items-per-page');
    
    if (prevBtn) prevBtn.onclick = goToMachinePrevPage;
    if (nextBtn) nextBtn.onclick = goToMachineNextPage;
    if (itemsPerPageSelect) itemsPerPageSelect.onchange = changeMachineItemsPerPage;
});