// Listen for clicks on the entire document
document.addEventListener('click', function(event) {
    // Check if the clicked element has the class we want
    if (event.target.classList.contains('edit-btn')) {
        // This works even for dynamically added elements
        const modal = document.getElementById('editCustomPartModalDashboardApplicator');
        if (modal) {
            modal.style.display = 'block';
        }
        const modal2 = document.getElementById('editCustomPartModalDashboardMachine');
        if (modal2) {
            modal2.style.display = 'block';
        }
        const modal3 = document.getElementById('addCustomPartModalDashboardMachine');
        if (modal3) {
            modal3.style.display = 'block';
        }
    }
    if (event.target.classList.contains('delete-btn')) {
        // This works even for dynamically added elements
        const modal = document.getElementById('deleteCustomPartModalDashboardApplicator');
        if (modal) {
            modal.style.display = 'block';
        }
        const modal2 = document.getElementById('deleteCustomPartModalDashboardMachine');
        if (modal2) {
            modal2.style.display = 'block';
        }
    }
});