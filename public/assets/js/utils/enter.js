document.addEventListener('DOMContentLoaded', function() {
    // Universal modal opener - works with any button that has data-modal attribute
    document.addEventListener('click', function(event) {
// Dashboard Applicator
        if (event.target.classList.contains('add-parts')) {
            const modal = document.getElementById('addCustomPartModalDashboardApplicator');
            if (modal) {
                modal.style.display = 'block';
            }
        }
    });
});
