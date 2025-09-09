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
        if (event.target.classList.contains('export-output-data')) {
            const modal = document.getElementById('exportModal');
            if (modal) {
                modal.style.display = 'block';
            }
        }
        if (event.target.classList.contains('export-reset-data')) {
            const modal = document.getElementById('exportModalRecentlyReset');
            if (modal) {
                modal.style.display = 'block';
            }
        }
        if (event.target.classList.contains('edit-maximum-output')) {
            const modal = document.getElementById('editMaximumOutputModalDashboardApplicator');
            if (modal) {
                modal.style.display = 'block';
            }
        }
    });
});
