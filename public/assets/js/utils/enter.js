// Listen for clicks on the entire document
document.addEventListener('click', function(event) {
    // Check if the clicked element has the class we want
    if (event.target.classList.contains('edit-btn')) {
        // This works even for dynamically added elements
        const modal = document.getElementById('editCustomPartModalDashboardApplicator');
        if (modal) {
            modal.style.display = 'block';
        }
    }
});