// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    const confirmCheckbox = document.getElementById('confirmRestore');
    const restoreBtn = document.getElementById('restoreBtn');

    // Only add event listener if elements exist (for restore modal)
    if (confirmCheckbox && restoreBtn) {
        confirmCheckbox.addEventListener('change', function() {
            restoreBtn.disabled = !this.checked;
            
            if (this.checked) {
                restoreBtn.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    restoreBtn.style.transform = 'scale(1)';
                }, 150);
            }
        });
    }
});
