// Refresh the page
function refreshPage(btn) {
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Refreshing...';
    btn.disabled = true;
    window.location.href = '/OMS/app/views/dashboard_machine.php';
}

// Open the reset modal
function openResetModal(button) {
    const machineId = button.getAttribute("data-id");
    document.getElementById("reset_machine_id").value = machineId;
    document.getElementById('resetModalDashboardMachine').style.display = 'block';
}

// Close the reset modal
function closeResetModal() {
    const modal = document.getElementById('resetModalDashboardMachine');
    modal.style.display = 'none';
    const form = modal.querySelector('form');
    if (form) form.reset();
}

// Open the undo modal
function openUndoModal(button) {
    const machineId = button.getAttribute("data-id");
    document.getElementById("undo_machine_id").value = machineId;
    document.getElementById('undoModalDashboardMachine').style.display = 'block';
}

// Close the undo modal
function closeUndoModal() {
    const modal = document.getElementById('undoModalDashboardMachine');
    modal.style.display = 'none';
    const form = modal.querySelector('form');
    if (form) form.reset();
    const dropdown = modal.querySelector('#editStatus');
    if (dropdown) dropdown.innerHTML = '<option value="">Select a part first</option>';
}

// Open the machine modal
function openMachineModal() {
    document.getElementById('machineModalDashboardMachine').style.display = 'block';
}

// Close the machine modal
function closeMachineModal() {
    const modal = document.getElementById('machineModalDashboardMachine');
    modal.style.display = 'none';
}

// Listen for clicks on edit buttons in the custom parts table
document.addEventListener('click', function(event) {
    const btn = event.target.closest('.btn-edit');
    if (btn) {
        const partId = btn.getAttribute('data-part-id');
        const partName = btn.getAttribute('data-part-name');
        openEditCustomPartModal(partId, partName);
    }
});

// Open the edit custom part modal
function openEditCustomPartModal(partId, partName) {
    const modal = document.getElementById('editCustomPartModalDashboardMachine');
    modal.style.display = 'block';
    document.getElementById('edit_part_id').value = partId;
    document.getElementById('edit_part_name').value = partName;
}

// Close the edit custom part modal
function closeEditCustomPartModal() {
    document.getElementById('editCustomPartModalDashboardMachine').style.display = 'none';
}


// Listen for clicks on delete buttons in the custom parts table
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('btn-delete')) {
        const partId = event.target.getAttribute('data-part-id');
        const partName = event.target.getAttribute('data-part-name');
        confirmDeleteCustomPart(partId, partName);
    }
});


function confirmDeleteCustomPart(partId, partName) {
    const modal = document.getElementById('deleteCustomPartModalDashboardMachine');
    modal.style.display = 'block';
    
    // Populate the modal with part information
    document.getElementById('delete_part_id').value = partId;
    document.getElementById('delete_part_name').textContent = partName;
    document.getElementById('delete_part_id_display').textContent = '#' + partId;
    
    // Reset the confirmation checkbox and disable delete button
    document.getElementById('confirmDelete').checked = false;
    document.getElementById('deleteBtn').disabled = true;
}

// Close the delete custom part modal
function closeDeleteCustomPartModal() {
    const modal = document.getElementById('deleteCustomPartModalDashboardMachine');
    modal.style.display = 'none';
    
    // Reset form
    const form = modal.querySelector('form');
    if (form) form.reset();
    
    // Reset confirmation checkbox and disable delete button
    document.getElementById('confirmDelete').checked = false;
    document.getElementById('deleteBtn').disabled = true;
}

// Toggle delete button based on confirmation checkbox
function toggleDeleteButton() {
    const confirmCheckbox = document.getElementById('confirmDelete');
    const deleteBtn = document.getElementById('deleteBtn');
    
    deleteBtn.disabled = !confirmCheckbox.checked;
}

// Close modal when clicking outside of it
document.addEventListener('click', function(event) {
    const deleteModal = document.getElementById('deleteCustomPartModalDashboardMachine');
    if (event.target === deleteModal) {
        closeDeleteCustomPartModal();
    }
});

