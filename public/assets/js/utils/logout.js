// Logout functionality
function confirmLogout() {
    // Check if user confirmed logout
    const confirmCheckbox = document.getElementById('confirmLogout');
    if (confirmCheckbox && confirmCheckbox.checked) {
        // Perform logout action
        window.location.href = '/OMS/app/controllers/log_out.php';
    } else {
        alert('Please confirm that you want to log out by checking the checkbox.');
    }
}

function closeLogoutModal() {
    document.getElementById('logoutModalOverlay').style.display = 'none';
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('logoutModalOverlay');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});
