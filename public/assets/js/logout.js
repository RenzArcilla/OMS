// Enhanced JavaScript for the logout modal
function toggleLogoutButton() {
    const checkbox = document.getElementById('confirmLogout');
    const logoutBtn = document.getElementById('logoutBtn');
    
    if (checkbox.checked) {
        logoutBtn.classList.add('active');
    } else {
        logoutBtn.classList.remove('active');
    }
}


function closeLogoutModal() {
    const modal = document.getElementById('logoutModalOverlay');
    if (modal) {
        modal.style.display = 'none';
        const form = modal.querySelector('form');
        if (form) form.reset();
    }
}

// Enhanced confirm logout function
function confirmLogout() {
    const checkbox = document.getElementById('confirmLogout');
    if (checkbox.checked) {
        // Add loading state
        const logoutBtn = document.getElementById('logoutBtn');
        const originalText = logoutBtn.innerHTML;
        logoutBtn.innerHTML = '<span>Logging out...</span>';
        logoutBtn.style.pointerEvents = 'none';
        
        // Simulate logout process
        setTimeout(() => {
            window.location.href = '/OMS/app/controllers/log_out.php';
        }, 1000);
    } else {
        // Shake animation for checkbox
        const checkboxGroup = document.querySelector('.logout-checkbox-group');
        checkboxGroup.style.animation = 'shake 0.5s ease-in-out';
        setTimeout(() => {
            checkboxGroup.style.animation = '';
        }, 500);
    }
}

// Add shake animation
const style = document.createElement('style');
style.textContent = `
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
`;
document.head.appendChild(style);

