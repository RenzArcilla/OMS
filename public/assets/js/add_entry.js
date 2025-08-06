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


// Function to show machine form and hide applicator form
function showMachineForm() {
    const machineForm = document.getElementById('machine-form');
    const applicatorForm = document.getElementById('applicator-form');
    const buttons = document.querySelectorAll('.toggle-btn');
    
    // Show machine form, hide applicator form
    machineForm.classList.remove('hidden');
    applicatorForm.classList.add('hidden');
    
    // Update button states
    buttons[0].classList.add('active');
    buttons[1].classList.remove('active');
}


// Function to show applicator form and hide machine form
function showApplicatorForm() {
    const machineForm = document.getElementById('machine-form');
    const applicatorForm = document.getElementById('applicator-form');
    const buttons = document.querySelectorAll('.toggle-btn');
    
    // Show applicator form, hide machine form
    machineForm.classList.add('hidden');
    applicatorForm.classList.remove('hidden');
    
    // Update button states
    buttons[0].classList.remove('active');
    buttons[1].classList.add('active');
}
// Function to show machine form and hide applicator form
function showMachineForm() {
    const machineForm = document.getElementById('machine-form');
    const applicatorForm = document.getElementById('applicator-form');
    const buttons = document.querySelectorAll('.toggle-btn');
   
    // Show machine form, hide applicator form
    machineForm.classList.remove('hidden');
    applicatorForm.classList.add('hidden');
   
    // Update button states
    buttons[0].classList.add('active');
    buttons[1].classList.remove('active');
}


// Function to show applicator form and hide machine form
function showApplicatorForm() {
    const machineForm = document.getElementById('machine-form');
    const applicatorForm = document.getElementById('applicator-form');
    const buttons = document.querySelectorAll('.toggle-btn');
   
    // Show applicator form, hide machine form
    machineForm.classList.add('hidden');
    applicatorForm.classList.remove('hidden');
   
    // Update button states
    buttons[0].classList.remove('active');
    buttons[1].classList.add('active');
}
