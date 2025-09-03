// Refresh the page
function refreshPage() {
    // Add loading state
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Refreshing...';
    btn.disabled = true;
    
    window.location.reload();
}

// Open the reset modal
function openResetModal(button) {
    const applicatorId = button.getAttribute("data-id");
    document.getElementById("reset_applicator_id").value = applicatorId;
    document.getElementById("resetModalDashboardApplicator").style.display = "block";
    window.onclick = function(event) {
        if (event.target === document.getElementById('resetModalDashboardApplicator')) {
            document.getElementById('resetModalDashboardApplicator').style.display = 'none';
        }
    }
}

// Close the reset modal
function closeResetModal() {
    document.getElementById('resetModalDashboardApplicator').style.display = 'none';
}

// Refresh progress bars after reset
function refreshProgressBarsAfterReset() {
    // If ProgressBarManager exists, refresh the data
    if (window.progressBarManager) {
        window.progressBarManager.loadProgressData();
    }
}

// Open the undo modal
function openUndoModal(button) {
    const applicatorId = button.getAttribute("data-id");
    document.getElementById("undo_applicator_id").value = applicatorId;
    document.getElementById('undoModalDashboardApplicator').style.display = 'block';
}

// Close the undo modal
function closeUndoModal() {
    const modal = document.getElementById('undoModalDashboardApplicator');

    // Hide modal
    modal.style.display = 'none';

    // Reset the form inside modal
    const form = modal.querySelector('form');
    if (form) form.reset();

    // Clear the dates dropdown
    const dropdown = modal.querySelector('#editStatus');
    if (dropdown) {
        dropdown.innerHTML = '<option value="">Select a part first</option>';
    }
}

// Open the applicator modal
function openApplicatorModal() {
    document.getElementById('applicatorModalDashboardApplicator').style.display = 'block';
    window.onclick = function(event) {
        if (event.target === document.getElementById('applicatorModalDashboardApplicator')) {
            document.getElementById('applicatorModalDashboardApplicator').style.display = 'none';
        }
    }
}

// Close the applicator modal
function closeApplicatorModal() {
    document.getElementById('applicatorModalDashboardApplicator').style.display = 'none';
}

// Open the add custom part modal
function openAddCustomPartModal() {
    document.getElementById('addCustomPartModalDashboardApplicator').style.display = 'block';
    window.onclick = function(event) {
        if (event.target === document.getElementById('addCustomPartModalDashboardApplicator')) {
            document.getElementById('addCustomPartModalDashboardApplicator').style.display = 'none';
        }
    }
}

// Close the add custom part modal
function closeAddCustomPartModal() {
    document.getElementById('addCustomPartModalDashboardApplicator').style.display = 'none';
}

// Listen for clicks on edit buttons in the custom parts table
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('btn-edit')) {
        const partId = event.target.getAttribute('data-part-id');
        const partName = event.target.getAttribute('data-part-name');
        openEditCustomPartModal(partId, partName);
    }
});

// Open the edit custom part modal
function openEditCustomPartModal(partId, partName) {
    document.getElementById('editCustomPartModalDashboardApplicator').style.display = 'block';
    document.getElementById('edit_part_id').value = partId; // Set the part ID
    document.getElementById('edit_part_name').value = partName; // Set the part name
    window.onclick = function(event) {
        if (event.target === document.getElementById('editCustomPartModalDashboardApplicator')) {
            document.getElementById('editCustomPartModalDashboardApplicator').style.display = 'none';
        }
    }
}

// Close the edit custom part modal
function closeEditCustomPartModal() {
    document.getElementById('editCustomPartModalDashboardApplicator').style.display = 'none';
}

// Listen for clicks on delete buttons in the custom parts table
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('btn-delete')) {
        const partId = event.target.getAttribute('data-part-id');
        const partType = event.target.getAttribute('data-part-type');
        confirmDeleteCustomPart(partId, partType);
    }
});

// Delete confirmation
function confirmDeleteCustomPart(partId, type) {
    if (confirm("Are you sure you want to delete this custom part? This action CANNOT be undone!")) {
        // Create a form dynamically
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "../controllers/delete_custom_part.php";

        // Add hidden input for part_id
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "part_id";
        input.value = partId;

        // Add hidden input for equipment type
        const inputType = document.createElement("input");
        inputType.type = "hidden";
        inputType.name = "equipment_type";
        inputType.value = type;

        form.appendChild(input);
        form.appendChild(inputType);
        document.body.appendChild(form);

        form.submit();
    }
}

// Listen for clicks only on the restore buttons
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('restore-applicator-btn')) {
        const applicatorId = event.target.dataset.applicatorId;
        confirmRestoreApplicator(applicatorId);
    }
});

// Restore confirmation
function confirmRestoreApplicator(applicatorId) {
    if (confirm("Are you sure you want to restore this applicator?")) {
        // Create a form dynamically
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "../controllers/restore_applicator.php";

        // Add hidden input for applicator_id
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "applicator_id";
        input.value = applicatorId;

        form.appendChild(input);
        document.body.appendChild(form);

        form.submit();
    }
}

// Open the parts inventory modal
function openPartsInventoryModal() {
    document.getElementById('partsInventoryModalDashboardApplicator').style.display = 'block';
    window.onclick = function(event) {
        if (event.target === document.getElementById('partsInventoryModalDashboardApplicator')) {
            document.getElementById('partsInventoryModalDashboardApplicator').style.display = 'none';
        }
    }
}

// Close the parts inventory modal
function closePartsInventoryModal() {
    document.getElementById('partsInventoryModalDashboardApplicator').style.display = 'none';
}


// Listen for changes in the "part" dropdown
document.getElementById("editWireType").addEventListener("change", function() {
    let partName = this.value; // selected part
    let applicatorId = document.getElementById("undo_applicator_id").value; // hidden input

    // Do nothing if no part is selected
    if (!partName) return;

    // Send request to server to fetch reset dates for this part + applicator
    fetch("../controllers/get_reset_dates_applicator.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "part_name=" + encodeURIComponent(partName) +
            "&applicator_id=" + encodeURIComponent(applicatorId)
    })
    .then(res => res.json()) // parse JSON response
    .then(data => {
        let dropdown = document.getElementById("editStatus");
        dropdown.innerHTML = ""; // clear old options

        if (data.length > 0) {
            // Populate dropdown with reset timestamps
            data.forEach(row => {
                let option = document.createElement("option");
                option.value = row.reset_time;
                option.textContent = row.reset_time;
                dropdown.appendChild(option);
            });
        } else {
            // If no records found, show fallback option
            let option = document.createElement("option");
            option.value = "";
            option.textContent = "No reset history";
            dropdown.appendChild(option);
        }
    })
    .catch(err => console.error(err)); // log any errors
});


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

    
    // Add event listener for reset form submission
    const resetForm = document.getElementById('resetForm');
    if (resetForm) {
        resetForm.addEventListener('submit', function() {
            // After successful reset, refresh progress bars
            setTimeout(function() {
                refreshProgressBarsAfterReset();
            }, 1000); // Wait 1 second for the reset to complete
        });
    }
});

// Export Recently Reset Modal functions
function exportRecentlyResetModal() {
    const modal = document.getElementById('exportModalRecentlyReset');
    if (modal) {
        modal.style.display = 'block';
    }
}

function closeExportRecentlyResetModal() {
    const modal = document.getElementById('exportModalRecentlyReset');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('exportModalRecentlyReset');
    if (modal && e.target === modal) {
        closeExportRecentlyResetModal();
    }
});