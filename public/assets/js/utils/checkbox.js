// Universal checkbox handler for modals
document.addEventListener('DOMContentLoaded', function() {
    // Handle any checkbox with confirmation-input class
    document.addEventListener('change', function(event) {
        if (event.target.classList.contains('confirmation-input')) {
            const checkbox = event.target;
            const modal = checkbox.closest('.modal-overlay');
            
            if (modal) {
                // Find the submit button in the same modal
                const submitBtn = modal.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = !checkbox.checked;
                    
                    if (checkbox.checked) {
                        submitBtn.style.transform = 'scale(1.02)';
                        setTimeout(() => {
                            submitBtn.style.transform = 'scale(1)';
                        }, 150);
                    }
                }
            }
        }
    });
});

// Universal toggle restore button function for dashboard modals
window.toggleRestoreButton = function() {
    const confirmCheckbox = document.getElementById('confirmRestore');
    const restoreBtn = document.getElementById('restoreBtn');
    
    if (confirmCheckbox && restoreBtn) {
        restoreBtn.disabled = !confirmCheckbox.checked;
        
        if (confirmCheckbox.checked) {
            restoreBtn.style.transform = 'scale(1.02)';
            setTimeout(() => {
                restoreBtn.style.transform = 'scale(1)';
            }, 150);
        }
    }
};
