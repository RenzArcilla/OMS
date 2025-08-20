// Open the add user modal
function openAddUserModal() {
    document.getElementById("addUserModal").style.display = "block";
}

// Close the add user modal
function closeAddUserModal() {
    document.getElementById('addUserModal').style.display = 'none';
}

// Open the view user modal
function openViewUserModal() {
    document.getElementById("viewUserModal").style.display = "block";
}

function closeViewUserModal() {
    document.getElementById('viewUserModal').style.display = 'none';
}

// Open the edit user modal
function openEditUserModal() {
    document.getElementById("editUserModal").style.display = "block";
}

function closeEditUserModal() {
    document.getElementById('editUserModal').style.display = 'none';
}

// Open the account deletion modal
function openAccountDeletionModal() {
    document.getElementById("accountDeletionModal").style.display = "block";
}

function closeAccountDeletionModal() {
    document.getElementById('accountDeletionModal').style.display = 'none';
}

// Refresh the page
function refreshData() {
    // Add loading state
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Refreshing...';
    btn.disabled = true;
    
    // Add a small delay to show the loading state
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

// Toggle the page delete button
function togglePageDeleteButton() {
    const checkbox = document.getElementById('confirmPageDelete');
    const deleteBtn = document.getElementById('pageDeleteBtn');
    
    if (checkbox && deleteBtn) {
        if (checkbox.checked) {
            deleteBtn.classList.add('enabled');
            deleteBtn.classList.remove('disabled');
        } else {
            deleteBtn.classList.remove('enabled');
            deleteBtn.classList.add('disabled');
        }
    }
}
function confirmPageDeletion(event) {
    const checkbox = document.getElementById('confirmPageDelete');
    if (!checkbox || !checkbox.checked) {
        event.preventDefault();
        alert('Please confirm you understand this action is permanent by checking the confirmation box.');
        return false;
    }
    
    // Add your deletion logic here
    const confirmed = confirm('Are you absolutely sure? This will permanently delete your account and all data.');
    if (confirmed) {
        console.log('Account deletion confirmed');
        alert('Account deletion confirmed. This would normally redirect to a confirmation page.');
    }
    event.preventDefault();
    return false;
}