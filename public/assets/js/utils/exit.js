document.addEventListener('DOMContentLoaded', function() {
    // Listen for clicks on the entire document
    document.addEventListener('click', function(event) {
        // Check if the clicked element has the class we want
        if (event.target.classList.contains('modal-close-btn , cancel-btn')) {
            // This works even for dynamically added elements
            const modal = document.getElementById('exportModal');
            if (modal) {
                modal.style.display = 'none';
            }
            const modal2 = document.getElementById('deleteCustomPartModalDashboardApplicator');
            if (modal2) {
                modal2.style.display = 'none';
            }
            const modal3 = document.getElementById('deleteCustomPartModalDashboardMachine');
            if (modal3) {
                modal3.style.display = 'none';
            }
            const modal4 = document.getElementById('editCustomPartModalDashboardMachine');
            if (modal4) {
                modal4.style.display = 'none';
            }
            const modal5 = document.getElementById('addCustomPartModalDashboardMachine');
            if (modal5) {
                modal5.style.display = 'none';
            }
            const modal6 = document.getElementById('editCustomPartModalDashboardMachine');
            if (modal6) {
                modal6.style.display = 'none';
            }
            const modal7 = document.getElementById('addCustomPartModalDashboardMachine');
            if (modal7) {
                modal7.style.display = 'none';
            }
            const modal8 = document.getElementById('exportMachineModal');
            if (modal8) {
                modal8.style.display = 'none';
            }
            const modal9 = document.getElementById('modalOverlay');
            if (modal9) {
                modal9.style.display = 'none';
            }
            const modal10 = document.getElementById('logoutModalOverlay');
            if (modal10) {
                modal10.style.display = 'none';
            }
        }
    });

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('exportModal');
        if (event.target === modal) {
            closeExportModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeExportModal();
        }
    });

    
});

