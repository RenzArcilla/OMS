// Tab switching functionality
function switchTab(tab) {
    currentTab = tab;
   
    // Update tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
   
    // Show/hide tables
    document.querySelectorAll('.entries-table-card').forEach(card => {
        card.classList.remove('active');
    });
   
    if (tab === 'machines') {
        document.getElementById('machines-table').classList.add('active');
    } else if (tab === 'applicators') {
        document.getElementById('applicators-table').classList.add('active');
    }
}

// Function to open machine modal
function openMachineModal() {
    const modal = document.getElementById('addMachineModal');
    if (modal) {
        modal.style.display = 'block';
    }
}

// Function to open applicator modal
function openApplicatorModal() {
    const modal = document.getElementById('addApplicatorModal');
    if (modal) {
        modal.style.display = 'block';
    }
}

// Function to close modal
function closeModal() {
    const modals = document.querySelectorAll('.modal-overlay');
    modals.forEach(modal => {
        modal.style.display = 'none';
    });
}