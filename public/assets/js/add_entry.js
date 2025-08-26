// Switch between machines and applicators tabs
function switchTab(tab) {
    currentTab = tab;

    // Update tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');

    // Hide all tables first
    document.getElementById('machine-table').style.display = 'none';
    document.getElementById('applicators-table').style.display = 'none';

    // Show the selected table
    if (tab === 'machines') {
        document.getElementById('machine-table').style.display = 'block';
    } else if (tab === 'applicators') {
        document.getElementById('applicators-table').style.display = 'block';
    }
}

// Open Add Machine Modal
function openMachineModal() {
    document.getElementById('addMachineModal').style.display = 'block';
}

// Close Add Applicator Modal
function closeAddApplicatorModal() {
    document.getElementById('addApplicatorModal').style.display = 'none';
}

// Open Add Applicator Modal
function openApplicatorModal() {
    document.getElementById('addApplicatorModal').style.display = 'block';
}

// Close Add Machine Modal
function closeAddMachineModal() {
    document.getElementById('addMachineModal').style.display = 'none';
}

// Close modal when clicking outside of it
document.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('.modal-overlay');
    modals.forEach(modal => {
        if (event.target === modal) {
            closeAddApplicatorModal();
            closeAddMachineModal();
            closeApplicatorModal();
            closeMachineModal();
        }
    });
});

// Close modal when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAddApplicatorModal();
        closeAddMachineModal();
        closeApplicatorModal();
        closeMachineModal();
    }
});