// Initialize event listeners when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM loaded, initializing dashboard machine...'); // Debug log
    const undoPartSelect = document.getElementById('undoPartSelect');
    
    // Handle changes in the undo part dropdown
    if (undoPartSelect) {
        undoPartSelect.addEventListener('change', function () {
            const partName = this.value;
            const machineId = document.getElementById("undo_machine_id").value;
            if (!partName || !machineId) return;

            // Fetch reset history for the selected part and machine
            fetch("/OMS/app/controllers/get_reset_dates_machine.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `part_name=${encodeURIComponent(partName)}&machine_id=${encodeURIComponent(machineId)}`
            })
            .then(res => res.json())
            .then(data => {
                const dropdown = document.getElementById("editStatus");
                dropdown.innerHTML = "";
                // Populate dropdown with reset timestamps or show no history
                if (Array.isArray(data) && data.length) {
                    data.forEach(row => {
                        const option = document.createElement("option");
                        option.value = row.reset_time;
                        option.textContent = row.reset_time;
                        dropdown.appendChild(option);
                    });
                } else {
                    const option = document.createElement("option");
                    option.value = "";
                    option.textContent = "No reset history";
                    dropdown.appendChild(option);
                }
            })
            .catch(err => console.error('Fetch error:', err));
        });
    }

    // Close modals when clicking outside their content
    window.addEventListener('click', function (event) {
        const resetModal = document.getElementById('resetModalDashboardMachine');
        const undoModal = document.getElementById('undoModalDashboardMachine');
        const machineModal = document.getElementById('machineModalDashboardMachine');
        const addCustomPartModal = document.getElementById('addCustomPartModalDashboardMachine');
        if (event.target === resetModal) closeResetModal();
        if (event.target === undoModal) closeUndoModal();
        if (event.target === machineModal) closeMachineModal();
        if (event.target === addCustomPartModal) closeAddCustomPartModal();
    });

    // Add event listener for reset form submission
    const resetForm = document.getElementById('resetForm');
    if (resetForm) {
        console.log('Reset form found, adding event listener');
        resetForm.addEventListener('submit', function(e) {
            console.log('Reset form submitted');
            // Don't refresh here - let the page reload handle it
        });
    } else {
        console.error('Reset form not found');
    }
    
    // Check if we're returning from a successful reset
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('filter_by') === 'last_updated') {
        console.log('Detected successful reset, refreshing progress bars...');
        // Wait a bit for the page to fully load, then refresh
        setTimeout(function() {
            if (window.machineProgressBarManager) {
                console.log('Refreshing machine progress bars after successful reset...');
                window.machineProgressBarManager.loadProgressData();
            } else {
                console.error('MachineProgressBarManager not found');
            }
        }, 1000);
        
        // Also try refreshing again after a longer delay to ensure data is updated
        setTimeout(function() {
            if (window.machineProgressBarManager) {
                console.log('Second refresh attempt after reset...');
                window.machineProgressBarManager.loadProgressData();
            }
        }, 3000);
    }
    
    // Add manual refresh function for testing
    window.testMachineReset = function() {
        console.log('Manual test of machine reset refresh...');
        if (window.machineProgressBarManager) {
            console.log('MachineProgressBarManager found, refreshing...');
            window.machineProgressBarManager.loadProgressData();
        } else {
            console.error('MachineProgressBarManager not found!');
        }
    };
    
    // Add function to check current data
    window.checkMachineData = function() {
        console.log('Checking current machine data...');
        fetch('/OMS/app/controllers/get_machine_outputs.php')
            .then(response => response.json())
            .then(data => {
                console.log('Current machine data:', data);
                if (data.success && data.data) {
                    console.log('Progress data:', data.data);
                    if (Array.isArray(data.data)) {
                        data.data.forEach((machine, index) => {
                            console.log(`Machine ${index + 1} (ID: ${machine.machine_id}):`, machine);
                            if (machine.progress) {
                                Object.keys(machine.progress).forEach(part => {
                                    console.log(`  ${part}: ${machine.progress[part].current} / ${machine.progress[part].limit} (${machine.progress[part].percentage}%)`);
                                });
                            }
                        });
                    }
                }
            })
            .catch(error => console.error('Error fetching machine data:', error));
    };
    
    // Add function to force refresh progress bars
    window.forceRefreshProgressBars = function() {
        console.log('Force refreshing progress bars...');
        if (window.machineProgressBarManager) {
            window.machineProgressBarManager.loadProgressData();
        } else {
            console.error('MachineProgressBarManager not found');
        }
    };
});


