function openEditModal(button) {
    document.getElementById('editModal').style.display = 'block';
    window.onclick = function(event) {
        if (event.target === document.getElementById('editModal')) {
            document.getElementById('editModal').style.display = 'none';
        }
    }
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Function to open the edit modal for dashboard applicator
function openEditModalDashboardApplicatorButton(button) {
    document.getElementById('editModalDashboardApplicator').style.display = 'block';
}

// Function to close any modal by ID
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
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
    const editData = {
        wireType: wireType,
        status: status,
        wireCrimper: wireCrimper,
        wireAnvil: wireAnvil,
        insulationCrimper: insulationCrimper,
        insulationAnvil: insulationAnvil,
        slideCutter: slideCutter,
        cutterHolder: cutterHolder
    };
    
    console.log('Edit data:', editData);
    
    // Here you can add your AJAX call to save the data
    // fetch('/api/update-applicator', {
    //     method: 'POST',
    //     headers: { 'Content-Type': 'application/json' },
    //     body: JSON.stringify(editData)
    // });
    
    // Show success message
    alert('Changes saved successfully!');
    
    // Close the modal
    closeModal('editModalDashboardApplicator');
}