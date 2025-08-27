// Refresh the page
function refreshPage(btn) {
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Refreshing...';
    btn.disabled = true;
    window.location.reload();
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
            fetch("../controllers/get_reset_dates_machine.php", {
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