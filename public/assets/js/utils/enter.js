document.addEventListener('DOMContentLoaded', function() {
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
            const modal4 = document.getElementById('deleteCustomPartModalDashboardApplicator');
            if (modal4) {
                modal4.style.display = 'block';
            }
            const modal5 = document.getElementById('deleteCustomPartModalDashboardMachine');
            if (modal5) {
                modal5.style.display = 'block';
            }
        }
        if (event.target.closest('.modal-btn')) {
            // This works even for dynamically added elements
            console.log('Modal button clicked!');
            const modal6 = document.getElementById('exportModalRecentlyReset');
            console.log('Modal element:', modal6);
            if (modal6) {
                console.log('Showing exportModalRecentlyReset...');
                modal6.style.display = 'block';
            } else {
                console.log('exportModalRecentlyReset not found!');
            }

            // Check button text to show specific modal
            const buttonText = event.target.textContent.trim();
            console.log('Button text:', buttonText);
            if (buttonText === 'Export Applicators') {
                console.log('Showing exportApplicatorReportModal...');
                const modal10 = document.getElementById('exportApplicatorReportModal');
                if (modal10) {
                    modal10.style.display = 'block';
                    console.log('exportApplicatorReportModal shown successfully');
                } else {
                    console.log('exportApplicatorReportModal not found!');
                }
            } else if (buttonText === 'Export Machines') {
                console.log('Showing exportMachineReportModal...');
                const modal9 = document.getElementById('exportMachineReportModal');
                if (modal9) {
                    modal9.style.display = 'block';
                    console.log('exportMachineReportModal shown successfully');
                } else {
                    console.log('exportMachineReportModal not found!');
                }
            }
        }
        if (event.target.classList.contains('logout-btn')) {
            // This works even for dynamically added elements
            const logoutModal7 = document.getElementById('logoutModalOverlay');
            if (logoutModal7) {
                logoutModal7.style.display = 'block';
            }
        }
    });
});