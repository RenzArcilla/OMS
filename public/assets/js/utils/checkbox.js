const confirmCheckbox = document.getElementById('confirmDelete');

// Checkbox state handler
confirmCheckbox.addEventListener('change', function() {
    deleteBtn.disabled = !this.checked;
    
    if (this.checked) {
        deleteBtn.style.transform = 'scale(1.02)';
        setTimeout(() => {
            deleteBtn.style.transform = 'scale(1)';
        }, 150);
    }
});