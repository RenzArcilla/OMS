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
    const machineId = button.getAttribute("data-id");
    document.getElementById("reset_machine_id").value = machineId;
    document.getElementById('resetModalDashboardMachine').style.display = 'block';
}

// Close the reset modal
function closeResetModalDashboardMachine() {
    document.getElementById('resetModalDashboardMachine').style.display = 'none';
}

// Open the undo modal
function openUndoModalDashboardMachine() {
    const machineId = button.getAttribute("data-id");
    document.getElementById("undo_machine_id").value = machineId;
    document.getElementById('undoModalDashboardMachine').style.display = 'block';
}

// Close the undo modal
function closeUndoModalDashboardMachine() {
    const modal = document.getElementById('undoModalDashboardMachine');

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

// Open the machine modal
function openMachineModal() {
    document.getElementById('machineModalDashboardmachine').style.display = 'block';
    window.onclick = function(event) {
        if (event.target === document.getElementById('machineModalDashboardmachine')) {
            document.getElementById('machineModalDashboardmachine').style.display = 'none';
        }
    }
}

// Close the machine modal
function closeMachineModal() {
    document.getElementById('machineModalDashboardmachine').style.display = 'none';
}

// Listen for changes in the "part" dropdown
document.getElementById("editWireType").addEventListener("change", function() {
    let partName = this.value; // selected part
    let machineId = document.getElementById("undo_machine_id").value; // hidden input

    // Do nothing if no part is selected
    if (!partName) return;

    // Send request to server to fetch reset dates for this part + machine
    fetch("../controllers/get_reset_dates.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "part_name=" + encodeURIComponent(partName) +
            "&machine_id=" + encodeURIComponent(machineId)
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
