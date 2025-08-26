// Switch between machines and applicators tabs
function switchTab(tab) {
    currentTab = tab;

    // Update tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');

    // Hide all tables first
    document.getElementById('machine-table').style.display = 'none';
    document.getElementById('applicators-table').style.display = 'none';

    // Show the selected table
    if (tab === 'machines') {
        document.getElementById('machine-table').style.display = 'block';
    } else if (tab === 'applicators') {
        document.getElementById('applicators-table').style.display = 'block';
    }
}

// Open Add Machine Modal
function openMachineModal() {
    document.getElementById('addMachineModal').style.display = 'block';
}

// Close Add Applicator Modal
function closeAddApplicatorModal() {
    document.getElementById('addApplicatorModal').style.display = 'none';
}

// Open Add Applicator Modal
function openApplicatorModal() {
    document.getElementById('addApplicatorModal').style.display = 'block';
}

// Close Add Machine Modal
function closeAddMachineModal() {
    document.getElementById('addMachineModal').style.display = 'none';
}

// Close modal when clicking outside of it
document.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('.modal-overlay');
    modals.forEach(modal => {
        if (event.target === modal) {
            closeAddApplicatorModal();
            closeAddMachineModal();
            closeApplicatorModal();
            closeMachineModal();
        }
    });
});

// Close modal when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAddApplicatorModal();
        closeAddMachineModal();
        closeApplicatorModal();
        closeMachineModal();
    }
});

// Filter functionality
function applyFilters() {
    const typeFilter = document.getElementById('typeFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const searchQuery = document.getElementById('searchInput').value.toLowerCase();
    
    // Get current active tab
    const currentTab = document.querySelector('.tab-btn.active').textContent.toLowerCase();
    
    if (currentTab.includes('machines')) {
        filterTableRows('machine-body', typeFilter, statusFilter, searchQuery);
    } else {
        filterTableRows('applicator-body', typeFilter, statusFilter, searchQuery);
    }
}

function filterTableRows(tableBodyId, typeFilter, statusFilter, searchQuery) {
    const tableBody = document.getElementById(tableBodyId);
    const rows = tableBody.querySelectorAll('tr');
    
    rows.forEach(row => {
        let showRow = true;
        
        // Type filter
        if (typeFilter !== 'all') {
            const descriptionCell = row.querySelector('td:nth-child(2)'); // Description column
            if (descriptionCell && !descriptionCell.textContent.includes(typeFilter)) {
                showRow = false;
            }
        }
        
        // Search filter
        if (searchQuery && showRow) {
            const rowText = row.textContent.toLowerCase();
            if (!rowText.includes(searchQuery)) {
                showRow = false;
            }
        }
        
        // Show/hide row
        row.style.display = showRow ? '' : 'none';
    });
}

function clearFilters() {
    document.getElementById('typeFilter').value = 'all';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('searchInput').value = '';
    
    // Show all rows
    const machineRows = document.querySelectorAll('#machine-body tr');
    const applicatorRows = document.querySelectorAll('#applicator-body tr');
    
    machineRows.forEach(row => row.style.display = '');
    applicatorRows.forEach(row => row.style.display = '');
}

// Export functionality
function exportData() {
    // Get current active tab
    const currentTab = document.querySelector('.tab-btn.active').textContent.toLowerCase();
    let tableId, filename;
    
    if (currentTab.includes('machines')) {
        tableId = 'machine-table';
        filename = 'machines_export.csv';
    } else {
        tableId = 'applicators-table';
        filename = 'applicators_export.csv';
    }
    
    const table = document.querySelector(`#${tableId} table`);
    const rows = Array.from(table.querySelectorAll('tr'));
    
    let csvContent = "data:text/csv;charset=utf-8,";
    
    rows.forEach(row => {
        const cells = Array.from(row.querySelectorAll('th, td'));
        const rowData = cells.map(cell => {
            // Remove action buttons and get only text content
            if (cell.querySelector('.actions')) {
                return '';
            }
            return `"${cell.textContent.trim()}"`;
        }).filter(cell => cell !== '""'); // Remove empty cells
        
        if (rowData.length > 0) {
            csvContent += rowData.join(',') + '\r\n';
        }
    });
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Refresh functionality
function refreshData() {
    location.reload();
}

// Edit modal functions
function openEditModal(button) {
    const row = button.closest('tr');
    const machineId = button.getAttribute('data-id');
    const controlNo = button.getAttribute('data-control');
    const description = button.getAttribute('data-description');
    const model = button.getAttribute('data-model');
    const maker = button.getAttribute('data-maker');
    const serialNo = button.getAttribute('data-serial');
    const invoiceNo = button.getAttribute('data-invoice');
    
    // Populate the edit form
    document.getElementById('edit_machine_id').value = machineId;
    document.getElementById('edit_control_no').value = controlNo;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_model').value = model;
    document.getElementById('edit_maker').value = maker;
    document.getElementById('edit_serial_no').value = serialNo;
    document.getElementById('edit_invoice_no').value = invoiceNo;
    
    // Show the modal
    document.getElementById('editModal').style.display = 'block';
}

function openApplicatorEditModal(button) {
    const row = button.closest('tr');
    const applicatorId = button.getAttribute('data-id');
    const controlNo = button.getAttribute('data-control');
    const terminalNo = button.getAttribute('data-terminal');
    const description = button.getAttribute('data-description');
    const wire = button.getAttribute('data-wire');
    const terminalMaker = button.getAttribute('data-terminal-maker');
    const applicatorMaker = button.getAttribute('data-applicator-maker');
    const serialNo = button.getAttribute('data-serial');
    const invoiceNo = button.getAttribute('data-invoice');
    
    // Populate the edit form
    document.getElementById('edit_applicator_id').value = applicatorId;
    document.getElementById('edit_applicator_control').value = controlNo;
    document.getElementById('edit_terminal_no').value = terminalNo;
    document.getElementById('edit_applicator_description').value = description;
    document.getElementById('edit_wire_type').value = wire;
    document.getElementById('edit_terminal_maker').value = terminalMaker;
    document.getElementById('edit_applicator_maker').value = applicatorMaker;
    document.getElementById('edit_applicator_serial_no').value = serialNo;
    document.getElementById('edit_applicator_invoice_no').value = invoiceNo;
    
    // Show the modal
    document.getElementById('editApplicatorModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
    document.getElementById('editApplicatorModal').style.display = 'none';
}

function closeMachineModal() {
    document.getElementById('editModal').style.display = 'none';
}

function closeApplicatorModal() {
    document.getElementById('editApplicatorModal').style.display = 'none';
}
