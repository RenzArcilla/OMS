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