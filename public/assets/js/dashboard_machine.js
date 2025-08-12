function openUndoModalDashboardMachine() {
    document.getElementById('undoModalDashboardMachine').style.display = 'block';
}

function closeUndoModalDashboardMachine() {
    document.getElementById('undoModalDashboardMachine').style.display = 'none';
}

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

// Function to save the edit changes
function saveChanges() {
    // Get all the form values
    const cutBlade = document.getElementById('editCutBlade').value;
    const stripBladeA = document.getElementById('editStripBladeA').value;
    const stripBladeB = document.getElementById('editStripBladeB').value;
    
    // Create data object
    const undoData = {
        cutBlade: cutBlade,
        stripBladeA: stripBladeA,
        stripBladeB: stripBladeB
    };
    
    console.log('Undo data:', undoData);
    
    
    // Show success message
    alert('Changes saved successfully!');
    
    // Close the modal
    closeUndoModalDashboardMachine();
}

// Reset
function openResetModal() {
    document.getElementById('resetModalDashboardMachine').style.display = 'block';
    window.onclick = function(event) {
        if (event.target === document.getElementById('resetModalDashboardMachine')) {
            document.getElementById('resetModalDashboardMachine').style.display = 'none';
        }
    }
}

function closeResetModalDashboardMachine() {
    document.getElementById('resetModalDashboardMachine').style.display = 'none';
}

// Refresh the page
function refreshPage() {
    window.location.reload();
}