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
function openUndoModal() {
    const machineId = this.getAttribute("data-id");
    document.getElementById("undo_machine_id").value = machineId;
    document.getElementById('undoModalDashboardMachine').style.display = 'block';
}

// Close the undo modal
function closeUndoModal() {
    const modal = document.getElementById('undoModalDashboardMachine');

    // Hide modal
    modal.style.display = 'none';

    // Reset the form inside modal
    const form = modal.querySelector('form');
    if (form) form.reset();

    // Clear the dates dropdown
    const dropdown = modal.querySelector('#editStatus');
    if (dropdown) dropdown.innerHTML = '<option value="">Select a part first</option>';
}


// Initialize event listeners when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function () {
    const undoPartSelect = document.getElementById('undoPartSelect');
    
    // Handle changes in the undo part dropdown
    if (undoPartSelect) {
        undoPartSelect.addEventListener('change', function () {
            const partName = this.value;
            const machineId = document.getElementById("undo_machine_id").value;
            if (!partName || !machineId) return;

            // Fetch reset history for the selected part and machine
            fetch("../controllers/get_reset_dates.php", {
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
        if (event.target === resetModal) closeResetModal();
        if (event.target === undoModal) closeUndoModal();
    });
});

// Set up the click outside to close functionality once
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('undoModalDashboardMachine');
    if (modal) {
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeUndoModalDashboardMachine();
            }
        });
    }
});
