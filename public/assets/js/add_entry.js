// Refresh the page
function refreshPage(btn) {
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Refreshing...';
    btn.disabled = true;
    window.location.href = '/OMS/app/views/add_entry.php';
}


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

function openMachineDeleteModal(button) {
    // Store the form reference for later use
    window.currentDeleteForm = button.closest('form');
    
    // Show the delete modal
    document.getElementById('deleteModalOverlay').style.display = 'block';
}

function closeMachineDeleteModal() {
    document.getElementById('deleteModalOverlay').style.display = 'none';
}

function confirmDelete() {
    // Close the modal first
    closeMachineDeleteModal();
    
    // Submit the stored form
    if (window.currentDeleteForm) {
        window.currentDeleteForm.submit();
    }
}






function openApplicatorDeleteModal(button) {
    // Store the form reference for later use
    window.currentDeleteForm = button.closest('form');
    
    // Show the delete modal
    document.getElementById('deleteModalOverlay').style.display = 'block';
}

function closeApplicatorDeleteModal() {
    document.getElementById('deleteModalOverlay').style.display = 'none';
}

function confirmDelete() {
    // Close the modal first
    closeApplicatorDeleteModal();
    
    // Submit the stored form
    if (window.currentDeleteForm) {
        window.currentDeleteForm.submit();
    }
}


// Open the export modal
// Listen for clicks on the "Export Data" button to open the export modal
document.querySelectorAll('[onclick="exportData()"]').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        // Prevent default if button is inside a form
        e.preventDefault();
        document.getElementById('exportModal').style.display = 'block';
    });
});

// Listen for clicks on the close button or outside the modal to close it
document.addEventListener('DOMContentLoaded', function() {
    // Close button
    var closeBtn = document.querySelector('#exportModal .modal-close-btn');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            document.getElementById('exportModal').style.display = 'none';
        });
    }

    // Click outside modal content
    var exportModal = document.getElementById('exportModal');
    if (exportModal) {
        exportModal.addEventListener('click', function(event) {
            if (event.target === exportModal) {
                exportModal.style.display = 'none';
            }
        });
    }
});

// State variables
let selectedFormat = 'csv';
let selectedDateRange = 'all';
let customStartDate = '';
let customEndDate = '';
let includeHeaders = true;
let isExporting = false;

// Format selection
function initializeFormatOptions() {
    const formatOptions = document.querySelectorAll('.format-option');
    formatOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            formatOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked option
            this.classList.add('selected');
            selectedFormat = this.dataset.format;
        });
    });
}

// Date range handling
function initializeDateRange() {
    const dateRangeSelect = document.getElementById('dateRange');
    const customDatesDiv = document.getElementById('customDates');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');

    dateRangeSelect.addEventListener('change', function() {
        selectedDateRange = this.value;
        if (this.value === 'custom') {
            customDatesDiv.classList.remove('hidden');
        } else {
            customDatesDiv.classList.add('hidden');
        }
    });

    startDateInput.addEventListener('change', function() {
        customStartDate = this.value;
    });

    endDateInput.addEventListener('change', function() {
        customEndDate = this.value;
    });
}

// Checkbox handling
function initializeCheckbox() {
    const headersCheckbox = document.getElementById('includeHeaders');
    headersCheckbox.addEventListener('change', function() {
        includeHeaders = this.checked;
    });
}


// Close modal when clicking outside
document.getElementById('exportModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeExportModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeExportModal();
    }
});

// Initialize all functionality when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeFormatOptions();
    initializeDateRange();
    initializeCheckbox();
});

// Export modal functions
function showExportApplicatorModal() {
    console.log('Showing exportApplicatorReportModal...');
    const modal = document.getElementById('exportApplicatorReportModal');
    if (modal) {
        modal.style.display = 'block';
        console.log('exportApplicatorReportModal shown successfully');
    } else {
        console.log('exportApplicatorReportModal not found!');
    }
}

function showExportMachineModal() {
    console.log('Showing exportMachineReportModal...');
    const modal = document.getElementById('exportMachineReportModal');
    if (modal) {
        modal.style.display = 'block';
        console.log('exportMachineReportModal shown successfully');
    } else {
        console.log('exportMachineReportModal not found!');
    }
}

function closeExportApplicatorModal() {
    console.log('Closing exportApplicatorReportModal...');
    const modal = document.getElementById('exportApplicatorReportModal');
    if (modal) {
        modal.style.display = 'none';
        console.log('exportApplicatorReportModal closed successfully');
    }
}

function closeExportMachineModal() {
    console.log('Closing exportMachineReportModal...');
    const modal = document.getElementById('exportMachineReportModal');
    if (modal) {
        modal.style.display = 'none';
        console.log('exportMachineReportModal closed successfully');
    }
}