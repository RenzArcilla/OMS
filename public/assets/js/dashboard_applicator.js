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
}

// Close the reset modal
function closeResetModal() {
    document.getElementById('resetModalDashboardApplicator').style.display = 'none';
}

// Open the undo modal
function openUndoModal() {
    document.getElementById('undoModalDashboardApplicator').style.display = 'block';
    window.onclick = function(event) {
        if (event.target === document.getElementById('undoModalDashboardApplicator')) {
            document.getElementById('undoModalDashboardApplicator').style.display = 'none';
        }
    }
}

// Close the undo modal
function closeUndoModal() {
    document.getElementById('undoModalDashboardApplicator').style.display = 'none';
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

function closeApplicatorModal() {
    document.getElementById('applicatorModalDashboardApplicator').style.display = 'none';
}