// Open the add custom parts modal
function openAddPartsModal() {
    console.log('openAddPartsModal function called'); // Debug log
    const modal = document.getElementById('addCustomPartModalDashboardMachine');
    if (modal) {
        modal.style.display = 'block';
        console.log('Modal opened successfully'); // Debug log
    } else {
        console.error('Modal element not found'); // Debug log
    }
}

// Close the add custom parts modal
function closeAddCustomPartModal() {
    const modal = document.getElementById('addCustomPartModalDashboardMachine');
    modal.style.display = 'none';
    const form = modal.querySelector('form');
    if (form) form.reset();
}

// Ensure functions are available globally
console.log('Dashboard machine JS loaded, functions available:', {
    openAddPartsModal: typeof openAddPartsModal,
    closeAddCustomPartModal: typeof closeAddCustomPartModal,
    openResetModal: typeof openResetModal,
    closeResetModal: typeof closeResetModal
});

// Add missing functions if they don't exist
if (typeof openAddPartsModal === 'undefined') {
    window.openAddPartsModal = function() {
        console.log('openAddPartsModal function called');
        const modal = document.getElementById('addCustomPartModalDashboardMachine');
        if (modal) {
            modal.style.display = 'block';
            console.log('Modal opened successfully');
        } else {
            console.error('Modal element not found');
        }
    };
}

if (typeof closeAddCustomPartModal === 'undefined') {
    window.closeAddCustomPartModal = function() {
        const modal = document.getElementById('addCustomPartModalDashboardMachine');
        if (modal) {
            modal.style.display = 'none';
            const form = modal.querySelector('form');
            if (form) form.reset();
        }
    };
}

// Open the export modal
// Listen for clicks on the "Export Data" button to open the export modal
document.querySelectorAll('[onclick="exportData()"]').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        // Prevent default if button is inside a form
        e.preventDefault();
        document.getElementById('exportMachineModal').style.display = 'block';
    });
});

// Listen for clicks on the close button or outside the modal to close it
document.addEventListener('DOMContentLoaded', function() {
    // Close button
    var closeBtn = document.querySelector('#exportMachineModal .modal-close-btn');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            document.getElementById('exportMachineModal').style.display = 'none';
        });
    }

    // Click outside modal content
    var exportModal = document.getElementById('exportMachineModal');
    if (exportModal) {
        exportModal.addEventListener('click', function(event) {
            if (event.target === exportModal) {
                exportModal.style.display = 'none';
            }
        });
    }
});

// State variables
selectedFormat = 'csv';
selectedDateRange = 'all';
customStartDate = '';
customEndDate = '';
includeHeaders = true;
isExporting = false;

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

// Export handling
async function handleExport() {
    if (isExporting) return;

    isExporting = true;
    const exportBtn = document.querySelector('.export-btn');
    
    // Update button to loading state
    exportBtn.disabled = true;
    exportBtn.innerHTML = `
        <div class="loading-spinner"></div>
        Exporting...
    `;

    try {
        // Simulate export process
        await new Promise(resolve => setTimeout(resolve, 2000));
        
        // Here you would normally make an API call or generate the file
        console.log('Export configuration:', {
            format: selectedFormat,
            dateRange: selectedDateRange,
            customStartDate,
            customEndDate,
            includeHeaders
        });

        // Show success message (you could add a toast notification here)
        alert('Export completed successfully!');
        
        closeExportModal();
    } catch (error) {
        console.error('Export failed:', error);
        alert('Export failed. Please try again.');
    } finally {
        // Reset button state
        isExporting = false;
        exportBtn.disabled = false;
        exportBtn.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7,10 12,15 17,10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Export Data
        `;
    }
}

// Close modal when clicking outside
document.getElementById('exportMachineModal').addEventListener('click', function(e) {
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