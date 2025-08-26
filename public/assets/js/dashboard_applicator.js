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

// Close the machine modal
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
