document.addEventListener('DOMContentLoaded', function() {
    // Listen for clicks on the entire document
    document.addEventListener('click', function(event) {
        // Handle edit button
        if (event.target.classList.contains('edit-btn')) {
            const modalApplicator = document.getElementById('editCustomPartModalDashboardApplicator');
            if (modalApplicator) {
                modalApplicator.style.display = 'block';
            }
            const modalMachine = document.getElementById('editCustomPartModalDashboardMachine');
            if (modalMachine) {
                modalMachine.style.display = 'block';
            }
            const addModalMachine = document.getElementById('addCustomPartModalDashboardMachine');
            if (addModalMachine) {
                addModalMachine.style.display = 'block';
            }
        }

        // Handle delete button
        if (event.target.classList.contains('delete-btn')) {
            const modalApplicator = document.getElementById('deleteCustomPartModalDashboardApplicator');
            if (modalApplicator) {
                modalApplicator.style.display = 'block';
            }
            const modalMachine = document.getElementById('deleteCustomPartModalDashboardMachine');
            if (modalMachine) {
                modalMachine.style.display = 'block';
            }
        }

        // Handle modal-btn (by text)
        if (event.target.closest('.modal-btn')) {
            const buttonText = event.target.textContent.trim();

            if (buttonText === 'Export Applicators') {
                const exportApplicatorReportModal = document.getElementById('exportApplicatorReportModal');
                if (exportApplicatorReportModal) {
                    exportApplicatorReportModal.style.display = 'block';
                }
            } else if (buttonText === 'Export Machines') {
                const exportMachineReportModal = document.getElementById('exportMachineReportModal');
                if (exportMachineReportModal) {
                    exportMachineReportModal.style.display = 'block';
                }
            } else if (buttonText === 'Edit Maximum Output') {
                const editMaxOutputModal = document.getElementById('editMaxOutputModal');
                if (editMaxOutputModal) {
                    editMaxOutputModal.style.display = 'block';
                }
            } else {
                // Default modal handling for other modal-btn clicks
                const exportModalRecentlyReset = document.getElementById('exportModalRecentlyReset');
                if (exportModalRecentlyReset) {
                    exportModalRecentlyReset.style.display = 'block';
                }
            }
        }

        // Handle logout button
        if (event.target.classList.contains('logout-btn')) {
            const logoutModalOverlay = document.getElementById('logoutModalOverlay');
            if (logoutModalOverlay) {
                logoutModalOverlay.style.display = 'block';
            }
        }

        // Handle export button
        if (event.target.classList.contains('export-btn')) {
            const exportMachineModal = document.getElementById('exportMachineModal');
            if (exportMachineModal) {
                exportMachineModal.style.display = 'block';
            }
            const exportModal = document.getElementById('exportModal');
            if (exportModal) {
                exportModal.style.display = 'block';
            }
        }
    });
});