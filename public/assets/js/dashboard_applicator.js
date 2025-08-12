function openUndoModal() {
    document.getElementById('undoModalDashboardApplicator').style.display = 'block';
    window.onclick = function(event) {
        if (event.target === document.getElementById('undoModalDashboardApplicator')) {
            document.getElementById('undoModalDashboardApplicator').style.display = 'none';
        }
    }
}

function closeUndoModal() {
    document.getElementById('undoModalDashboardApplicator').style.display = 'none';
}


// Function to save the edit changes
function saveEdit() {
    // Get all the form values
    const wireType = document.getElementById('editWireType').value;
    const status = document.getElementById('editStatus').value;
    const wireCrimper = document.getElementById('editWireCrimper').value;
    const wireAnvil = document.getElementById('editWireAnvil').value;
    const insulationCrimper = document.getElementById('editInsulationCrimper').value;
    const insulationAnvil = document.getElementById('editInsulationAnvil').value;
    const slideCutter = document.getElementById('editSlideCutter').value;
    const cutterHolder = document.getElementById('editCutterHolder').value;
    
    // Create data object
    const undoData = {
        wireType: wireType,
        status: status,
        wireCrimper: wireCrimper,
        wireAnvil: wireAnvil,
        insulationCrimper: insulationCrimper,
        insulationAnvil: insulationAnvil,
        slideCutter: slideCutter,
        cutterHolder: cutterHolder
    };
    
    console.log('Undo data:', undoData);
    
    
    // Show success message
    alert('Changes saved successfully!');
    
    // Close the modal
    closeModal('undoModalDashboardApplicator');
}
// Reset
function openResetModal() {
    document.getElementById('resetModalDashboardApplicator').style.display = 'block';
    window.onclick = function(event) {
        if (event.target === document.getElementById('resetModalDashboardApplicator')) {
            document.getElementById('resetModalDashboardApplicator').style.display = 'none';
        }
    }
}

function closeResetModal() {
    document.getElementById('resetModalDashboardApplicator').style.display = 'none';
}

// Function to open the undo modal for dashboard applicator
function openResetModalDashboardApplicatorButton(button) {
    document.getElementById('resetModalDashboardApplicator').style.display = 'block';
}

// Function to close any modal by ID
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }xa
}

// Function to save the edit changes
function saveEdit() {
    // Get all the form values
    const wireType = document.getElementById('editWireType').value;
    const status = document.getElementById('editStatus').value;
    const wireCrimper = document.getElementById('editWireCrimper').value;
    const wireAnvil = document.getElementById('editWireAnvil').value;
    const insulationCrimper = document.getElementById('editInsulationCrimper').value;
    const insulationAnvil = document.getElementById('editInsulationAnvil').value;
    const slideCutter = document.getElementById('editSlideCutter').value;
    const cutterHolder = document.getElementById('editCutterHolder').value;
    
    // Create data object
    const undoData = {
        wireType: wireType,
        status: status,
        wireCrimper: wireCrimper,
        wireAnvil: wireAnvil,
        insulationCrimper: insulationCrimper,
        insulationAnvil: insulationAnvil,
        slideCutter: slideCutter,
        cutterHolder: cutterHolder
    };
    
    console.log('Reset data:', resetData);
    
    
    // Show success message
    alert('Changes saved successfully!');
    
    // Close the modal
    closeModal('resetModalDashboardApplicator');
}

// Refresh the page
function refreshPage() {
    window.location.reload();
}