// Find buttons
const buttons = document.querySelectorAll('.modal-close-btn, .cancel-btn');

// Add click listener to each button
buttons.forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('exportModal').style.display = 'none';
    });
    
    button.addEventListener('click', function() {
        document.getElementById('deleteCustomPartModalDashboardApplicator').style.display = 'none';
    });
    button.addEventListener('click', function() {
        document.getElementById('deleteCustomPartModalDashboardMachine').style.display = 'none';
    });
    button.addEventListener('click', function() {
        document.getElementById('editCustomPartModalDashboardMachine').style.display = 'none';
    });
    button.addEventListener('click', function() {
        document.getElementById('addCustomPartModalDashboardMachine').style.display = 'none';
    });
    button.addEventListener('click', function() {
        document.getElementById('exportMachineModal').style.display = 'none';
    });
    button.addEventListener('click', function() {
        document.getElementById('modalOverlay').style.display = 'none';
    });
    button.addEventListener('click', function() {
        document.getElementById('logoutModalOverlay').style.display = 'none';
    });
});

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('logoutModalOverlay');
    if (event.target === modal) {
        closeLogoutModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeLogoutModal();
    }
});