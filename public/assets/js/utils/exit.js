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
});

